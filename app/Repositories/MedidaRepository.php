<?php

namespace App\Repositories;

use Framework\Database\Repository;
use App\Models\MedidaModel;

/**
 * MedidaRepository
 * ─────────────────────────────────────────────────────────────────────────────
 * Encapsula todas as queries relacionadas à entidade Medida.
 *
 * Herda do Repository base:
 *   all(), findById(), create(), update(), delete(), paginate()
 *
 * Adicione aqui apenas queries específicas desta entidade.
 * Lógica de negócio → Service (nunca aqui).
 *
 * Uso no Controller ou Service:
 *   $repo = new MedidaRepository();
 *   $item = $repo->findById(42);
 *   $list = $repo->paginate(15, (int)($_GET['page'] ?? 1));
 */
class MedidaRepository extends Repository
{
    /** Model associado a este repository */
    protected string $modelClass = MedidaModel::class;

    // ── Buscas customizadas ───────────────────────────────────────────────────

    /**
     * Busca por nome exato.
     */
    public function findByNome(string $nome): object|false
    {
        return $this->model()->findBy('nome', $nome);
    }

    /**
     * Retorna registros ativos (se a tabela tiver campo 'ativo').
     */
    public function getAtiva(): array
    {
        return $this->model()
            ->where('ativo', 1)
            ->orderBy('nome', 'ASC')
            ->get();
    }

    /**
     * Retorna apenas medidas ativas, ordenadas pela ordem de exibição.
     */
    public function getAtivas(): array
    {
        return $this->model()
            ->where('ativo', 1)
            ->orderBy('ordem_exibicao', 'ASC')
            ->orderBy('nome', 'ASC')
            ->get();
    }

    /**
     * Retorna todas as medidas ordenadas pelo campo 'ordem_exibicao'.
     */
    public function getOrdenadas(string $direction = 'ASC'): array
    {
        return $this->model()
            ->orderBy('ordem_exibicao', $direction)
            ->get();
    }

    /**
     * Busca paginada com filtro de texto por nome.
     *
     * @return array{data: array, total: int, page: int, per_page: int, last_page: int, from: int, to: int}
     */
    public function search(string $term = '', int $page = 1, int $perPage = 15): array
    {
        return $this->model()
            ->whereLike('nome', $term)
            ->orderBy('ordem_exibicao', 'ASC')
            ->paginate($perPage, $page);
    }

    // ── Atualizações de Status e Ordem ────────────────────────────────────────

    /**
     * Alterna o status 'ativo' de uma medida (Ativa/Inativa).
     */
    public function toggleAtivo(int $id): bool
    {
        $medida = $this->findById($id);

        if (!$medida) {
            return false;
        }

        $novoStatus = $medida->ativo ? 0 : 1;

        return $this->update($id, ['ativo' => $novoStatus]);
    }

    /**
     * Alterna o status 'destaque' de uma medida (Em Destaque/Normal).
     */
    public function toggleDestaque(int $id): bool
    {
        $medida = $this->findById($id);

        if (!$medida) {
            return false;
        }

        $novoDestaque = $medida->destaque ? 0 : 1;

        return $this->update($id, ['destaque' => $novoDestaque]);
    }

    /**
     * Atualiza o valor do campo 'ordem_exibicao' de uma medida específica.
     */
    public function updateOrdem(int $id, int $novaOrdem): bool
    {
        return $this->update($id, ['ordem_exibicao' => $novaOrdem]);
    }
}
