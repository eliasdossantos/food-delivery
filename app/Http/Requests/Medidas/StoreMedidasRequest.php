<?php

namespace App\Http\Requests\Medidas;

use App\Http\Requests\FormRequest;
use Framework\Auth\Auth;
use Framework\Http\Request;

/**
 * StoreMedidasRequest
 * ─────────────────────────────────────────────────────────────────────────────
 * Centraliza validação, sanitização e autorização do formulário de medidas.
 *
 * Fluxo automático ao instanciar:
 *   new StoreMedidasRequest()
 *     → authorize()
 *     → sanitize()
 *     → validate()
 *
 * Uso no Controller:
 *   $request = new StoreMedidasRequest();
 *
 *   if ($request->fails()) {
 *       Session::flash('error', $request->firstError());
 *       Session::flashInput($request->all());
 *       $this->back();
 *   }
 *
 *   $data = $request->validated();
 */
class StoreMedidasRequest extends FormRequest
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
            'nome'           => 'required|min:2|max:200|unique:medidas,nome',
            'descricao'      => 'nullable|max:1000',

            'unidade'        => 'nullable|max:20',

            'imagem_url'     => 'nullable|max:250',

            'ativo'          => 'required|in:0,1',
            'ordem_exibicao' => 'nullable|integer',
        ];
    }

    /**
     * Mensagens de erro customizadas.
     */
    public function messages(): array
    {
        return [
            'nome.required' => 'O nome da medida é obrigatório.',
            'nome.min'      => 'O nome da medida deve ter pelo menos 2 caracteres.',
            'nome.max'      => 'O nome da medida deve ter no máximo 200 caracteres.',
            'nome.unique'   => 'Já existe uma medida com este nome.',

            'descricao.max' => 'A descrição deve ter no máximo 1000 caracteres.',

            'unidade.max' => 'A unidade deve ter no máximo 20 caracteres.',

            'imagem_url.max' => 'A URL da imagem deve ter no máximo 250 caracteres.',

            'ativo.required' => 'O status da medida é obrigatório.',
            'ativo.in'       => 'O status da medida deve ser "Ativo" ou "Inativo".',

            'ordem_exibicao.integer' =>
            'A ordem de exibição deve ser um número inteiro.',
        ];
    }

    /**
     * Sanitiza os dados antes da validação.
     */
    public function sanitize(): array
    {
        return [
            'nome' => trim(
                Request::sanitizeValue(
                    $this->input['nome'] ?? ''
                )
            ),

            'descricao' => trim(
                Request::sanitizeValue(
                    $this->input['descricao'] ?? ''
                )
            ),

            'unidade' => strtolower(
                trim(
                    Request::sanitizeValue(
                        $this->input['unidade'] ?? ''
                    )
                )
            ),

            'imagem_url' => trim(
                Request::sanitizeValue(
                    $this->input['imagem_url'] ?? ''
                )
            ),

            'ativo' => trim(
                $this->input['ativo'] ?? '1'
            ),

            'ordem_exibicao' => trim(
                $this->input['ordem_exibicao'] ?? '0'
            ),
        ];
    }
}
