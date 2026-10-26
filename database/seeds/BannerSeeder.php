<?php

namespace Database\Seeders;

use App\Models\BannerModel;
use Framework\Database\Seeder;

/**
 * BannerSeeder — inicializa dados associados ao Model Banner.
 *
 * Antes de executar, confirme que o Model existe, aponta para a tabela
 * `banners` e permite os campos usados por meio de $fillable.
 *
 * As imagens (pizza.png, tortilla.png, burger.png) precisam existir em
 * public/uploads/banners/ para aparecerem na home.
 */
class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            // Sem datas: sempre visível
            [
                'titulo'         => 'Comida Deliciosa',
                'descricao'      => 'Peça sua pizza favorita e receba quentinha em casa, com ingredientes frescos e muito sabor.',
                'imagem'         => 'pizza.png',
                'badge_destaque' => '20%',
                'badge_rotulo'   => 'OFF',
                'botao_texto'    => 'Fazer Pedido',
                'botao_link'     => '#menu',
                'ordem_exibicao' => 1,
                'ativo'          => 1,
                'data_inicio'    => null,
                'data_fim'       => null,
            ],

            // Vigente: começou ontem e termina em 7 dias
            [
                'titulo'         => 'Sabor de Verdade',
                'descricao'      => 'Tortilhas recheadas e preparadas na hora. Aproveite a promoção da semana e experimente.',
                'imagem'         => 'tortilla.png',
                'badge_destaque' => '20%',
                'badge_rotulo'   => 'OFF',
                'botao_texto'    => 'Ver Cardápio',
                'botao_link'     => '#menu',
                'ordem_exibicao' => 2,
                'ativo'          => 1,
                'data_inicio'    => $this->data('-1 day'),
                'data_fim'       => $this->data('+7 days'),
            ],

            // Vigente: só data fim (sem início)
            [
                'titulo'         => 'Hambúrguer Artesanal',
                'descricao'      => 'Pão macio, carne suculenta e molho especial da casa. Peça o seu e aproveite o desconto.',
                'imagem'         => 'burger.png',
                'badge_destaque' => '20%',
                'badge_rotulo'   => 'OFF',
                'botao_texto'    => 'Peça Agora',
                'botao_link'     => '#menu',
                'ordem_exibicao' => 3,
                'ativo'          => 1,
                'data_inicio'    => null,
                'data_fim'       => $this->data('+30 days'),
            ],

            // Agendado: começa em 5 dias (não deve aparecer ainda)
            [
                'titulo'         => 'Promoção da Próxima Semana',
                'descricao'      => 'Uma promoção especial de pizzas está chegando. Fique de olho e não perca.',
                'imagem'         => 'pizza.png',
                'badge_destaque' => '20%',
                'badge_rotulo'   => 'OFF',
                'botao_texto'    => 'Fazer Pedido',
                'botao_link'     => '#menu',
                'ordem_exibicao' => 4,
                'ativo'          => 1,
                'data_inicio'    => $this->data('+5 days'),
                'data_fim'       => $this->data('+15 days'),
            ],

            // Expirado: terminou há 2 dias (não deve aparecer mais)
            [
                'titulo'         => 'Promoção Encerrada',
                'descricao'      => 'Esta promoção de hambúrgueres já terminou. Fique atento às próximas ofertas.',
                'imagem'         => 'burger.png',
                'badge_destaque' => '20%',
                'badge_rotulo'   => 'OFF',
                'botao_texto'    => 'Ver Cardápio',
                'botao_link'     => '#menu',
                'ordem_exibicao' => 5,
                'ativo'          => 1,
                'data_inicio'    => $this->data('-10 days'),
                'data_fim'       => $this->data('-2 days'),
            ],

            // Inativo: dentro do período, mas desligado manualmente
            [
                'titulo'         => 'Banner Desativado',
                'descricao'      => 'Este banner está dentro do período, mas foi desligado manualmente no painel.',
                'imagem'         => 'tortilla.png',
                'badge_destaque' => null,
                'badge_rotulo'   => null,
                'botao_texto'    => null,
                'botao_link'     => null,
                'ordem_exibicao' => 6,
                'ativo'          => 0,
                'data_inicio'    => $this->data('-1 day'),
                'data_fim'       => $this->data('+10 days'),
            ],
        ];

        $model = new BannerModel();

        foreach ($banners as $banner) {
            $model->create($banner);
        }
    }

    /** Converte um texto relativo ('+7 days', '-1 day') em data e hora do banco. */
    private function data(string $relativa): string
    {
        return date('Y-m-d H:i:s', strtotime($relativa));
    }
}