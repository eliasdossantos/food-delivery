<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\BaseController;
use App\Http\Requests\Usuarios\StoreUsuariosRequest;
use App\Http\Requests\Usuarios\UpdateUsuariosRequest;
use App\Repositories\PerfilRepository;
use App\Repositories\UsuarioRepository;
use App\Services\BuscaNome\BuscaNomeService;
use Framework\Auth\Auth;
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
 * Regras de permissão:
 *
 *   - Super Administrador:
 *       • Pode criar usuários.
 *       • Pode editar qualquer usuário.
 *       • Pode alterar qualquer perfil.
 *       • Pode criar/atribuir Super Administrador.
 *       • Pode editar outro Super Administrador.
 *       • Nunca pode ser excluído.
 *
 *   - Administrador:
 *       • Pode criar usuários.
 *       • Pode editar qualquer usuário, exceto as proteções
 *         específicas de Super Administrador.
 *       • Pode editar outro Administrador.
 *       • Não pode atribuir Super Administrador.
 *       • Não pode alterar o perfil de um Super Administrador.
 *
 *   - Demais perfis:
 *       • Não podem cadastrar usuários.
 *       • Podem editar somente o próprio cadastro.
 *       • Não podem editar outros usuários.
 *       • Não veem o campo Perfil na edição.
 */
class UsuariosController extends BaseController
{
    private UsuarioRepository $usuarioModel;
    private PerfilRepository $perfilRepository;

    public function __construct()
    {
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
            'titulo' => "Usuários",
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
        if (!$this->podeCadastrar()) {
            $this->redirectWith('admin/usuario', 'error', 'Você não tem permissão para cadastrar usuários.');
            return;
        }

        $data = [
            'titulo' => 'Cadastrar Usuário',
            'perfis' => $this->perfisPermitidos(),
        ];

        $this->view('admin.usuarios.create', $data, 'main');
    }

    /**
     * Processa a criação de um novo registro.
     * POST /usuarios
     */
    public function store(StoreUsuariosRequest $request): void
    {
        if (!$this->podeCadastrar()) {
            $this->redirectWith('admin/usuario', 'error', 'Você não tem permissão para cadastrar usuários.');
            return;
        }

        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $data = $this->normalizarPerfil($request->validated());

        // Só um Super Administrador pode criar outro Super Administrador
        if (!$this->perfilPermitido($data['perfil_id'])) {
            Session::flash('error', 'Você não tem permissão para atribuir este perfil.');
            Session::flashInput($request->all());

            $this->back();
            return;
        }

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
        $usuario = $this->usuarioModel->findComPerfil($id);
        $this->abortUnless((bool)$usuario, 404);

        // Só Super Administrador abre a edição de um Super Administrador
        if ($this->ehSuperAdmin($usuario) && !Auth::is('Super Administrador')) {
            $this->redirectWith('admin/usuario', 'error', 'Você não tem permissão para editar um Super Administrador.');
            return;
        }

        // Verifica se o usuário logado pode editar o usuário solicitado.
        if (!$this->podeEditarUsuario($id)) {
            $this->redirectWith(
                'admin/usuario',
                'error',
                'Você não tem permissão para editar os dados deste usuário.'
            );

            return;
        }

        // Somente Super Administrador pode abrir a edição de outro Super Administrador.
        if ($this->ehSuperAdmin($usuario) && !Auth::is('Super Administrador')) {
            $this->redirectWith(
                'admin/usuario',
                'error',
                'Você não tem permissão para editar um Super Administrador.'
            );

            return;
        }

        $data = [
            'titulo' => 'Detalhes do Usuário',
            'usuarios' => $usuario,
            'perfis' => $this->perfisPermitidos(),

            // O campo Perfil só deve permitir alteração para Super Administrador e Administrador.
            'podeEditarPerfil' => $this->podeCadastrar(),
        ];

        $this->view('admin.usuarios.edit', $data, 'main');
    }

    /**
     * Processa a atualização de um registro.
     * PUT /usuarios/{id}
     */
    public function update(UpdateUsuariosRequest $request, int $id): void
    {
        /**
         * PRIMEIRA PROTEÇÃO:
         *
         * Impede que um usuário comum altere outro usuário
         * simplesmente acessando diretamente a rota PUT.
         */
        if (!$this->podeEditarUsuario($id)) {
            Session::flash(
                'error',
                'Você não tem permissão para alterar os dados deste usuário.'
            );

            $this->back();

            return;
        }

        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $data = $this->normalizarPerfil($request->validated());

        // Quem não pode definir perfil nunca altera o perfil_id (o campo nem aparece na tela).
        // Sem o unset, o campo ausente viraria NULL e apagaria o perfil do usuário.
        if (!$this->podeCadastrar()) {
            unset($data['perfil_id']);
        }

        // findComPerfil traz também o perfil_nome (usado nas proteções abaixo)
        $usuarios = $this->usuarioModel->findComPerfil($id);

        $this->abortUnless((bool)$usuarios, 404, 'Usuário não encontrado.');

        /**
         * Usuários que não podem administrar perfis
         * não podem alterar o perfil_id.
         *
         * O campo nem aparece na tela para eles,
         * mas a proteção também existe no backend.
         */
        if (!$this->podeCadastrar()) {
            unset($data['perfil_id']);
        }

        // Super Administrador não pode ter o perfil trocado (senão deixaria de ser protegido)
        if (
            $this->ehSuperAdmin($usuarios)
            && array_key_exists('perfil_id', $data)
            && (int)$data['perfil_id'] !== (int)($usuarios->perfil_id ?? 0)
        ) {
            Session::flash('error', 'O perfil de um Super Administrador não pode ser alterado.');
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        // Só Super Administrador altera um Super Administrador ou atribui esse perfil
        if (
            !Auth::is('Super Administrador')
            && ($this->ehSuperAdmin($usuarios) || !$this->perfilPermitido($data['perfil_id'] ?? null))
        ) {
            Session::flash('error', 'Você não tem permissão para alterar um Super Administrador.');
            Session::flashInput($request->all());

            $this->back();
            return;
        }

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

        $updateUsuario = $this->usuarioModel->update($id, $data);

        if (!$updateUsuario) {
            $this->redirectWith(
                'admin/usuario',
                'error',
                "Não foi possível atualizar o usuário {$usuarios->nome}."
            );
            return;
        }

        // Se a pessoa editou o próprio cadastro, atualiza a sessão para o topo refletir na hora
        $this->atualizarSessaoSeForOProprio($id, $data);

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
        $usuarios = $this->usuarioModel->findComPerfil($id);
        $this->abortUnless((bool)$usuarios, 404, 'Usuário não encontrado.');

        // Somente Super Administrador e Administrador podem excluir usuários.
        if (!Auth::isAny('Super Administrador', 'Administrador')) {
            $this->redirectWith(
                'admin/usuario',
                'error',
                'Você não tem permissão para excluir usuários.'
            );
            return;
        }

        // Super Administrador nunca pode ser excluído
        if ($this->ehSuperAdmin($usuarios)) {
            $this->redirectWith('admin/usuario', 'error', 'Um usuário Super Administrador não pode ser excluído.');
            return;
        }

        // Ninguém exclui a própria conta
        if ($id === Auth::id()) {
            $this->redirectWith('admin/usuario', 'error', 'Você não pode excluir a sua própria conta.');
            return;
        }

        $this->usuarioModel->delete($id);

        $this->redirectWith('admin/usuario', 'success', 'Usuário removido com sucesso.');
    }

    /**
     * Autocomplete por nome.
     * GET /usuario/procurar?term=...
     */
    public function procurar(): never
    {
        $service = new BuscaNomeService($this->usuarioModel);

        $this->json($service->buscar($this->request->get('term', '')));
    }

    // ── Auxiliares ────────────────────────────────────────────────────────────

    /**
     * Verifica se o usuário logado pode editar o usuário informado.
     *
     * Regras:
     *
     *   - O próprio usuário pode editar seus próprios dados.
     *   - Super Administrador pode editar qualquer usuário.
     *   - Administrador pode editar qualquer usuário.
     *   - Demais perfis só podem editar a própria conta.
     */
    private function podeEditarUsuario(int $id): bool
    {
        /**
         * O próprio usuário sempre pode editar
         * os seus próprios dados
         */
        if ($id === Auth::id()) {
            return true;
        }

        /**
         * Somente Super Administrador e Administrador
         * podem editar outros usuários
         */
        return Auth::isAny('Super Administrador', 'Administrador');
    }

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

    /** 
     * O usuário (vindo de findComPerfil) tem o perfil Super Administrador?
     */
    private function ehSuperAdmin(object $usuario): bool
    {
        return Auth::normalizarPerfil((string)($usuario->perfil_nome ?? '')) === 'super_administrador';
    }

    /** 
     * Só Super Administrador e Administrador podem cadastrar usuários 
     */
    private function podeCadastrar(): bool
    {
        return Auth::isAny('Super Administrador', 'Administrador');
    }

    /** 
     * O usuário logado pode atribuir este perfil? (Super Administrador só por outro Super Administrador) 
     */
    private function perfilPermitido(?int $perfilId): bool
    {
        if ($perfilId === null || Auth::is('Super Administrador')) {
            return true;
        }

        $perfil = $this->perfilRepository->findById($perfilId);

        return $perfil && Auth::normalizarPerfil((string)($perfil->nome ?? '')) !== 'super_administrador';
    }

    /** 
     * Perfis que aparecem no select, de acordo com quem está logado 
     */
    private function perfisPermitidos(): array
    {
        $perfis = $this->perfilRepository->getActive();

        if (Auth::is('Super Administrador')) {
            return $perfis;
        }

        return array_values(array_filter(
            $perfis,
            fn($perfil) => Auth::normalizarPerfil((string)($perfil->nome ?? '')) !== 'super_administrador'
        ));
    }

    /**
     * Se o usuário editado é o próprio logado, atualiza os dados guardados na sessão
     * (nome no topo etc.) sem precisar sair e entrar de novo.
     * Senha e perfil ficam de fora: o hash não vai para a sessão e o perfil
     * só é recalculado no login.
     */
    private function atualizarSessaoSeForOProprio(int $id, array $data): void
    {
        if ($id !== Auth::id()) {
            return;
        }

        $atual = Auth::usuario();

        if (!$atual) {
            return;
        }

        $identidade = clone $atual;

        foreach ($data as $campo => $valor) {
            if (in_array($campo, ['password', 'password_confirmation', 'perfil_id'], true)) {
                continue;
            }

            $identidade->$campo = $valor;
        }

        // O login grava a mesma identidade nas duas chaves
        Session::set('usuario', $identidade);
        Session::set('usuario_identidade', $identidade);
    }
}
