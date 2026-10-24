<?php

namespace Database\Seeders;

use App\Models\ExpedienteModel;
use Framework\Database\Seeder;

/**
 * ExpedienteSeeder — inicializa dados associados ao Model Expediente.
 *
 * Antes de executar, confirme que o Model existe, aponta para a tabela
 * `expedientes` e permite os campos usados por meio de $fillable.
 */
class ExpedienteSeeder extends Seeder
{
    public function run(): void
    {
        // 0 = domingo ... 6 = sábado
        $dias = [
            0 => 'Domingo',
            1 => 'Segunda-feira',
            2 => 'Terça-feira',
            3 => 'Quarta-feira',
            4 => 'Quinta-feira',
            5 => 'Sexta-feira',
            6 => 'Sábado',
        ];

        $model = new ExpedienteModel();

        foreach ($dias as $diaSemana => $descricao) {
            $model->firstOrCreate(
                ['dia_semana' => $diaSemana],
                [
                    'dia_descricao' => $descricao,
                    'aberto'        => 1,
                    'abertura'      => '18:00:00',
                    'fechamento'    => '23:00:00',
                    'pausa_inicio'  => null,
                    'pausa_fim'     => null,
                    'ativo'         => 1,
                ]
            );
        }
    }
}
