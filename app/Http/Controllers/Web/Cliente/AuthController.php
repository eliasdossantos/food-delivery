<?php
namespace App\Http\Controllers\Web\Cliente;
use App\Http\Controllers\Web\BaseController;
use App\Http\Requests\Auth\ClienteForgotPasswordRequest;
use App\Http\Requests\Auth\ClienteLoginRequest;
use App\Http\Requests\Auth\ClienteResetPasswordRequest;
use App\Http\Requests\Clientes\ClienteRegisterRequest;
use App\Models\ClienteModel;
use App\Services\Auth\RecuperacaoSenhaService;
use Framework\Auth\Auth;
use Framework\Support\Session;
class AuthController extends BaseController
{
    public function loginForm(): void { $this->view('auth.cliente.login',['titulo'=>'Acesso do cliente'],'auth-cliente'); }
    public function login(ClienteLoginRequest $request): void
    {
        if ($request->fails()) { $this->falha($request,'/cliente/login'); return; }
        $data=$request->validated();
        if (!Auth::guard('cliente')->attempt($data['email'],$data['senha'],!empty($_POST['lembrar']))) { Session::flash('error','E-mail ou senha inválidos, ou cliente inativo.'); Session::flashInput(['email'=>$data['email']]); $this->redirect('/cliente/login'); }
        $this->redirect('/cliente');
    }
    public function registerForm(): void { $this->view('auth.cliente.register',['titulo'=>'Criar cadastro de cliente'],'auth-cliente'); }
    public function register(ClienteRegisterRequest $request): void
    {
        if ($request->fails()) { $this->falha($request,'/cliente/cadastro'); return; }
        $data=$request->validated();
        $id=(new ClienteModel())->createWithHash(['nome'=>$data['nome'],'email'=>$data['email'],'celular'=>$data['celular'],'password_cliente'=>$data['senha'],'ativo'=>1]);
        if (!$id) { Session::flash('error','Não foi possível concluir o cadastro.'); $this->redirect('/cliente/cadastro'); }
        Session::flash('success','Cadastro criado com sucesso. Faça login para continuar.'); $this->redirect('/cliente/login');
    }
    public function forgotForm(): void { $this->view('auth.cliente.forgot',['titulo'=>'Recuperar acesso do cliente'],'auth-cliente'); }
    public function forgot(ClienteForgotPasswordRequest $request): void
    {
        if ($request->fails()) { $this->falha($request,'/cliente/esqueci-senha'); return; }
        (new RecuperacaoSenhaService())->solicitar('cliente',$request->validated()['email']);
        Session::flash('success','Se o e-mail estiver cadastrado, você receberá as instruções de recuperação.'); $this->redirect('/cliente/esqueci-senha');
    }
    public function resetForm(): void { $this->view('auth.cliente.reset',['titulo'=>'Redefinir acesso','token'=>$_GET['token']??''],'auth-cliente'); }
    public function reset(ClienteResetPasswordRequest $request): void
    {
        if ($request->fails()) { $this->falha($request,'/cliente/redefinir-senha'); return; }
        $data=$request->validated();
        if (!(new RecuperacaoSenhaService())->redefinir('cliente',$data['token'],$data['senha'])) { Session::flash('error','Token inválido, expirado ou já utilizado.'); $this->redirect('/cliente/redefinir-senha?token='.urlencode($data['token'])); }
        Session::flash('success','Senha redefinida com sucesso.'); $this->redirect('/cliente/login');
    }
    public function logout(): void { Auth::guard('cliente')->logout(); Session::flash('success','Sessão encerrada.'); $this->redirect('/cliente/login'); }
    private function falha(object $request,string $url): never { Session::flash('error',$request->firstError() ?: 'Revise os dados informados.'); Session::flashErrors($request->errors()); Session::flashInput($request->all()); $this->redirect($url); }
}
