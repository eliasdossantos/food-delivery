<?php
namespace App\Http\Middlewares;
use Framework\Auth\Auth;
use Framework\Http\Request;
class PerfilAdministrativoMiddleware
{
    public function handle(Request $request): void
    {
        if (!Auth::guard('usuario')->check() || Auth::guard('usuario')->perfil() === '') { http_response_code(403); exit('Perfil administrativo não autorizado.'); }
    }
}
