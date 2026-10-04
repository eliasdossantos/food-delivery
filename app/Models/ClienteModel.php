<?php

namespace App\Models;

use Framework\Database\Model;

/**
 * ClienteModel
 * ─────────────────────────────────────────────────────────────────────────────
 * Model responsável pela tabela `clientes`.
 *
 * Propriedades principais:
 *   $table      → nome da tabela no banco
 *   $fillable   → campos permitidos para INSERT/UPDATE
 *   $hidden     → campos excluídos da serialização
 *   $timestamps → gerencia created_at/updated_at automaticamente
 *   $softDelete → usa deleted_at ao invés de DELETE físico
 */
class ClienteModel extends Model
{
    /** Tabela correspondente no banco de dados */
    protected string $table = 'clientes';

    /**
     * Campos aceitos em create() e update().
     */
    protected array $fillable = [
        'nome',
        'email',
        'celular',
        'cpf',
        'data_nascimento',
        'password_cliente',
        'email_verificado_em',
        'ultimo_login_em',
        'lembrar_cliente_token',
        'token_cliente_lembrar_expira_em',
        'avatar',
        'ativo',
    ];

    /**
     * Campos ocultos ao serializar o objeto.
     */
    protected array $hidden = [
        'password_cliente',
        'lembrar_cliente_token',
    ];

    /** Gerencia created_at e updated_at automaticamente */
    protected bool $timestamps = true;

    /** Usa deleted_at (soft delete) ao invés de DELETE físico */
    protected bool $softDelete = true;

    // ── Buscas customizadas ───────────────────────────────────────────────────

    /**
     * Busca um cliente pelo nome.
     */
    public function findByNome(string $nome): object|false
    {
        return $this->findBy('nome', $nome);
    }

    /**
     * Busca um cliente pelo e-mail.
     */
    public function findByEmail(string $email): object|false
    {
        return $this->findBy('email', $email);
    }

    /**
     * Busca um cliente pelo celular.
     */
    public function findByCelular(string $celular): object|false
    {
        return $this->findBy('celular', $celular);
    }

    /**
     * Busca paginada de clientes por nome.
     */
    public function search(
        string $term = '',
        int $page = 1,
        int $perPage = 15
    ): array {
        return $this
            ->whereLike('nome', $term)
            ->orderBy('id', 'DESC')
            ->paginate($perPage, $page);
    }

    // ── Autenticação ──────────────────────────────────────────────────────────

    /**
     * Autentica um cliente utilizando e-mail e senha.
     *
     * Chamado pelo fluxo de autenticação de clientes.
     */
    public function authenticate(string $email, string $password): object|false
    {
        $cliente = $this->findByEmail($email);

        if (!$cliente) {
            return false;
        }

        if (empty($cliente->ativo)) {
            return false;
        }

        if (!password_verify($password, $cliente->password_cliente)) {
            return false;
        }

        return $cliente;
    }

    /**
     * Cria um cliente armazenando a senha com hash.
     */
    public function createWithHash(array $data): string|false
    {
        $data['password_cliente'] = password_hash(
            $data['password_cliente'],
            PASSWORD_BCRYPT,
            ['cost' => 12]
        );

        $data['ativo'] ??= 1;

        return $this->create($data);
    }

    /**
     * Atualiza a senha de um cliente.
     */
    public function updatePassword(int $id, string $newPassword): bool
    {
        return $this->update($id, [
            'password_cliente' => password_hash(
                $newPassword,
                PASSWORD_BCRYPT,
                ['cost' => 12]
            ),
        ]);
    }
}
