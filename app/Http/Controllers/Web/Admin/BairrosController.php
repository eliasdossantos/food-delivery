<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\BaseController;
use App\Http\Requests\Bairros\StoreBairrosRequest;
use App\Http\Requests\Bairros\UpdateBairrosRequest;
use App\Repositories\BairroRepository;
use App\Services\BuscaNome\BuscaNomeService;
use App\Services\Integrations\Cep\CepService;
use Framework\Support\Session;

/**
 * BairrosController
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
class BairrosController extends BaseController
{
    // Rota da listagem
    private const ROTA_LISTA = 'admin/bairro';

    // Caminho base das views
    private string $rota = 'admin.bairros';

    private BairroRepository $bairroModel;

    public function __construct()
    {
        $this->bairroModel = new BairroRepository();
    }

    /**
     * Lista todos os registros.
     * GET /bairros
     */
    public function index(): void
    {
        $data = [
            'titulo'    => 'Bairros Atendidos',
            'subtitulo' => 'Lista dos Bairros Atendidos',
            'bairros'   => $this->bairroModel->all(),
        ];

        $this->view($this->rota . '.index', $data, 'main');
    }

    /**
     * Exibe um registro específico.
     * GET /bairros/{id}
     */
    public function show(int $id): void
    {
        $bairros = $this->bairroModel->findById($id);
        $this->abortUnless((bool)$bairros, 404, 'Bairro não encontrado.');

        $data = [
            'titulo'    => 'Bairros Atendidos',
            'subtitulo' => 'Detalhes do Bairro',
            'bairros'   => $bairros,
        ];

        $this->view($this->rota . '.show', $data, 'main');
    }

    /**
     * Exibe o formulário de criação.
     * GET /bairros/create
     */
    public function create(): void
    {
        $data = [
            'titulo'    => 'Novo Bairro Atendido',
            'subtitulo' => 'Cadastrar Bairro Atendido',
        ];

        $this->view($this->rota . '.create', $data, 'main');
    }

    /**
     * Processa a criação de um novo registro.
     * POST /bairros
     */
    public function store(StoreBairrosRequest $request): void
    {
        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());

            $this->back();
            return;
        }
        $data = $request->validated();

        // NOVO: impede o mesmo bairro duas vezes na mesma cidade
        if ($this->bairroModel->findByCidadeESlug($data['cidade'], slug($data['nome']))) {
            Session::flash('error', 'Esse bairro já existe nessa cidade.');
            Session::flashInput($request->all());
            $this->back();
            return;
        }

        // Slug: o digitado ou, em branco, gerado a partir do nome (único dentro da cidade)
        $data['slug'] = $this->bairroModel->gerarSlugUnico(
            $data['slug'] !== '' ? $data['slug'] : $data['nome'],
            null,
            $data['cidade']
        );

        $id = $this->bairroModel->create($data);

        if (!$id) {
            $this->redirectWith(self::ROTA_LISTA, 'error', 'Não foi possível criar bairro atendido.');
            return;
        }

        $this->redirectWith(self::ROTA_LISTA, 'success', 'Bairros criado com sucesso!');
    }

    /**
     * Exibe o formulário de edição.
     * GET /bairros/{id}/edit
     */
    public function edit(int $id): void
    {
        $bairros = $this->bairroModel->findById($id);
        $this->abortUnless((bool)$bairros, 404, 'Bairro não encontrado.');

        $data = [
            'titulo'    => 'Bairros Atendidos',
            'subtitulo' => 'Editar Bairro Atendido',
            'bairros'   => $bairros,
        ];

        $this->view($this->rota . '.edit', $data, 'main');
    }

    /**
     * Processa a atualização de um registro.
     * PUT /bairros/{id}
     */
    public function update(UpdateBairrosRequest $request, int $id): void
    {
        $bairros = $this->bairroModel->findById($id);
        $this->abortUnless((bool)$bairros, 404, 'Bairro não encontrado.');

        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $data = $request->validated();

        $existente = $this->bairroModel->findByCidadeESlug($data['cidade'], slug($data['nome']));

        if ($existente && (int) $existente->id !== $id) {
            Session::flash('error', 'Já existe outro bairro com esse nome nessa cidade.');
            Session::flashInput($request->all());
            $this->back();
            return;
        }

        // Mantém o slug atual quando nome e cidade não mudarem.
        // Quando mudarem, gera um novo slug com base no nome (único dentro da cidade).
        if ($data['nome'] !== $bairros->nome || $data['cidade'] !== $bairros->cidade) {
            $data['slug'] = $this->bairroModel->gerarSlugUnico(
                $data['nome'],
                $id,
                $data['cidade']
            );
        } else {
            // Preserva o slug original, mesmo que o formulário
            // envie um valor diferente ou vazio.
            $data['slug'] = $bairros->slug;
        }

        $updateBairro = $this->bairroModel->update($id, $data);

        if (!$updateBairro) {
            $this->redirectWith(self::ROTA_LISTA, 'error', 'Não foi possível atualizar o Bairro.');
            return;
        }

        $this->redirectWith(self::ROTA_LISTA, 'success', 'Bairro atualizado com sucesso!');
    }

    /**
     * Remove um registro.
     * DELETE /bairros/{id}
     */
    public function destroy(int $id): void
    {
        $bairros = $this->bairroModel->findById($id);
        $this->abortUnless((bool)$bairros, 404, 'Bairro não encontrado.');

        $this->bairroModel->delete($id);

        $this->redirectWith(self::ROTA_LISTA, 'success', 'Bairro removido com sucesso.');
    }

    // ── Auxiliares ────────────────────────────────────────────────────────────

    /**
     * GET /bairros/procurar?term=...
     */
    public function procurar(): never
    {
        $service = new BuscaNomeService($this->bairroModel, ['cidade']);

        $this->json(
            $service->buscar($this->request->get('term', ''))
        );
    }

    /**
     * GET /admin/bairro/cep?cep=58000000
     */
    public function consultarCep(): never
    {
        $cep = preg_replace('/\D/', '', (string) $this->request->get('cep', ''));

        if (strlen($cep) !== 8) {
            http_response_code(422);
            $this->json(['ok' => false, 'message' => 'CEP inválido.']);
        }

        $endereco = (new CepService())->buscar($cep);

        if ($endereco === null || $endereco['bairro'] === '') {
            http_response_code(404);
            $this->json(['ok' => false, 'message' => 'CEP não encontrado ou sem bairro (CEP geral da cidade).']);
        }

        $existente = $this->bairroModel->findByCidadeESlug(
            $endereco['cidade'],
            slug($endereco['bairro'])
        );

        $this->json([
            'ok'        => true,
            'cidade'    => $endereco['cidade'],
            'bairro'    => $endereco['bairro'],
            'uf'        => $endereco['uf'],
            'slug'      => slug($endereco['bairro']),
            'cadastrado' => $existente ? [
                'id'            => (int) $existente->id,
                'valor_entrega' => $existente->valor_entrega,
                'ativo'         => (bool) $existente->ativo,
            ] : null,
        ]);
    }
}
