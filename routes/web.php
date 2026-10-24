<?php

use App\Http\Controllers\Web\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Web\Admin\BairrosController;
use App\Http\Controllers\Web\Admin\CategoriasController;
use App\Http\Controllers\Web\Admin\ClientesController;
use App\Http\Controllers\Web\Admin\EntregadoresController;
use App\Http\Controllers\Web\Admin\ExpedienteController;
use App\Http\Controllers\Web\Admin\ExtrasController;
use App\Http\Controllers\Web\Admin\FormasPagamentoController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Web\Admin\MedidasController;
use App\Http\Controllers\Web\Admin\PerfisController;
use App\Http\Controllers\Web\Admin\ProdutosController;
use App\Http\Controllers\Web\Admin\UsuariosController;
use App\Http\Controllers\Web\Cliente\AuthController as ClienteAuthController;
use App\Http\Controllers\Web\Cliente\HomeController as ClienteHomeController;
use Framework\Http\Router;

/** @var Router $router */

// ── Raiz (Site) ──────────────────────────────────────────────────────────────────────
$router->get('/', [HomeController::class, 'index'])->name('home');

// ── Fluxo de autenticação (Administradores e Clientes) ──────────────────────────────────
$authFlows = [
    'admin'   => [AdminAuthController::class,   'AdminGuestMiddleware',   'AdminAuthMiddleware'],
    'cliente' => [ClienteAuthController::class, 'ClienteGuestMiddleware', 'ClienteAuthMiddleware'],
];

foreach ($authFlows as $area => [$ctrl, $guest, $auth]) {
    $router->group(
        ['prefix' => "/{$area}", 'as' => "{$area}.", 'middleware' => [$guest]],
        function (Router $r) use ($ctrl) {
            $r->get('/login', [$ctrl, 'loginForm'])->name('login');
            $r->post('/login', [$ctrl, 'login'], ['CsrfMiddleware', 'RateLimitMiddleware:login'])->name('login.store');

            $r->get('/cadastro', [$ctrl, 'registerForm'])->name('cadastro');
            $r->post('/cadastro', [$ctrl, 'register'], ['CsrfMiddleware', 'RateLimitMiddleware:register'])->name('cadastro.store');

            $r->get('/esqueci-senha', [$ctrl, 'forgotForm'])->name('esqueci_senha');
            $r->post('/esqueci-senha', [$ctrl, 'forgot'], ['CsrfMiddleware', 'RateLimitMiddleware:forgot'])->name('esqueci_senha.store');

            $r->get('/redefinir-senha', [$ctrl, 'resetForm'])->name('redefinir_senha');
            $r->post('/redefinir-senha', [$ctrl, 'reset'], ['CsrfMiddleware'])->name('redefinir_senha.store');
        }
    );

    $router->post("/{$area}/logout", [$ctrl, 'logout'], [$auth, 'CsrfMiddleware'])->name("{$area}.logout");
}

// ── Home do cliente ──────────────────────────────────────────────────────────
$router->get('/cliente', [ClienteHomeController::class, 'index'], ['ClienteAuthMiddleware'])->name('cliente.home');

// ── Área do Administrador ────────────────────────────────────────────────────
$router->group(['prefix' => '/admin', 'as' => 'admin.', 'middleware' => ['AdminAuthMiddleware']], function (Router $r) {

    $r->get('', [AdminHomeController::class, 'index'])->name('home');

    // CRUDs padrão (index, create, procurar, store, show, edit, update, destroy)
    $r->resource('/perfil',     PerfisController::class);
    $r->resource('/usuario',    UsuariosController::class);
    $r->resource('/cliente',    ClientesController::class);
    $r->resource('/categoria',  CategoriasController::class);
    $r->resource('/extra',      ExtrasController::class);
    $r->resource('/medida',     MedidasController::class);
    $r->resource('/produto',    ProdutosController::class);
    $r->resource('/pagamento',  FormasPagamentoController::class);
    $r->resource('/entregador', EntregadoresController::class);
    $r->resource('/expediente', ExpedienteController::class);

    // CRUD com rota extra (registrada antes de /{id})
    $r->resource('/bairro', BairrosController::class, [], function (Router $r) {
        $r->get('/cep', [BairrosController::class, 'consultarCep'])->name('cep');
    });
});