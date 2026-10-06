<?php

namespace Framework\Auth;

use App\Models\ClienteModel;
use App\Models\UsuarioModel;
use App\Repositories\UsuarioRepository;
use Framework\Database\Database;
use Framework\Support\Logger;
use Framework\Support\Session;

/** Núcleo único para os dois fluxos pt-BR: usuario e cliente. */
class Auth
{
    private static string $guardPadrao = 'usuario';
    public static array $validacao = [];

    public static function guard(string $nome): Guard
    {
        if (!in_array($nome, ['usuario', 'cliente'], true)) {
            throw new \InvalidArgumentException('Fluxo de autenticação inválido.');
        }
        return new Guard($nome);
    }
    public static function attempt(string $email, string $senha, bool $lembrar = false): bool
    {
        return static::guard('usuario')->attempt($email, $senha, $lembrar);
    }
    public static function check(): bool
    {
        return static::guard(static::$guardPadrao)->check();
    }
    public static function guest(): bool
    {
        return !static::check();
    }
    public static function usuario(): ?object
    {
        return static::guard('usuario')->identidade();
    }
    public static function id(): ?int
    {
        return static::guard('usuario')->id();
    }
    public static function perfil(): string
    {
        return static::guard('usuario')->perfil();
    }
    public static function role(): string
    {
        return static::perfil();
    }
    public static function is(string $perfil): bool
    {
        return static::check() && static::perfil() === static::normalizarPerfil($perfil);
    }
    public static function isAny(string ...$perfis): bool
    {
        foreach ($perfis as $perfil) if (static::is($perfil)) return true;
        return false;
    }
    public static function logout(): void
    {
        static::guard('usuario')->logout();
    }
    public static function setUserModel(string $modelClass): void
    {
        if ($modelClass !== UsuarioModel::class) {
            throw new \InvalidArgumentException('A autenticação administrativa utiliza UsuarioModel.');
        }
    }
    public static function recoverFromCookie(): bool
    {
        return static::guard('usuario')->recuperarCookie() || static::guard('cliente')->recuperarCookie();
    }
    public static function normalizarPerfil(string $perfil): string
    {
        $perfil = mb_strtolower(trim($perfil), 'UTF-8');
        $perfil = strtr($perfil, ['á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'é' => 'e', 'ê' => 'e', 'í' => 'i', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ú' => 'u', 'ç' => 'c']);
        return preg_replace('/[^a-z0-9]+/', '_', $perfil) ?: '';
    }
    public static function sessionKey(string $fluxo, string $item): string
    {
        return $fluxo . '_' . $item;
    }
    public static function rememberConfig(string $fluxo): array
    {
        return $fluxo === 'cliente'
            ? ['cookie' => 'lembrar_cliente', 'tabela' => 'clientes', 'campo' => 'lembrar_cliente_token', 'expira' => 'token_cliente_lembrar_expira_em']
            : ['cookie' => 'lembrar_usuario', 'tabela' => 'usuarios', 'campo' => 'lembrar_token', 'expira' => 'token_lembrar_expira_em'];
    }
    public static function exigir(string $fluxo, string $destino): void
    {
        if (!static::guard($fluxo)->check()) {
            Session::flash('error', 'Faça login para continuar.');
            redirect($destino);
        }
    }
}

class Guard
{
    private string $nome;
    private string $modelo;
    public function __construct(string $nome)
    {
        $this->nome = $nome;
        $this->modelo = $nome === 'cliente' ? ClienteModel::class : UsuarioModel::class;
    }
    public function attempt(string $email, string $senha, bool $lembrar = false): bool
    {
        try {
            $registro = (new ($this->modelo)())->authenticate($email, $senha);
        } catch (\Throwable $e) {
            Logger::error('Falha ao consultar credenciais', ['fluxo' => $this->nome]);
            return false;
        }
        if (!$registro) return false;
        $this->login($registro, $lembrar);
        return true;
    }
    public function login(object $registro, bool $lembrar = false): void
    {
        session_regenerate_id(true);
        $id = (int) $registro->id;
        Session::set(Auth::sessionKey($this->nome, 'id'), $id);
        if ($this->nome === 'usuario') {
            $perfil = $this->perfilDoUsuario($id, $registro);
            Session::set('usuario_perfil', $perfil);
            Session::set('usuario', $this->seguro($registro, ['perfil_nome' => $perfil]));
        } else {
            Session::set('cliente', $this->seguro($registro));
        }
        Session::set(Auth::sessionKey($this->nome, 'identidade'), $this->nome === 'usuario' ? Session::get('usuario') : Session::get('cliente'));
        $this->ultimoAcesso($this->nome === 'cliente' ? 'clientes' : 'usuarios', $id);
        if ($lembrar) $this->criarLembranca($id);
        Logger::info('Autenticação realizada', ['fluxo' => $this->nome, 'registro_id' => $id]);
    }
    public function check(): bool
    {
        $id = Session::get(Auth::sessionKey($this->nome, 'id'));
        if (!$id) return false;
        $cache = Auth::sessionKey($this->nome, 'validado');
        if (isset(Auth::$validacao[$cache]) && Auth::$validacao[$cache]['id'] === (int)$id) return Auth::$validacao[$cache]['ok'];
        try {
            $registro = (new ($this->modelo)())->find((int)$id);
            $ok = (bool)($registro && !empty($registro->ativo));
        } catch (\Throwable $e) {
            Logger::error('Falha ao validar autenticação', ['fluxo' => $this->nome]);
            $ok = false;
        }
        Auth::$validacao[$cache] = ['id' => (int)$id, 'ok' => $ok];
        if (!$ok) $this->limparSessao();
        return $ok;
    }
    public function identidade(): ?object
    {
        return Session::get(Auth::sessionKey($this->nome, 'identidade'));
    }
    public function id(): ?int
    {
        $id = Session::get(Auth::sessionKey($this->nome, 'id'));
        return $id ? (int)$id : null;
    }
    public function perfil(): string
    {
        return $this->nome === 'usuario' ? (string)Session::get('usuario_perfil', '') : 'cliente';
    }
    public function logout(): void
    {
        $id = $this->id();
        $this->invalidarLembranca($id);
        $this->limparSessao();
        if ($id) Logger::info('Logout realizado', ['fluxo' => $this->nome, 'registro_id' => $id]);
    }
    public function recuperarCookie(): bool
    {
        if ($this->check()) return true;
        $cfg = Auth::rememberConfig($this->nome);
        $token = $_COOKIE[$cfg['cookie']] ?? '';
        if (!is_string($token) || $token === '') return false;
        try {
            $registro = Database::getInstance()->query("SELECT * FROM {$cfg['tabela']} WHERE {$cfg['campo']} = :token AND {$cfg['expira']} > NOW() AND ativo = 1 LIMIT 1")->bind(':token', hash('sha256', $token))->fetch();
        } catch (\Throwable) {
            return false;
        }
        if (!$registro) {
            $this->limparCookie($cfg['cookie']);
            return false;
        }
        $this->login($registro, true);
        return true;
    }
    private function perfilDoUsuario(int $id, object $registro): string
    {
        $perfil = (new UsuarioRepository())->findComPerfil($id);
        return Auth::normalizarPerfil((string)($perfil->perfil_nome ?? $registro->perfil_nome ?? ''));
    }
    private function seguro(object $registro, array $extras = []): object
    {
        $dados = (array)$registro;
        unset($dados['password'], $dados['password_cliente'], $dados['lembrar_token'], $dados['lembrar_cliente_token'], $dados['token_lembrar_expira_em'], $dados['token_cliente_lembrar_expira_em']);
        return (object)array_merge($dados, $extras);
    }
    private function ultimoAcesso(string $tabela, int $id): void
    {
        try {
            Database::getInstance()->query("UPDATE {$tabela} SET ultimo_login_em = :data WHERE id = :id")->bind(':data', date('Y-m-d H:i:s'))->bind(':id', $id)->execute();
        } catch (\Throwable) {
        }
    }
    private function criarLembranca(int $id): void
    {
        $cfg = Auth::rememberConfig($this->nome);
        $token = Session::generateToken();
        $expira = date('Y-m-d H:i:s', time() + 2592000);
        try {
            Database::getInstance()->query("UPDATE {$cfg['tabela']} SET {$cfg['campo']} = :token, {$cfg['expira']} = :expira WHERE id = :id")->bind(':token', hash('sha256', $token))->bind(':expira', $expira)->bind(':id', $id)->execute();
            setcookie($cfg['cookie'], $token, ['expires' => time() + 2592000, 'path' => '/', 'secure' => Session::shouldUseSecureCookies(defined('APP_ENV') ? APP_ENV : 'production'), 'httponly' => true, 'samesite' => 'Lax']);
        } catch (\Throwable) {
        }
    }
    private function invalidarLembranca(?int $id): void
    {
        $cfg = Auth::rememberConfig($this->nome);
        if ($id) {
            try {
                Database::getInstance()->query("UPDATE {$cfg['tabela']} SET {$cfg['campo']} = NULL, {$cfg['expira']} = NULL WHERE id = :id")->bind(':id', $id)->execute();
            } catch (\Throwable) {
            }
        }
        $this->limparCookie($cfg['cookie']);
    }
    private function limparCookie(string $nome): void
    {
        setcookie($nome, '', ['expires' => time() - 3600, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax']);
        unset($_COOKIE[$nome]);
    }
    private function limparSessao(): void
    {
        Session::forget(Auth::sessionKey($this->nome, 'id'));
        Session::forget(Auth::sessionKey($this->nome, 'identidade'));
        Session::forget(Auth::sessionKey($this->nome, 'validado'));
        if ($this->nome === 'usuario') {
            Session::forget('usuario');
            Session::forget('usuario_perfil');
        } else Session::forget('cliente');
    }
}
