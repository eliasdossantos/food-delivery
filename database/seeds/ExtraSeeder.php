<?php

namespace Database\Seeders;

use App\Models\EstraModel;
use Framework\Database\Seeder;

/**
 * ExtraSeeder — inicializa dados associados ao Model Estra.
 *
 * Antes de executar, confirme que o Model existe, aponta para a tabela
 * `estras` e permite os campos usados por meio de $fillable.
 */
class ExtraSeeder extends Seeder
{
    public function run(): void
    {
        $extras = [
            [
                'nome'           => 'Bacon',
                'slug'           => 'bacon',
                'descricao'      => 'Porção adicional de bacon crocante.',
                'imagem_url'     => 'https://images.unsplash.com/photo-1528607929212-2636ec44253e',
                'preco'          => 5.00,
                'ativo'          => 1,
                'destaque'       => true,
                'ordem_exibicao' => 1,
            ],
            [
                'nome'           => 'Queijo Extra',
                'slug'           => 'queijo-extra',
                'descricao'      => 'Porção adicional de queijo derretido.',
                'imagem_url'     => 'https://images.unsplash.com/photo-1486297678162-eb2a19b0a32d',
                'preco'          => 4.00,
                'ativo'          => 1,
                'destaque'       => true,
                'ordem_exibicao' => 2,
            ],
            [
                'nome'           => 'Cheddar',
                'slug'           => 'cheddar',
                'descricao'      => 'Porção adicional de cheddar cremoso.',
                'imagem_url'     => 'https://images.unsplash.com/photo-1618164436241-4473940d1f5c',
                'preco'          => 4.50,
                'ativo'          => 1,
                'destaque'       => false,
                'ordem_exibicao' => 3,
            ],
            [
                'nome'           => 'Catupiry',
                'slug'           => 'catupiry',
                'descricao'      => 'Porção adicional de catupiry cremoso.',
                'imagem_url'     => 'https://images.unsplash.com/photo-1628088062854-d1870b4553da',
                'preco'          => 5.00,
                'ativo'          => 1,
                'destaque'       => false,
                'ordem_exibicao' => 4,
            ],
            [
                'nome'           => 'Ovo',
                'slug'           => 'ovo',
                'descricao'      => 'Ovo frito preparado na hora.',
                'imagem_url'     => 'https://images.unsplash.com/photo-1482049016688-2d3e1b311543',
                'preco'          => 3.00,
                'ativo'          => 1,
                'destaque'       => false,
                'ordem_exibicao' => 5,
            ],
            [
                'nome'           => 'Cebola Caramelizada',
                'slug'           => 'cebola-caramelizada',
                'descricao'      => 'Cebola caramelizada lentamente até ficar macia e adocicada.',
                'imagem_url'     => 'https://images.unsplash.com/photo-1518977676601-b53f82aba655',
                'preco'          => 3.50,
                'ativo'          => 1,
                'destaque'       => false,
                'ordem_exibicao' => 6,
            ],
            [
                'nome'           => 'Molho Especial',
                'slug'           => 'molho-especial',
                'descricao'      => 'Molho especial da casa.',
                'imagem_url'     => 'https://images.unsplash.com/photo-1472476443507-c7a5948772fc',
                'preco'          => 2.50,
                'ativo'          => 1,
                'destaque'       => false,
                'ordem_exibicao' => 7,
            ],
            [
                'nome'           => 'Maionese Temperada',
                'slug'           => 'maionese-temperada',
                'descricao'      => 'Maionese cremosa temperada com ervas e especiarias.',
                'imagem_url'     => 'https://images.unsplash.com/photo-1559339352-11d035aa65de',
                'preco'          => 2.50,
                'ativo'          => 1,
                'destaque'       => false,
                'ordem_exibicao' => 8,
            ],
            [
                'nome'           => 'Borda Recheada',
                'slug'           => 'borda-recheada',
                'descricao'      => 'Borda da pizza recheada com queijo cremoso.',
                'imagem_url'     => 'https://images.unsplash.com/photo-1579751626657-72bc17010498',
                'preco'          => 8.00,
                'ativo'          => 1,
                'destaque'       => true,
                'ordem_exibicao' => 9,
            ],
            [
                'nome'           => 'Azeitona',
                'slug'           => 'azeitona',
                'descricao'      => 'Porção adicional de azeitonas.',
                'imagem_url'     => 'https://images.unsplash.com/photo-1447175008436-054170c2e4c8',
                'preco'          => 2.50,
                'ativo'          => 1,
                'destaque'       => false,
                'ordem_exibicao' => 10,
            ],
            [
                'nome'           => 'Milho',
                'slug'           => 'milho',
                'descricao'      => 'Porção adicional de milho verde.',
                'imagem_url'     => 'https://images.unsplash.com/photo-1551754655-cd27e38d2076',
                'preco'          => 2.50,
                'ativo'          => 1,
                'destaque'       => false,
                'ordem_exibicao' => 11,
            ],
            [
                'nome'           => 'Tomate',
                'slug'           => 'tomate',
                'descricao'      => 'Porção adicional de tomate fresco.',
                'imagem_url'     => 'https://images.unsplash.com/photo-1546094096-0df4bcaaa337',
                'preco'          => 2.00,
                'ativo'          => 1,
                'destaque'       => false,
                'ordem_exibicao' => 12,
            ],
            [
                'nome'           => 'Batata Frita',
                'slug'           => 'batata-frita',
                'descricao'      => 'Porção adicional de batatas fritas crocantes.',
                'imagem_url'     => 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877',
                'preco'          => 7.00,
                'ativo'          => 1,
                'destaque'       => true,
                'ordem_exibicao' => 13,
            ],
            [
                'nome'           => 'Guacamole',
                'slug'           => 'guacamole',
                'descricao'      => 'Porção de guacamole fresco preparado com abacate.',
                'imagem_url'     => 'https://images.unsplash.com/photo-1551326344-4eef8ccf7d2f',
                'preco'          => 7.00,
                'ativo'          => 1,
                'destaque'       => false,
                'ordem_exibicao' => 14,
            ],
            [
                'nome'           => 'Cream Cheese',
                'slug'           => 'cream-cheese',
                'descricao'      => 'Porção adicional de cream cheese cremoso.',
                'imagem_url'     => 'https://images.unsplash.com/photo-1628088062854-d1870b4553da',
                'preco'          => 5.00,
                'ativo'          => 1,
                'destaque'       => false,
                'ordem_exibicao' => 15,
            ],
            [
                'nome'           => 'Shoyu',
                'slug'           => 'shoyu',
                'descricao'      => 'Sachê ou porção adicional de molho shoyu.',
                'imagem_url'     => 'https://images.unsplash.com/photo-1582450871972-ab5ca641643d',
                'preco'          => 1.50,
                'ativo'          => 1,
                'destaque'       => false,
                'ordem_exibicao' => 16,
            ],
        ];

        $model = new EstraModel();

        foreach ($extras as $extra) {
            $model->create($extra);
        }
    }
}
