<?php

namespace Database\Seeders;

use App\Models\ClienteModel;
use App\Models\EnderecoClienteModel;
use Framework\Database\Seeder;
use RuntimeException;

class EnderecoClienteSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = new ClienteModel();
        $enderecosClientes = new EnderecoClienteModel();

        $dados = [
            ['email' => 'gabriel.ferreira@example.com', 'nome' => 'Principal', 'cep' => '58000001', 'logradouro' => 'Avenida Epitácio Pessoa', 'numero' => '100', 'complemento' => 'Apto 101', 'bairro' => 'Torre', 'cidade' => 'João Pessoa', 'estado' => 'PB', 'referencia' => 'Próximo ao supermercado'],
            ['email' => 'beatriz.nascimento@example.com', 'nome' => 'Principal', 'cep' => '58000002', 'logradouro' => 'Avenida Dom Pedro II', 'numero' => '250', 'complemento' => null, 'bairro' => 'Centro', 'cidade' => 'João Pessoa', 'estado' => 'PB', 'referencia' => 'Próximo à praça principal'],
            ['email' => 'thiago.barbosa@example.com', 'nome' => 'Principal', 'cep' => '58000003', 'logradouro' => 'Rua Bancário Sérgio Guerra', 'numero' => '350', 'complemento' => 'Casa', 'bairro' => 'Bancários', 'cidade' => 'João Pessoa', 'estado' => 'PB', 'referencia' => 'Próximo à universidade'],
            ['email' => 'camila.mendes@example.com', 'nome' => 'Principal', 'cep' => '58000004', 'logradouro' => 'Rua Professora Maria Sales', 'numero' => '80', 'complemento' => null, 'bairro' => 'Manaíra', 'cidade' => 'João Pessoa', 'estado' => 'PB', 'referencia' => 'Próximo à praia'],
            ['email' => 'bruno.carvalho@example.com', 'nome' => 'Principal', 'cep' => '58000005', 'logradouro' => 'Avenida Ruy Carneiro', 'numero' => '520', 'complemento' => 'Sala 04', 'bairro' => 'Miramar', 'cidade' => 'João Pessoa', 'estado' => 'PB', 'referencia' => 'Próximo ao shopping'],
            ['email' => 'larissa.araujo@example.com', 'nome' => 'Principal', 'cep' => '58000006', 'logradouro' => 'Rua dos Ipês', 'numero' => '120', 'complemento' => null, 'bairro' => 'Altiplano', 'cidade' => 'João Pessoa', 'estado' => 'PB', 'referencia' => 'Casa de esquina'],
            ['email' => 'matheus.gomes@example.com', 'nome' => 'Principal', 'cep' => '58000007', 'logradouro' => 'Rua Empresário João Rodrigues Alves', 'numero' => '410', 'complemento' => 'Apto 202', 'bairro' => 'Brisamar', 'cidade' => 'João Pessoa', 'estado' => 'PB', 'referencia' => 'Próximo à avenida principal'],
            ['email' => 'isabela.ramos@example.com', 'nome' => 'Principal', 'cep' => '58000008', 'logradouro' => 'Rua Antônio Lira', 'numero' => '75', 'complemento' => null, 'bairro' => 'Tambaú', 'cidade' => 'João Pessoa', 'estado' => 'PB', 'referencia' => 'Próximo à orla'],
            ['email' => 'andre.monteiro@example.com', 'nome' => 'Principal', 'cep' => '58000009', 'logradouro' => 'Rua José Américo de Almeida', 'numero' => '190', 'complemento' => 'Casa', 'bairro' => 'Castelo Branco', 'cidade' => 'João Pessoa', 'estado' => 'PB', 'referencia' => 'Próximo ao parque'],
            ['email' => 'mariana.freitas@example.com', 'nome' => 'Principal', 'cep' => '58000010', 'logradouro' => 'Avenida Hilton Souto Maior', 'numero' => '630', 'complemento' => null, 'bairro' => 'Mangabeira', 'cidade' => 'João Pessoa', 'estado' => 'PB', 'referencia' => 'Próximo ao mercado'],
        ];

        foreach ($dados as $endereco) {
            $cliente = $clientes->findBy('email', $endereco['email']);
            if ($cliente === false) {
                throw new RuntimeException(
                    "Cliente '{$endereco['email']}' não encontrado. Execute ClienteSeeder antes de EnderecoClienteSeeder."
                );
            }

            $clienteId = (int) $cliente->id;
            $nome = $endereco['nome'];
            unset($endereco['email'], $endereco['nome']);

            $enderecosClientes->firstOrCreate(
                [
                    'cliente_id' => $clienteId,
                    'nome' => $nome,
                ],
                [
                    ...$endereco,
                    'principal' => 1,
                ]
            );
        }
    }
}
