<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\BaseController;
use App\Http\Requests\Categorias\StoreCategoriasRequest;
use App\Http\Requests\Categorias\UpdateCategoriasRequest;
use App\Repositories\CategoriaRepository;
use Framework\Support\Session;

/**
 * CategoriasController
 * ─────────────────────────────────────────────────────────────────────────────
 * Responsável por receber as requisições HTTP, delegar para o Repository
 * e retornar a resposta adequada (View ou redirect).
 *
 * Regra: controllers devem ser finos.
 * Acesso a dados → Repository
 *
 * Upload de imagem:
 *   Os arquivos ficam em public/uploads/categorias/ e no banco (imagem_url)
 *   salvamos SOMENTE o nome do arquivo. A URL é montada na view com
 *   uploadUrl('categorias', $categoria->imagem_url).
 */
class CategoriasController extends BaseController
{
    /** Entidade (subpasta) dos uploads de categoria */
    private const UPLOAD_ENTITY = 'categorias';

    /** Tamanho máximo da imagem, em MB */
    private const UPLOAD_MAX_MB = 5;

    private CategoriaRepository $categoriaModel;

    public function __construct()
    {
        parent::__construct();

        $this->categoriaModel = new CategoriaRepository();
    }

    /**
     * Lista todos os registros.
     * GET /categorias
     */
    public function index(): void
    {
        $data = [
            'titulo'       => 'Categorias',
            'tituloTabela' => 'Lista das Categorias',
            'categorias'   => $this->categoriaModel->getOrdenadas(),
        ];

        $this->view('admin.categorias.index', $data, 'main');
    }

    /**
     * Exibe um registro específico.
     * GET /categorias/{id}
     */
    public function show(int $id): void
    {
        $categoria = $this->categoriaModel->findById($id);
        $this->abortUnless((bool) $categoria, 404, 'Categoria não encontrada.');

        $data = [
            'titulo'    => 'Categorias',
            'subtitulo' => 'Detalhes da Categoria',
            'categoria' => $categoria,
        ];

        $this->view('admin.categorias.show', $data, 'main');
    }

    /**
     * Exibe o formulário de criação.
     * GET /categorias/create
     */
    public function create(): void
    {
        $data = [
            'titulo'    => 'Nova Categoria',
            'subtitulo' => 'Criar nova Categoria',
            'uploadMaxMb' => self::UPLOAD_MAX_MB,
        ];

        $this->view('admin.categorias.create', $data, 'main');
    }

    /**
     * Processa a criação de um novo registro.
     * POST /categorias
     */
    public function store(StoreCategoriasRequest $request): void
    {
        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $data = $this->prepararDados($request->validated());

        // Slug: o digitado ou, em branco, gerado a partir do nome (sempre único)
        $data['slug'] = $this->categoriaModel->gerarSlugUnico(
            $data['slug'] !== '' ? $data['slug'] : $data['nome']
        );

        // ── Upload da imagem (opcional) ──────────────────────────────────────
        $nomeArquivo = null;

        if (!empty($_FILES['imagem']['name'])) {
            $nomeArquivo = $this->saveUploadedFile(
                $_FILES['imagem'],
                self::UPLOAD_ENTITY,
                'image',
                self::UPLOAD_MAX_MB
            );

            if ($nomeArquivo === null) {
                Session::flash('error', $this->uploadErrors[0] ?? 'Falha no upload da imagem.');
                Session::flashInput($request->all());

                $this->back();
                return;
            }

            $data['imagem_url'] = $nomeArquivo;
        }

        $id = $this->categoriaModel->create($data);

        if (!$id) {
            // Não deixa arquivo órfão em public/uploads se o registro não foi criado
            if ($nomeArquivo !== null) {
                $this->deleteUploadedFile(self::UPLOAD_ENTITY, $nomeArquivo);
            }

            $this->redirectWith('admin/categoria', 'error', 'Não foi possível criar a categoria.');
            return;
        }

        $this->redirectWith('admin/categoria', 'success', 'Categoria criada com sucesso!');
    }

    /**
     * Exibe o formulário de edição.
     * GET /categorias/{id}/edit
     */
    public function edit(int $id): void
    {
        $categoria = $this->categoriaModel->findById($id);
        $this->abortUnless((bool) $categoria, 404, 'Categoria não encontrada.');

        $data = [
            'titulo'    => 'Editar Categoria',
            'subtitulo' => 'Editar Categoria',
            'categoria' => $categoria,
            'uploadMaxMb' => self::UPLOAD_MAX_MB,
        ];

        $this->view('admin.categorias.edit', $data, 'main');
    }

    /**
     * Processa a atualização de um registro.
     * PUT /categorias/{id}
     */
    public function update(UpdateCategoriasRequest $request, int $id): void
    {
        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $categoria = $this->categoriaModel->findById($id);
        $this->abortUnless((bool) $categoria, 404, 'Categoria não encontrada.');

        $data = $this->prepararDados($request->validated());

        // Slug: o digitado ou, em branco, gerado a partir do nome.
        // Passa o $id para a categoria não conflitar consigo mesma.
        $data['slug'] = $this->categoriaModel->gerarSlugUnico(
            $data['slug'] !== '' ? $data['slug'] : $data['nome'],
            $id
        );

        // ── Troca da imagem (só se um novo arquivo foi enviado) ──────────────
        // O arquivo antigo só é apagado se o novo for salvo com sucesso.
        // Sem arquivo novo, imagem_url não entra em $data e a atual é mantida.
        if (!empty($_FILES['imagem']['name'])) {
            $nomeAntigo = $this->nomeArquivoLocal($categoria->imagem_url ?? null);

            $nomeNovo = $this->replaceUploadedFile(
                $_FILES['imagem'],
                self::UPLOAD_ENTITY,
                $nomeAntigo,
                'image',
                self::UPLOAD_MAX_MB
            );

            if ($nomeNovo === null) {
                Session::flash('error', $this->uploadErrors[0] ?? 'Falha no upload da imagem.');
                Session::flashInput($request->all());

                $this->back();
                return;
            }

            $data['imagem_url'] = $nomeNovo;
        }

        $atualizada = $this->categoriaModel->update($id, $data);

        if (!$atualizada) {
            $this->redirectWith('admin/categoria', 'error', 'Não foi possível atualizar a categoria.');
            return;
        }

        $this->redirectWith('admin/categoria', 'success', 'Categoria atualizada com sucesso!');
    }

    /**
     * Remove um registro.
     * DELETE /categorias/{id}
     */
    public function destroy(int $id): void
    {
        $categoria = $this->categoriaModel->findById($id);
        $this->abortUnless((bool) $categoria, 404, 'Categoria não encontrada.');

        if (!$this->categoriaModel->delete($id)) {
            $this->redirectWith('admin/categoria', 'error', 'Não foi possível remover a categoria.');
            return;
        }

        // Apaga a imagem junto (evita lixo em public/uploads)
        $nomeArquivo = $this->nomeArquivoLocal($categoria->imagem_url ?? null);

        if ($nomeArquivo !== null) {
            $this->deleteUploadedFile(self::UPLOAD_ENTITY, $nomeArquivo);
        }

        $this->redirectWith('admin/categoria', 'success', 'Categoria removida com sucesso.');
    }

    // ── Auxiliares ────────────────────────────────────────────────────────────

    /**
     * Monta o array final com os campos da categoria, já com os tipos corretos.
     * Os checkboxes (ativo/destaque) chegam como "0" ou "1" por causa do
     * <input type="hidden"> que vem antes de cada checkbox nas views.
     */
    private function prepararDados(array $input): array
    {
        return [
            'nome'           => trim((string) ($input['nome'] ?? '')),
            'slug'           => trim((string) ($input['slug'] ?? '')),
            'descricao'      => trim((string) ($input['descricao'] ?? '')),
            'icone'          => trim((string) ($input['icone'] ?? '')),
            'ordem_exibicao' => max(1, (int) ($input['ordem_exibicao'] ?? 1)),
            'ativo'          => !empty($input['ativo']) ? 1 : 0,
            'destaque'       => !empty($input['destaque']) ? 1 : 0,
        ];
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
