<?php

namespace App\Repositories;

use Framework\Database\Repository;
use App\Models\EnderecoClienteModel;

/**
 * EnderecoRepository
 * ─────────────────────────────────────────────────────────────────────────────
 * Encapsula todas as queries relacionadas à entidade Endereco.
 *
 * Herda do Repository base:
 *   all(), findById(), create(), update(), delete(), paginate()
 *
 * Adicione aqui apenas queries específicas desta entidade.
 * Lógica de negócio → Service (nunca aqui).
 *
 * Uso no Controller ou Service:
 *   $repo = new EnderecoRepository();
 *   $item = $repo->findById(42);
 *   $list = $repo->paginate(15, (int)($_GET['page'] ?? 1));
 */
class EnderecoRepository extends Repository
{
    /** Model associado a este repository */
    protected string $modelClass = EnderecoClienteModel::class;

    // ── Buscas customizadas ───────────────────────────────────────────────────

    /**
     * Busca por nome exato.
     */
    public function findByNome(string $nome): object|false
    {
        return $this->model()->findBy('nome', $nome);
    }

    /**
     * Busca paginada com filtro de texto por nome.
     * Usa o query builder do Model — evite escrever SQL manual aqui.
     *
     * @return array{data: array, total: int, page: int, per_page: int, last_page: int, from: int, to: int}
     */
    public function search(string $term = '', int $page = 1, int $perPage = 15): array
    {
        return $this->model()
            ->whereLike('nome', $term)
            ->orderBy('id', 'DESC')
            ->paginate($perPage, $page);
    }

    /**
     * Remove (soft delete) todos os endereços de um cliente.
     *
     * Usado ao excluir o cliente: como os dois usam soft delete, o
     * ON DELETE CASCADE do banco não dispara.
     */
    public function deleteByCliente(int $clienteId): bool
    {
        return $this->model()
            ->where('cliente_id', $clienteId)
            ->whereNull('deleted_at')
            ->deleteWhere();
    }
}
