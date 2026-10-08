<?php

namespace App\Http\Requests\Categorias;

use App\Http\Requests\FormRequest;
use Framework\Http\Request;
use Framework\Auth\Auth;

/**
 * UpdateCategoriasRequest
 * ─────────────────────────────────────────────────────────────────────────────
 * Centraliza validação, sanitização e autorização do formulário.
 *
 * Fluxo automático ao instanciar:
 *   new UpdateCategoriasRequest()
 *     → authorize()   — verifica permissão
 *     → sanitize()    — limpa os dados
 *     → validate()    — aplica rules() com messages()
 *
 * Uso no Controller:
 *   $request = new UpdateCategoriasRequest();
 *   if ($request->fails()) {
 *       Session::flash('error', $request->firstError());
 *       Session::flashInput($request->all());
 *       $this->back();
 *   }
 *   $data = $request->validated();
 */
class UpdateCategoriasRequest extends FormRequest
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
            'nome'           => 'required|min:2|max:100',
            'slug'           => 'nullable|max:120',
            'descricao'      => 'nullable|max:500',
            'icone'          => 'nullable|max:50',
            'ordem_exibicao' => 'nullable|integer',
            'ativo'          => 'required|in:0,1',
            'destaque'       => 'required|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome da categoria é obrigatório.',
            'nome.min'      => 'O nome da categoria deve ter pelo menos 2 caracteres.',
            'nome.max'      => 'O nome da categoria deve ter no máximo 100 caracteres.',

            'slug.max'      => 'O slug deve ter no máximo 120 caracteres.',

            'descricao.max' => 'A descrição deve ter no máximo 500 caracteres.',

            'icone.max'     => 'O ícone deve ter no máximo 50 caracteres.',

            'ordem_exibicao.integer' => 'A ordem de exibição deve ser um número inteiro.',

            'ativo.required' => 'O status da categoria é obrigatório.',
            'ativo.in'       => 'O status da categoria deve ser "Ativo" ou "Inativo".',

            'destaque.required' => 'O destaque da categoria é obrigatório.',
            'destaque.in'       => 'O destaque da categoria deve ser "Sim" ou "Não".',
        ];
    }

    public function sanitize(): array
    {
        return [
            'nome' => trim(
                Request::sanitizeValue($this->input['nome'] ?? '')
            ),

            'slug' => strtolower(
                trim(Request::sanitizeValue($this->input['slug'] ?? ''))
            ),

            'descricao' => trim(
                Request::sanitizeValue($this->input['descricao'] ?? '')
            ),

            'icone' => strtolower(
                trim(Request::sanitizeValue($this->input['icone'] ?? ''))
            ),

            'ordem_exibicao' => trim(
                $this->input['ordem_exibicao'] ?? '1'
            ),

            'ativo' => trim(
                $this->input['ativo'] ?? '1'
            ),

            'destaque' => trim(
                $this->input['destaque'] ?? '0'
            ),
        ];
    }
}
