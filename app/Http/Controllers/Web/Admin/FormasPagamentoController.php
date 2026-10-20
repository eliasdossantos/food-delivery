<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\BaseController;
use App\Http\Requests\FormasPagamento\StoreFormasPagamentoRequest;
use App\Http\Requests\FormasPagamento\UpdateFormasPagamentoRequest;
use App\Repositories\FormaPagamentoRepository;
use App\Services\BuscaNome\BuscaNomeService;
use Framework\Support\Session;

/**
 * FormasPagamentoController
 * ─────────────────────────────────────────────────────────────────────────────
 * Responsável por receber as requisições HTTP, delegar para o Service/Repository
 * e retornar a resposta adequada (View ou JSON).
 *
 * Regra: controllers devem ser finos.
 * Lógica de negócio → Service | Acesso a dados → Repository
 */
class FormasPagamentoController extends BaseController
{
    private FormaPagamentoRepository $pagamentoModel;

    public function __construct()
    {
        $this->pagamentoModel = new FormaPagamentoRepository();
    }

    /**
     * Lista todos os registros.
     * GET /formas-pagamento
     */
    public function index(): void
    {
        $data = [
            'titulo' => 'Formas de Pagamento',
            'subtitulo' => 'Listando as formas de pagamento',
            'pagamentos' => $this->pagamentoModel->all(),
        ];

        $this->view('admin.formaspagamento.index', $data, 'main');
    }

    /**
     * Exibe um registro específico.
     * GET /formas-pagamento/{id}
     */
    public function show(int $id): void
    {
        $pagamentos = $this->pagamentoModel->findById($id);
        $this->abortUnless((bool)$pagamentos, 404, 'Forma de Pagamento não encontrado.');

        $data = [
            'titulo' => 'Formas de Pagamento',
            'subtitulo' => 'Detalhes da Forma de Pagamento',
            'pagamentos' => $pagamentos,
        ];

        $this->view('admin.formaspagamento.show', $data, 'main');
    }

    /**
     * Exibe o formulário de criação.
     * GET /formas-pagamento/create
     */
    public function create(): void
    {
        $data = [
            'titulo' => 'Formas de Pagamento do Sistema',
            'subtitulo' => 'Criar nova Forma de Pagamento',
        ];

        $this->view('admin.formaspagamento.create', $data, 'main');
    }

    /**
     * Processa a criação de um novo registro.
     * POST /formas-pagamento
     */
    public function store(StoreFormasPagamentoRequest $request): void
    {
        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $data = $request->validated();

        $id = $this->pagamentoModel->create($data);

        if (!$id) {
            $this->redirectWith('admin/pagamento', 'error', 'Não foi possível criar forma de pagamento.');
            return;
        }

        $this->redirectWith('admin/pagamento', 'success', 'Forma de Pagamento criado com sucesso!');
    }

    /**
     * Exibe o formulário de edição.
     * GET /formas-pagamento/{id}/edit
     */
    public function edit(int $id): void
    {
        $pagamentos = $this->pagamentoModel->findById($id);
        $this->abortUnless((bool)$pagamentos, 404, 'Forma de Pagamento não encontrado.');

        if (FormaPagamentoRepository::ehProtegida($pagamentos)) {
            $this->redirectWith('admin/pagamento', 'error', FormaPagamentoRepository::MENSAGEM_PROTEGIDA);
            return;
        }

        $data = [
            'titulo' => 'Forma de Pagamento do Sistema',
            'subtitulo' => 'Editar Forma de Pagamento',
            'pagamentos' => $pagamentos,
        ];

        $this->view('admin.formaspagamento.edit', $data, 'main');
    }

    /**
     * Processa a atualização de um registro.
     * PUT /formas-pagamento/{id}
     */
    public function update(UpdateFormasPagamentoRequest $request, int $id): void
    {

        $pagamentos = $this->pagamentoModel->findById($id);
        $this->abortUnless((bool)$pagamentos, 404, 'Forma de Pagamento não encontrado.');

        if (FormaPagamentoRepository::ehProtegida($pagamentos)) {
            $this->redirectWith('admin/pagamento', 'error', FormaPagamentoRepository::MENSAGEM_PROTEGIDA);
            return;
        }
        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $data = $request->validated();

        $updateformaPagamento = $this->pagamentoModel->update($id, $data);

        if (!$updateformaPagamento) {
            $this->redirectWith('admin/pagamento', 'error', 'Não foi possível atualizar a forma de pagamento.');
            return;
        }

        $this->redirectWith('admin/pagamento', 'success', 'Forma de Pagamento atualizado com sucesso!');
    }

    /**
     * Remove um registro.
     * DELETE /formas-pagamento/{id}
     */
    public function destroy(int $id): void
    {
        $pagamentos = $this->pagamentoModel->findById($id);
        $this->abortUnless((bool)$pagamentos,  404, 'Forma de Pagamento não encontrado.');

        if (FormaPagamentoRepository::ehProtegida($pagamentos)) {
            $this->redirectWith('admin/pagamento', 'error', FormaPagamentoRepository::MENSAGEM_PROTEGIDA);
            return;
        }

        $this->pagamentoModel->delete($id);

        $this->redirectWith('admin/pagamento', 'success', 'Forma de Pagamento removido com sucesso.');
    }

    /**
     * Autocomplete por nome.
     * GET /pagamento/procurar?term=...
     */
    public function procurar(): never
    {
        $service = new BuscaNomeService($this->pagamentoModel);

        $this->json($service->buscar($this->request->get('term', '')));
    }
}
