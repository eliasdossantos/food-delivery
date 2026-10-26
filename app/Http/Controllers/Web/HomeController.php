<?php

namespace App\Http\Controllers\Web;

use App\Repositories\CategoriaRepository;
use App\Repositories\ProdutoEspecificacaoRepository;
use App\Repositories\ProdutoExtraRepository;
use App\Repositories\ProdutoRepository;
use App\Repositories\SobreNosRepository;

class HomeController extends BaseController
{
    private $sobreNosModel;
    private $categoriaModel;
    private $produtoModel;
    private $produtoEspecificacaoModel;
    private $produtoExtraModel;

    public function __construct()
    {
        $this->sobreNosModel = new SobreNosRepository();
        $this->categoriaModel = new CategoriaRepository();
        $this->produtoModel = new ProdutoRepository();
        $this->produtoEspecificacaoModel = new ProdutoEspecificacaoRepository();
        $this->produtoExtraModel = new ProdutoExtraRepository();
    }

    public function index(): void
    {
        $produtosPorCategoria = $this->getProdutosPorCategoria();

        $data = [
            'titulo' => 'Sejam todos bem-vindos(as)!',
            'sobreNos' => $this->sobreNosModel->ativo(),
            'categorias' => $this->categoriaModel->getAtivas(),
            'produtosPorCategoria' => $produtosPorCategoria,
            'especificacoesPorProduto' => $this->produtoEspecificacaoModel->getAgrupadasPorProduto(),
        ];

        $this->view('home.index', $data, 'home');
    }

    /**
     * Tela com todos os produtos (abas por categoria + detalhes).
     */
    public function produtos(): void
    {
        $produtos = $this->produtoModel->getAtivas();

        $data = [
            'titulo' => 'Todos os produtos',
            'categorias' => $this->categoriaModel->getAtivas(),
            'produtos' => $produtos,
            'produtosPorCategoria' => $this->getProdutosPorCategoria($produtos),
            'especificacoesPorProduto' => $this->produtoEspecificacaoModel->getAgrupadasPorProduto(),
            'extrasPorProduto' => $this->produtoExtraModel->getExtrasAgrupadosPorProduto(),
        ];

        $this->view('home.produtos', $data, 'home');
    }

    /**
     * Agrupa os produtos por categoria_id.
     * Se a lista não for informada, busca os produtos ativos.
     */
    private function getProdutosPorCategoria(?array $produtos = null): array
    {
        $produtos ??= $this->produtoModel->getAtivas();
        $produtosPorCategoria = [];

        foreach ($produtos as $produto) {
            $produtosPorCategoria[$produto->categoria_id][] = $produto;
        }

        return $produtosPorCategoria;
    }
}
