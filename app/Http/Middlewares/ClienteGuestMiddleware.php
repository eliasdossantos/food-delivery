<?php
namespace App\Http\Middlewares;
use Framework\Auth\Auth;
use Framework\Http\Request;
class ClienteGuestMiddleware
{
    public function handle(Request $request): void
    {
        if (Auth::guard('cliente')->check()) redirect('/cliente');
    }
}
