<?php

namespace App\Models;

use Framework\Database\Model;

/**
 * CategoriaModel
 * ─────────────────────────────────────────────────────────────────────────────
 * Model responsável pela tabela `categorias`.
 *
 * Propriedades principais:
 *   $table      → nome da tabela no banco
 *   $fillable   → campos permitidos para INSERT/UPDATE (whitelist de segurança)
 *   $hidden     → campos excluídos da serialização (ex: senha, tokens)
 *   $timestamps → gerencia created_at/updated_at automaticamente
 *   $softDelete → usa deleted_at ao invés de DELETE físico
 */
class CategoriaModel extends Model
{
    /** Tabela correspondente no banco de dados */
    protected string $table = 'categorias';

    /**
     * Campos aceitos em create() e update().
     * Campos fora desta lista são ignorados silenciosamente.
     */
    protected array $fillable = [
        'nome',
        'slug',
        'descricao',
        'imagem_url',
        'icone',
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
}
