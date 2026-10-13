<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\BaseController;
use App\Http\Requests\Produtos\StoreProdutosRequest;
use App\Http\Requests\Produtos\UpdateProdutosRequest;
use App\Repositories\CategoriaRepository;
use App\Repositories\ExtraRepository;
use App\Repositories\ProdutoExtraRepository;
use App\Repositories\ProdutoRepository;
use App\Services\BuscaNome\BuscaNomeService;
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
    private ExtraRepository $extraModel;
    private ProdutoExtraRepository $produtoExtraModel;

    public function __construct()
    {
        $this->produtoModel = new ProdutoRepository();
        $this->categoriaModel = new CategoriaRepository();
        $this->extraModel = new ExtraRepository();
        $this->produtoExtraModel = new ProdutoExtraRepository();
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
            'produtos' => $this->produtoModel->tudoComCategoria(),
            'extrasPorProduto' => $this->produtoExtraModel->getExtrasAgrupadosPorProduto(),
        ];

        $this->view('admin.produtos.index', $data, 'main');
    }

    /**
     * Exibe um registro específico.
     * GET /produtos/{id}
     */
    public function show(int $id): void
    {
        // findComCategoria traz também categoria_nome
        $produtos = $this->produtoModel->findComCategoria($id);
        $this->abortUnless((bool) $produtos, 404, 'Produto não encontrado.');

        $data = [
            'titulo'    => 'Produtos',
            'subtitulo' => 'Detalhes do Produto',
            'produtos'  => $produtos,
            'extras'    => $this->produtoExtraModel->getExtrasDoProduto($id),
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
            'categorias'  => $this->categoriasDisponiveis(),
            'extras'      => $this->extraModel->getAtivas(),
            'extraIds'    => [],
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

        $input = $request->all();
        $extraIds = $this->validarExtraIds($input);

        if ($extraIds === false) {
            Session::flash('error', 'Uma ou mais extras selecionadas são inválidas ou estão inativas.');
            Session::flashInput($input);
            $this->back();
            return;
        }

        $data = $this->normalizarCategoria($request->validated());

        $extraIds = $this->validarExtraIds($request->all());

        if ($extraIds === false) {
            Session::flash(
                'error',
                'Uma ou mais extras selecionadas são inválidas ou estão inativas.'
            );
            Session::flashInput($request->all());
            $this->back();
            return;
        }

        unset($data['extra_ids']);

        if (!$this->categoriaPermitida($data['categoria_id'])) {
            Session::flash('error', 'A categoria selecionada é inválida ou está inativa.');
            Session::flashErrors(['categoria_id' => ['Categoria inválida.']]);
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        // Slug: o digitado ou, em branco, gerado a partir do nome (sempre único)
        $slug = trim((string) ($data['slug'] ?? ''));
        $data['slug'] = $this->produtoModel->gerarSlugUnico($slug !== '' ? $slug : $data['nome']);

        // Código (SKU): o digitado ou, em branco, gerado a partir do nome
        $codigo = trim((string) ($data['codigo'] ?? ''));
        $data['codigo'] = $codigo !== ''
            ? $codigo
            : $this->produtoModel->gerarCodigoUnico($data['nome']);

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
            $this->redirectWith(
                'admin/produto',
                'error',
                'Não foi possível criar o produto.'
            );
            return;
        }

        if (!$this->produtoExtraModel->sincronizar(
            (int) $id,
            $extraIds
        )) {
            $this->redirectWith(
                'admin/produto',
                'error',
                'O produto foi criado, mas não foi possível salvar as extras.'
            );
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
        $produtos = $this->produtoModel->findComCategoria($id);
        $this->abortUnless((bool) $produtos, 404, 'Produto não encontrado.');

        $data = [
            'titulo'      => 'Produto',
            'subtitulo'   => 'Editar Produto',
            'produtos'    => $produtos,
            'uploadMaxMb' => self::UPLOAD_MAX_MB,
            'categorias'  => $this->categoriasDisponiveis($produtos->categoria_id ?? null),
            'extras'      => $this->extraModel->getAtivas(),
            'extraIds'    => array_map(
                'intval',
                $this->produtoExtraModel->getIdsDasExtrasDoProduto($id)
            ),
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

        $input = $request->all();
        $extraIds = $this->validarExtraIds($input);

        if ($extraIds === false) {
            Session::flash('error', 'Uma ou mais extras selecionadas são inválidas ou estão inativas.');
            Session::flashInput($input);
            $this->back();
            return;
        }

        $data = $this->normalizarCategoria($request->validated());
        unset($data['extra_ids']);

        // Mantém a categoria atual mesmo se ela estiver inativa; só valida se mudou
        $atual = isset($produtos->categoria_id) ? (int) $produtos->categoria_id : null;

        if (!$this->categoriaPermitida($data['categoria_id'], $atual)) {
            Session::flash('error', 'A categoria selecionada é inválida ou está inativa.');
            Session::flashErrors(['categoria_id' => ['Categoria inválida.']]);
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $slug = trim((string) ($data['slug'] ?? ''));
        $data['slug'] = $this->produtoModel->gerarSlugUnico(
            $slug !== '' ? $slug : $data['nome'],
            $id
        );

        // Código (SKU): o digitado; em branco, mantém o atual (ou gera, se o produto não tinha)
        $codigo = trim((string) ($data['codigo'] ?? ''));

        if ($codigo === '') {
            $codigo = ($produtos->codigo ?? '') !== ''
                ? $produtos->codigo
                : $this->produtoModel->gerarCodigoUnico($data['nome'], $id);
        }

        $data['codigo'] = $codigo;

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

        if (!$this->produtoExtraModel->sincronizar($id, $extraIds)) {
            $this->redirectWith(
                'admin/produto',
                'error',
                'O produto foi atualizado, mas não foi possível salvar as extras.'
            );
            return;
        }

        $this->redirectWith('admin/produto', 'success', 'Produto atualizado com sucesso!');
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

    // ── Auxiliares ────────────────────────────────────────────────────────────

    /**
     * Autocomplete por nome.
     * GET /produtos/procurar?term=...
     */
    public function procurar(): never
    {
        $service = new BuscaNomeService($this->produtoModel, ['codigo']);

        $this->json($service->buscar($this->request->get('term', '')));
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

    /**
     * "Sem categoria" vira NULL (nunca '' em coluna inteira com FK)
     * e uma categoria escolhida vira inteiro.
     */
    private function normalizarCategoria(array $data): array
    {
        $valor = $data['categoria_id'] ?? null;

        $data['categoria_id'] = ($valor === null || $valor === '')
            ? null
            : (int) $valor;

        return $data;
    }

    /**
     * A categoria existe e está ativa? (ou é a que o produto já tinha)
     */
    private function categoriaPermitida(?int $categoriaId, ?int $atualId = null): bool
    {
        if ($categoriaId === null || $categoriaId === $atualId) {
            return true;
        }

        $categoria = $this->categoriaModel->findById($categoriaId);

        return $categoria && !empty($categoria->ativo);
    }

    /**
     * Categorias que aparecem no select. Se o produto já está numa categoria
     * inativa, ela é incluída para não "sumir" na edição.
     */
    private function categoriasDisponiveis(?int $incluirId = null): array
    {
        $categorias = $this->categoriaModel->getAtivas();

        if ($incluirId === null) {
            return $categorias;
        }

        foreach ($categorias as $categoria) {
            if ((int) $categoria->id === (int) $incluirId) {
                return $categorias;
            }
        }

        $atual = $this->categoriaModel->findById($incluirId);

        if ($atual) {
            $categorias[] = $atual;
        }

        return $categorias;
    }

    /**
     * Lê extra_ids[] do formulário, remove duplicados e valida os IDs.
     *
     * @return int[]|false Retorna false se algum ID for inválido ou inativo.
     */
    private function validarExtraIds(array $input): array|false
    {
        $valores = $input['extra_ids'] ?? [];

        if (!is_array($valores)) {
            return false;
        }

        $ids = [];

        foreach ($valores as $valor) {
            if (!filter_var($valor, FILTER_VALIDATE_INT) || (int) $valor < 1) {
                return false;
            }

            $ids[] = (int) $valor;
        }

        $ids = array_values(array_unique($ids));

        foreach ($ids as $extraId) {
            $extra = $this->extraModel->findById($extraId);

            if (!$extra || empty($extra->ativo)) {
                return false;
            }
        }

        return $ids;
    }
}
