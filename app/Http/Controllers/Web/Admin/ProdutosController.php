<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\BaseController;
use App\Http\Requests\Produtos\StoreProdutosRequest;
use App\Http\Requests\Produtos\UpdateProdutosRequest;
use App\Repositories\CategoriaRepository;
use App\Repositories\ProdutoRepository;
use Framework\Support\Session;

/**
 * ProdutosController
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
class ProdutosController extends BaseController
{
    /**
     * Nome da entidade usada como subpasta em public/uploads.
     * Exemplo: 'produtos' gera arquivos em public/uploads/produtos/.
     */
    private const UPLOAD_ENTITY = 'produtos';

    /**
     * Tamanho máximo permitido para o upload, em megabytes (MB).
     * Exemplo: 5 permite arquivos de até 5 MB.
     */
    private const UPLOAD_MAX_MB = 5;

    private ProdutoRepository $produtoModel;
    private CategoriaRepository $categoriaModel;

    public function __construct()
    {
        $this->produtoModel = new ProdutoRepository();
        $this->categoriaModel = new CategoriaRepository();
    }

    /**
     * Lista todos os registros.
     * GET /produtos
     */
    public function index(): void
    {
        $data = [
            'titulo' => 'Produtos',
            'subtitulo' => 'Lista de produtos',
            'produtos' => $this->produtoModel->all(),
        ];

        $this->view('admin.produtos.index', $data, 'main');
    }

    /**
     * Exibe um registro específico.
     * GET /produtos/{id}
     */
    public function show(int $id): void
    {
        $produtos = $this->produtoModel->findById($id);
        $this->abortUnless((bool)$produtos, 404, 'Produto não encontrada.');

        $data = [
            'titulo' => 'Produtos',
            'subtitulo' => 'Detalhes do Produto',
            'produtos' => $produtos,
        ];

        $this->view('admin.produtos.show', $data, 'main');
    }

    /**
     * Exibe o formulário de criação.
     * GET /produtos/create
     */
    public function create(): void
    {
        $data = [
            'titulo' => 'Novo Produtos',
            'subtitulo' => 'Criar novo Produtos',
            'uploadMaxMb' => self::UPLOAD_MAX_MB,
        ];

        $this->view('admin.produtos.create', $data, 'main');
    }

    /**
     * Processa a criação de um novo registro.
     * POST /produtos
     */
    public function store(StoreProdutosRequest $request): void
    {
        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $data = $request->validated();

        // Slug: o digitado ou, em branco, gerado a partir do nome (sempre único)
        $data['slug'] = $this->produtoModel->gerarSlugUnico(
            $data['slug'] !== '' ? $data['slug'] : $data['nome']
        );

        // ── Upload de arquivo ───────────
        $nomeArquivo = null;

        if (!empty($_FILES['imagem']['name'])) {
            $nomeArquivo = $this->saveUploadedFile(
                $_FILES['imagem'],
                self::UPLOAD_ENTITY,
                'image',
                self::UPLOAD_MAX_MB
            );

            if ($nomeArquivo === null) {
                Session::flash('error', $this->uploadErrors[0] ?? 'Falha no upload.');
                Session::flashInput($data);
                $this->back();
                return;
            }

            $data['imagem_url'] = $nomeArquivo;
        }

        $id = $this->produtoModel->create($data);

        if (!$id) {
            // Não deixa arquivo órfão em public/uploads se o registro não foi criado
            if ($nomeArquivo !== null) {
                $this->deleteUploadedFile(self::UPLOAD_ENTITY, $nomeArquivo);
            }

            $this->redirectWith('admin/categoria', 'error', 'Não foi possível criar a categoria.');
            return;
        }

        $this->redirectWith('admin/produto', 'success', 'Produto criada com sucesso!');
    }

    /**
     * Exibe o formulário de edição.
     * GET /produtos/{id}/edit
     */
    public function edit(int $id): void
    {
        $produtos = $this->produtoModel->findById($id);
        $this->abortUnless((bool)$produtos, 404, 'Produto não encontrado.');

        $data = [
            'titulo'    => 'Produto',
            'subtitulo' => 'Editar Produto',
            'produtos' => $produtos,
            'uploadMaxMb' => self::UPLOAD_MAX_MB,
        ];

        $this->view('admin.produtos.edit', $data, 'main');
    }

    /**
     * Processa a atualização de um registro.
     * PUT /produtos/{id}
     */
    public function update(UpdateProdutosRequest $request, int $id): void
    {
        $produtos = $this->produtoModel->findById($id);
        $this->abortUnless((bool)$produtos, 404, 'Produto não encontrada.');

        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $data = $request->validated();

        // Slug: o digitado ou, em branco, gerado a partir do nome.
        // Passa o $id para a Produto não conflitar consigo mesma.
        $data['slug'] = $this->produtoModel->gerarSlugUnico(
            $data['slug'] !== '' ? $data['slug'] : $data['nome'],
            $id
        );

        // ── Upload de arquivo ───────────
        if (!empty($_FILES['imagem']['name'])) {
            $nomeAntigo = $this->nomeArquivoLocal($produtos->imagem_url ?? null);

            $nomeNovo = $this->replaceUploadedFile(
                $_FILES['imagem'],
                self::UPLOAD_ENTITY,
                $nomeAntigo,
                'image',
                self::UPLOAD_MAX_MB
            );

            if ($nomeNovo === null) {
                Session::flash('error', $this->uploadErrors[0] ?? 'Falha no upload.');
                Session::flashInput($data);
                $this->back();
                return;
            }

            $data['imagem_url'] = $nomeNovo;
        }

        $atualizada = $this->produtoModel->update($id, $data);

        if (!$atualizada) {
            $this->redirectWith('admin/produto', 'error', 'Não foi possível atualizar o produto.');
            return;
        }

        $this->redirectWith('admin/produto', 'success', 'Produto atualizada com sucesso!');
    }

    /**
     * Remove um registro.
     * DELETE /produtos/{id}
     */
    public function destroy(int $id): void
    {
        $produtos = $this->produtoModel->findById($id);
        $this->abortUnless((bool) $produtos, 404, 'produtos não encontrada.');

        if (!$this->produtoModel->delete($id)) {
            $this->redirectWith('admin/produto', 'error', 'Não foi possível remover o produtos.');
            return;
        }

        // Apaga a imagem junto (evita lixo em public/uploads)
        $nomeArquivo = $this->nomeArquivoLocal($produtos->imagem_url ?? null);

        if ($nomeArquivo !== null) {
            $this->deleteUploadedFile(self::UPLOAD_ENTITY, $nomeArquivo);
        }

        $this->redirectWith('admin/produto', 'success', 'Produto removido com sucesso.');
    }

    /**
     * Devolve o nome do arquivo apenas se ele for um upload local.
     * Registros antigos com URL externa (ex.: Unsplash) retornam null,
     * para nunca tentar apagar algo que não está em public/uploads.
     */
    private function nomeArquivoLocal(?string $imagem): ?string
    {
        if ($imagem === null || $imagem === '' || preg_match('#^https?://#i', $imagem)) {
            return null;
        }

        return $imagem;
    }
}
