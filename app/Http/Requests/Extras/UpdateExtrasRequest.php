<?php

namespace App\Http\Requests\Extras;

use App\Http\Requests\FormRequest;
use Framework\Auth\Auth;
use Framework\Http\Request;

/**
 * UpdateExtrasRequest
 * ─────────────────────────────────────────────────────────────────────────────
 * Centraliza validação, sanitização e autorização do formulário.
 *
 * Fluxo automático ao instanciar:
 *   new UpdateExtrasRequest()
 *     → authorize()   — verifica permissão
 *     → sanitize()    — limpa os dados
 *     → validate()    — aplica rules() com messages()
 *
 * Uso no Controller:
 *   $request = new UpdateExtrasRequest();
 *   if ($request->fails()) {
 *       Session::flash('error', $request->firstError());
 *       Session::flashInput($request->all());
 *       $this->back();
 *   }
 *   $data = $request->validated();
 */
class UpdateExtrasRequest extends FormRequest
{
    /**
     * Define quem pode realizar esta ação.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação.
     */
    public function rules(): array
    {
        return [
            'nome'           => 'nullable|min:2|max:100|unique:extras,nome,{id}',
            'slug'           => 'nullable|max:120',
            'descricao'      => 'nullable|max:500',
            'preco'          => 'required|numeric|min:0|max:99999999.99',
            'icone'          => 'nullable|max:50',
            'ordem_exibicao' => 'nullable|integer',
            'ativo'          => 'required|in:0,1',
            'destaque'       => 'required|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do extra é obrigatório.',
            'nome.min'      => 'O nome do extra deve ter pelo menos 2 caracteres.',
            'nome.max'      => 'O nome do extra deve ter no máximo 100 caracteres.',

            'slug.max'      => 'O slug deve ter no máximo 120 caracteres.',

            'descricao.max' => 'A descrição deve ter no máximo 500 caracteres.',

            'preco.required' => 'O preço do extra é obrigatório.',
            'preco.numeric'  => 'O preço do extra deve ser um valor numérico.',
            'preco.min'      => 'O preço do extra não pode ser negativo.',
            'preco.max'      => 'O preço do extra não pode ser maior que R$ 99.999.999,99.',

            'icone.max' => 'O ícone deve ter no máximo 50 caracteres.',

            'ordem_exibicao.integer' => 'A ordem de exibição deve ser um número inteiro.',

            'ativo.required' => 'O status do extra é obrigatório.',
            'ativo.in'       => 'O status do extra deve ser "Ativo" ou "Inativo".',

            'destaque.required' => 'O destaque do extra é obrigatório.',
            'destaque.in'       => 'O destaque do extra deve ser "Sim" ou "Não".',
        ];
    }

    /**
     * Sanitiza os dados antes da validação.
     */
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

            'preco' => trim(
                str_replace(',', '.', $this->input['preco'] ?? '0')
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
