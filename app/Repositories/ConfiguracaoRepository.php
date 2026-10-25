<?php

namespace App\Repositories;

use Framework\Database\Repository;
use App\Models\ConfiguracaoModel;

/**
 * ConfiguracaoRepository
 * ─────────────────────────────────────────────────────────────────────────────
 * Encapsula todas as queries relacionadas à entidade Configuracao.
 *
 * Herda do Repository base:
 *   all(), findById(), create(), update(), delete(), paginate()
 *
 * Adicione aqui apenas queries específicas desta entidade.
 * Lógica de negócio → Service (nunca aqui).
 *
 * Uso no Controller ou Service:
 *   $repo = new ConfiguracaoRepository();
 *   $item = $repo->findById(42);
 *   $list = $repo->paginate(15, (int)($_GET['page'] ?? 1));
 */
class ConfiguracaoRepository extends Repository
{
    /** Model associado a este repository */
    protected string $modelClass = ConfiguracaoModel::class;

    // ── Buscas customizadas ───────────────────────────────────────────────────

    /**
     * Busca por nome exato.
     */
    public function findByNome(string $nome): object|false
    {
        return $this->model()->findBy('nome_fantasia', $nome);
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
            ->whereLike('nome_fantasia', $term)
            ->orderBy('id', 'DESC')
            ->paginate($perPage, $page);
    }
}