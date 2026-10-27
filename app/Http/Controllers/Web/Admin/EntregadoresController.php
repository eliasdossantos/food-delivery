<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\BaseController;
use App\Http\Requests\Entregadores\StoreEntregadoresRequest;
use App\Http\Requests\Entregadores\UpdateEntregadoresRequest;
use App\Repositories\EntregadorRepository;
use App\Services\BuscaNome\BuscaNomeService;
use Framework\Support\Session;

class EntregadoresController extends BaseController
{
    /**
     * Entidade usada na organização dos arquivos.
     *
     * Com entityId, a foto fica vinculada ao ID do entregador:
     * public/uploads/entregadores/{id}/
     */
    private const UPLOAD_ENTITY = 'entregadores';
    private const UPLOAD_MAX_MB = 5;

    // Nome da coluna da IMAGEN na tabela de entregadores.
    private const FOTO_FIELD = 'avatar';

    // Rota da listagem
    private const ROTA_LISTA = 'admin/entregador';

    // Caminho base das views
    private string $rota = 'admin.entregadores';

    private EntregadorRepository $entregadorModel;

    public function __construct()
    {
        parent::__construct();

        $this->entregadorModel = new EntregadorRepository();
    }

    /**
     * GET /entregadores
     */
    public function index(): void
    {
        $data = [
            'titulo'       => 'Entregadores',
            'subtitulo'    => 'Lista de Entregadores',
            'entregadores' => $this->entregadorModel->all(),
        ];

        $this->view($this->rota . '.index', $data, 'main');
    }

    /**
     * GET /entregadores/{id}
     */
    public function show(int $id): void
    {
        $entregador = $this->entregadorModel->findById($id);

        $this->abortUnless((bool) $entregador, 404, 'Entregador não encontrado.');

        $data = [
            'titulo'      => 'Entregadores',
            'subtitulo'   => 'Detalhes do Entregador',
            'entregador'  => $entregador,
        ];

        $this->view($this->rota . '.show', $data, 'main');
    }

    /**
     * GET /entregadores/create
     */
    public function create(): void
    {
        $data = [
            'titulo'      => 'Entregadores',
            'subtitulo'   => 'Novo Entregador',
            'uploadMaxMb' => self::UPLOAD_MAX_MB,
        ];

        $this->view($this->rota . '.create', $data, 'main');
    }

    /**
     * POST /entregadores
     *
     * Cria o registro primeiro para obter seu ID.
     * Depois salva a foto na pasta correspondente a esse ID.
     *
     * A foto é opcional.
     */
    public function store(StoreEntregadoresRequest $request): void
    {
        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $data = $request->validated();

        // O arquivo é tratado separadamente do restante dos dados.
        unset($data['avatar']);

        // Cria o entregador sem depender da foto.
        $id = $this->entregadorModel->create($data);

        if (!$id || (int) $id < 1) {
            Session::flash('error', 'Não foi possível cadastrar o entregador.');
            Session::flashInput($data);
            $this->back();
            return;
        }

        $id = (int) $id;

        // A foto só é processada depois que temos o ID do entregador.
        if (isset($_FILES['avatar']) && !empty($_FILES['avatar']['name'])) {
            $nomeArquivo = $this->saveUploadedFile(
                $_FILES['avatar'],
                self::UPLOAD_ENTITY,
                'image',
                self::UPLOAD_MAX_MB,
                $id
            );

            if ($nomeArquivo === null) {
                // O cadastro permanece válido, mesmo sem foto.
                // O administrador poderá adicionar a foto depois.
                Session::flash('error', 'Entregador cadastrado, mas a foto não pôde ser salva: ' . ($this->uploadErrors[0] ?? 'falha no upload.'));
                $this->redirect('admin/entregador/' . $id . '/edit');
                return;
            }

            // Salva no banco somente o nome retornado pelo Upload.
            $atualizado = $this->entregadorModel->update($id, [self::FOTO_FIELD => $nomeArquivo]);

            if (!$atualizado) {
                // Evita deixar um arquivo sem vínculo com o registro.
                $this->deleteUploadedFile(self::UPLOAD_ENTITY, $nomeArquivo, $id);
                Session::flash('error', 'O entregador foi cadastrado, mas não foi possível vincular a foto.');
                $this->redirect('admin/entregador/' . $id . '/edit');
                return;
            }
        }

        $this->redirectWith(
            self::ROTA_LISTA,
            'success',
            'Entregador cadastrado com sucesso!'
        );
    }

    /**
     * GET /entregadores/{id}/edit
     */
    public function edit(int $id): void
    {
        $entregador = $this->entregadorModel->findById($id);

        $this->abortUnless((bool) $entregador, 404, 'Entregador não encontrado.');

        $data = [
            'titulo'      => 'Entregadores',
            'subtitulo'   => 'Editar Entregador',
            'entregador'  => $entregador,
            'uploadMaxMb' => self::UPLOAD_MAX_MB,
        ];

        $this->view($this->rota . '.edit', $data, 'main');
    }

    /**
     * PUT /entregadores/{id}
     *
     * Atualiza os dados e, opcionalmente, substitui a foto.
     * Sem arquivo novo, a foto atual é mantida.
     */
    public function update(UpdateEntregadoresRequest $request, int $id): void
    {
        $entregador = $this->entregadorModel->findById($id);

        $this->abortUnless((bool) $entregador, 404, 'Entregador não encontrado.');

        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $data = $request->validated();

        // Foto não é atualizada por dados comuns do formulário.
        unset($data['avatar']);

        // Sem novo arquivo, a coluna da foto permanece inalterada.
        if (isset($_FILES['avatar']) && !empty($_FILES['avatar']['name'])) {
            $nomeAntigo = $this->nomeArquivoLocal(
                $entregador->{self::FOTO_FIELD} ?? null
            );

            $nomeNovo = $this->replaceUploadedFile(
                $_FILES['avatar'],
                self::UPLOAD_ENTITY,
                $nomeAntigo,
                'image',
                self::UPLOAD_MAX_MB,
                $id
            );

            if ($nomeNovo === null) {
                Session::flash('error', $this->uploadErrors[0] ?? 'Não foi possível enviar a foto.');
                Session::flashInput($data);
                $this->back();
                return;
            }

            $data[self::FOTO_FIELD] = $nomeNovo;
        }

        $atualizado = $this->entregadorModel->update($id, $data);

        if (!$atualizado) {
            $this->redirectWith(self::ROTA_LISTA, 'error', 'Não foi possível atualizar o entregador.');
            return;
        }

        $this->redirectWith(self::ROTA_LISTA, 'success', 'Entregador atualizado com sucesso!');
    }

    /**
     * DELETE /entregadores/{id}
     */
    public function destroy(int $id): void
    {
        $entregador = $this->entregadorModel->findById($id);

        $this->abortUnless(
            (bool) $entregador,
            404,
            'Entregador não encontrado.'
        );

        if (!$this->entregadorModel->delete($id)) {
            $this->redirectWith(self::ROTA_LISTA, 'error', 'Não foi possível remover o entregador.');
            return;
        }

        // Remove a foto da pasta vinculada ao entregador.
        $nomeArquivo = $this->nomeArquivoLocal(
            $entregador->{self::FOTO_FIELD} ?? null
        );

        if ($nomeArquivo !== null) {
            $this->deleteUploadedFile(self::UPLOAD_ENTITY, $nomeArquivo, $id);
        }

        $this->redirectWith(self::ROTA_LISTA, 'success', 'Entregador removido com sucesso.');
    }

    /**
     * GET /entregador/procurar?term=...
     */
    public function procurar(): never
    {
        $service = new BuscaNomeService($this->entregadorModel, ['tipo_vinculo', 'placa_veiculo', 'marca_veiculo',]);

        $this->json(
            $service->buscar($this->request->get('term', ''))
        );
    }

    /**
     * Aceita apenas nomes de arquivos locais.
     * URLs externas nunca devem ser apagadas do disco.
     */
    private function nomeArquivoLocal(?string $imagem): ?string
    {
        if ($imagem === null || $imagem === '' || preg_match('#^https?://#i', $imagem)) {
            return null;
        }

        return $imagem;
    }
}
