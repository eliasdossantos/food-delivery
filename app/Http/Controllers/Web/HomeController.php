<?php

namespace App\Http\Controllers\Web;

use App\Repositories\CategoriaRepository;
use App\Repositories\ProdutoEspecificacaoRepository;
use App\Repositories\ProdutoRepository;

class HomeController extends BaseController
{
    public function index(): void
    {
        $categorias = (new CategoriaRepository())->getAtivas();
        $produtos   = (new ProdutoRepository())->getAtivas();

        // [categoria_id => [produto, produto...]]
        $produtosPorCategoria = [];
        foreach ($produtos as $produto) {
            $produtosPorCategoria[$produto->categoria_id][] = $produto;
        }

        $data = [
            'titulo'                   => 'Seja todos bem vindo(a)!',
            'categorias'               => $categorias,
            'produtosPorCategoria'     => $produtosPorCategoria,
            'especificacoesPorProduto' => (new ProdutoEspecificacaoRepository())->getAgrupadasPorProduto(),
        ];

        $this->view('home.index', $data, 'home');
    }
}