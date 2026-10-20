<?php

namespace App\Http\Requests\FormasPagamento;

use App\Http\Requests\FormRequest;
use Framework\Auth\Auth;
use Framework\Http\Request;

/**
 * UpdateFormasPagamentoRequest
 * ─────────────────────────────────────────────────────────────────────────────
 * Centraliza validação, sanitização e autorização do formulário.
 *
 * Fluxo automático ao instanciar:
 *   new UpdateFormasPagamentoRequest()
 *     → authorize()   — verifica permissão
 *     → sanitize()    — limpa os dados
 *     → validate()    — aplica rules() com messages()
 *
 * Uso no Controller:
 *   $request = new UpdateFormasPagamentoRequest();
 *   if ($request->fails()) {
 *       Session::flash('error', $request->firstError());
 *       Session::flashInput($request->all());
 *       $this->back();
 *   }
 *   $data = $request->validated();
 */
class UpdateFormasPagamentoRequest extends FormRequest
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
            'nome'              => 'required|min:3|max:200|unique:formas_pagamento,nome,{id}',
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
            'nome.required'      => 'O nome do estilo do pagamento é obrigatório.',
            'nome.min'           => 'O nome da forma de pagamento deve ter pelo menos 3 caracteres.',
            'nome.max'           => 'O nome da forma de pagamento deve ter no máximo 200 caracteres.',
            'nome.unique'        => 'Este nome da forma de pagamento já está em uso.',
            'ativo.required'     => 'O status da forma de pagamento é obrigatório.',
            'ativo.in'           => 'O status da forma de pagamento deve ser "Ativo" ou "Inativo".',
        ];
    }

    public function sanitize(): array
    {
        return [
            'nome' => trim(
                Request::sanitizeValue($this->input['nome'] ?? '')
            ),

            'ativo' => trim(
                $this->input['ativo'] ?? '1'
            ),

        ];
    }
}
