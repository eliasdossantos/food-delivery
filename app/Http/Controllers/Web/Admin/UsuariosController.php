<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\BaseController;
use App\Http\Requests\Usuarios\StoreUsuariosRequest;
use App\Http\Requests\Usuarios\UpdateUsuariosRequest;
use App\Repositories\PerfilRepository;
use App\Repositories\UsuarioRepository;
use Framework\Support\Session;

/**
 * UsuariosController
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
class UsuariosController extends BaseController
{
    private UsuarioRepository $usuarioModel;
    private PerfilRepository $perfilRepository;

    public function __construct()
    {
        parent::__construct();

        $this->usuarioModel = new UsuarioRepository();
        $this->perfilRepository = new PerfilRepository();
    }

    /**
     * Lista todos os registros.
     * GET /usuarios
     */
    public function index(): void
    {
        $data = [
            'titulo' => 'Food Delivery',
            'subtitulo' => 'Home da área restrita',
            'tituloTabela' => 'Lista de Usuários',
            'usuarios' => $this->usuarioModel->tudoComPerfil()
        ];

        $this->view('admin.usuarios.index', $data, 'main');
    }

    /**
     * Exibe um registro específico.
     * GET /usuarios/{id}
     */
    public function show(int $id): void
    {
        $usuarios = $this->usuarioModel->findComPerfil($id);
        $this->abortUnless((bool)$usuarios, 404, 'Usuário não encontrado.');

        $data = [
            'titulo' => 'Detalhes do Usuário',
            'usuarios' => $usuarios,
        ];

        $this->view('admin.usuarios.show', $data, 'main');
    }

    /**
     * Exibe o formulário de criação.
     * GET /usuarios/create
     */
    public function create(): void
    {
        $data = [
            'titulo' => 'Cadastrar Usuário',
            'perfis' => $this->perfilRepository->getActive(),
        ];

        $this->view('admin.usuarios.create', $data, 'main');
    }

    /**
     * Processa a criação de um novo registro.
     * POST /usuarios
     */
    public function store(StoreUsuariosRequest $request): void
    {
        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $data = $this->normalizarPerfil($request->validated());
        $data['password'] = $this->hashSenha($data['password']);

        $id = $this->usuarioModel->create($data);

        if (!$id) {
            $this->redirectWith('admin/usuario', 'error', 'Não foi possível criar o usuário.');
            return;
        }

        // Após criar, vai direto para a página (show) do usuário criado
        $this->redirectWith('admin/usuario/' . (int) $id, 'success', "Usuário {$data['nome']} criado com sucesso!");
    }

    /**
     * Exibe o formulário de edição.
     * GET /usuarios/{id}/edit
     */
    public function edit(int $id): void
    {
        $usuario = $this->usuarioModel->findById($id);
        $this->abortUnless((bool)$usuario, 404);

        $data = [
            'titulo' => 'Detalhes do Usuário',
            'usuarios' => $usuario,
            'perfis' => $this->perfilRepository->getActive(),
        ];

        $this->view('admin.usuarios.edit', $data, 'main');
    }

    /**
     * Processa a atualização de um registro.
     * PUT /usuarios/{id}
     */
    public function update(UpdateUsuariosRequest $request, int $id): void
    {

        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $data = $this->normalizarPerfil($request->validated());

        $senha = $data['password'] ?? '';
        $confirmacao = $data['password_confirmation'] ?? '';

        // Senha nova informada: a confirmação precisa ser idêntica.
        // (O nullable do Validator ignora a confirmação vazia, então checamos aqui.)
        if ($senha !== '' && $senha !== $confirmacao) {
            $mensagem = 'A confirmação da senha não confere.';

            Session::flash('error', $mensagem);
            Session::flashErrors(['password_confirmation' => [$mensagem]]);
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        // Senha vazia = manter a senha atual (não pode sobrescrever com '')
        if ($senha === '') {
            unset($data['password']);
        } else {
            $data['password'] = $this->hashSenha($senha);
        }

        unset($data['password_confirmation']);

        $usuarios = $this->usuarioModel->findById($id);
        $this->abortUnless((bool)$usuarios, 404, 'Usuário não encontrado.');

        $updateUsuario = $this->usuarioModel->update($id, $data);

        if (!$updateUsuario) {
            $this->redirectWith(
                'admin/usuario',
                'error',
                "Não foi possível atualizar o usuário {$usuarios->nome}."
            );
            return;
        }

        $this->redirectWith(
            'admin/usuario',
            'success',
            "Usuário {$usuarios->nome} atualizado com sucesso!"
        );
    }

    /**
     * Remove um registro.
     * DELETE /usuarios/{id}
     */
    public function destroy(int $id): void
    {
        $usuarios = $this->usuarioModel->findById($id);
        $this->abortUnless((bool)$usuarios, 404, 'Usuário não encontrado.');

        $this->usuarioModel->delete($id);

        $this->redirectWith('admin/usuario', 'success', 'Usuário removido com sucesso.');
    }

    public function procurar(): never
    {
        $term = $this->request->get('term', '');

        $usuarios = $this->usuarioModel->procurar($term);

        $resultados = [];

        foreach ($usuarios as $usuario) {
            $resultados[] = [
                'id'    => $usuario->id,
                'value' => $usuario->nome,
            ];
        }

        $this->json($resultados);
    }

    // ── Auxiliares ────────────────────────────────────────────────────────────

    /**
     * Garante que "Sem perfil" seja NULL (nunca '' em coluna inteira com FK)
     * e que um perfil escolhido seja inteiro.
     */
    private function normalizarPerfil(array $data): array
    {
        $valor = $data['perfil_id'] ?? null;

        $data['perfil_id'] = ($valor === null || $valor === '')
            ? null
            : (int) $valor;

        return $data;
    }

    private function hashSenha(string $senha): string
    {
        return password_hash($senha, PASSWORD_BCRYPT, ['cost' => 12]);
    }
}
