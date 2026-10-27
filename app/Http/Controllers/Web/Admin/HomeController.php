<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\BaseController;
use App\Repositories\ClienteRepository;
use App\Repositories\ProdutoRepository;
use App\Repositories\UsuarioRepository;

/**
 * HomeController
 * ─────────────────────────────────────────────────────────────────────────────
 * Responsável por receber as requisições HTTP, delegar para o Service/Repository
 * e retornar a resposta adequada (View ou JSON).
 *
 * Regra: controllers devem ser finos.
 * Lógica de negócio → Service | Acesso a dados → Repository
 *
 * Upload de arquivo (se o Model tiver algum campo de arquivo):
 *   Os blocos de upload em store()/update() estão comentados por padrão.
 *   Descomente e ajuste o nome do campo (ex: 'imagem', 'avatar', 'anexo').
 *   Ver Framework\Support\Upload e BaseController::saveUploadedFile()/replaceUploadedFile()
 *   para detalhes.
 */
class HomeController extends BaseController
{
    // Caminho base das views
    private string $rota = 'admin.home';

    private UsuarioRepository $usuarioRepository;
    private ClienteRepository $clienteRepository;
    private ProdutoRepository $produtoRepository;

    public function __construct()
    {
        $this->usuarioRepository = new UsuarioRepository();
        $this->clienteRepository = new ClienteRepository();
        $this->produtoRepository = new ProdutoRepository();
    }

    public function index(): void
    {
        $usuarios = $this->usuarioRepository->tudoComPerfil();
        $clientes = $this->clienteRepository->tudoComEndereco();
        $produtos = $this->produtoRepository->tudoComCategoria();

        $data = [
            'titulo' => 'Dashboard',
            'subtitulo' => 'Painel Administrativo',

            'totalUsuarios' => count($usuarios),
            'totalClientes' => count($clientes),
            'totalProdutos' => count($produtos),
        ];

        $this->view($this->rota . '.index', $data, 'main');
    }
}
