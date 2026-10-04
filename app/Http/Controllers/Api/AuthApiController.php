<?php

namespace App\Http\Controllers\Api;

use Framework\Support\Logger;
use Framework\Http\Api\ApiAuthContext;
use App\Http\Requests\Auth\LoginRequest;
use App\Repositories\ApiTokenRepository;
use App\Http\Resources\UsuarioResource;

/**
 * AuthApiController — POST /api/v1/auth/login, /logout, GET /me
 * ─────────────────────────────────────────────────────────────────────────────
 * Reaproveita App\Http\Requests\Auth\LoginRequest (mesmas regras/mensagens do
 * login web) e App\Models\User::authenticate() — não duplica validação de
 * credencial. A diferença é só o resultado: em vez de sessão, emite um
 * Bearer Token.
 */
class AuthApiController extends ApiController
{
    public function login(): void
    {
        $data = $this->validated(new LoginRequest());

        $user = (new \App\Models\UsuarioModel())->authenticate($data['email'], $data['password']);

        if (!$user) {
            Logger::warning('API: login falhou', ['email' => $data['email']]);
            $this->error('E-mail ou senha incorretos.', [], 401);
        }

        if (empty($user->active)) {
            $this->error('Conta inativa. Entre em contato com o suporte.', [], 403);
        }

        $tokenRepo = new ApiTokenRepository();
        $issued    = $tokenRepo->issue((int) $user->id, 'api-login');

        Logger::info('API: login bem-sucedido', ['usuario_id' => $user->id]);

        $this->success([
            'token'      => $issued['token'],
            'token_type' => 'Bearer',
            'usuario'       => UsuarioResource::make($user),
        ], 'Login realizado com sucesso.');
    }

    public function logout(): void
    {
        $token = ApiAuthContext::token();

        if ($token) {
            (new ApiTokenRepository())->revoke((int) $token->id);
            Logger::info('API: logout', ['usuario_id' => ApiAuthContext::id()]);
        }

        $this->success(null, 'Sessão encerrada.');
    }

    public function me(): void
    {
        $this->success(UsuarioResource::make(ApiAuthContext::usuario()));
    }
}
