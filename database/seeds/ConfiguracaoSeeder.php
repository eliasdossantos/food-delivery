<?php

namespace Database\Seeders;

use App\Models\ConfiguracaoModel;
use Framework\Database\Seeder;

/**
 * ConfiguracaoSeeder — inicializa dados associados ao Model Configuracao.
 *
 * Antes de executar, confirme que o Model existe, aponta para a tabela
 * `configuracaos` e permite os campos usados por meio de $fillable.
 */
class ConfiguracaoSeeder extends Seeder
{
    public function run(): void
    {
        $records = new ConfiguracaoModel();

        $configuracao = [
            // Identidade do estabelecimento
            'nome_fantasia' => 'Food Delivery',
            'razao_social' => 'Food Delivery Alimentos LTDA',
            'cnpj' => '12.345.678/0001-90',
            'descricao' => 'Sabor de verdade, direto na sua casa. Peça online e receba quentinho.',

            // Identidade visual
            'logo' => null,

            // Contato
            'email' => 'contato@deliveryexemplo.com.br',
            'telefone' => '(11) 3456-7890',
            'whatsapp' => '(11) 98765-4321',

            // Endereço
            'cep' => '01001-000',
            'logradouro' => 'Praça da Sé',
            'numero' => '100',
            'complemento' => 'Loja 1',
            'bairro' => 'Sé',
            'cidade' => 'São Paulo',
            'estado' => 'SP',
            'latitude' => -23.5505199,
            'longitude' => -46.6333094,
            'link_google_maps' => null,

            // Redes sociais
            'instagram' => '@deliveryexemplo',
            'facebook' => 'facebook.com/deliveryexemplo',
            'youtube' => 'youtube.com/@deliveryexemplo',
            'twitter' => '@deliveryexemplo',

            // Pedidos
            'aceita_entrega' => 1,
            'aceita_retirada' => 1,
            'valor_pedido_minimo' => 20.00,
            'tempo_medio_entrega_min' => 45,
            'tempo_medio_retirada_min' => 20,

            // Entrega
            'taxa_entrega_padrao' => 6.00,
            'frete_gratis_acima_de' => 50.00,
            'raio_entrega_km' => 8.00,

            // Pagamento
            'chave_pix' => '12.345.678/0001-90',

            // SEO e rodapé
            'meta_title' => 'Food Delivery — Peça online',
            'meta_description' => 'Faça seu pedido online no Food Delivery e receba em casa com rapidez.',
            'texto_rodape' => '© Food Delivery. Todos os direitos reservados.',
        ];

        $records->create($configuracao);
    }
}