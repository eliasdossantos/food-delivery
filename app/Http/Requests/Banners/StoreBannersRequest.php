<?php

namespace App\Http\Requests\Banners;

use App\Http\Requests\FormRequest;
use Framework\Auth\Auth;
use Framework\Http\Request;

/**
 * StoreBannersRequest
 * ─────────────────────────────────────────────────────────────────────────────
 * Centraliza validação, sanitização e autorização do formulário.
 *
 * Fluxo automático ao instanciar:
 *   new StoreBannersRequest()
 *     → authorize()   — verifica permissão
 *     → sanitize()    — limpa os dados
 *     → validate()    — aplica rules() com messages()
 *
 * Uso no Controller:
 *   $request = new StoreBannersRequest();
 *   if ($request->fails()) {
 *       Session::flash('error', $request->firstError());
 *       Session::flashInput($request->all());
 *       $this->back();
 *   }
 *   $data = $request->validated();
 */
class StoreBannersRequest extends FormRequest
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
            // Conteúdo
            'titulo'    => 'required|min:2|max:150',
            'descricao' => 'nullable|max:300',

            // Selo de desconto
            'badge_destaque' => 'nullable|max:20',
            'badge_rotulo'   => 'nullable|max:20',

            // Botão
            'botao_texto' => 'nullable|max:100',
            'botao_link'  => 'nullable|max:500|regex:/^(?!\s*javascript:)(?!\s*data:)(?!\s*vbscript:)/i',


            // Exibição
            'ordem_exibicao' => 'required|integer|min:0|max:9999',
            'ativo'          => 'required|in:0,1',

            // Período de exibição
            'data_inicio' => 'nullable|date',
            'data_fim'    => 'nullable|date',

            // Arquivo (o nome salvo vai para a coluna `imagem`, definido no controller)
            'imagem' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif',
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'O título do banner é obrigatório.',
            'titulo.min'      => 'O título deve ter pelo menos 2 caracteres.',
            'titulo.max'      => 'O título deve ter no máximo 150 caracteres.',

            'descricao.max' => 'A descrição deve ter no máximo 300 caracteres.',

            'badge_destaque.max' => 'O valor do selo deve ter no máximo 20 caracteres.',
            'badge_rotulo.max'   => 'O texto do selo deve ter no máximo 20 caracteres.',

            'botao_texto.max'   => 'O texto do botão deve ter no máximo 100 caracteres.',
            'botao_link.max'    => 'O link do botão deve ter no máximo 500 caracteres.',
            'botao_link.regex'  => 'Este tipo de link não é permitido.',

            'ordem_exibicao.required' => 'A ordem de exibição é obrigatória.',
            'ordem_exibicao.integer'  => 'A ordem de exibição deve ser um número inteiro.',
            'ordem_exibicao.min'      => 'A ordem de exibição não pode ser negativa.',
            'ordem_exibicao.max'      => 'A ordem de exibição é muito alta.',

            'ativo.required' => 'O status do banner é obrigatório.',
            'ativo.in'       => 'O status deve ser "Ativo" ou "Inativo".',

            'data_inicio.date' => 'Informe uma data de início válida.',
            'data_fim.date'    => 'Informe uma data de fim válida.',

            'imagem.image' => 'O arquivo enviado deve ser uma imagem válida.',
            'imagem.mimes' => 'A imagem deve estar nos formatos JPG, JPEG, PNG, GIF ou WEBP.',
            'imagem.max'   => 'A imagem deve ter no máximo 5MB.',
        ];
    }

    /**
     * Sanitiza os dados antes da validação.
     * Campos opcionais vazios viram NULL.
     */
    public function sanitize(): array
    {
        $ordem = trim((string) ($this->input['ordem_exibicao'] ?? ''));

        return [
            // Conteúdo
            'titulo'    => trim(Request::sanitizeValue($this->input['titulo'] ?? '')),
            'descricao' => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['descricao'] ?? ''))),

            // Selo de desconto
            'badge_destaque' => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['badge_destaque'] ?? ''))),
            'badge_rotulo'   => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['badge_rotulo'] ?? ''))),

            // Botão
            'botao_texto' => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['botao_texto'] ?? ''))),
            // Link: sem sanitizeValue para não escapar "&" e "#" de URLs/âncoras
            'botao_link'  => $this->nuloSeVazio(trim($this->input['botao_link'] ?? '')),

            // Exibição
            'ordem_exibicao' => $ordem === '' ? '0' : $ordem,
            'ativo'          => trim($this->input['ativo'] ?? '1'),

            // Período de exibição
            'data_inicio' => $this->nuloSeVazio(trim($this->input['data_inicio'] ?? '')),
            'data_fim'    => $this->nuloSeVazio(trim($this->input['data_fim'] ?? '')),
        ];
    }

    /** '' vira NULL, para não gravar string vazia em colunas nullable */
    private function nuloSeVazio(string $valor): ?string
    {
        return $valor === '' ? null : $valor;
    }
}