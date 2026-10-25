<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\BaseController;
use App\Http\Requests\Configuracoes\StoreConfiguracoesRequest;
use App\Http\Requests\Configuracoes\UpdateConfiguracoesRequest;
use App\Repositories\ConfiguracaoRepository;
use App\Services\Integrations\Cep\CepService;
use Framework\Support\Session;

/**
 * ConfiguracoesController
 * ─────────────────────────────────────────────────────────────────────────────
 * Responsável por receber as requisições HTTP, delegar para o Service/Repository
 * e retornar a resposta adequada (View ou JSON).
 *
 * Regra: controllers devem ser finos.
 * Lógica de negócio → Service | Acesso a dados → Repository
 *
 * Upload de arquivo:
 *   O único campo de arquivo é o 'logo'.
 *   Ver Framework\Support\Upload e BaseController::saveUploadedFile()/replaceUploadedFile()
 *   para detalhes.
 */
class ConfiguracoesController extends BaseController
{
    private const UPLOAD_ENTITY = 'configuracoes';
    private const UPLOAD_MAX_MB = 5;
    private const UPLOAD_FIELD  = 'logo';

    private ConfiguracaoRepository $configuracaoModel;

    public function __construct()
    {
        $this->configuracaoModel = new ConfiguracaoRepository();
    }

    /** GET /configuracoes */
    public function index(): void
    {
        $data = [
            'titulo'         => 'Configurações',
            'subtitulo'      => 'Lista de configurações',
            'configuracoes'  => $this->configuracaoModel->all(),
        ];

        $this->view('admin.configuracoes.index', $data, 'main');
    }

    /** GET /configuracoes/{id} */
    public function show(int $id): void
    {
        $configuracao = $this->configuracaoModel->findById($id);
        $this->abortUnless((bool) $configuracao, 404, 'Configuração não encontrada.');

        $data = [
            'titulo'        => 'Configurações',
            'subtitulo'     => 'Detalhes da configuração',
            'configuracao'  => $configuracao,
        ];

        $this->view('admin.configuracoes.show', $data, 'main');
    }

    /** GET /configuracoes/create */
    public function create(): void
    {
        $data = [
            'titulo'      => 'Nova configuração',
            'subtitulo'   => 'Criar configuração',
            'uploadMaxMb' => self::UPLOAD_MAX_MB,
        ];

        $this->view('admin.configuracoes.create', $data, 'main');
    }

    /** POST /configuracoes */
    public function store(StoreConfiguracoesRequest $request): void
    {
        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());
            $this->back();
            return;
        }

        $data = $request->validated();
        $logo = null;

        if (!empty($_FILES[self::UPLOAD_FIELD]['name'])) {
            $logo = $this->salvarLogo();

            if ($logo === null) {
                Session::flash('error', $this->uploadErrors[0] ?? 'Falha no upload.');
                Session::flashInput($request->all());
                $this->back();
                return;
            }

            $data[self::UPLOAD_FIELD] = $logo;
        }

        $id = $this->configuracaoModel->create($data);
        if (!$id) {
            if ($logo !== null) {
                $this->deleteUploadedFile(self::UPLOAD_ENTITY, $logo);
            }
            $this->redirectWith('admin/configuracao', 'error', 'Não foi possível criar a configuração.');
            return;
        }

        $this->redirectWith('admin/configuracao', 'success', 'Configuração criada com sucesso!');
    }

    /** GET /configuracoes/{id}/edit */
    public function edit(int $id): void
    {
        $configuracao = $this->configuracaoModel->findById($id);
        $this->abortUnless((bool) $configuracao, 404, 'Configuração não encontrada.');

        $data = [
            'titulo'        => 'Configuração',
            'subtitulo'     => 'Editar configuração',
            'uploadMaxMb'   => self::UPLOAD_MAX_MB,
            'configuracao'  => $configuracao,
        ];

        $this->view('admin.configuracoes.edit', $data, 'main');
    }

    /** PUT /configuracoes/{id} */
    public function update(UpdateConfiguracoesRequest $request, int $id): void
    {
        $configuracao = $this->configuracaoModel->findById($id);
        $this->abortUnless((bool) $configuracao, 404, 'Configuração não encontrada.');

        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());
            $this->back();
            return;
        }

        $data = $request->validated();

        if (empty($_FILES[self::UPLOAD_FIELD]['name'])) {
            // Sem arquivo novo, o valor validado não deve apagar o logo atual.
            unset($data[self::UPLOAD_FIELD]);
        } else {
            $logoAntigo = $this->nomeArquivoLocal($configuracao->{self::UPLOAD_FIELD} ?? null);
            $logoNovo = $this->substituirLogo($logoAntigo);

            if ($logoNovo === null) {
                Session::flash('error', $this->uploadErrors[0] ?? 'Falha no upload.');
                Session::flashInput($request->all());
                $this->back();
                return;
            }

            $data[self::UPLOAD_FIELD] = $logoNovo;
        }

        $atualizada = $this->configuracaoModel->update($id, $data);
        if (!$atualizada) {
            $this->redirectWith('admin/configuracao', 'error', 'Não foi possível atualizar a configuração.');
            return;
        }

        $this->redirectWith('admin/configuracao', 'success', 'Configuração atualizada com sucesso!');
    }

    /** DELETE /configuracoes/{id} */
    public function destroy(int $id): void
    {
        $configuracao = $this->configuracaoModel->findById($id);
        $this->abortUnless((bool) $configuracao, 404, 'Configuração não encontrada.');

        if (!$this->configuracaoModel->delete($id)) {
            $this->redirectWith('admin/configuracao', 'error', 'Não foi possível remover a configuração.');
            return;
        }

        // Só remove o logo depois que o registro foi excluído com sucesso.
        $logo = $this->nomeArquivoLocal($configuracao->{self::UPLOAD_FIELD} ?? null);
        if ($logo !== null) {
            $this->deleteUploadedFile(self::UPLOAD_ENTITY, $logo);
        }

        $this->redirectWith('admin/configuracao', 'success', 'Configuração removida com sucesso.');
    }

    /** GET /admin/configuracao/cep?cep=58000000 */
    public function consultarCep(): never
    {
        $cep = preg_replace('/\D/', '', (string) $this->request->get('cep', ''));

        if (strlen($cep) !== 8) {
            http_response_code(422);
            $this->json(['ok' => false, 'message' => 'CEP inválido.']);
        }

        $endereco = (new CepService())->buscar($cep);

        if ($endereco === null) {
            http_response_code(404);
            $this->json(['ok' => false, 'message' => 'CEP não encontrado.']);
        }

        $this->json([
            'ok'         => true,
            'logradouro' => $endereco['logradouro'] ?? '',
            'bairro'     => $endereco['bairro'] ?? '',
            'cidade'     => $endereco['cidade'] ?? '',
            'uf'         => $endereco['uf'] ?? '',
        ]);
    }

    // ── Auxiliares ────────────────────────────────────────────────────────────

    /** Salva o logo novo, sem arquivo anterior. */
    private function salvarLogo(): ?string
    {
        return $this->saveUploadedFile(
            $_FILES[self::UPLOAD_FIELD],
            self::UPLOAD_ENTITY,
            'image',
            self::UPLOAD_MAX_MB
        );
    }

    /** Substitui o logo já existente. */
    private function substituirLogo(?string $arquivoAntigo): ?string
    {
        return $this->replaceUploadedFile(
            $_FILES[self::UPLOAD_FIELD],
            self::UPLOAD_ENTITY,
            $arquivoAntigo,
            'image',
            self::UPLOAD_MAX_MB
        );
    }

    /** Retorna apenas nomes locais; URLs externas não devem ser apagadas. */
    private function nomeArquivoLocal(?string $arquivo): ?string
    {
        if ($arquivo === null || $arquivo === '' || preg_match('#^https?://#i', $arquivo)) {
            return null;
        }

        return $arquivo;
    }
}