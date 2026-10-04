<?php

namespace App\Http\Requests\Perfis;

use App\Http\Requests\FormRequest;
use Framework\Auth\Auth;

/**
 * UpdatePerfisRequest
 * ─────────────────────────────────────────────────────────────────────────────
 * Centraliza validação, sanitização e autorização do formulário.
 *
 * Fluxo automático ao instanciar:
 *   new UpdatePerfisRequest()
 *     → authorize()   — verifica permissão
 *     → sanitize()    — limpa os dados
 *     → validate()    — aplica rules() com messages()
 *
 * Uso no Controller:
 *   $request = new UpdatePerfisRequest();
 *   if ($request->fails()) {
 *       Session::flash('error', $request->firstError());
 *       Session::flashInput($request->all());
 *       $this->back();
 *   }
 *   $data = $request->validated();
 */
class UpdatePerfisRequest extends FormRequest
{
    /**
     * Define quem pode realizar esta ação.
     *
     * Exemplos:
     *   return true;               // sempre permitido
     *   return Auth::check();      // apenas logados
     *   return Auth::is('admin');  // apenas admins
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação.
     * Sintaxe: 'campo' => 'regra1|regra2|regra3:param'
     *
     * Regras disponíveis:
     *   required, email, min:N, max:N, numeric, integer,
     *   confirmed, same:outro, different:outro,
     *   in:a,b,c, not_in:a,b,c, regex:/pattern/,
     *   unique:tabela,coluna, exists:tabela,coluna, nullable
     */
    public function rules(): array
    {
        return [
            'nome'              => 'required|min:5|max:149|unique:perfis,nome,{id}',
            'ativo'             => 'required|in:0,1',
        ];
    }

    /**
     * Mensagens de erro customizadas.
     * Formato: 'campo.regra' => 'mensagem'
     * Retorne [] para usar as mensagens padrão do Validator.
     */
    public function messages(): array
    {
        return [
            'nome.required'      => 'O nome do perfil é obrigatório.',
            'nome.unique'        => 'Este nome de perfil já está em uso.',
            'nome.min'           => 'O nome do perfil deve ter pelo menos 5 caracteres.',
            'nome.max'           => 'O nome do perfil deve ter no máximo 149 caracteres.',
            'ativo.required'    => 'O status do perfil é obrigatório.',
            'ativo.in'          => 'O status do perfil deve ser ativo ou inativo.',
        ];
    }
}
