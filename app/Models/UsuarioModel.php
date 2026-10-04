<?php

namespace App\Models;

use Framework\Database\Model;

/**
 * UsuarioModel
 * ─────────────────────────────────────────────────────────────────────────────
 * Model responsável pela tabela `usuarios`.
 *
 * Propriedades principais:
 *   $table      → nome da tabela no banco
 *   $fillable   → campos permitidos para INSERT/UPDATE (whitelist de segurança)
 *   $hidden     → campos excluídos da serialização (ex: senha, tokens)
 *   $timestamps → gerencia created_at/updated_at automaticamente
 *   $softDelete → usa deleted_at ao invés de DELETE físico
 */
class UsuarioModel extends Model
{
    /** Tabela correspondente no banco de dados */
    protected string $table = 'usuarios';

    /**
     * Campos aceitos em create() e update().
     * Campos fora desta lista são ignorados silenciosamente.
     */
    protected array $fillable = [
        'perfil_id',
        'nome',
        'cpf',
        'celular',
        'email',
        'password',
        'email_verificado_em',
        'ultimo_login_em',
        'lembrar_token',
        'ativo',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'referencia',
        // adicione os campos do seu model aqui
    ];

    /**
     * Campos ocultos ao serializar o objeto (ex: para JSON de API).
     */
    protected array $hidden = [
        'password',
        'lembrar_token',
    ];

    /** Gerencia created_at e updated_at automaticamente */
    protected bool $timestamps = true;

    /** true = usa deleted_at (soft delete) ao invés de DELETE físico */
    protected bool $softDelete = false;

    // ── Buscas customizadas ───────────────────────────────────────────────────

    /**
     * Exemplo: busca por nome (exato).
     * Adicione aqui métodos de busca específicos desta entidade.
     */
    public function findByNome(string $nome): object|false
    {
        return $this->findBy('nome', $nome);
    }

    /**
     * Exemplo: busca paginada com filtro por nome.
     * Usa o query builder do próprio Model — evite escrever SQL manual aqui.
     */
    public function search(string $term = '', int $page = 1, int $perPage = 15): array
    {
        return $this
            ->whereLike('nome', $term)
            ->orderBy('id', 'DESC')
            ->paginate($perPage, $page);
    }

    public function findByEmail(string $email): object|false
    {
        return $this->findBy('email', $email);
    }

    /**
     * Autentica email + password.
     * Chamado por Framework\Auth\Auth::attempt().
     */
    public function authenticate(string $email, string $password): object|false
    {
        $user = $this->findByEmail($email);
        if (!$user)                                        return false;
        if (empty($user->ativo))                            return false;
        if (!password_verify($password, $user->password))     return false;
        return $user;
    }

    public function createWithHash(array $data): string|false
    {
        $data['password']    = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        $data['ativo']  ??= 1;
        return $this->create($data);
    }

    public function updatePassword(int $id, string $newPassword): bool
    {
        return $this->update($id, [
            'password' => password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]),
        ]);
    }
}
