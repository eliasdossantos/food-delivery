<?php

namespace App\Http\Middlewares;

use Framework\Http\Request;
use Framework\Support\Session;

/**
 * CsrfMiddleware — Valida token CSRF em requisições POST/PUT/DELETE
 *
 * Uso:
 *   $router->post('/Users', [UserController::class, 'store'], ['CsrfMiddleware']);
 *
 * Para incluir o token em formulários:
 *   <?= csrf_field() ?>
 *
 * Para incluir via JS/AJAX:
 * headers: { 'X-CSRF-Token': '<?= csrf_token() ?>' }
 */
class CsrfMiddleware
{
    protected array $safeMethods = ['GET', 'HEAD', 'OPTIONS'];

    public function handle(Request $request): void
    {
        if (in_array($request->method(), $this->safeMethods, true)) return;

        // ── Tratamento de erros
        // O corpo da requisição pode assumir as seguintes formas:
        // $_POST['_csrf_token'] ?? $request->header('X-CSRF-Token') ?? $request->header('X-XSRF-Token') ?? '';
        // Request::header() nunca retorna null — o default é '' — então assim
        // que $_POST['_csrf_token'] estivesse ausente, a cadeia caía direto no
        // header('X-CSRF-Token'), e mesmo que ELE também estivesse ausente
        // (retornando ''), o `??` já tinha "resolvido" naquele ponto: o
        // fallback pro X-XSRF-Token nunca era alcançado. Código morto.
        //
        // Também corrige um risco de TypeError: se "_csrf_token" viesse como
        // array (ex: corpo "_csrf_token[]=x"), Session::validateCsrf()
        // receberia um array em vez de string.
        $token = $_POST['_csrf_token'] ?? '';
        if (!is_string($token)) {
            $token = '';
        }
        if ($token === '') {
            $token = $request->header('X-CSRF-Token') ?: $request->header('X-XSRF-Token');
        }

        if ($token === '' || !Session::validateCsrf($token)) {
            if (!headers_sent()) {
                http_response_code(419);
            }

            $isAjax = $request->isAjax() || $request->isJson();

            if ($isAjax) {
                if (!headers_sent()) {
                    header('Content-Type: application/json');
                }
                echo json_encode(['success' => false, 'message' => 'Token CSRF inválido ou expirado.']);
                exit;
            }

            // Regenera token após falha para que uma tentativa inválida não
            // possa ser reutilizada.
            Session::regenerateCsrf();
            if (!headers_sent()) {
                header('Content-Type: text/html; charset=UTF-8');
            }
            // Não redireciona: o cliente deve observar explicitamente o 419.
            echo '<!doctype html><html lang="pt-BR"><head><meta charset="UTF-8"><title>Token CSRF inválido</title></head><body><h1>Token CSRF inválido ou expirado.</h1><p>Recarregue a página e tente novamente.</p></body></html>';
            exit;
        }
    }
}
