<?php

use App\Http\Controllers\Web\Admin\ClientesController;
use App\Http\Controllers\Web\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Web\Admin\HomeController;
use App\Http\Controllers\Web\Admin\PerfisController;
use App\Http\Controllers\Web\Admin\UsuariosController;
use App\Http\Controllers\Web\Cliente\AuthController as ClienteAuthController;
use App\Http\Controllers\Web\Cliente\HomeController as ClienteHomeController;
use Framework\Http\Router;

/** @var Router $router */

// ── Raiz ──────────────────────────────────────────────────────────────────────
$router->get('/', [HomeController::class, 'index'])->name('home');

// ── Fluxo administrativo: rotas próprias ─────────────────────────────────────
$router->get('/admin/login', [AdminAuthController::class, 'loginForm'], ['AdminGuestMiddleware'])->name('admin.login');
$router->post('/admin/login', [AdminAuthController::class, 'login'], ['AdminGuestMiddleware', 'CsrfMiddleware', 'RateLimitMiddleware:login'])->name('admin.login.store');
$router->get('/admin/cadastro', [AdminAuthController::class, 'registerForm'], ['AdminGuestMiddleware'])->name('admin.cadastro');
$router->post('/admin/cadastro', [AdminAuthController::class, 'register'], ['AdminGuestMiddleware', 'CsrfMiddleware', 'RateLimitMiddleware:register'])->name('admin.cadastro.store');
$router->get('/admin/esqueci-senha', [AdminAuthController::class, 'forgotForm'], ['AdminGuestMiddleware'])->name('admin.esqueci_senha');
$router->post('/admin/esqueci-senha', [AdminAuthController::class, 'forgot'], ['AdminGuestMiddleware', 'CsrfMiddleware', 'RateLimitMiddleware:forgot'])->name('admin.esqueci_senha.store');
$router->get('/admin/redefinir-senha', [AdminAuthController::class, 'resetForm'], ['AdminGuestMiddleware'])->name('admin.redefinir_senha');
$router->post('/admin/redefinir-senha', [AdminAuthController::class, 'reset'], ['AdminGuestMiddleware', 'CsrfMiddleware'])->name('admin.redefinir_senha.store');
$router->post('/admin/logout', [AdminAuthController::class, 'logout'], ['AdminAuthMiddleware', 'CsrfMiddleware'])->name('admin.logout');

// ── Fluxo do cliente: rotas próprias ─────────────────────────────────────────
$router->get('/cliente/login', [ClienteAuthController::class, 'loginForm'], ['ClienteGuestMiddleware'])->name('cliente.login');
$router->post('/cliente/login', [ClienteAuthController::class, 'login'], ['ClienteGuestMiddleware', 'CsrfMiddleware', 'RateLimitMiddleware:login'])->name('cliente.login.store');
$router->get('/cliente/cadastro', [ClienteAuthController::class, 'registerForm'], ['ClienteGuestMiddleware'])->name('cliente.cadastro');
$router->post('/cliente/cadastro', [ClienteAuthController::class, 'register'], ['ClienteGuestMiddleware', 'CsrfMiddleware', 'RateLimitMiddleware:register'])->name('cliente.cadastro.store');
$router->get('/cliente/esqueci-senha', [ClienteAuthController::class, 'forgotForm'], ['ClienteGuestMiddleware'])->name('cliente.esqueci_senha');
$router->post('/cliente/esqueci-senha', [ClienteAuthController::class, 'forgot'], ['ClienteGuestMiddleware', 'CsrfMiddleware', 'RateLimitMiddleware:forgot'])->name('cliente.esqueci_senha.store');
$router->get('/cliente/redefinir-senha', [ClienteAuthController::class, 'resetForm'], ['ClienteGuestMiddleware'])->name('cliente.redefinir_senha');
$router->post('/cliente/redefinir-senha', [ClienteAuthController::class, 'reset'], ['ClienteGuestMiddleware', 'CsrfMiddleware'])->name('cliente.redefinir_senha.store');
$router->get('/cliente', [ClienteHomeController::class, 'index'], ['ClienteAuthMiddleware'])->name('cliente.home');
$router->post('/cliente/logout', [ClienteAuthController::class, 'logout'], ['ClienteAuthMiddleware', 'CsrfMiddleware'])->name('cliente.logout');

// ── Área do Administrador ────────────────────────────────────────────────
$router->group(['prefix' => '/admin', 'as' => 'admin.', 'middleware' => ['AdminAuthMiddleware']], function (Router $r) {

    // ── Home do Admin ──────────────────────────────────────────────────
    $r->get('', [HomeController::class, 'index'])->name('home');

    // ── Área PERFIS
    $r->group(['prefix' => '/perfil', 'as' => 'perfil.'], function (Router $r) {

        $r->get('', [PerfisController::class, 'index'])->name('index');
        $r->get('/create', [PerfisController::class, 'create'])->name('create');
        $r->post('', [PerfisController::class, 'store'])->name('store');
        $r->get('/{id}', [PerfisController::class, 'show'])->name('show');
        $r->get('/{id}/edit', [PerfisController::class, 'edit'])->name('edit');
        $r->put('/{id}', [PerfisController::class, 'update'])->name('update');
        $r->delete('/{id}', [PerfisController::class, 'destroy'])->name('destroy');
    });

    // ── Área USUÁRIOS
    $r->group(['prefix' => '/usuario', 'as' => 'usuario.'], function (Router $r) {

        $r->get('', [UsuariosController::class, 'index'])->name('index');
        $r->get('/create', [UsuariosController::class, 'create'])->name('create');
        $r->get('/procurar', [UsuariosController::class, 'procurar'])->name('procurar');
        $r->post('', [UsuariosController::class, 'store'])->name('store');
        $r->get('/{id}', [UsuariosController::class, 'show'])->name('show');
        $r->get('/{id}/edit', [UsuariosController::class, 'edit'])->name('edit');
        $r->put('/{id}', [UsuariosController::class, 'update'])->name('update');
        $r->delete('/{id}', [UsuariosController::class, 'destroy'])->name('destroy');
    });

    // ── Área CLIENTES
    $r->group(['prefix' => '/cliente', 'as' => 'cliente.'], function (Router $r) {

        $r->get('', [ClientesController::class, 'index'])->name('index');
        $r->get('/create', [ClientesController::class, 'create'])->name('create');
        $r->get('/procurar', [ClientesController::class, 'procurar'])->name('procurar');
        $r->post('', [ClientesController::class, 'store'])->name('store');
        $r->get('/{id}', [ClientesController::class, 'show'])->name('show');
        $r->get('/{id}/edit', [ClientesController::class, 'edit'])->name('edit');
        $r->put('/{id}', [ClientesController::class, 'update'])->name('update');
        $r->delete('/{id}', [ClientesController::class, 'destroy'])->name('destroy');
    });
});
