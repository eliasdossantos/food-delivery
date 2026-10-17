<?php

namespace App\Http\Requests\Produtos;

use App\Http\Requests\FormRequest;
use Framework\Http\Request;

/**
 * UpdateProdutosRequest
 * Validação e sanitização da edição de produto (tabela `produtos`).
 */
class UpdateProdutosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Identificação
            'nome'              => 'required|min:2|max:200|unique:produtos,nome,{id}',
            'slug'              => 'nullable|max:200|unique:produtos,slug,{id}',
            'codigo'            => 'nullable|string|max:100|unique:produtos,codigo,{id}',
            'categoria_id'      => 'nullable|integer|exists:categorias,id',
            'extra_ids'         => 'nullable|array',
            'especificacoes'    => 'nullable|array',

            // Conteúdo
            'descricao'         => 'nullable|max:500',
            'ingredientes'      => 'nullable|max:500',

            // Preços (decimal 10,2)
            'preco'             => 'required|numeric|min:0|max:99999999.99',

            // Operação e estoque (unsigned smallint: 0..65535)
            'tempo_preparo'     => 'nullable|integer|min:0|max:65535',
            'controla_estoque'  => 'nullable|in:0,1',
            'estoque'           => 'nullable|integer|min:0|max:65535',

            // Exibição
            'ordem_exibicao'    => 'nullable|integer|min:0|max:65535',
            'ativo'             => 'required|in:0,1',
            'destaque'          => 'required|in:0,1',

            // Arquivo: tamanho controlado pela classe Upload
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

            'preco.required'            => 'O preço do produto é obrigatório.',
            'preco.numeric'             => 'O preço deve ser numérico.',

            'categoria_id.integer' => 'A categoria selecionada é inválida.',
            'categoria_id.exists'  => 'A categoria selecionada não existe.',

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

            'tempo_preparo'    => $tempo === '' ? null : $tempo,
            'controla_estoque' => $this->input['controla_estoque'] ?? '0',
            'estoque'          => $estoque === '' ? '0' : $estoque,

            // Coluna NOT NULL default 0: nunca enviar NULL
            'ordem_exibicao' => $ordem === '' ? '0' : $ordem,

            'ativo'    => $this->input['ativo'] ?? '1',
            'destaque' => $this->input['destaque'] ?? '0',
            'extra_ids' => is_array($this->input['extra_ids'] ?? null)
                ? array_values($this->input['extra_ids'])
                : [],

            'extra_ids' => is_array($this->input['extra_ids'] ?? null)
                ? array_values($this->input['extra_ids'])
                : [],

            'especificacoes' => $this->sanitizarEspecificacoes($this->input['especificacoes'] ?? []),

        ];
    }

    /**
     * especificacoes[i][medida_id|preco|customizavel] → lista limpa, sem índices soltos.
     * A conversão do preço e as demais validações ficam no controller.
     */
    private function sanitizarEspecificacoes(mixed $linhas): array
    {
        if (!is_array($linhas)) {
            return [];
        }

        $saida = [];

        foreach ($linhas as $linha) {
            if (!is_array($linha)) {
                continue;
            }

            $saida[] = [
                'medida_id'    => trim(Request::sanitizeValue($linha['medida_id'] ?? '')),
                'preco'        => trim(Request::sanitizeValue($linha['preco'] ?? '')),
                'customizavel' => !empty($linha['customizavel']) ? '1' : '0',
            ];
        }

        return $saida;
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