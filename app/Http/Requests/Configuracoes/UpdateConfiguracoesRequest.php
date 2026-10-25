<?php

namespace App\Http\Requests\Configuracoes;

use App\Http\Requests\FormRequest;
use Framework\Auth\Auth;
use Framework\Http\Request;

/**
 * UpdateConfiguracoesRequest
 * ─────────────────────────────────────────────────────────────────────────────
 * Validação e sanitização do formulário de configurações (tabela `configuracoes`).
 *
 * A tabela é singleton (um único registro), por isso não há regras `unique`
 * e o {id} não é necessário.
 *
 * Não vêm do formulário (definidos no controller):
 *   logo, favicon (o nome do arquivo salvo vai para as colunas), created_at, updated_at
 *
 * Fluxo automático ao instanciar:
 *   new UpdateConfiguracoesRequest()
 *     → authorize()   — verifica permissão
 *     → sanitize()    — limpa os dados
 *     → validate()    — aplica rules() com messages()
 *
 * Uso no Controller:
 *   $request = new UpdateConfiguracoesRequest();
 *   if ($request->fails()) {
 *       Session::flash('error', $request->firstError());
 *       Session::flashInput($request->all());
 *       $this->back();
 *   }
 *   $data = $request->validated();
 */
class UpdateConfiguracoesRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            // Identidade do estabelecimento
            'nome_fantasia' => 'required|min:2|max:250',
            'razao_social'  => 'nullable|max:250',
            'cnpj'          => 'nullable|max:25|regex:/^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/',
            'descricao'     => 'nullable|max:2000',

            // Contato
            'email'    => 'nullable|email|max:180',
            'telefone' => 'nullable|phone|max:20',
            'whatsapp' => 'nullable|phone|max:20',

            // Endereço
            'cep'              => 'nullable|cep',
            'logradouro'       => 'nullable|max:250',
            'numero'           => 'nullable|max:30',
            'complemento'      => 'nullable|max:150',
            'bairro'           => 'nullable|max:200',
            'cidade'           => 'nullable|max:200',
            'estado'           => 'nullable|uf',
            'latitude'         => 'nullable|numeric|min:-90|max:90',
            'longitude'        => 'nullable|numeric|min:-180|max:180',
            'link_google_maps' => 'nullable|max:500|regex:/^https?:\/\/\S+$/',

            // Redes sociais
            'instagram' => 'nullable|max:200',
            'facebook'  => 'nullable|max:200',
            'youtube'   => 'nullable|max:200',
            'twitter'   => 'nullable|max:200',

            // Pedidos
            'aceita_entrega'           => 'nullable|in:0,1',
            'aceita_retirada'          => 'nullable|in:0,1',
            'valor_pedido_minimo'      => 'nullable|numeric|min:0|max:99999999.99',
            'tempo_medio_entrega_min'  => 'nullable|integer|min:0|max:1440',
            'tempo_medio_retirada_min' => 'nullable|integer|min:0|max:1440',

            // Entrega
            'taxa_entrega_padrao'   => 'nullable|numeric|min:0|max:99999999.99',
            'frete_gratis_acima_de' => 'nullable|numeric|min:0|max:99999999.99',
            'raio_entrega_km'       => 'nullable|numeric|min:0|max:9999.99',

            // Pagamento
            'chave_pix' => 'nullable|max:200',

            // SEO e rodapé
            'meta_title'       => 'nullable|max:150',
            'meta_description' => 'nullable|max:300',
            'texto_rodape'     => 'nullable|max:250',

            // Arquivos (o nome salvo vai para as colunas `logo` e `favicon`, definido no controller)
            'logo'    => 'nullable|image|mimes:jpg,jpeg,png,webp,gif',
        ];
    }

    public function messages(): array
    {
        return [
            // Identidade do estabelecimento
            'nome_fantasia.required' => 'O nome fantasia é obrigatório.',
            'nome_fantasia.min'      => 'O nome fantasia deve ter pelo menos 2 caracteres.',
            'nome_fantasia.max'      => 'O nome fantasia deve ter no máximo 250 caracteres.',
            'razao_social.max'       => 'A razão social deve ter no máximo 250 caracteres.',
            'cnpj.max'               => 'O CNPJ deve ter no máximo 25 caracteres.',
            'cnpj.regex'             => 'Informe um CNPJ válido (ex.: 00.000.000/0000-00).',
            'descricao.max'          => 'A descrição deve ter no máximo 2000 caracteres.',

            // Contato
            'email.email'    => 'Informe um e-mail válido.',
            'email.max'      => 'O e-mail deve ter no máximo 180 caracteres.',
            'telefone.phone' => 'Informe um telefone válido.',
            'telefone.max'   => 'O telefone deve ter no máximo 20 caracteres.',
            'whatsapp.phone' => 'Informe um WhatsApp válido.',
            'whatsapp.max'   => 'O WhatsApp deve ter no máximo 20 caracteres.',

            // Endereço
            'cep.cep'                => 'Informe um CEP válido.',
            'logradouro.max'         => 'O logradouro deve ter no máximo 250 caracteres.',
            'numero.max'             => 'O número deve ter no máximo 30 caracteres.',
            'complemento.max'        => 'O complemento deve ter no máximo 150 caracteres.',
            'bairro.max'             => 'O bairro deve ter no máximo 200 caracteres.',
            'cidade.max'             => 'A cidade deve ter no máximo 200 caracteres.',
            'estado.uf'              => 'Informe uma UF válida.',
            'latitude.numeric'       => 'A latitude deve ser um número válido.',
            'latitude.min'           => 'A latitude deve estar entre -90 e 90.',
            'latitude.max'           => 'A latitude deve estar entre -90 e 90.',
            'longitude.numeric'      => 'A longitude deve ser um número válido.',
            'longitude.min'          => 'A longitude deve estar entre -180 e 180.',
            'longitude.max'          => 'A longitude deve estar entre -180 e 180.',
            'link_google_maps.max'   => 'O link do Google Maps deve ter no máximo 500 caracteres.',
            'link_google_maps.regex' => 'O link do Google Maps deve começar com http:// ou https://.',

            // Redes sociais
            'instagram.max' => 'O Instagram deve ter no máximo 200 caracteres.',
            'facebook.max'  => 'O Facebook deve ter no máximo 200 caracteres.',
            'youtube.max'   => 'O YouTube deve ter no máximo 200 caracteres.',
            'twitter.max'   => 'O Twitter deve ter no máximo 200 caracteres.',

            // Pedidos
            'aceita_entrega.in'  => 'A opção "aceita entrega" deve ser 0 ou 1.',
            'aceita_retirada.in' => 'A opção "aceita retirada" deve ser 0 ou 1.',

            'valor_pedido_minimo.numeric' => 'O valor do pedido mínimo deve ser numérico.',
            'valor_pedido_minimo.min'     => 'O valor do pedido mínimo não pode ser negativo.',
            'valor_pedido_minimo.max'     => 'O valor do pedido mínimo é muito alto.',

            'tempo_medio_entrega_min.integer'  => 'O tempo médio de entrega deve ser um número inteiro (minutos).',
            'tempo_medio_entrega_min.min'      => 'O tempo médio de entrega não pode ser negativo.',
            'tempo_medio_entrega_min.max'      => 'O tempo médio de entrega deve ser de no máximo 1440 minutos.',
            'tempo_medio_retirada_min.integer' => 'O tempo médio de retirada deve ser um número inteiro (minutos).',
            'tempo_medio_retirada_min.min'     => 'O tempo médio de retirada não pode ser negativo.',
            'tempo_medio_retirada_min.max'     => 'O tempo médio de retirada deve ser de no máximo 1440 minutos.',

            // Entrega
            'taxa_entrega_padrao.numeric'   => 'A taxa de entrega deve ser numérica.',
            'taxa_entrega_padrao.min'       => 'A taxa de entrega não pode ser negativa.',
            'taxa_entrega_padrao.max'       => 'A taxa de entrega é muito alta.',
            'frete_gratis_acima_de.numeric' => 'O valor para frete grátis deve ser numérico.',
            'frete_gratis_acima_de.min'     => 'O valor para frete grátis não pode ser negativo.',
            'frete_gratis_acima_de.max'     => 'O valor para frete grátis é muito alto.',
            'raio_entrega_km.numeric'       => 'O raio de entrega deve ser numérico (km).',
            'raio_entrega_km.min'           => 'O raio de entrega não pode ser negativo.',
            'raio_entrega_km.max'           => 'O raio de entrega deve ser de no máximo 9999,99 km.',

            // Pagamento
            'chave_pix.max' => 'A chave PIX deve ter no máximo 200 caracteres.',

            // SEO e rodapé
            'meta_title.max'       => 'O meta title deve ter no máximo 150 caracteres.',
            'meta_description.max' => 'A meta description deve ter no máximo 300 caracteres.',
            'texto_rodape.max'     => 'O texto do rodapé deve ter no máximo 250 caracteres.',

            // Arquivos
            'logo.image'    => 'O logo deve ser uma imagem válida.',
            'logo.mimes'    => 'O logo deve estar nos formatos JPG, JPEG, PNG, GIF ou WEBP.',
        ];
    }

    /**
     * Sanitiza os dados antes da validação.
     * Campos opcionais vazios viram NULL.
     * Valores no formato BR (1.234,56) viram decimal (1234.56).
     */
    public function sanitize(): array
    {
        return [
            // Identidade do estabelecimento (mantém a capitalização digitada)
            'nome_fantasia' => trim(Request::sanitizeValue($this->input['nome_fantasia'] ?? '')),
            'razao_social'  => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['razao_social'] ?? ''))),
            'cnpj'          => $this->formatarCnpj($this->input['cnpj'] ?? ''),
            'descricao'     => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['descricao'] ?? ''))),

            // Contato
            'email'    => $this->nuloSeVazio(strtolower(trim($this->input['email'] ?? ''))),
            'telefone' => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['telefone'] ?? ''))),
            'whatsapp' => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['whatsapp'] ?? ''))),

            // Endereço
            'cep'         => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['cep'] ?? ''))),
            'logradouro'  => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['logradouro'] ?? ''))),
            'numero'      => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['numero'] ?? ''))),
            'complemento' => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['complemento'] ?? ''))),
            'bairro'      => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['bairro'] ?? ''))),
            'cidade'      => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['cidade'] ?? ''))),
            'estado'      => $this->nuloSeVazio(strtoupper(trim($this->input['estado'] ?? ''))),
            'latitude'    => $this->coordenadaOuNulo($this->input['latitude'] ?? ''),
            'longitude'   => $this->coordenadaOuNulo($this->input['longitude'] ?? ''),

            // URL: só trim (sanitizeValue poderia alterar & e = da query string).
            // Lembre de escapar na exibição (view).
            'link_google_maps' => $this->nuloSeVazio(trim($this->input['link_google_maps'] ?? '')),

            // Redes sociais
            'instagram' => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['instagram'] ?? ''))),
            'facebook'  => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['facebook'] ?? ''))),
            'youtube'   => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['youtube'] ?? ''))),
            'twitter'   => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['twitter'] ?? ''))),

            // Pedidos
            'aceita_entrega'           => trim($this->input['aceita_entrega'] ?? '0'),
            'aceita_retirada'          => trim($this->input['aceita_retirada'] ?? '0'),
            'valor_pedido_minimo'      => $this->moedaOuNulo($this->input['valor_pedido_minimo'] ?? ''),
            'tempo_medio_entrega_min'  => $this->nuloSeVazio(trim($this->input['tempo_medio_entrega_min'] ?? '')),
            'tempo_medio_retirada_min' => $this->nuloSeVazio(trim($this->input['tempo_medio_retirada_min'] ?? '')),

            // Entrega
            'taxa_entrega_padrao'   => $this->moedaOuNulo($this->input['taxa_entrega_padrao'] ?? ''),
            'frete_gratis_acima_de' => $this->moedaOuNulo($this->input['frete_gratis_acima_de'] ?? ''),
            'raio_entrega_km'       => $this->moedaOuNulo($this->input['raio_entrega_km'] ?? ''),

            // Pagamento
            'chave_pix' => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['chave_pix'] ?? ''))),

            // SEO e rodapé
            'meta_title'       => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['meta_title'] ?? ''))),
            'meta_description' => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['meta_description'] ?? ''))),
            'texto_rodape'     => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['texto_rodape'] ?? ''))),
        ];
    }

    /** '' vira NULL, para não gravar string vazia em colunas nullable */
    private function nuloSeVazio(string $valor): ?string
    {
        return $valor === '' ? null : $valor;
    }

    /** Converte valor BR (1.234,56) em decimal (1234.56); '' vira NULL */
    private function moedaOuNulo(string $valor): ?string
    {
        $valor = trim($valor);

        return $valor === ''
            ? null
            : str_replace(',', '.', str_replace('.', '', $valor));
    }

    /** Coordenadas usam ponto decimal; só troca vírgula por ponto. '' vira NULL */
    private function coordenadaOuNulo(string $valor): ?string
    {
        $valor = trim($valor);

        return $valor === '' ? null : str_replace(',', '.', $valor);
    }

    /**
     * Normaliza o CNPJ para 00.000.000/0000-00 (aceita com ou sem máscara).
     * Se vier incompleto ou errado, devolve como está e o regex da validação acusa o erro.
     */
    private function formatarCnpj(string $cnpj): ?string
    {
        $original = trim($cnpj);
        $numeros  = preg_replace('/\D/', '', $original);

        if ($numeros === '') {
            return null;
        }

        return strlen($numeros) === 14
            ? preg_replace('/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/', '$1.$2.$3/$4-$5', $numeros)
            : $original;
    }
}