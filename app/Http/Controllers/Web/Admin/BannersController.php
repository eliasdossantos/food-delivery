<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\BaseController;
use App\Http\Requests\Banners\StoreBannersRequest;
use App\Http\Requests\Banners\UpdateBannersRequest;
use App\Repositories\BannerRepository;
use Framework\Support\Session;

/**
 * BannersController
 * ─────────────────────────────────────────────────────────────────────────────
 * Responsável por receber as requisições HTTP, delegar para o Repository
 * e retornar a resposta adequada (View ou JSON).
 *
 * Regra: controllers devem ser finos.
 * Regra de vigência (ativo + período) → BannerRepository::vigentes() e statusBanner()
 *
 * Upload de arquivo:
 *   O único campo de arquivo é a 'imagem' (opcional).
 *   Ver Framework\Support\Upload e BaseController::saveUploadedFile()/replaceUploadedFile().
 */
class BannersController extends BaseController
{
    private const UPLOAD_ENTITY = 'banners';
    private const UPLOAD_MAX_MB = 5;
    private const UPLOAD_FIELD  = 'imagem';

    private BannerRepository $bannerModel;

    public function __construct()
    {
        $this->bannerModel = new BannerRepository();
    }

    /** GET /banners */
    public function index(): void
    {
        $data = [
            'titulo'    => 'Banners',
            'subtitulo' => 'Lista de banners',
            'banners'   => $this->bannerModel->todos(),
        ];

        $this->view('admin.banners.index', $data, 'main');
    }

    /** GET /banners/{id} */
    public function show(int $id): void
    {
        $banner = $this->bannerModel->findById($id);
        $this->abortUnless((bool) $banner, 404, 'Banner não encontrado.');

        $data = [
            'titulo'    => 'Banners',
            'subtitulo' => 'Detalhes do banner',
            'banner'    => $banner,
        ];

        $this->view('admin.banners.show', $data, 'main');
    }

    /** GET /banners/create */
    public function create(): void
    {
        $data = [
            'titulo'      => 'Novo banner',
            'subtitulo'   => 'Criar banner',
            'uploadMaxMb' => self::UPLOAD_MAX_MB,
        ];

        $this->view('admin.banners.create', $data, 'main');
    }

    /** POST /banners */
    public function store(StoreBannersRequest $request): void
    {
        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());
            $this->back();
            return;
        }

        $data = $this->normalizarPeriodo($request->validated());
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

        $id = $this->bannerModel->create($data);

        if (!$id) {
            // Não deixa arquivo órfão em public/uploads se o registro não foi criado
            if ($nomeArquivo !== null) {
                $this->deleteUploadedFile(self::UPLOAD_ENTITY, $nomeArquivo);
            }

            $this->redirectWith('admin/banner', 'error', 'Não foi possível criar o banner.');
            return;
        }

        $this->redirectWith('admin/banner', 'success', 'Banner criado com sucesso!');
    }

    /** GET /banners/{id}/edit */
    public function edit(int $id): void
    {
        $banner = $this->bannerModel->findById($id);
        $this->abortUnless((bool) $banner, 404, 'Banner não encontrado.');

        $data = [
            'titulo'      => 'Editar banner',
            'subtitulo'   => 'Editar banner',
            'uploadMaxMb' => self::UPLOAD_MAX_MB,
            'banner'      => $banner,
        ];

        $this->view('admin.banners.edit', $data, 'main');
    }

    /** PUT /banners/{id} */
    public function update(UpdateBannersRequest $request, int $id): void
    {
        $banner = $this->bannerModel->findById($id);
        $this->abortUnless((bool) $banner, 404, 'Banner não encontrado.');

        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());
            $this->back();
            return;
        }

        $data = $this->normalizarPeriodo($request->validated());

        // ── Upload da imagem (opcional) ──────────────────────────────────────
        if (empty($_FILES[self::UPLOAD_FIELD]['name'])) {
            // Sem arquivo novo, o valor validado não deve apagar a imagem atual.
            unset($data[self::UPLOAD_FIELD]);
        } else {
            $nomeAntigo = $this->nomeArquivoLocal($banner->{self::UPLOAD_FIELD} ?? null);

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

        if (!$this->bannerModel->update($id, $data)) {
            $this->redirectWith('admin/banner', 'error', 'Não foi possível atualizar o banner.');
            return;
        }

        $this->redirectWith('admin/banner', 'success', 'Banner atualizado com sucesso!');
    }

    /** DELETE /banners/{id} */
    public function destroy(int $id): void
    {
        $banner = $this->bannerModel->findById($id);
        $this->abortUnless((bool) $banner, 404, 'Banner não encontrado.');

        if (!$this->bannerModel->delete($id)) {
            $this->redirectWith('admin/banner', 'error', 'Não foi possível remover o banner.');
            return;
        }

        // Só remove a imagem depois que o registro foi excluído com sucesso.
        $nomeArquivo = $this->nomeArquivoLocal($banner->{self::UPLOAD_FIELD} ?? null);

        if ($nomeArquivo !== null) {
            $this->deleteUploadedFile(self::UPLOAD_ENTITY, $nomeArquivo);
        }

        $this->redirectWith('admin/banner', 'success', 'Banner removido com sucesso.');
    }

    // ── Auxiliares ────────────────────────────────────────────────────────────

    /**
     * Período de exibição: campo vazio vira NULL (= sem limite naquele lado).
     * O formulário sempre envia os dois campos, então limpar uma data remove o limite.
     * String vazia em coluna datetime dá erro no MySQL em modo estrito.
     */
    private function normalizarPeriodo(array $data): array
    {
        foreach (['data_inicio', 'data_fim'] as $campo) {
            $valor = trim((string) ($data[$campo] ?? ''));
            $ts    = $valor !== '' ? strtotime($valor) : false;

            $data[$campo] = $ts ? date('Y-m-d H:i:s', $ts) : null;
        }

        return $data;
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