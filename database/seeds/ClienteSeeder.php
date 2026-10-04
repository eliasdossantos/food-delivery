<?php

namespace Database\Seeders;

use App\Models\ClienteModel;
use Framework\Database\Seeder;
use RuntimeException;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = new ClienteModel();
        $verificadoEm = date('Y-m-d H:i:s');

        $dados = [
            ['nome' => 'Gabriel Ferreira', 'email' => 'gabriel.ferreira@example.com', 'celular' => '(83) 99000-0001', 'cpf' => '111.111.111-01', 'data_nascimento' => '1995-03-15', 'password_cliente' => '123456'],
            ['nome' => 'Beatriz Nascimento', 'email' => 'beatriz.nascimento@example.com', 'celular' => '(83) 99000-0002', 'cpf' => '111.111.111-02', 'data_nascimento' => '1998-07-22', 'password_cliente' => '123456'],
            ['nome' => 'Thiago Barbosa', 'email' => 'thiago.barbosa@example.com', 'celular' => '(83) 99000-0003', 'cpf' => '111.111.111-03', 'data_nascimento' => '1992-11-08', 'password_cliente' => '123456'],
            ['nome' => 'Camila Mendes', 'email' => 'camila.mendes@example.com', 'celular' => '(83) 99000-0004', 'cpf' => '111.111.111-04', 'data_nascimento' => '1997-01-19', 'password_cliente' => '123456'],
            ['nome' => 'Bruno Carvalho', 'email' => 'bruno.carvalho@example.com', 'celular' => '(83) 99000-0005', 'cpf' => '111.111.111-05', 'data_nascimento' => '1990-05-27', 'password_cliente' => '123456'],
            ['nome' => 'Larissa Araújo', 'email' => 'larissa.araujo@example.com', 'celular' => '(83) 99000-0006', 'cpf' => '111.111.111-06', 'data_nascimento' => '2000-09-12', 'password_cliente' => '123456'],
            ['nome' => 'Matheus Gomes', 'email' => 'matheus.gomes@example.com', 'celular' => '(83) 99000-0007', 'cpf' => '111.111.111-07', 'data_nascimento' => '1994-12-03', 'password_cliente' => '123456'],
            ['nome' => 'Isabela Ramos', 'email' => 'isabela.ramos@example.com', 'celular' => '(83) 99000-0008', 'cpf' => '111.111.111-08', 'data_nascimento' => '1999-04-30', 'password_cliente' => '123456'],
            ['nome' => 'André Monteiro', 'email' => 'andre.monteiro@example.com', 'celular' => '(83) 99000-0009', 'cpf' => '111.111.111-09', 'data_nascimento' => '1989-08-17', 'password_cliente' => '123456'],
            ['nome' => 'Mariana Freitas', 'email' => 'mariana.freitas@example.com', 'celular' => '(83) 99000-0010', 'cpf' => '111.111.111-10', 'data_nascimento' => '1996-10-25', 'password_cliente' => '123456'],
        ];

        foreach ($dados as $cliente) {
            $senhaHash = password_hash($cliente['password_cliente'], PASSWORD_DEFAULT);
            if ($senhaHash === false) {
                throw new RuntimeException("Não foi possível gerar o hash da senha de {$cliente['email']}.");
            }

            $cpf = $cliente['cpf'];
            unset($cliente['cpf'], $cliente['password_cliente']);

            $clientes->firstOrCreate(
                ['cpf' => $cpf],
                [
                    ...$cliente,
                    'password_cliente' => $senhaHash,
                    'email_verificado_em' => $verificadoEm,
                    'ativo' => 1,
                ]
            );
        }
    }
}
