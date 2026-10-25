<?php

namespace App\Models;

use Framework\Database\Model;

/**
 * ConfiguracaoModel
 * ─────────────────────────────────────────────────────────────────────────────
 * Model responsável pela tabela `configuracao_models`.
 *
 * Propriedades principais:
 *   $table      → nome da tabela no banco
 *   $fillable   → campos permitidos para INSERT/UPDATE (whitelist de segurança)
 *   $hidden     → campos excluídos da serialização (ex: senha, tokens)
 *   $timestamps → gerencia created_at/updated_at automaticamente
 *   $softDelete → usa deleted_at ao invés de DELETE físico
 */
class ConfiguracaoModel extends Model
{
    /** Tabela correspondente no banco de dados */
    protected string $table = 'configuracoes';

    /**
     * Campos aceitos em create() e update().
     * Campos fora desta lista são ignorados silenciosamente.
     */
    protected array $fillable = [
        'nome_fantasia',
        'razao_social',
        'cnpj',
        'descricao',
        'logo',
        'email',
        'telefone',
        'whatsapp',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'latitude',
        'longitude',
        'link_google_maps',
        'instagram',
        'facebook',
        'youtube',
        'twitter',
        'aceita_entrega',
        'aceita_retirada',
        'valor_pedido_minimo',
        'tempo_medio_entrega_min',
        'tempo_medio_retirada_min',
        'taxa_entrega_padrao',
        'frete_gratis_acima_de',
        'raio_entrega_km',
        'chave_pix',
        'meta_title',
        'meta_description',
        'texto_rodape',
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