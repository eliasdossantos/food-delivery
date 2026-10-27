<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\BaseController;
use App\Http\Requests\Perfis\StorePerfisRequest;
use App\Http\Requests\Perfis\UpdatePerfisRequest;
use App\Repositories\PerfilRepository;
use App\Services\BuscaNome\BuscaNomeService;
use Framework\Support\Session;

/**
 * PerfisController
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
class PerfisController extends BaseController
{
    // Rota da listagem
    private const ROTA_LISTA = 'admin/perfil';

    // Caminho base das views
    private string $rota = 'admin.perfis';

    private PerfilRepository $perfilModel;

    public function __construct()
    {
        $this->perfilModel = new PerfilRepository();
    }

    /**
     * Lista todos os registros.
     * GET /perfis
     */
    public function index(): void
    {
        $data = [
            'titulo' => 'Perfis',
            'subtitulo' => 'Lista de Perfis',
            'tituloTabela' => 'Perfis do Sistema',
            'perfis' => $this->perfilModel->all()
        ];

        $this->view($this->rota . '.index', $data, 'main');
    }

    /**
     * Exibe um registro específico.
     * GET /perfis/{id}
     */
    public function show(int $id): void
    {
        $perfis = $this->perfilModel->findById($id);
        $this->abortUnless((bool)$perfis, 404, 'Perfis não encontrado.');

        $data = [
            'titulo' => 'Perfis',
            'subtitulo' => 'Detalhes do Perfis',
            'perfis' => $perfis,
        ];

        $this->view($this->rota . '.show', $data, 'main');
    }

    /**
     * Exibe o formulário de criação.
     * GET /perfis/create
     */
    public function create(): void
    {
        $data = [
            'titulo' => 'Perfil do Sistema',
            'subtitulo' => 'Criar novo Perfil',
        ];

        $this->view($this->rota . '.create', $data, 'main');
    }

    /**
     * Processa a criação de um novo registro.
     * POST /perfis
     */
    public function store(StorePerfisRequest $request): void
    {
        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $data = $request->validated();

        $id = $this->perfilModel->create($data);

        if (!$id) {
            $this->redirectWith(self::ROTA_LISTA, 'error', 'Não foi possível criar o perfil.');
            return;
        }

        $this->redirectWith(self::ROTA_LISTA, 'success', 'Perfil criado com sucesso!');
    }

    /**
     * Exibe o formulário de edição.
     * GET /perfis/{id}/edit
     */
    public function edit(int $id): void
    {
        $perfis = $this->perfilModel->findById($id);
        $this->abortUnless((bool)$perfis, 404, 'Perfis não encontrado.');

        $data = [
            'titulo' => 'Perfil do Sistema',
            'subtitulo' => 'Editar Perfis',
            'perfis' => $perfis,
        ];

        $this->view($this->rota . '.edit', $data, 'main');
    }

    /**
     * Processa a atualização de um registro.
     * PUT /perfis/{id}
     */
    public function update(UpdatePerfisRequest $request, int $id): void
    {

        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $data = $request->validated();

        $perfis = $this->perfilModel->findById($id);
        $this->abortUnless((bool)$perfis, 404, 'Perfil não encontrado.');

        $updatePerfil = $this->perfilModel->update($id, $data);

        if (!$updatePerfil) {
            $this->redirectWith(
                self::ROTA_LISTA,
                'error',
                'Não foi possível atualizar o perfil.'
            );
            return;
        }

        $this->redirectWith(
            self::ROTA_LISTA,
            'success',
            'Perfil atualizado com sucesso!'
        );
    }

    /**
     * Remove um registro.
     * DELETE /perfis/{id}
     */
    public function destroy(int $id): void
    {
        $perfis = $this->perfilModel->findById($id);
        $this->abortUnless((bool)$perfis, 404, 'Perfis não encontrado.');

        $this->perfilModel->delete($id);

        $this->redirectWith(self::ROTA_LISTA, 'success', 'Perfil removido com sucesso.');
    }

    /**
     * Autocomplete por nome.
     * GET /perfis/procurar?term=...
     */
    public function procurar(): never
    {
        $service = new BuscaNomeService($this->perfilModel);

        $this->json($service->buscar($this->request->get('term', '')));
    }
}
