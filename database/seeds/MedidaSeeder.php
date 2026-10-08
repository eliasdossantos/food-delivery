<?php

namespace Database\Seeders;

use App\Models\MedidaModel;
use Framework\Database\Seeder;

/**
 * MedidaSeeder — inicializa dados associados ao Model Medida.
 *
 * Antes de executar, confirme que o Model existe, aponta para a tabela
 * `medidas` e permite os campos usados por meio de $fillable.
 */
class MedidaSeeder extends Seeder
{
    public function run(): void
    {
        $medidas = [

            // =========================================================================
            // PIZZAS
            // =========================================================================
            [
                'nome'           => 'Pizza Broto',
                'descricao'      => '20 cm, 4 fatias, ideal para 1 pessoa.',
                'unidade'        => 'cm',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 1,
            ],
            [
                'nome'           => 'Pizza Pequena',
                'descricao'      => '25 cm, 6 fatias, ideal para 1 a 2 pessoas.',
                'unidade'        => 'cm',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 2,
            ],
            [
                'nome'           => 'Pizza Média',
                'descricao'      => '30 cm, 8 fatias, ideal para 2 a 3 pessoas.',
                'unidade'        => 'cm',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 3,
            ],
            [
                'nome'           => 'Pizza Grande',
                'descricao'      => '35 cm, 10 fatias, ideal para 3 a 4 pessoas.',
                'unidade'        => 'cm',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 4,
            ],
            [
                'nome'           => 'Pizza Família',
                'descricao'      => '40 cm, 12 fatias, ideal para 4 a 5 pessoas.',
                'unidade'        => 'cm',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 5,
            ],
            [
                'nome'           => 'Pizza Gigante',
                'descricao'      => '45 cm, 14 fatias, ideal para 5 a 6 pessoas.',
                'unidade'        => 'cm',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 6,
            ],
            [
                'nome'           => 'Pizza Super Gigante',
                'descricao'      => '50 cm, 16 fatias, ideal para 6 a 8 pessoas.',
                'unidade'        => 'cm',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 7,
            ],

            // =========================================================================
            // BEBIDAS
            // =========================================================================
            [
                'nome'           => 'Bebida 200 ml',
                'descricao'      => 'Embalagem individual com 200 ml.',
                'unidade'        => 'ml',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 8,
            ],
            [
                'nome'           => 'Bebida 250 ml',
                'descricao'      => 'Embalagem individual com 250 ml.',
                'unidade'        => 'ml',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 9,
            ],
            [
                'nome'           => 'Bebida 300 ml',
                'descricao'      => 'Embalagem individual com 300 ml.',
                'unidade'        => 'ml',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 10,
            ],
            [
                'nome'           => 'Bebida 350 ml',
                'descricao'      => 'Embalagem individual com 350 ml.',
                'unidade'        => 'ml',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 11,
            ],
            [
                'nome'           => 'Bebida 500 ml',
                'descricao'      => 'Embalagem individual com 500 ml.',
                'unidade'        => 'ml',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 12,
            ],
            [
                'nome'           => 'Bebida 1 Litro',
                'descricao'      => 'Garrafa com 1 litro, ideal para compartilhar.',
                'unidade'        => 'l',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 13,
            ],
            [
                'nome'           => 'Bebida 2 Litros',
                'descricao'      => 'Garrafa com 2 litros, ideal para compartilhar.',
                'unidade'        => 'l',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 14,
            ],

            // =========================================================================
            // PORÇÕES
            // =========================================================================
            [
                'nome'           => 'Porção Individual',
                'descricao'      => '200 g, ideal para 1 pessoa.',
                'unidade'        => 'g',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 15,
            ],
            [
                'nome'           => 'Porção Pequena',
                'descricao'      => '300 g, ideal para 1 a 2 pessoas.',
                'unidade'        => 'g',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 16,
            ],
            [
                'nome'           => 'Porção Média',
                'descricao'      => '500 g, ideal para 2 pessoas.',
                'unidade'        => 'g',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 17,
            ],
            [
                'nome'           => 'Porção Grande',
                'descricao'      => '700 g, ideal para 2 a 3 pessoas.',
                'unidade'        => 'g',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 18,
            ],
            [
                'nome'           => 'Porção Família',
                'descricao'      => '1 kg, ideal para 3 a 4 pessoas.',
                'unidade'        => 'kg',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 19,
            ],
            [
                'nome'           => 'Porção 1,5 Kg',
                'descricao'      => '1,5 kg, ideal para 4 a 5 pessoas.',
                'unidade'        => 'kg',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 20,
            ],
            [
                'nome'           => 'Porção 2 Kg',
                'descricao'      => '2 kg, ideal para 5 a 6 pessoas.',
                'unidade'        => 'kg',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 21,
            ],

            // =========================================================================
            // HAMBÚRGUERES
            // =========================================================================
            [
                'nome'           => 'Hambúrguer Simples',
                'descricao'      => '1 hambúrguer com um disco de carne.',
                'unidade'        => 'un',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 22,
            ],
            [
                'nome'           => 'Hambúrguer Duplo',
                'descricao'      => '2 discos de carne em um único hambúrguer.',
                'unidade'        => 'un',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 23,
            ],
            [
                'nome'           => 'Hambúrguer Triplo',
                'descricao'      => '3 discos de carne em um único hambúrguer.',
                'unidade'        => 'un',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 24,
            ],
            [
                'nome'           => 'Hambúrguer Smash',
                'descricao'      => '1 disco de carne preparado no estilo smash.',
                'unidade'        => 'un',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 25,
            ],
            [
                'nome'           => 'Hambúrguer Smash Duplo',
                'descricao'      => '2 discos de carne preparados no estilo smash.',
                'unidade'        => 'un',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 26,
            ],
            [
                'nome'           => 'Hambúrguer Artesanal',
                'descricao'      => '1 hambúrguer artesanal com carne selecionada.',
                'unidade'        => 'un',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 27,
            ],
            [
                'nome'           => 'Hambúrguer em Combo',
                'descricao'      => '1 hambúrguer acompanhado de batata e bebida.',
                'unidade'        => 'un',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 28,
            ],

            // =========================================================================
            // AÇAÍ / SORVETES
            // =========================================================================
            [
                'nome'           => 'Açaí 200 ml',
                'descricao'      => 'Copo de 200 ml, ideal para 1 pessoa.',
                'unidade'        => 'ml',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 29,
            ],
            [
                'nome'           => 'Açaí 300 ml',
                'descricao'      => 'Copo de 300 ml, ideal para 1 pessoa.',
                'unidade'        => 'ml',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 30,
            ],
            [
                'nome'           => 'Açaí 400 ml',
                'descricao'      => 'Copo de 400 ml, ideal para 1 pessoa.',
                'unidade'        => 'ml',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 31,
            ],
            [
                'nome'           => 'Açaí 500 ml',
                'descricao'      => 'Copo de 500 ml, ideal para 1 pessoa.',
                'unidade'        => 'ml',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 32,
            ],
            [
                'nome'           => 'Açaí 700 ml',
                'descricao'      => 'Copo de 700 ml, ideal para 1 a 2 pessoas.',
                'unidade'        => 'ml',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 33,
            ],
            [
                'nome'           => 'Açaí 1 Litro',
                'descricao'      => 'Embalagem de 1 litro, ideal para compartilhar.',
                'unidade'        => 'l',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 34,
            ],
            [
                'nome'           => 'Açaí 2 Litros',
                'descricao'      => 'Embalagem de 2 litros, ideal para compartilhar.',
                'unidade'        => 'l',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 35,
            ],

            // =========================================================================
            // SOBREMESAS
            // =========================================================================
            [
                'nome'           => 'Sobremesa Mini',
                'descricao'      => 'Porção pequena, ideal para 1 pessoa.',
                'unidade'        => 'un',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 36,
            ],
            [
                'nome'           => 'Sobremesa Individual',
                'descricao'      => 'Porção individual, ideal para 1 pessoa.',
                'unidade'        => 'un',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 37,
            ],
            [
                'nome'           => 'Sobremesa Pequena',
                'descricao'      => 'Porção pequena para 1 pessoa.',
                'unidade'        => 'un',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 38,
            ],
            [
                'nome'           => 'Sobremesa Média',
                'descricao'      => 'Porção média para 1 a 2 pessoas.',
                'unidade'        => 'un',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 39,
            ],
            [
                'nome'           => 'Sobremesa Grande',
                'descricao'      => 'Porção grande para 2 a 3 pessoas.',
                'unidade'        => 'un',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 40,
            ],
            [
                'nome'           => 'Sobremesa em Fatia',
                'descricao'      => '1 fatia, ideal para 1 pessoa.',
                'unidade'        => 'un',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 41,
            ],
            [
                'nome'           => 'Sobremesa Inteira',
                'descricao'      => 'Sobremesa inteira para compartilhar.',
                'unidade'        => 'un',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 42,
            ],

            // =========================================================================
            // MARMITAS / REFEIÇÕES
            // =========================================================================
            [
                'nome'           => 'Marmita 300 g',
                'descricao'      => '300 g, ideal para uma refeição individual.',
                'unidade'        => 'g',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 43,
            ],
            [
                'nome'           => 'Marmita 400 g',
                'descricao'      => '400 g, ideal para uma refeição individual.',
                'unidade'        => 'g',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 44,
            ],
            [
                'nome'           => 'Marmita 500 g',
                'descricao'      => '500 g, ideal para uma refeição individual completa.',
                'unidade'        => 'g',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 45,
            ],
            [
                'nome'           => 'Marmita 600 g',
                'descricao'      => '600 g, ideal para uma refeição individual reforçada.',
                'unidade'        => 'g',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 46,
            ],
            [
                'nome'           => 'Marmita 700 g',
                'descricao'      => '700 g, ideal para uma refeição individual generosa.',
                'unidade'        => 'g',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 47,
            ],
            [
                'nome'           => 'Marmita 800 g',
                'descricao'      => '800 g, ideal para uma refeição individual grande.',
                'unidade'        => 'g',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 48,
            ],
            [
                'nome'           => 'Marmita 1 Kg',
                'descricao'      => '1 kg, ideal para compartilhar ou para uma refeição muito reforçada.',
                'unidade'        => 'kg',
                'imagem_url'     => null,
                'ativo'          => 1,
                'ordem_exibicao' => 49,
            ],
        ];

        $model = new MedidaModel();

        foreach ($medidas as $medida) {
            $model->create($medida);
        }
    }
}
