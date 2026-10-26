<?php

namespace App\Http\Requests\SobreNos;

use App\Http\Requests\FormRequest;
use Framework\Http\Request;

/**
 * UpdateSobreNosRequest
 * ─────────────────────────────────────────────────────────────────────────────
 * Validação e sanitização da edição da seção "Sobre nós".
 *
 * Imagem: opcional. Sem arquivo novo, o controller mantém a imagem atual
 * (unset($data['imagem'])).
 */
class UpdateSobreNosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Conteúdo
            'titulo' => 'required|min:2|max:150',
            'texto'  => 'required|min:10|max:500',

            // Imagem
            'imagem_alt' => 'nullable|max:150',

            // Exibição
            'ativo' => 'required|in:0,1',

            // Arquivo
            'imagem' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif',
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'O título é obrigatório.',
            'titulo.min'      => 'O título deve ter pelo menos 2 caracteres.',
            'titulo.max'      => 'O título deve ter no máximo 150 caracteres.',

            'texto.required' => 'O texto é obrigatório.',
            'texto.min'      => 'O texto deve ter pelo menos 10 caracteres.',
            'texto.max'      => 'O texto deve ter no máximo 500 caracteres.',

            'imagem_alt.max' => 'A descrição da imagem deve ter no máximo 150 caracteres.',

            'ativo.required' => 'O status é obrigatório.',
            'ativo.in'       => 'O status deve ser "Ativo" ou "Inativo".',

            'imagem.image' => 'O arquivo enviado deve ser uma imagem válida.',
            'imagem.mimes' => 'A imagem deve estar nos formatos JPG, JPEG, PNG, GIF ou WEBP.',
            'imagem.max'   => 'A imagem deve ter no máximo 5MB.',
        ];
    }

    /**
     * Sanitiza os dados antes da validação.
     */
    public function sanitize(): array
    {
        return [
            'titulo'     => trim(Request::sanitizeValue($this->input['titulo'] ?? '')),
            'texto'      => trim(Request::sanitizeValue($this->input['texto'] ?? '')),
            'imagem_alt' => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['imagem_alt'] ?? ''))),
            'ativo'      => trim($this->input['ativo'] ?? '1'),
        ];
    }

    /** '' vira NULL, para não gravar string vazia em colunas nullable */
    private function nuloSeVazio(string $valor): ?string
    {
        return $valor === '' ? null : $valor;
    }
}