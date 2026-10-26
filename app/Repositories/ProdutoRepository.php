<?php

namespace App\Repositories;

use Framework\Database\Repository;
use App\Models\ProdutoModel;
use App\Repositories\Concerns\BuscaPorNomeRepository;

/**
 * ProdutoRepository
 * ─────────────────────────────────────────────────────────────────────────────
 * Encapsula todas as queries relacionadas à entidade Produto.
 *
 * Herda do Repository base:
 *   all(), findById(), create(), update(), delete(), paginate()
 *
 * Adicione aqui apenas queries específicas desta entidade.
 * Lógica de negócio → Service (nunca aqui).
 *
 * Uso no Controller ou Service:
 *   $repo = new ProdutoRepository();
 *   $item = $repo->findById(42);
 *   $list = $repo->paginate(15, (int)($_GET['page'] ?? 1));
 */
class ProdutoRepository extends Repository
{
    use BuscaPorNomeRepository;

    /** Model associado a este repository */
    protected string $modelClass = ProdutoModel::class;

    // ── Buscas customizadas ───────────────────────────────────────────────────

    /**
     * Busca por nome exato.
     */
    public function findByNome(string $nome): object|false
    {
        return $this->model()->findBy('nome', $nome);
    }

    /**
     * Busca por slug exato.
     */
    public function findBySlug(string $slug): object|false
    {
        return $this->model()->findBy('slug', $slug);
    }

    /**
     * Busca um produto pelo ID com o nome da categoria (categoria_nome).
     * LEFT JOIN: produto sem categoria retorna categoria_nome = NULL.
     *
     * Usa whereRaw() porque where() gera o nome do parâmetro a partir da
     * coluna, e "produtos.id" produziria um placeholder inválido (com ponto).
     */
    public function findComCategoria(int $id): object|false
    {
        return $this->queryComCategoria()
            ->whereRaw('produtos.id = :produto_id', ['produto_id' => $id])
            ->first();
    }

    /**
     * Retorna produtos ativos com o nome da categoria.
     */
    public function getActive(): array
    {
        return $this->queryComCategoria()
            ->whereRaw('produtos.ativo = :ativo', ['ativo' => 1])
            ->orderBy('produtos.nome', 'ASC')
            ->get();
    }

    /**
     * Retorna produtos ativos ordenados pela ordem de exibição,
     * incluindo o nome da categoria.
     */
    public function getAtivas(): array
    {
        return $this->queryComCategoria()
            ->whereRaw('produtos.ativo = :ativo', ['ativo' => 1])
            ->orderBy('produtos.ordem_exibicao', 'ASC')
            ->orderBy('produtos.nome', 'ASC')
            ->get();
    }

    /**
     * Retorna produtos em destaque com o nome da categoria.
     */
    public function getDestaques(bool $apenasAtivas = true): array
    {
        $query = $this->queryComCategoria()
            ->whereRaw('produtos.destaque = :destaque', ['destaque' => 1]);

        if ($apenasAtivas) {
            $query->whereRaw('produtos.ativo = :ativo', ['ativo' => 1]);
        }

        return $query
            ->orderBy('produtos.ordem_exibicao', 'ASC')
            ->orderBy('produtos.nome', 'ASC')
            ->get();
    }

    /**
     * Retorna os produtos de uma categoria (ex.: vitrine por categoria).
     */
    public function getPorCategoria(int $categoriaId, bool $apenasAtivas = true): array
    {
        $query = $this->queryComCategoria()
            ->whereRaw('produtos.categoria_id = :categoria_id', ['categoria_id' => $categoriaId]);

        if ($apenasAtivas) {
            $query->whereRaw('produtos.ativo = :ativo', ['ativo' => 1]);
        }

        return $query
            ->orderBy('produtos.ordem_exibicao', 'ASC')
            ->orderBy('produtos.nome', 'ASC')
            ->get();
    }

    /**
     * Retorna todos os produtos com o nome da categoria,
     * ordenados pela ordem de exibição.
     */
    public function getOrdenadas(string $direction = 'ASC'): array
    {
        $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';

        return $this->queryComCategoria()
            ->orderBy('produtos.ordem_exibicao', $direction)
            ->get();
    }

    /**
     * Busca paginada por nome ou descrição, incluindo o nome da categoria.
     * O OR fica entre parênteses para não quebrar a precedência
     * com outras condições.
     *
     * @return array{data: array, total: int, page: int, per_page: int, last_page: int, from: int, to: int}
     */
    public function search(string $term = '', int $page = 1, int $perPage = 15): array
    {
        $like = '%' . $term . '%';

        return $this->queryComCategoria()
            ->whereRaw(
                '(produtos.nome LIKE :termo_nome OR produtos.descricao LIKE :termo_descricao)',
                [
                    'termo_nome'      => $like,
                    'termo_descricao' => $like,
                ]
            )
            ->orderBy('produtos.ordem_exibicao', 'ASC')
            ->orderBy('produtos.nome', 'ASC')
            ->paginate($perPage, $page);
    }

    /**
     * Retorna todos os produtos com os dados da categoria.
     */
    public function tudoComCategoria(): array
    {
        return $this->queryComCategoria()
            ->orderBy('produtos.nome', 'ASC')
            ->get();
    }

    // ── Slug ──────────────────────────────────────────────────────────────────

    /**
     * Verifica se o slug já está em uso por outro produto.
     *
     * @param int|null $ignorarId ID do produto em edição (ele não conta como duplicado)
     */
    public function slugExiste(string $slug, ?int $ignorarId = null): bool
    {
        $encontrado = $this->findBySlug($slug);

        if (!$encontrado) {
            return false;
        }

        return $ignorarId === null || (int) $encontrado->id !== $ignorarId;
    }

    /**
     * Gera um slug único a partir de um texto.
     *
     * Exemplos (já existindo "pizza-calabresa"):
     *   "Pizza Calabresa"  → "pizza-calabresa-2"
     *   "Açaí 500ml"       → "acai-500ml"
     *
     * @param string   $texto     Slug digitado ou, na falta dele, o nome do produto
     * @param int|null $ignorarId ID do produto em edição (evita conflito consigo mesmo)
     */
    public function gerarSlugUnico(string $texto, ?int $ignorarId = null): string
    {
        $base = slug($texto);

        if ($base === '') {
            $base = 'produto';
        }

        $slug     = $base;
        $contador = 2;

        while ($this->slugExiste($slug, $ignorarId)) {
            $slug = $base . '-' . $contador;
            $contador++;
        }

        return $slug;
    }

    // ── Atualizações de Status e Ordem ────────────────────────────────────────

    /**
     * Alterna o status 'ativo' de um produto (Ativo/Inativo).
     */
    public function toggleAtivo(int $id): bool
    {
        $produto = $this->findById($id);

        if (!$produto) {
            return false;
        }

        return $this->update($id, ['ativo' => $produto->ativo ? 0 : 1]);
    }

    /**
     * Alterna o status 'destaque' de um produto (Em Destaque/Normal).
     */
    public function toggleDestaque(int $id): bool
    {
        $produto = $this->findById($id);

        if (!$produto) {
            return false;
        }

        return $this->update($id, ['destaque' => $produto->destaque ? 0 : 1]);
    }

    /**
     * Atualiza o valor do campo 'ordem_exibicao' de um produto específico.
     */
    public function updateOrdem(int $id, int $novaOrdem): bool
    {
        return $this->update($id, ['ordem_exibicao' => $novaOrdem]);
    }

    /**
     * Atualiza a ordem de múltiplos produtos em lote (reordenação por drag-and-drop).
     *
     * @param array<int, int> $ordens Exemplo: [id_produto => posicao_ordem]
     */
    public function reordenarLote(array $ordens): bool
    {
        foreach ($ordens as $id => $ordem) {
            $this->updateOrdem((int) $id, (int) $ordem);
        }

        return true;
    }

    /**
     * Produtos mais vendidos (soma da quantidade em pedido_itens).
     * AJUSTE o nome da tabela/colunas conforme o seu banco de pedidos.
     */
    public function getMaisVendidos(int $limite = 6): array
    {
        return $this->queryComCategoria()
            ->select(
                'produtos.*',
                'categorias.nome AS categoria_nome',
                'SUM(pedido_itens.quantidade) AS total_vendido'
            )
            ->join('pedido_itens', 'pedido_itens.produto_id', '=', 'produtos.id')
            ->whereRaw('produtos.ativo = :ativo', ['ativo' => 1])
            ->groupBy('produtos.id')
            ->orderBy('total_vendido', 'DESC')
            ->limit($limite)
            ->get();
    }

    // ── Interno ───────────────────────────────────────────────────────────────

    /**
     * Base de todas as consultas que precisam do nome da categoria:
     * SELECT produtos.*, categorias.nome AS categoria_nome + LEFT JOIN.
     *
     * Colunas SEMPRE qualificadas (produtos.ativo, produtos.nome...) nas
     * condições, porque `produtos` e `categorias` têm colunas com o mesmo
     * nome (ativo, nome, destaque, slug, ordem_exibicao...).
     */
    private function queryComCategoria()
    {
        return $this->model()
            ->select(
                'produtos.*',
                'categorias.nome AS categoria_nome'
            )
            ->leftJoin(
                'categorias',
                'produtos.categoria_id',
                '=',
                'categorias.id'
            );
    }

    // ── Código (SKU) ──────────────────────────────────────────────────────────

    /**
     * Verifica se o código já está em uso por outro produto.
     *
     * @param int|null $ignorarId ID do produto em edição (ele não conta como duplicado)
     */
    public function codigoExiste(string $codigo, ?int $ignorarId = null): bool
    {
        $encontrado = $this->model()->findBy('codigo', $codigo);

        if (!$encontrado) {
            return false;
        }

        return $ignorarId === null || (int) $encontrado->id !== $ignorarId;
    }

    /**
     * Gera um código único no formato LLL-NNN a partir do nome do produto.
     *
     * Exemplos:
     *   "Pizza Calabresa"  → "PIZ-001"  (e "PIZ-002" para o próximo "Pizza...")
     *   "Açaí 500ml"       → "ACA-001"
     *   "X"                → "XXX-001"   (completa com X se tiver menos de 3 letras)
     *
     * @param string   $nome      Nome do produto (base do prefixo)
     * @param int|null $ignorarId ID do produto em edição
     */
    public function gerarCodigoUnico(string $nome, ?int $ignorarId = null): string
    {
        // slug() remove acentos; depois ficam só as letras
        $letras  = preg_replace('/[^a-z]/', '', slug($nome));
        $prefixo = strtoupper(str_pad(substr($letras, 0, 3), 3, 'X'));

        $numero = 1;

        do {
            $codigo = sprintf('%s-%03d', $prefixo, $numero);
            $numero++;
        } while ($this->codigoExiste($codigo, $ignorarId));

        return $codigo;
    }
}