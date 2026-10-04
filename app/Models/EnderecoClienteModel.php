<?php

namespace App\Models;

use Framework\Database\Model;

/**
 * EnderecoClienteModel
 * ─────────────────────────────────────────────────────────────────────────────
 * Model responsável pela tabela `enderecos_clientes`.
 *
 * Cada registro representa um endereço pertencente a um cliente.
 *
 * Propriedades principais:
 *   $table      → nome da tabela no banco
 *   $fillable   → campos permitidos para INSERT/UPDATE
 *   $timestamps → gerencia created_at/updated_at automaticamente
 *   $softDelete → usa deleted_at ao invés de DELETE físico
 */
class EnderecoClienteModel extends Model
{
    /** Tabela correspondente no banco de dados */
    protected string $table = 'enderecos_clientes';

    /**
     * Campos aceitos em create() e update().
     */
    protected array $fillable = [
        'cliente_id',
        'nome',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'referencia',
        'principal',
    ];

    /** Gerencia created_at e updated_at automaticamente */
    protected bool $timestamps = true;

    /** Usa deleted_at (soft delete) ao invés de DELETE físico */
    protected bool $softDelete = true;

    // ── Buscas ────────────────────────────────────────────────────────────────

    /**
     * Retorna todos os endereços de um cliente.
     */
    public function findByCliente(int $clienteId): array
    {
        return $this
            ->where('cliente_id', $clienteId)
            ->orderBy('principal', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();
    }

    /**
     * Busca o endereço principal de um cliente.
     */
    public function findPrincipal(int $clienteId): object|false
    {
        return $this
            ->where('cliente_id', $clienteId)
            ->where('principal', 1)
            ->first();
    }

    /**
     * Define um endereço como principal.
     *
     * Remove a marcação de principal dos demais endereços
     * do cliente e define o endereço informado como principal.
     */
    public function setPrincipal(int $id, int $clienteId): bool
    {
        // Remove a marcação dos demais endereços.
        $this
            ->where('cliente_id', $clienteId)->updateWhere(['principal' => 0,]);

        // Define o endereço escolhido como principal.
        return $this->update($id, [
            'principal' => 1,
        ]);
    }
}