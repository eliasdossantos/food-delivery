<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\BaseController;
use App\Http\Requests\SobreNos\StoreSobreNosRequest;
use App\Http\Requests\SobreNos\UpdateSobreNosRequest;
use App\Repositories\SobreNosRepository;
use Framework\Support\Session;

/**
 * SobreNosController
 * ─────────────────────────────────────────────────────────────────────────────
 * Responsável por receber as requisições HTTP, delegar para o Repository
 * e retornar a resposta adequada (View ou redirect).
 *
 * Regra: controllers devem ser finos.
 *
 * Upload de arquivo:
 *   O único campo de arquivo é a 'imagem' (opcional).
 *   Ver Framework\Support\Upload e BaseController::saveUploadedFile()/replaceUploadedFile().
 */
class SobreNosController extends BaseController
{
    private const UPLOAD_ENTITY = 'sobrenos';
    private const UPLOAD_MAX_MB = 5;
    private const UPLOAD_FIELD  = 'imagem';

    // Rota da listagem
    private const ROTA_LISTA = 'admin/sobrenos';

    // Caminho base das views
    private string $rota = 'admin.sobrenos';

    private SobreNosRepository $sobreNosModel;

    public function __construct()
    {
        $this->sobreNosModel = new SobreNosRepository();
    }

    /** GET /sobre-nos */
    public function index(): void
    {
        $data = [
            'titulo'    => 'Sobre nós',
            'subtitulo' => 'Lista de registros',
            'sobrenos'  => $this->sobreNosModel->all(),
        ];

        $this->view($this->rota . '.index', $data, 'main');
    }

    /** GET /sobre-nos/{id} */
    public function show(int $id): void
    {
        $sobrenos = $this->sobreNosModel->findById($id);
        $this->abortUnless((bool) $sobrenos, 404, 'Registro não encontrado.');

        $data = [
            'titulo'    => 'Sobre nós',
            'subtitulo' => 'Detalhes',
            'sobrenos'      => $sobrenos,
        ];

        $this->view($this->rota . '.show', $data, 'main');
    }

    /** GET /sobre-nos/create */
    public function create(): void
    {
        $data = [
            'titulo'      => 'Novo registro',
            'subtitulo'   => 'Criar sobre nós',
            'uploadMaxMb' => self::UPLOAD_MAX_MB,
        ];

        $this->view($this->rota . '.create', $data, 'main');
    }

    /** POST /sobre-nos */
    public function store(StoreSobreNosRequest $request): void
    {
        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());
            $this->back();
            return;
        }

        $data        = $request->validated();
        $nomeArquivo = null;

        // ── Upload da imagem (opcional) ──────────────────────────────────────
        if (!empty($_FILES[self::UPLOAD_FIELD]['name'])) {
            $nomeArquivo = $this->saveUploadedFile(
                $_FILES[self::UPLOAD_FIELD],
                self::UPLOAD_ENTITY,
                'image',
                self::UPLOAD_MAX_MB
            );

            if ($nomeArquivo === null) {
                Session::flash('error', $this->uploadErrors[0] ?? 'Falha no upload.');
                Session::flashInput($request->all());
                $this->back();
                return;
            }

            $data[self::UPLOAD_FIELD] = $nomeArquivo;
        }

        $id = $this->sobreNosModel->create($data);

        if (!$id) {
            // Não deixa arquivo órfão em public/uploads se o registro não foi criado
            if ($nomeArquivo !== null) {
                $this->deleteUploadedFile(self::UPLOAD_ENTITY, $nomeArquivo);
            }

            $this->redirectWith(self::ROTA_LISTA, 'error', 'Não foi possível criar o registro.');
            return;
        }

        $this->redirectWith(self::ROTA_LISTA, 'success', 'Registro criado com sucesso!');
    }

    /** GET /sobre-nos/{id}/edit */
    public function edit(int $id): void
    {
        $sobrenos = $this->sobreNosModel->findById($id);
        $this->abortUnless((bool) $sobrenos, 404, 'Registro não encontrado.');

        $data = [
            'titulo'      => 'Editar registro',
            'subtitulo'   => 'Editar sobre nós',
            'uploadMaxMb' => self::UPLOAD_MAX_MB,
            'sobrenos'        => $sobrenos,
        ];

        $this->view($this->rota . '.edit', $data, 'main');
    }

    /** PUT /sobre-nos/{id} */
    public function update(UpdateSobreNosRequest $request, int $id): void
    {
        $sobrenos = $this->sobreNosModel->findById($id);
        $this->abortUnless((bool) $sobrenos, 404, 'Registro não encontrado.');

        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());
            $this->back();
            return;
        }

        $data = $request->validated();

        // ── Upload da imagem (opcional) ──────────────────────────────────────
        if (empty($_FILES[self::UPLOAD_FIELD]['name'])) {
            // Sem arquivo novo, o valor validado não deve apagar a imagem atual.
            unset($data[self::UPLOAD_FIELD]);
        } else {
            $nomeAntigo = $this->nomeArquivoLocal($sobrenos->{self::UPLOAD_FIELD} ?? null);

            $nomeNovo = $this->replaceUploadedFile(
                $_FILES[self::UPLOAD_FIELD],
                self::UPLOAD_ENTITY,
                $nomeAntigo,
                'image',
                self::UPLOAD_MAX_MB
            );

            if ($nomeNovo === null) {
                Session::flash('error', $this->uploadErrors[0] ?? 'Falha no upload.');
                Session::flashInput($request->all());
                $this->back();
                return;
            }

            $data[self::UPLOAD_FIELD] = $nomeNovo;
        }

        if (!$this->sobreNosModel->update($id, $data)) {
            $this->redirectWith(self::ROTA_LISTA, 'error', 'Não foi possível atualizar o registro.');
            return;
        }

        $this->redirectWith(self::ROTA_LISTA, 'success', 'Registro atualizado com sucesso!');
    }

    /** DELETE /sobre-nos/{id} */
    public function destroy(int $id): void
    {
        $sobrenos = $this->sobreNosModel->findById($id);
        $this->abortUnless((bool) $sobrenos, 404, 'Registro não encontrado.');

        if (!$this->sobreNosModel->delete($id)) {
            $this->redirectWith(self::ROTA_LISTA, 'error', 'Não foi possível remover o registro.');
            return;
        }

        // Só remove a imagem depois que o registro foi excluído com sucesso.
        $nomeArquivo = $this->nomeArquivoLocal($sobrenos->{self::UPLOAD_FIELD} ?? null);

        if ($nomeArquivo !== null) {
            $this->deleteUploadedFile(self::UPLOAD_ENTITY, $nomeArquivo);
        }

        $this->redirectWith(self::ROTA_LISTA, 'success', 'Registro removido com sucesso.');
    }

    // ── Auxiliares ────────────────────────────────────────────────────────────

    /** Retorna apenas nomes locais; URLs externas não devem ser apagadas. */
    private function nomeArquivoLocal(?string $arquivo): ?string
    {
        if ($arquivo === null || $arquivo === '' || preg_match('#^https?://#i', $arquivo)) {
            return null;
        }

        return $arquivo;
    }
}
