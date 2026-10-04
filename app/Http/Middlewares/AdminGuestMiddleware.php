<?php
namespace App\Http\Middlewares;
use Framework\Auth\Auth;
use Framework\Http\Request;
class AdminGuestMiddleware
{
    public function handle(Request $request): void
    {
        if (Auth::guard('usuario')->check()) redirect('/admin');
    }
}
