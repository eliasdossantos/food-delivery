<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\BaseController;
use App\Repositories\UsuarioRepository;

/**
 * HomeController
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
class HomeController extends BaseController
{

    private UsuarioRepository $usuarioModel;

    public function __construct()
    {
        parent::__construct();

        // Inicialize dependências aqui
        $this->usuarioModel = new UsuarioRepository();
    }

    /**
     * Lista os usuários com seus respectivos perfis.
     *
     * O UsuarioRepository já realiza o JOIN com a tabela perfis
     * e disponibiliza o nome do perfil através de "perfil_nome".
     *
     * GET /home
     */
    public function index(): void
    {
        $data = [
            'titulo' => 'Food Delivery',
            'subtitulo' => 'Home da área restrita',
            'tituloTabela' => 'Lista de Usuários',
            'usuarios' => $this->usuarioModel->tudoComPerfil()
        ];

        $this->view('admin.home.index', $data, 'main');
    }

    /**
     * Exibe um registro específico.
     * GET /home/{id}
     */
    public function show(int $id): void
    {
        // $item = $this->repository->findById($id);
        // $this->abortUnless((bool)$item, 404);

        $this->view('home.show', [
            'title' => 'Detalhes',
            // 'item'  => $item,
        ]);
    }

    /**
     * Exibe o formulário de criação.
     * GET /home/create
     */
    public function create(): void
    {
        $this->view('home.create', [
            'title' => 'Novo Home',
        ]);
    }

    /**
     * Processa a criação de um novo registro.
     * POST /home
     */
    public function store(): void
    {
        // Exemplo com FormRequest:
        // $request = new StoreHomeRequest();
        // if ($request->fails()) {
        //     Session::flash('error', $request->firstError());
        //     Session::flashErrors($request->errors());
        //     Session::flashInput($request->all());
        //
        //     $this->back();
        //     return;
        // }
        // $data = $request->validated();

        // Exemplo com validate() inline — usa $this->request->all() (dados já
        // sanitizados) em vez de $_POST direto, mantendo a mesma camada de
        // acesso a dados do resto do framework:
        $this->validate($this->request->all(), [
            'nome' => 'required|min:2|max:100',
        ]);

        $data = $this->request->all();

        // ── Upload de arquivo (exemplo — descomente e ajuste o campo) ───────────
        // Sem dono (arquivo avulso, ex: imagem de capa):
        //     uploads/home/arquivo.jpg
        //
        // if (!empty($_FILES['imagem']['name'])) {
        //     $nomeArquivo = $this->saveUploadedFile(
        //         $_FILES['imagem'],
        //         'home', // entity — categoria da pasta
        //         'image',              // ou 'document'
        //         5                     // maxMb
        //         // , $this->userId()  // entityId opcional — agrupa em home/{id}/
        //         //                      quando o arquivo pertence a um dono (ex: usuário logado)
        //     );
        //
        //     if ($nomeArquivo === null) {
        //         Session::flash('error', $this->uploadErrors[0] ?? 'Falha no upload.');
        //         Session::flashInput($data);
        //         $this->back();
        //         return;
        //     }
        //
        //     $data['imagem'] = $nomeArquivo;
        // }

        // $id = $this->repository->create($data);
        $this->redirectWith('home', 'success', 'Home criado com sucesso!');
    }

    /**
     * Exibe o formulário de edição.
     * GET /home/{id}/edit
     */
    public function edit(int $id): void
    {
        // $item = $this->repository->findById($id);
        // $this->abortUnless((bool)$item, 404);

        $this->view('home.edit', [
            'title' => 'Editar Home',
            // 'item'  => $item,
        ]);
    }

    /**
     * Processa a atualização de um registro.
     * PUT /home/{id}
     */
    public function update(int $id): void
    {
        // $item = $this->repository->findById($id);
        // $this->abortUnless((bool)$item, 404);

        // Exemplo com FormRequest:
        // $request = new UpdateHomeRequest();
        // if ($request->fails()) {
        //     Session::flash('error', $request->firstError());
        //     Session::flashErrors($request->errors());
        //     Session::flashInput($request->all());
        //
        //     $this->back();
        //     return;
        // }
        //
        // $data = $request->validated();

        // Exemplo com validate() inline:
        $this->validate($this->request->all(), [
            'nome' => 'required|min:2|max:100',
        ]);

        $data = $this->request->all();

        // ── Upload de arquivo (exemplo — descomente e ajuste o campo) ───────────
        // Troca o arquivo antigo pelo novo (o antigo só é apagado se o novo
        // upload for salvo com sucesso). $item precisa estar carregado acima.
        //
        // if (!empty($_FILES['imagem']['name'])) {
        //     $nomeArquivo = $this->replaceUploadedFile(
        //         $_FILES['imagem'],
        //         'home',        // entity
        //         $item->imagem ?? null,       // nome antigo salvo no banco
        //         'image',                     // ou 'document'
        //         5                             // maxMb
        //         // , $this->userId()          // entityId opcional
        //     );
        //
        //     if ($nomeArquivo === null) {
        //         Session::flash('error', $this->uploadErrors[0] ?? 'Falha no upload.');
        //         Session::flashInput($data);
        //         $this->back();
        //         return;
        //     }
        //
        //     $data['imagem'] = $nomeArquivo;
        // }

        // $updated = $this->repository->update($id, $data);
        //
        // if (!$updated) {
        //     $this->redirectWith(
        //         'home',
        //         'error',
        //         'Não foi possível atualizar Home.'
        //     );
        //     return;
        // }

        $this->redirectWith(
            'home',
            'success',
            'Home atualizado com sucesso!'
        );
    }

    /**
     * Remove um registro.
     * DELETE /home/{id}
     */
    public function destroy(int $id): void
    {
        // $item = $this->repository->findById($id);
        // $this->abortUnless((bool)$item, 404);

        // Se o Model tiver arquivo, apague-o junto (evita lixo em storage/uploads):
        // if (!empty($item->imagem)) {
        //     $this->deleteUploadedFile('home', $item->imagem);
        // }

        // $this->repository->delete($id);

        $this->jsonSuccess('Home removido com sucesso.');
    }
}
