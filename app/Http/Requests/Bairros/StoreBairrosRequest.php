<?php

namespace App\Http\Requests\Bairros;

use App\Http\Requests\FormRequest;
use Framework\Auth\Auth;
use Framework\Http\Request;

/**
 * StoreBairrosRequest
 * ─────────────────────────────────────────────────────────────────────────────
 * Centraliza validação, sanitização e autorização do formulário.
 *
 * Fluxo automático ao instanciar:
 *   new StoreBairrosRequest()
 *     → authorize()   — verifica permissão
 *     → sanitize()    — limpa os dados
 *     → validate()    — aplica rules() com messages()
 *
 * Uso no Controller:
 *   $request = new StoreBairrosRequest();
 *   if ($request->fails()) {
 *       Session::flash('error', $request->firstError());
 *       Session::flashInput($request->all());
 *       $this->back();
 *   }
 *   $data = $request->validated();
 */
class StoreBairrosRequest extends FormRequest
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
            'cidade'           => 'required|min:4|max:200',
            'nome'             => 'required|min:4|max:200',
            'slug'             => 'nullable|max:200',
            'valor_entrega'    => 'nullable|numeric|min:0|max:99999999.99',
            'ativo'            => 'required|in:0,1',
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
            'cidade.required'           => 'O nome da cidade é obrigatório.',
            'cidade.min'                => 'O nome da cidade deve ter pelo menos 4 caracteres.',
            'cidade.max'                => 'O nome da cidade deve ter no máximo 200 caracteres.',
            'nome.required'             => 'O nome do bairro é obrigatório.',
            'nome.min'                  => 'O nome do bairro deve ter pelo menos 4 caracteres.',
            'nome.max'                  => 'O nome do bairro deve ter no máximo 200 caracteres.',
            'slug.max'                  => 'O slug deve ter no máximo 200 caracteres.',
            'valor_entrega.nullable'    => 'O preço do bairro não é obrigatório.',
            'valor_entrega.numeric'     => 'O preço do bairro deve ser um valor numérico.',
            'valor_entrega.min'         => 'O preço do bairro não pode ser negativo.',
            'valor_entrega.max'         => 'O preço do bairro não pode ser maior que R$ 99.999.999,99.',
            'ativo.required'            => 'O status do bairro é obrigatório.',
            'ativo.in'                  => 'O status do bairro deve ser "Ativo" ou "Inativo".',

        ];
    }

    /**
     * Sanitiza os dados antes da validação.
     * Executado automaticamente pelo FormRequest base.
     *
     * Dicas:
     *   - Senhas: use trim() APENAS (strip_tags corrompe caracteres especiais)
     *   - Emails: strtolower() + trim()
     *   - Nomes:  ucwords() + trim()
     */
    public function sanitize(): array
    {
        return [
            'cidade' => trim(
                Request::sanitizeValue($this->input['cidade'] ?? '')
            ),
            'nome' => trim(
                Request::sanitizeValue($this->input['nome'] ?? '')
            ),
            'slug' => strtolower(
                trim(Request::sanitizeValue($this->input['slug'] ?? ''))
            ),
            'valor_entrega' => str_replace(
                ',',
                '.',
                str_replace('.', '', trim($this->input['valor_entrega'] ?? '0'))
            ),
            'ativo' => trim(
                $this->input['ativo'] ?? '1'
            ),
        ];
    }
}
