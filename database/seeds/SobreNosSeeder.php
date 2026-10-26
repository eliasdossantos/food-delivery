<?php

namespace Database\Seeders;

use App\Models\SobreNosModel;
use Framework\Database\Seeder;

/**
 * SobreNosSeeder — inicializa dados associados ao Model SobreNos.
 *
 * Antes de executar, confirme que o Model existe, aponta para a tabela
 * `sobre_noses` e permite os campos usados por meio de $fillable.
 */
class SobreNosSeeder extends Seeder
{
    public function run(): void
    {
        $records = new SobreNosModel();
        $records->create([
            'titulo'     => 'Sobre nós',
            'texto'      => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'imagem'     => 'about-us.jpg',
            'imagem_alt' => 'Sobre nós',
            'ativo'      => 1,
        ]);
    }
}