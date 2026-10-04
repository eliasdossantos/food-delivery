<?php

namespace Database\Seeders;

use App\Models\PerfilModel;
use Framework\Database\Seeder;

class PerfilSeeder extends Seeder
{
    public function run(): void
    {
        $perfis = new PerfilModel();

        foreach (
            [
                'Super Administrador',
                'Administrador',
                'Gestor',
                'Atendente',
                'Cozinheiro',
                'Entregador',
                'Caixa',
            ] as $nome
        ) {
            $perfis->firstOrCreate(
                ['nome' => $nome],
                ['ativo' => 1]
            );
        }
    }
}
