<?php

namespace Database\Seeders;

use App\Models\CategoriaModel;
use Framework\Database\Seeder;

/**
 * CategoriaSeeder — inicializa dados associados ao Model Categoria.
 */
class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            [
                'nome'           => 'Hambúrgueres',
                'slug'           => 'hamburgueres',
                'descricao'      => 'Saborosos hambúrgueres artesanais, smashs e combos completos.',
                'imagem_url'     => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd',
                'icone'          => 'mdi-hamburger',
                'ativo'          => 1,
                'destaque'       => true,
                'ordem_exibicao' => 1,
            ],
            [
                'nome'           => 'Pizzas',
                'slug'           => 'pizzas',
                'descricao'      => 'Pizzas tradicionais, doces e artesanais com massa fresca.',
                'imagem_url'     => 'https://images.unsplash.com/photo-1513104890138-7c749659a591',
                'icone'          => 'mdi-pizza',
                'ativo'          => 1,
                'destaque'       => true,
                'ordem_exibicao' => 2,
            ],
            [
                'nome'           => 'Comida Japonesa',
                'slug'           => 'comida-japonesa',
                'descricao'      => 'Sushis, sashimis, temakis e combinados especiais do chefe.',
                'imagem_url'     => 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c',
                'icone'          => 'mdi-fish',
                'ativo'          => 1,
                'destaque'       => true,
                'ordem_exibicao' => 3,
            ],
            [
                'nome'           => 'Marmitas & Pratos Feitos',
                'slug'           => 'marmitas-pratos-feitos',
                'descricao'      => 'Comida caseira, balanceada e deliciosa para o seu dia a dia.',
                'imagem_url'     => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c',
                'icone'          => 'mdi-silverware-variant',
                'ativo'          => 1,
                'destaque'       => 0,
                'ordem_exibicao' => 4,
            ],
            [
                'nome'           => 'Bebidas',
                'slug'           => 'bebidas',
                'descricao'      => 'Refrigerantes, sucos naturais, águas, cervejas e drinques.',
                'imagem_url'     => 'https://images.unsplash.com/photo-1581006852262-e4307cf6283a',
                'icone'          => 'mdi-cup-water',
                'ativo'          => 1,
                'destaque'       => 0,
                'ordem_exibicao' => 5,
            ],
            [
                'nome'           => 'Sobremesas & Doces',
                'slug'           => 'sobremesas-doces',
                'descricao'      => 'Bolos, tortas, brigadeiros, sorvetes e sobremesas especiais.',
                'imagem_url'     => 'https://images.unsplash.com/photo-1551024709-8f23befc6f87',
                'icone'          => 'mdi-ice-cream',
                'ativo'          => 1,
                'destaque'       => true,
                'ordem_exibicao' => 6,
            ],
            [
                'nome'           => 'Açaí & Sorvetes',
                'slug'           => 'acai-sorvetes',
                'descricao'      => 'Açaí na tigela com acompanhamentos variados e picolés recheados.',
                'imagem_url'     => 'https://images.unsplash.com/photo-1590080875515-8a3a8dc5735e',
                'icone'          => 'mdi-cup',
                'ativo'          => 1,
                'destaque'       => 0,
                'ordem_exibicao' => 7,
            ],
            [
                'nome'           => 'Pastéis & Salgados',
                'slug'           => 'pasteis-salgados',
                'descricao'      => 'Pastéis crocantes, coxinhas, empadas e salgados fritos na hora.',
                'imagem_url'     => 'https://images.unsplash.com/photo-1626777552726-4a6b54c97e46',
                'icone'          => 'mdi-cookie',
                'ativo'          => 1,
                'destaque'       => 0,
                'ordem_exibicao' => 8,
            ],
            [
                'nome'           => 'Saudável & Vegano',
                'slug'           => 'saudavel-vegano',
                'descricao'      => 'Saladas frescas, pratos sem glúten, sem lactose e opções veganas.',
                'imagem_url'     => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd',
                'icone'          => 'mdi-leaf',
                'ativo'          => 1,
                'destaque'       => 0,
                'ordem_exibicao' => 9,
            ],
            [
                'nome'           => 'Comida Mexicana',
                'slug'           => 'comida-mexicana',
                'descricao'      => 'Tacos, burritos, nachos e quesadillas bem temperados.',
                'imagem_url'     => 'https://images.unsplash.com/photo-1565299585323-38d6b0865b47',
                'icone'          => 'mdi-chili-hot',
                'ativo'          => 1,
                'destaque'       => 0,
                'ordem_exibicao' => 10,
            ],
        ];

        $model = new CategoriaModel();

        foreach ($categorias as $categoria) {
            $model->create($categoria);
        }
    }
}
