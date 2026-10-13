<?php

namespace App\Repositories;

use Framework\Database\Repository;
use App\Models\ProdutoExtraModel;

/**
 * ProdutoExtraRepository
 * ─────────────────────────────────────────────────────────────────────────────
 * Encapsula todas as queries relacionadas à entidade ProdutoExtra.
 *
 * Herda do Repository base:
 *   all(), findById(), create(), update(), delete(), paginate()
 *
 * Adicione aqui apenas queries específicas desta entidade.
 * Lógica de negócio → Service (nunca aqui).
 *
 * Uso no Controller ou Service:
 *   $repo = new ProdutoExtraRepository();
 *   $item = $repo->findById(42);
 *   $list = $repo->paginate(15, (int)($_GET['page'] ?? 1));
 */
class ProdutoExtraRepository extends Repository
{
    /** Model associado a este repository */
    protected string $modelClass = ProdutoExtraModel::class;

    // ── Buscas customizadas ───────────────────────────────────────────────────

    public function getExtrasDoProduto(int $produtoId): array
    {
        return $this->model()
            ->select(
                'extras.*',
                'produto_extras.produto_id AS produto_id'
            )
            ->join(
                'extras',
                'extras.id',
                '=',
                'produto_extras.extra_id'
            )
            ->whereRaw(
                'produto_extras.produto_id = :produto_id',
                ['produto_id' => $produtoId]
            )
            ->whereRaw('extras.ativo = :ativo', ['ativo' => 1])
            ->orderBy('extras.ordem_exibicao', 'ASC')
            ->orderBy('extras.nome', 'ASC')
            ->get();
    }

    public function getIdsDasExtrasDoProduto(int $produtoId): array
    {
        return $this->model()
            ->where('produto_id', $produtoId)
            ->pluck('extra_id');
    }

    /**
     * Substitui os vínculos atuais pelas extras selecionadas.
     *
     * $extraIds deve conter IDs já validados.
     */
    public function sincronizar(int $produtoId, array $extraIds): bool
    {
        $this->model()
            ->where('produto_id', $produtoId)
            ->deleteWhere();

        foreach ($extraIds as $extraId) {
            $id = $this->create([
                'produto_id' => $produtoId,
                'extra_id' => $extraId,
            ]);

            if (!$id) {
                return false;
            }
        }

        return true;
    }

    /**
     * Extras ativas de todos os produtos, agrupadas por produto_id.
     * Uma única query, para a listagem não fazer uma consulta por linha.
     *
     * @return array<int, array> [produto_id => [extra, extra, ...]]
     */
    public function getExtrasAgrupadosPorProduto(): array
    {
        $linhas = $this->model()
            ->select(
                'extras.*',
                'produto_extras.produto_id AS produto_id'
            )
            ->join(
                'extras',
                'extras.id',
                '=',
                'produto_extras.extra_id'
            )
            ->whereRaw('extras.ativo = :ativo', ['ativo' => 1])
            ->orderBy('extras.ordem_exibicao', 'ASC')
            ->orderBy('extras.nome', 'ASC')
            ->get();

        $agrupado = [];

        foreach ($linhas as $linha) {
            $agrupado[(int) $linha->produto_id][] = $linha;
        }

        return $agrupado;
    }
}
