<?php
namespace App\Http\Middlewares;
use Framework\Auth\Auth;
use Framework\Http\Request;
use Framework\Support\Session;
class AdminAuthMiddleware
{
    public function handle(Request $request): void
    {
        if (!Auth::guard('usuario')->check()) { Session::flash('error', 'Faça login administrativo para continuar.'); redirect('/admin/login'); }
        $perfil = Auth::guard('usuario')->perfil();
        if (!in_array($perfil, ['super_administrador','administrador','gestor','atendente','cozinheiro','entregador','caixa'], true)) { http_response_code(403); exit('Acesso administrativo não autorizado.'); }
    }
}
