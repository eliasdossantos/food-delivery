<?php

namespace App\Models;

use Framework\Database\Model;

/**
 * ProdutoModel
 * ─────────────────────────────────────────────────────────────────────────────
 * Model responsável pela tabela `produtos`.
 *
 * Propriedades principais:
 *   $table      → nome da tabela no banco
 *   $fillable   → campos permitidos para INSERT/UPDATE (whitelist de segurança)
 *   $hidden     → campos excluídos da serialização (ex: senha, tokens)
 *   $timestamps → gerencia created_at/updated_at automaticamente
 *   $softDelete → usa deleted_at ao invés de DELETE físico
 */
class ProdutoModel extends Model
{
    /** Tabela correspondente no banco de dados */
    protected string $table = 'produtos';

    /**
     * Campos aceitos em create() e update().
     * Campos fora desta lista são ignorados silenciosamente.
     */
    protected array $fillable = [
        'categoria_id',
        'nome',
        'slug',
        'descricao',
        'ingredientes',
        'imagem_url',
        'preco',
        'preco_promocional',
        'tempo_preparo',
        'controla_estoque',
        'estoque',
        'ativo',
        'destaque',
        'ordem_exibicao',
        // adicione os campos do seu model aqui
    ];

    /**
     * Campos ocultos ao serializar o objeto (ex: para JSON de API).
     */
    protected array $hidden = [];

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
