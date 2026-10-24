<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\BaseController;
use App\Http\Requests\Expediente\StoreExpedienteRequest;
use App\Http\Requests\Expediente\UpdateExpedienteRequest;
use App\Repositories\ExpedienteRepository;
use Framework\Support\Session;

/**
 * ExpedienteController
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
class ExpedienteController extends BaseController
{
    private ExpedienteRepository $expedienteModel;

    public function __construct()
    {
        $this->expedienteModel = new ExpedienteRepository();
    }

    /**
     * Lista todos os registros.
     * GET /expediente
     */
    public function index(): void
    {
        $data = [
            'titulo'     => 'Expediente',
            'subtitulo'  => 'Lista dos Expediente',
            'expediente' => $this->expedienteModel->all(),
        ];

        $this->view('admin.expediente.index', $data, 'main');
    }

    /**
     * Exibe um registro específico.
     * GET /expediente/{id}
     */
    public function show(int $id): void
    {
        $expediente = $this->expedienteModel->findById($id);
        $this->abortUnless((bool)$expediente, 404, 'Expediente não encontrado.');

        $data = [
            'titulo' => 'Detalhes',
            'subtitulo'  => 'Detalhes dos Expediente',
            'expediente'  => $expediente,
        ];

        $this->view('admin.expediente.show', $data, 'main');
    }

    /**
     * Exibe o formulário de criação.
     * GET /expediente/create
     */
    public function create(): void
    {
        $data = [
            'titulo' => 'Novo Expediente',
            'subtitulo'  => 'Cadastrar novo Expediente',
        ];

        $this->view('admin.expediente.create', $data, 'main');
    }

    /**
     * Processa a criação de um novo registro.
     * POST /expediente
     */
    public function store(StoreExpedienteRequest $request): void
    {
        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $data = $request->validated();

        $id = $this->expedienteModel->create($data);

        if (!$id) {
            $this->redirectWith('admin/expediente', 'error', 'Não foi possível criar o Expediente.');
            return;
        }
        $this->redirectWith('admin/expediente', 'success', 'Expediente criado com sucesso!');
    }

    /**
     * Exibe o formulário de edição.
     * GET /expediente/{id}/edit
     */
    public function edit(int $id): void
    {
        $expediente = $this->expedienteModel->findById($id);
        $this->abortUnless((bool)$expediente, 404, 'Expediente não encontrado.');

        $data = [
            'titulo' => 'Expediente',
            'subtitulo' => 'Editar Expediente',
            'expediente'  => $expediente,
        ];

        $this->view('admin.expediente.edit', $data, 'main');
    }

    /**
     * Processa a atualização de um registro.
     * PUT /expediente/{id}
     */
    public function update(UpdateExpedienteRequest $request, int $id): void
    {
        $expediente = $this->expedienteModel->findById($id);
        $this->abortUnless((bool)$expediente, 404, 'Expediente não encontrado.');

        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $data = $request->validated();

        // Dia fechado: mantém os horários já gravados, só muda o status
        if ((string) $data['aberto'] === '0') {
            foreach (['abertura', 'fechamento', 'pausa_inicio', 'pausa_fim'] as $campo) {
                if (empty($data[$campo])) {
                    $data[$campo] = $expediente->$campo;
                }
            }
        }

        $updateExpediente = $this->expedienteModel->update($id, $data);

        if (!$updateExpediente) {
            $this->redirectWith('admin/expediente', 'error', 'Não foi possível atualizar Expediente.');
            return;
        }

        $this->redirectWith('admin/expediente', 'success', 'Expediente atualizado com sucesso!');
    }

    /**
     * Remove um registro.
     * DELETE /expediente/{id}
     */
    public function destroy(int $id): void
    {
        $expediente = $this->expedienteModel->findById($id);
        $this->abortUnless((bool)$expediente, 404, 'Expediente não encontrado.');

        $this->expedienteModel->delete($id);

        $this->redirectWith('admin/expediente', 'success', 'Expediente removido com sucesso.');
    }
}
