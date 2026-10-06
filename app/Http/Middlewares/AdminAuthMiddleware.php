<?php

namespace App\Http\Middlewares;

use Framework\Auth\Auth;
use Framework\Http\Request;
use Framework\Support\Session;

class AdminAuthMiddleware
{
    public function handle(Request $request): void
    {
        $guard = Auth::guard('usuario');

        if (!$guard->check()) {
            Session::flash('error', 'Faça login administrativo para continuar.');
            redirect('/admin/login');
        }

        // Qualquer perfil cadastrado na tabela perfis entra na área administrativa.
        // As permissões por perfil ficam nas views e controllers.
        // Só fica de fora quem não tem perfil nenhum (perfil_id nulo).
        if ($guard->perfil() === '') {
            $guard->logout();
            Session::flash('error', 'Credenciais inválidas ou acesso não autorizado.');
            redirect('/admin/login');
        }
    }
}
