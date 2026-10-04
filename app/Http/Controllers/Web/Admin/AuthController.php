<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\BaseController;
use App\Http\Requests\Auth\AdminForgotPasswordRequest;
use App\Http\Requests\Auth\AdminLoginRequest;
use App\Http\Requests\Auth\AdminRegisterRequest;
use App\Http\Requests\Auth\AdminResetPasswordRequest;
use App\Models\PerfilModel;
use App\Models\UsuarioModel;
use App\Services\Auth\RecuperacaoSenhaService;
use Framework\Auth\Auth;
use Framework\Support\Session;

class AuthController extends BaseController
{
    public function loginForm(): void
    {
        $this->view('auth.admin.login', ['titulo' => 'Acesso administrativo', 'fluxo' => 'admin'], 'auth-admin');
    }
    public function login(AdminLoginRequest $request): void
    {
        if ($request->fails()) {
            $this->falha($request, '/admin/login');
            return;
        }
        $data = $request->validated();
        if (!Auth::guard('usuario')->attempt($data['email'], $data['senha'], !empty($_POST['lembrar']))) {
            Session::flash('error', 'E-mail ou senha inválidos, ou usuário inativo.');
            Session::flashInput(['email' => $data['email']]);
            $this->redirect('/admin/login');
        }
        $this->redirect('/admin');
    }
    public function registerForm(): void
    {
        if (!$this->cadastroInicialDisponivel()) {
            Session::flash('info', 'Novos acessos administrativos devem ser criados por um administrador já autenticado.');
            $this->redirect('/admin/login');
        }
        $this->view('auth.admin.register', ['titulo' => 'Cadastro administrativo'], 'auth-admin');
    }
    public function register(AdminRegisterRequest $request): void
    {
        if (!$this->cadastroInicialDisponivel()) {
            Session::flash('info', 'O cadastro administrativo público já foi encerrado.');
            $this->redirect('/admin/login');
        }
        if ($request->fails()) {
            $this->falha($request, '/admin/cadastro');
            return;
        }
        $data = $request->validated();
        $perfil = (new PerfilModel())->findByNome('Administrador');
        $id = (new UsuarioModel())->createWithHash(['nome' => $data['nome'], 'email' => $data['email'], 'password' => $data['senha'], 'perfil_id' => $perfil->id ?? null, 'ativo' => 1]);
        if (!$id) {
            Session::flash('error', 'Não foi possível concluir o cadastro.');
            $this->redirect('/admin/cadastro');
        }
        Session::flash('success', 'Cadastro concluído. Faça login para continuar.');
        $this->redirect('/admin/login');
    }
    public function forgotForm(): void
    {
        $this->view('auth.admin.forgot', ['titulo' => 'Recuperar acesso administrativo'], 'auth-admin');
    }
    public function forgot(AdminForgotPasswordRequest $request): void
    {
        if ($request->fails()) {
            $this->falha($request, '/admin/esqueci-senha');
            return;
        }
        (new RecuperacaoSenhaService())->solicitar('usuario', $request->validated()['email']);
        Session::flash('success', 'Se o e-mail estiver cadastrado, você receberá as instruções de recuperação.');
        $this->redirect('/admin/esqueci-senha');
    }
    public function resetForm(): void
    {
        $this->view('auth.admin.reset', ['titulo' => 'Redefinir acesso', 'token' => $_GET['token'] ?? ''], 'auth-admin');
    }
    public function reset(AdminResetPasswordRequest $request): void
    {
        if ($request->fails()) {
            $this->falha($request, '/admin/redefinir-senha');
            return;
        }
        $data = $request->validated();
        if (!(new RecuperacaoSenhaService())->redefinir('usuario', $data['token'], $data['senha'])) {
            Session::flash('error', 'Token inválido, expirado ou já utilizado.');
            $this->redirect('/admin/redefinir-senha?token=' . urlencode($data['token']));
        }
        Session::flash('success', 'Senha redefinida com sucesso.');
        $this->redirect('/admin/login');
    }
    public function logout(): void
    {
        Auth::guard('usuario')->logout();
        Session::flash('success', 'Sessão administrativa encerrada.');
        $this->redirect('/admin/login');
    }
    private function cadastroInicialDisponivel(): bool
    {
        try {
            return !(new UsuarioModel())->where('ativo', 1)->exists();
        } catch (\Throwable) {
            return false;
        }
    }
    private function falha(object $request, string $url): never
    {
        Session::flash('error', $request->firstError() ?: 'Revise os dados informados.');
        Session::flashErrors($request->errors());
        Session::flashInput($request->all());
        $this->redirect($url);
    }
}
