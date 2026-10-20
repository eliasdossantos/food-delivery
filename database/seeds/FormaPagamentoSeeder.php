<?php

namespace Database\Seeders;

use App\Models\FormaPagamentoModel;
use Framework\Database\Seeder;

/**
 * FormaPagamentoSeeder — inicializa dados associados ao Model FormaPagamento.
 *
 * Antes de executar, confirme que o Model existe, aponta para a tabela
 * `forma_pagamentos` e permite os campos usados por meio de $fillable.
 */
class FormaPagamentoSeeder extends Seeder
{
    public function run(): void
    {
        $formasPagamento = [
            ['nome' => 'Dinheiro'],
            ['nome' => 'PIX'],
            ['nome' => 'Cartão de Crédito'],
            ['nome' => 'Cartão de Débito'],
            ['nome' => 'Vale-Alimentação'],
            ['nome' => 'Vale-Refeição'],
            ['nome' => 'Pagamento por Aproximação'],
            ['nome' => 'Pagamento na Entrega - Dinheiro'],
            ['nome' => 'Pagamento na Entrega - Cartão de Crédito'],
            ['nome' => 'Pagamento na Entrega - Cartão de Débito'],
        ];

        $model = new FormaPagamentoModel();

        foreach ($formasPagamento as $forma) {
            $model->create([
                'nome' => $forma['nome'],
                'ativo' => true,
            ]);
        }
    }
}