<?php

namespace App\Services\Auth;

use App\Models\ClienteModel;
use App\Models\RedefinicaoSenha;
use App\Models\UsuarioModel;
use App\Support\Helpers\Mailer;
use Framework\Support\Session;

class RecuperacaoSenhaService
{
    public function solicitar(string $fluxo, string $email): void
    {
        $modelo = $fluxo === 'cliente' ? new ClienteModel() : new UsuarioModel();
        $registro = $modelo->findByEmail($email);
        if (!$registro) return;
        $token = (new RedefinicaoSenha())->createToken($email);
        $url = rtrim(defined('APP_URL') ? APP_URL : '', '/') . ($fluxo === 'cliente' ? '/cliente/redefinir-senha?token=' : '/admin/redefinir-senha?token=') . urlencode($token);
        (new Mailer())->send($email, 'Redefinição de senha', '<p>Olá,</p><p>Acesse o link para criar uma nova senha:</p><p><a href="' . e($url) . '">Redefinir senha</a></p><p>O link expira em uma hora.</p>', 'Acesse: ' . $url, (string)($registro->nome ?? ''));
    }
    public function redefinir(string $fluxo, string $token, string $senha): bool
    {
        $registro = (new RedefinicaoSenha())->findValid($token);
        if (!$registro) return false;
        $modelo = $fluxo === 'cliente' ? new ClienteModel() : new UsuarioModel();
        $conta = $modelo->findByEmail((string)$registro->email);
        if (!$conta || !$modelo->updatePassword((int)$conta->id, $senha)) return false;
        (new RedefinicaoSenha())->consume($token);
        return true;
    }
}
