<?php

namespace App\Models;

use Framework\Database\Model;

/**
 * EntregadorModel
 * ─────────────────────────────────────────────────────────────────────────────
 * Model responsável pela tabela `entregadores`.
 *
 * Propriedades principais:
 *   $table      → nome da tabela no banco
 *   $fillable   → campos permitidos para INSERT/UPDATE (whitelist de segurança)
 *   $hidden     → campos excluídos da serialização (ex: senha, tokens)
 *   $timestamps → gerencia created_at/updated_at automaticamente
 *   $softDelete → usa deleted_at ao invés de DELETE físico
 */
class EntregadorModel extends Model
{
    /** Tabela correspondente no banco de dados */
    protected string $table = 'entregadores';

    /**
     * Campos aceitos em create() e update().
     * Campos fora desta lista são ignorados silenciosamente.
     */
    protected array $fillable = [
        'tipo_vinculo',
        'nome',
        'email',
        'celular',
        'cpf',
        'documento_identidade',
        'data_nascimento',
        'cep',
        'logradouro',
        'numero',
        'bairro',
        'cidade',
        'estado',
        'password_entregador',
        'email_verificado_em',
        'ultimo_login_em',
        'lembrar_entregador_token',
        'token_entregador_lembrar_expira_em',
        'avatar',
        'numero_cnh',
        'categoria_cnh',
        'cnh_validade',
        'tipo_veiculo',
        'marca_veiculo',
        'modelo_veiculo',
        'placa_veiculo',
        'cor_veiculo',
        'tipo_remuneracao',
        'valor_remuneracao',
        'chave_pix',
        'ativo',
        'disponivel',
        'turno_manha',
        'turno_tarde',
        'turno_noite',
        'status_aprovacao',
        'motivo_reprovacao',
        'aprovado_por',
        'aprovado_em',
        'observacoes',
        // adicione os campos do seu model aqui
    ];

    /**
     * Campos ocultos ao serializar o objeto (ex: para JSON de API).
     */
    protected array $hidden = [
        'password_entregador',
        'lembrar_entregador_token',
        'token_entregador_lembrar_expira_em',
        'cpf',
        'data_nascimento',
        'documento_identidade',
        'numero_cnh',
        'chave_pix',
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
}
