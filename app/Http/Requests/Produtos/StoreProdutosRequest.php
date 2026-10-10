<?php

namespace App\Http\Requests\Produtos;

use App\Http\Requests\FormRequest;
use Framework\Auth\Auth;
use Framework\Http\Request;

/**
 * StoreProdutosRequest
 * ─────────────────────────────────────────────────────────────────────────────
 * Centraliza validação, sanitização e autorização do formulário.
 *
 * Fluxo automático ao instanciar:
 *   new StoreProdutosRequest()
 *     → authorize()   — verifica permissão
 *     → sanitize()    — limpa os dados
 *     → validate()    — aplica rules() com messages()
 *
 * Uso no Controller:
 *   $request = new StoreProdutosRequest();
 *   if ($request->fails()) {
 *       Session::flash('error', $request->firstError());
 *       Session::flashInput($request->all());
 *       $this->back();
 *   }
 *   $data = $request->validated();
 */
class StoreProdutosRequest extends FormRequest
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
            // Identificação
            'nome'              => 'required|min:2|max:190|unique:produtos,nome',
            'slug'              => 'required|max:190|unique:produtos,slug',
            'codigo'            => 'nullable|string|max:100',

            // Descrição
            'descricao'         => 'nullable|max:500',
            'ingredientes'      => 'nullable|max:500',

            // Preços
            'preco'             => 'required|numeric|min:0|max:99999999.99',
            'preco_promocional' => 'nullable|numeric|min:0|max:99999999.99',

            // Operação e estoque
            'tempo_preparo'     => 'nullable|integer|min:0|max:65535',
            'controla_estoque'  => 'nullable|boolean',
            'estoque'           => 'nullable|integer|min:0|max:65535',

            // Exibição
            'ordem_exibicao'    => 'nullable|integer',
            'ativo'             => 'required|in:0,1',
            'destaque'          => 'required|in:0,1',

            // Arquivos
            'imagem'             => 'nullable|image|mimes:jpg,jpeg,png,webp,gif',
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
            'nome.required' => 'O nome do produto é obrigatório.',
            'nome.min'      => 'O nome do produto deve ter pelo menos 2 caracteres.',
            'nome.max'      => 'O nome do produto deve ter no máximo 190 caracteres.',
            'slug.required' => 'O slug do produto é obrigatório.',
            'imagem.image'  => 'O arquivo enviado deve ser uma imagem válida.',
            'imagem.mimes'  => 'A imagem deve estar nos formatos JPG, JPEG, PNG, WEBP ou GIF.',
            'preco.required' => 'O preço do produto é obrigatório.',
            'preco.numeric'  => 'O preço deve ser numérico.',
            'preco_promocional.numeric'  => 'O preço promocional deve ser numérico.',
            'tempo_preparo.integer' => 'O tempo de preparo deve ser um número inteiro.',
            'estoque.integer'       => 'O estoque deve ser um número inteiro.',
            'controla_estoque.boolean'  => 'O controle de estoque deve ser verdadeiro ou falso.',
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
            'nome' => trim(
                Request::sanitizeValue($this->input['nome'] ?? '')
            ),

            'codigo' => trim(
                Request::sanitizeValue($this->input['codigo'] ?? '')
            ),

            'descricao' => trim(
                Request::sanitizeValue($this->input['descricao'] ?? '')
            ),

            'ingredientes' => trim(
                Request::sanitizeValue($this->input['ingredientes'] ?? '')
            ),

            'preco' => str_replace(
                ',',
                '.',
                str_replace('.', '', trim($this->input['preco'] ?? '0'))
            ),

            'preco_promocional' => str_replace(
                ',',
                '.',
                str_replace('.', '', trim($this->input['preco_promocional'] ?? '0'))
            ),

            'tempo_preparo' => $this->input['tempo_preparo'] ?? null,

            'controla_estoque' => $this->input['controla_estoque'] ?? '0',

            'estoque' => $this->input['estoque'] ?? '0',

            'ordem_exibicao' => $this->input['ordem_exibicao'] ?? null,

            'ativo' => $this->input['ativo'] ?? '1',

            'destaque' => $this->input['destaque'] ?? '0',
        ];
    }
}
