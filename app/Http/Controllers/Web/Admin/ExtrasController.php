<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\BaseController;
use App\Http\Requests\Extras\StoreExtrasRequest;
use App\Http\Requests\Extras\UpdateExtrasRequest;
use App\Repositories\ExtraRepository;
use Framework\Support\Session;

/**
 * ExtrasController
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
class ExtrasController extends BaseController
{
    /** Entidade (subpasta) dos uploads de extra */
    private const UPLOAD_ENTITY = 'extras';

    /** Tamanho máximo da imagem, em MB */
    private const UPLOAD_MAX_MB = 5;

    private ExtraRepository $extrasModel;

    public function __construct()
    {
        // Inicialize dependências aqui
        $this->extrasModel = new ExtraRepository();
    }

    /**
     * Lista todos os registros.
     * GET /extras
     */
    public function index(): void
    {
        $data = [
            'titulo' => 'Extras',
            'tituloTabela' => 'Lista os Extras',
            'extras' => $this->extrasModel->all(),
        ];

        $this->view('admin.extras.index', $data, 'main');
    }

    /**
     * Exibe um registro específico.
     * GET /extras/{id}
     */
    public function show(int $id): void
    {
        $extras = $this->extrasModel->findById($id);
        $this->abortUnless((bool) $extras, 404, 'Extra não encontrado.');

        $data = [
            'titulo'    => 'Extras',
            'subtitulo' => 'Detalhes do Extra',
            'extras' => $extras,
        ];

        $this->view('admin.extras.show', $data, 'main');
    }

    /**
     * Exibe o formulário de criação.
     * GET /extras/create
     */
    public function create(): void
    {
        $data = [
            'titulo'    => 'Nova Extra',
            'subtitulo' => 'Criar novo Extra',
            'uploadMaxMb' => self::UPLOAD_MAX_MB,
        ];

        $this->view('admin.extras.create', $data, 'main');
    }

    /**
     * Processa a criação de um novo registro.
     * POST /extras
     */
    public function store(StoreExtrasRequest $request): void
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
        $data['slug'] = $this->extrasModel->gerarSlugUnico(
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

        $id = $this->extrasModel->create($data);

        if (!$id) {
            // Não deixa arquivo órfão em public/uploads se o registro não foi criado
            if ($nomeArquivo !== null) {
                $this->deleteUploadedFile(self::UPLOAD_ENTITY, $nomeArquivo);
            }

            $this->redirectWith('admin/extra', 'error', 'Não foi possível criar o extra.');
            return;
        }

        $this->redirectWith('admin/extra', 'success', 'Extra criado com sucesso!');
    }

    /**
     * Exibe o formulário de edição.
     * GET /extras/{id}/edit
     */
    public function edit(int $id): void
    {
        $extras = $this->extrasModel->findById($id);
        $this->abortUnless((bool) $extras, 404, 'Extra não encontrado.');

        $data = [
            'titulo'    => 'Editar Extra',
            'subtitulo' => 'Editar Extra',
            'extras' => $extras,
            'uploadMaxMb' => self::UPLOAD_MAX_MB,
        ];

        $this->view('admin.extras.edit', $data, 'main');
    }

    /**
     * Processa a atualização de um registro.
     * PUT /extras/{id}
     */
    public function update(UpdateExtrasRequest $request, int $id): void
    {
        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $extras = $this->extrasModel->findById($id);
        $this->abortUnless((bool) $extras, 404, 'Extras não encontrado.');

        $data = $this->prepararDados($request->validated());

        // Slug: o digitado ou, em branco, gerado a partir do nome.
        // Passa o $id para a extra não conflitar consigo mesma.
        $data['slug'] = $this->extrasModel->gerarSlugUnico(
            $data['slug'] !== '' ? $data['slug'] : $data['nome'],
            $id
        );

        // ── Troca da imagem (só se um novo arquivo foi enviado) ──────────────
        // O arquivo antigo só é apagado se o novo for salvo com sucesso.
        // Sem arquivo novo, imagem_url não entra em $data e a atual é mantida.
        if (!empty($_FILES['imagem']['name'])) {
            $nomeAntigo = $this->nomeArquivoLocal($extras->imagem_url ?? null);

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

        $atualizada = $this->extrasModel->update($id, $data);

        if (!$atualizada) {
            $this->redirectWith('admin/extra', 'error', 'Não foi possível atualizar o extra.');
            return;
        }

        $this->redirectWith('admin/extra', 'success', 'Extra atualizado com sucesso!');
    }

    /**
     * Remove um registro.
     * DELETE /extras/{id}
     */
    public function destroy(int $id): void
    {
        $extras = $this->extrasModel->findById($id);
        $this->abortUnless((bool) $extras, 404, 'Extra não encontrado.');

        if (!$this->extrasModel->delete($id)) {
            $this->redirectWith('admin/extra', 'error', 'Não foi possível remover o extra.');
            return;
        }

        // Apaga a imagem junto (evita lixo em public/uploads)
        $nomeArquivo = $this->nomeArquivoLocal($extras->imagem_url ?? null);

        if ($nomeArquivo !== null) {
            $this->deleteUploadedFile(self::UPLOAD_ENTITY, $nomeArquivo);
        }

        $this->redirectWith('admin/extra', 'success', 'Extra removido com sucesso.');
    }

    // ── Auxiliares ────────────────────────────────────────────────────────────

    /**
     * Monta o array final com os campos da extra, já com os tipos corretos.
     * Os checkboxes (ativo/destaque) chegam como "0" ou "1" por causa do
     * <input type="hidden"> que vem antes de cada checkbox nas views.
     */
    private function prepararDados(array $input): array
    {
        return [
            'nome'           => trim((string) ($input['nome'] ?? '')),
            'slug'           => trim((string) ($input['slug'] ?? '')),
            'descricao'      => trim((string) ($input['descricao'] ?? '')),
            'preco'          => (float) ($input['preco'] ?? 0),
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
