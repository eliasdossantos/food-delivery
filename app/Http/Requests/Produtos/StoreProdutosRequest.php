<?php

namespace App\Http\Requests\Produtos;

use App\Http\Requests\FormRequest;
use Framework\Http\Request;

/**
 * StoreProdutosRequest
 * Validação e sanitização do cadastro de produto (tabela `produtos`).
 */
class StoreProdutosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Identificação
            'nome'              => 'required|min:2|max:200|unique:produtos,nome',
            'slug'              => 'nullable|max:200|unique:produtos,slug',
            'codigo'            => 'nullable|string|max:100|unique:produtos,codigo',
            'categoria_id'      => 'nullable|integer|exists:categorias,id',

            // Conteúdo
            'descricao'         => 'nullable|max:500',
            'ingredientes'      => 'nullable|max:500',

            // Preços (decimal 10,2)
            'preco'             => 'required|numeric|min:0|max:99999999.99',
            'preco_promocional' => 'nullable|numeric|min:0|max:99999999.99',

            // Operação e estoque (unsigned smallint: 0..65535)
            'tempo_preparo'     => 'nullable|integer|min:0|max:65535',
            'controla_estoque'  => 'nullable|in:0,1',
            'estoque'           => 'nullable|integer|min:0|max:65535',

            // Exibição
            'ordem_exibicao'    => 'nullable|integer|min:0|max:65535',
            'ativo'             => 'required|in:0,1',
            'destaque'          => 'required|in:0,1',

            // Arquivo (imagem_url é tratado no controller)
            'imagem'            => 'nullable|image|mimes:jpg,jpeg,png,webp,gif',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do produto é obrigatório.',
            'nome.min'      => 'O nome do produto deve ter pelo menos 2 caracteres.',
            'nome.max'      => 'O nome do produto deve ter no máximo 200 caracteres.',
            'nome.unique'   => 'Já existe um produto com este nome.',

            'slug.max'      => 'O slug deve ter no máximo 200 caracteres.',
            'slug.unique'   => 'Este slug já está em uso.',

            'codigo.max'    => 'O código deve ter no máximo 100 caracteres.',
            'codigo.unique' => 'Já existe um produto com este código.',

            'categoria_id.integer' => 'A categoria selecionada é inválida.',
            'categoria_id.exists'  => 'A categoria selecionada não existe.',

            'preco.required'            => 'O preço do produto é obrigatório.',
            'preco.numeric'             => 'O preço deve ser numérico.',
            'preco_promocional.numeric' => 'O preço promocional deve ser numérico.',

            'tempo_preparo.integer' => 'O tempo de preparo deve ser um número inteiro.',
            'estoque.integer'       => 'O estoque deve ser um número inteiro.',
            'controla_estoque.in'   => 'O controle de estoque deve ser 0 ou 1.',

            'ordem_exibicao.integer' => 'A ordem de exibição deve ser um número inteiro.',

            'imagem.image' => 'O arquivo enviado deve ser uma imagem válida.',
            'imagem.mimes' => 'A imagem deve estar nos formatos JPG, JPEG, PNG, WEBP ou GIF.',
        ];
    }

    public function sanitize(): array
    {
        $categoriaId = trim(Request::sanitizeValue($this->input['categoria_id'] ?? ''));
        $codigo      = trim(Request::sanitizeValue($this->input['codigo'] ?? ''));
        $tempo       = trim((string) ($this->input['tempo_preparo'] ?? ''));
        $estoque     = trim((string) ($this->input['estoque'] ?? ''));
        $ordem       = trim((string) ($this->input['ordem_exibicao'] ?? ''));

        return [
            'nome' => trim(Request::sanitizeValue($this->input['nome'] ?? '')),

            'slug' => strtolower(trim(Request::sanitizeValue($this->input['slug'] ?? ''))),

            'codigo' => $codigo === '' ? null : strtoupper($codigo),

            'categoria_id' => $categoriaId === '' ? null : $categoriaId,

            'descricao'    => trim(Request::sanitizeValue($this->input['descricao'] ?? '')),
            'ingredientes' => trim(Request::sanitizeValue($this->input['ingredientes'] ?? '')),

            'preco'             => self::moeda($this->input['preco'] ?? '') ?? '0',
            'preco_promocional' => self::moeda($this->input['preco_promocional'] ?? ''),

            'tempo_preparo'    => $tempo === '' ? null : $tempo,
            'controla_estoque' => $this->input['controla_estoque'] ?? '0',
            'estoque'          => $estoque === '' ? '0' : $estoque,

            // Coluna NOT NULL default 0: nunca enviar NULL
            'ordem_exibicao' => $ordem === '' ? '0' : $ordem,

            'ativo'    => $this->input['ativo'] ?? '1',
            'destaque' => $this->input['destaque'] ?? '0',
        ];
    }

    /**
     * "1.234,56" → "1234.56". Vazio → null.
     */
    private static function moeda(mixed $valor): ?string
    {
        $valor = trim((string) $valor);

        if ($valor === '') {
            return null;
        }

        return str_replace(',', '.', str_replace('.', '', $valor));
    }
}
