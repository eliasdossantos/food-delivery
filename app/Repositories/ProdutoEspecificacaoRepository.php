<?php

namespace App\Repositories;

use App\Models\ProdutoEspecificacaoModel;
use Framework\Database\Repository;

/**
 * ProdutoEspecificacaoRepository
 * ─────────────────────────────────────────────────────────────────────────────
 * Encapsula todas as queries relacionadas à entidade ProdutoEspecificacao.
 *
 * Herda do Repository base:
 *   all(), findById(), create(), update(), delete(), paginate()
 *
 * Adicione aqui apenas queries específicas desta entidade.
 * Lógica de negócio → Service (nunca aqui).
 *
 * Uso no Controller ou Service:
 *   $repo = new ProdutoEspecificacaoRepository();
 *   $item = $repo->findById(42);
 *   $list = $repo->paginate(15, (int)($_GET['page'] ?? 1));
 */
class ProdutoEspecificacaoRepository extends Repository
{
    /** Model associado a este repository */
    protected string $modelClass = ProdutoEspecificacaoModel::class;

    /** Colunas explícitas: produtos_especificacoes.id não pode ser sobrescrito por medidas.id */
    private function queryComMedida()
    {
        return $this->model()
            ->select(
                'produtos_especificacoes.*',
                'medidas.nome AS medida_nome',
                'medidas.unidade AS medida_unidade'
            )
            ->join(
                'medidas',
                'medidas.id',
                '=',
                'produtos_especificacoes.medida_id'
            );
    }

    public function getDoProduto(int $produtoId): array
    {
        return $this->queryComMedida()
            ->whereRaw(
                'produtos_especificacoes.produto_id = :produto_id',
                ['produto_id' => $produtoId]
            )
            ->orderBy('medidas.ordem_exibicao', 'ASC')
            ->orderBy('medidas.nome', 'ASC')
            ->get();
    }

    /**
     * Todas as especificações, agrupadas por produto_id (uma query só, para o index).
     *
     * @return array<int, array>
     */
    public function getAgrupadasPorProduto(): array
    {
        $linhas = $this->queryComMedida()
            ->orderBy('medidas.ordem_exibicao', 'ASC')
            ->orderBy('medidas.nome', 'ASC')
            ->get();

        $agrupado = [];

        foreach ($linhas as $linha) {
            $agrupado[(int) $linha->produto_id][] = $linha;
        }

        return $agrupado;
    }

    /**
     * Substitui as especificações atuais pelas enviadas.
     * $itens: [['medida_id' => int, 'preco' => float, 'customizavel' => 0|1], ...] já validados.
     */
    public function sincronizar(int $produtoId, array $itens): bool
    {
        $this->model()
            ->where('produto_id', $produtoId)
            ->deleteWhere();

        foreach ($itens as $item) {
            $id = $this->create([
                'produto_id'   => $produtoId,
                'medida_id'    => $item['medida_id'],
                'preco'        => $item['preco'],
                'customizavel' => $item['customizavel'],
            ]);

            if (!$id) {
                return false;
            }
        }

        return true;
    }
}