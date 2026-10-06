<?php

namespace App\Services\Auth;

use App\Models\ClienteModel;
use App\Models\RedefinicaoSenha;
use App\Models\UsuarioModel;
use App\Support\Helpers\Mailer;

class RecuperacaoSenhaService
{
    public function solicitar(string $fluxo, string $email): void
    {
        $modelo = $fluxo === 'cliente' ? new ClienteModel() : new UsuarioModel();
        $registro = $modelo->findByEmail($email);

        if (!$registro) {
            return;
        }

        $token = (new RedefinicaoSenha())->createToken($email);
        $baseUrl = rtrim(defined('APP_URL') ? APP_URL : '', '/');
        $path = $fluxo === 'cliente' ? '/cliente/redefinir-senha?token=' : '/admin/redefinir-senha?token=';
        $url = $baseUrl . $path . urlencode($token);

        $nome = (string)($registro->nome ?? 'Usuário');
        $appName = defined('APP_NAME') ? APP_NAME : 'Food Delivery';
        $assunto = 'Redefinição de senha — ' . $appName;

        // Conteúdo HTML do e-mail
        $html = $this->renderEmailHtml($nome, $url);

        // Versão em texto puro para clientes de e-mail legados
        $text = "Olá, {$nome}.\n\nPara redefinir sua senha, acesse o link abaixo:\n{$url}\n\nEste link é válido por 1 hora.\nSe você não solicitou esta alteração, desconsidere este e-mail.";

        (new Mailer())->send($email, $assunto, $html, $text, $nome);
    }

    public function redefinir(string $fluxo, string $token, string $senha): bool
    {
        $registro = (new RedefinicaoSenha())->findValid($token);
        if (!$registro) {
            return false;
        }

        $modelo = $fluxo === 'cliente' ? new ClienteModel() : new UsuarioModel();
        $conta = $modelo->findByEmail((string)$registro->email);

        if (!$conta || !$modelo->updatePassword((int)$conta->id, $senha)) {
            return false;
        }

        (new RedefinicaoSenha())->consume($token);
        return true;
    }

    /**
     * Gera o HTML do e-mail com layout responsivo e profissional
     */
    private function renderEmailHtml(string $nome, string $url): string
    {
        $appName = defined('APP_NAME') ? APP_NAME : 'Food Delivery';
        $urlEscaped = e($url);
        $nomeEscaped = e($nome);
        $anoAtual = date('Y');

        return <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinição de Senha</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f9; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f4f6f9; padding: 40px 0;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                    
                    <!-- Cabeçalho -->
                    <tr>
                        <td align="center" style="background-color: #e63946; padding: 25px 20px;">
                            <h1 style="color: #ffffff; font-size: 22px; margin: 0; font-weight: 700; letter-spacing: 0.5px;">{$appName}</h1>
                        </td>
                    </tr>

                    <!-- Conteúdo Principal -->
                    <tr>
                        <td style="padding: 35px 30px;">
                            <h2 style="color: #1d3557; font-size: 20px; margin-top: 0; margin-bottom: 15px; font-weight: 600;">Olá, {$nomeEscaped}!</h2>
                            
                            <p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin-bottom: 25px;">
                                Recebemos uma solicitação para redefinir a senha da sua conta no <strong>{$appName}</strong>. Clique no botão abaixo para cadastrar uma nova senha:
                            </p>

                            <!-- Botão CTA -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 30px;">
                                <tr>
                                    <td align="center">
                                        <a href="{$urlEscaped}" target="_blank" style="background-color: #e63946; color: #ffffff; font-size: 15px; font-weight: bold; text-decoration: none; padding: 14px 32px; border-radius: 6px; display: inline-block; box-shadow: 0 2px 6px rgba(230, 57, 70, 0.3);">
                                            Redefinir Minha Senha
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Alerta de Expiração -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #fff8f6; border-left: 4px solid #e63946; border-radius: 4px; margin-bottom: 25px;">
                                <tr>
                                    <td style="padding: 12px 15px;">
                                        <p style="color: #9b2c2c; font-size: 13px; margin: 0; line-height: 1.5;">
                                            <strong>Atenção:</strong> Por motivos de segurança, este link é válido por apenas <strong>1 hora</strong>.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="color: #718096; font-size: 13px; line-height: 1.5; margin-bottom: 20px;">
                                Se você não solicitou a alteração de senha, pode ignorar este e-mail com segurança. Sua senha atual permanecerá inalterada.
                            </p>

                            <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 25px 0;">

                            <!-- Link Alternativo -->
                            <p style="color: #a0aec0; font-size: 12px; line-height: 1.5; margin: 0; word-break: break-all;">
                                Caso o botão não funcione, copie e cole o link a seguir no seu navegador:<br>
                                <a href="{$urlEscaped}" style="color: #e63946; text-decoration: underline;">{$urlEscaped}</a>
                            </p>
                        </td>
                    </tr>

                    <!-- Rodapé Corrigido -->
                    <tr>
                        <td align="center" style="background-color: #f8fafc; padding: 20px; border-top: 1px solid #edf2f7;">
                            <p style="color: #a0aec0; font-size: 12px; margin: 0;">
                                &copy; {$anoAtual} {$appName}. Todos os direitos reservados.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
    }
}
