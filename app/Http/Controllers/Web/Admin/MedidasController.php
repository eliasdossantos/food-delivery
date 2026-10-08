<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\BaseController;
use App\Http\Requests\Medidas\StoreMedidasRequest;
use App\Http\Requests\Medidas\UpdateMedidasRequest;
use App\Repositories\MedidaRepository;
use Framework\Support\Session;

/**
 * MedidasController
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
class MedidasController extends BaseController
{
    /** Entidade (subpasta) dos uploads de extra */
    private const UPLOAD_ENTITY = 'medidas';

    /** Tamanho máximo da imagem, em MB */
    private const UPLOAD_MAX_MB = 5;

    private MedidaRepository $medidasModel;

    public function __construct()
    {
        $this->medidasModel = new MedidaRepository();
    }

    /**
     * Lista todos os registros.
     * GET /medidas
     */
    public function index(): void
    {
        $data = [
            'titulo' => 'Medidas dos Produtos',
            'tituloTabela' => 'Lista as Medidas dos Produtos',
            'medidas' => $this->medidasModel->all(),
        ];

        $this->view('admin.medidas.index', $data, 'main');
    }

    /**
     * Exibe um registro específico.
     * GET /medidas/{id}
     */
    public function show(int $id): void
    {
        $medidas = $this->medidasModel->findById($id);
        $this->abortUnless((bool)$medidas, 404, 'Medida do produto não encontrado.');

        $data = [
            'titulo' => 'Medidas dos Produtos',
            'subtitulo' => 'Detalhes das Medidas dos Produtos',
            'medidas' => $medidas,
        ];

        $this->view('admin.medidas.show', $data, 'main');
    }

    /**
     * Exibe o formulário de criação.
     * GET /medidas/create
     */
    public function create(): void
    {
        $data = [
            'titulo' => 'Nova Medidas',
            'subtitulo' => 'Criar nova medidas',
            'uploadMaxMb' => self::UPLOAD_MAX_MB,
        ];

        $this->view('admin.medidas.create', $data, 'main');
    }

    /**
     * Processa a criação de um novo registro.
     * POST /medidas
     */
    public function store(StoreMedidasRequest $request): void
    {
        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $data = $request->validated();

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
                Session::flash('error', $this->uploadErrors[0] ?? 'Falha no upload.');
                Session::flashInput($data);
                $this->back();
                return;
            }

            $data['imagem_url'] = $nomeArquivo;
        }

        $id = $this->medidasModel->create($data);

        if (!$id) {
            if ($nomeArquivo !== null) {
                $this->deleteUploadedFile(self::UPLOAD_ENTITY, $nomeArquivo);
            }

            $this->redirectWith('admin/medida', 'error', 'Não foi possível criar a medida.');
            return;
        }

        // $id = $this->medidasModel->create($data);
        $this->redirectWith('admin/medida', 'success', 'Medida criada com sucesso!');
    }

    /**
     * Exibe o formulário de edição.
     * GET /medidas/{id}/edit
     */
    public function edit(int $id): void
    {
        $medidas = $this->medidasModel->findById($id);
        $this->abortUnless((bool)$medidas, 404, 'Medida não encontrada.');

        $data = [
            'titulo' => 'Editar Medidas',
            'subtitulo' => 'Editando Medidas do Produto',
            'medidas' => $medidas,
            'uploadMaxMb' => self::UPLOAD_MAX_MB,
        ];


        $this->view('admin.medidas.edit', $data, 'main');
    }

    /**
     * Processa a atualização de um registro.
     * PUT /medidas/{id}
     */
    public function update(UpdateMedidasRequest $request, int $id): void
    {
        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $medidas = $this->medidasModel->findById($id);
        $this->abortUnless((bool)$medidas, 404, 'Medida não encontrada.');

        $data = $request->validated();


        // ── Troca da imagem (só se um novo arquivo foi enviado) ──────────────
        // O arquivo antigo só é apagado se o novo for salvo com sucesso.
        // Sem arquivo novo, imagem_url não entra em $data e a atual é mantida.

        if (!empty($_FILES['imagem']['name'])) {
            $nomeAntigo = $this->nomeArquivoLocal($medidas->imagem_url ?? null);

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

        $atualizada = $this->medidasModel->update($id, $data);

        if (!$atualizada) {
            $this->redirectWith('admin/medida', 'error', 'Não foi possível atualizar a medida.');
            return;
        }

        $this->redirectWith(
            'admin/medida',
            'success',
            'Medida atualizada com sucesso!'
        );
    }

    /**
     * Remove um registro.
     * DELETE /medidas/{id}
     */
    public function destroy(int $id): void
    {
        $medidas = $this->medidasModel->findById($id);
        $this->abortUnless((bool)$medidas, 404, 'Medida não encontrada.');

        if (!$this->medidasModel->delete($id)) {
            $this->redirectWith('admin/medida', 'error', 'Não foi possível remover a medida.');
            return;
        }

        // Apaga a imagem junto (evita lixo em public/uploads)
        $nomeArquivo = $this->nomeArquivoLocal($medidas->imagem_url ?? null);

        if ($nomeArquivo !== null) {
            $this->deleteUploadedFile(self::UPLOAD_ENTITY, $nomeArquivo);
        }

        $this->redirectWith('admin/medida', 'success', 'Medida removida com sucesso.');
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
