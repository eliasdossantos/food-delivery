<?php
namespace App\Http\Middlewares;
use Framework\Auth\Auth;
use Framework\Http\Request;
use Framework\Support\Session;
class ClienteAuthMiddleware
{
    public function handle(Request $request): void
    {
        if (!Auth::guard('cliente')->check()) { Session::flash('error', 'Faça login de cliente para continuar.'); redirect('/cliente/login'); }
    }
}
