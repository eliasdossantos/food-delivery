<?php

namespace Database\Seeders;

use App\Models\PerfilModel;
use App\Models\UsuarioModel;
use Framework\Database\Seeder;
use RuntimeException;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $perfis = new PerfilModel();
        $usuarios = new UsuarioModel();
        $verificadoEm = date('Y-m-d H:i:s');

        $dados = [
            [
                'nome' => 'Super Administrador',
                'perfil' => 'Super Administrador',
                'cpf' => '011.011.011-01',
                'celular' => '(83) 9900009-001001',
                'email' => 'super.admin@example.com',
                'password' => 'superadmin123',
                'cep' => '58000001',
                'logradouro' => 'Avenida Epitácio Pessoa exemplo',
                'numero' => '1001',
                'complemento' => 'Sala 1011',
                'bairro' => 'Torre Ipes',
                'cidade' => 'João Pessoa',
                'estado' => 'PB',
                'referencia' => 'Próximo ao supermercado exemplo',
                'ativo' => 1,
            ],
            [
                'nome' => 'Administrador',
                'perfil' => 'Administrador',
                'cpf' => '111.111.111-01',
                'celular' => '(83) 99000-0001',
                'email' => 'admin@example.com',
                'password' => 'admin123',
                'cep' => '58000001',
                'logradouro' => 'Avenida Epitácio Pessoa',
                'numero' => '100',
                'complemento' => 'Sala 101',
                'bairro' => 'Torre',
                'cidade' => 'João Pessoa',
                'estado' => 'PB',
                'referencia' => 'Próximo ao supermercado',
                'ativo' => 1,
            ],
            [
                'nome' => 'João da Silva',
                'perfil' => 'Gestor',
                'cpf' => '111.111.111-02',
                'celular' => '(83) 99000-0002',
                'email' => 'joao.silva@example.com',
                'password' => '123456',
                'cep' => '58000002',
                'logradouro' => 'Avenida Dom Pedro II',
                'numero' => '250',
                'complemento' => null,
                'bairro' => 'Centro',
                'cidade' => 'João Pessoa',
                'estado' => 'PB',
                'referencia' => 'Próximo à praça principal',
                'ativo' => 1,
            ],
            [
                'nome' => 'Maria Oliveira',
                'perfil' => 'Atendente',
                'cpf' => '111.111.111-03',
                'celular' => '(83) 99000-0003',
                'email' => 'maria.oliveira@example.com',
                'password' => '123456',
                'cep' => '58000003',
                'logradouro' => 'Rua Bancário Sérgio Guerra',
                'numero' => '350',
                'complemento' => 'Apto 202',
                'bairro' => 'Bancários',
                'cidade' => 'João Pessoa',
                'estado' => 'PB',
                'referencia' => 'Próximo à universidade',
                'ativo' => 1,
            ],
            [
                'nome' => 'Carlos Santos',
                'perfil' => 'Cozinheiro',
                'cpf' => '111.111.111-04',
                'celular' => '(83) 99000-0004',
                'email' => 'carlos.santos@example.com',
                'password' => '123456',
                'cep' => '58000004',
                'logradouro' => 'Rua Professora Maria Sales',
                'numero' => '80',
                'complemento' => null,
                'bairro' => 'Manaíra',
                'cidade' => 'João Pessoa',
                'estado' => 'PB',
                'referencia' => 'Próximo à praia',
                'ativo' => 1,
            ],
            [
                'nome' => 'Ana Souza',
                'perfil' => 'Atendente',
                'cpf' => '111.111.111-05',
                'celular' => '(83) 99000-0005',
                'email' => 'ana.souza@example.com',
                'password' => '123456',
                'cep' => '58000005',
                'logradouro' => 'Avenida Ruy Carneiro',
                'numero' => '520',
                'complemento' => 'Sala 04',
                'bairro' => 'Miramar',
                'cidade' => 'João Pessoa',
                'estado' => 'PB',
                'referencia' => 'Próximo ao shopping',
                'ativo' => 1,
            ],
            [
                'nome' => 'Pedro Almeida',
                'perfil' => 'Entregador',
                'cpf' => '111.111.111-06',
                'celular' => '(83) 99000-0006',
                'email' => 'pedro.almeida@example.com',
                'password' => '123456',
                'cep' => '58000006',
                'logradouro' => 'Rua dos Ipês',
                'numero' => '120',
                'complemento' => 'Casa',
                'bairro' => 'Altiplano',
                'cidade' => 'João Pessoa',
                'estado' => 'PB',
                'referencia' => 'Casa de esquina',
                'ativo' => 1,
            ],
            [
                'nome' => 'Juliana Costa',
                'perfil' => 'Caixa',
                'cpf' => '111.111.111-07',
                'celular' => '(83) 99000-0007',
                'email' => 'juliana.oliveira@example.com',
                'password' => '123456',
                'cep' => '58000007',
                'logradouro' => 'Rua Empresário João Rodrigues Alves',
                'numero' => '410',
                'complemento' => 'Apto 302',
                'bairro' => 'Brisamar',
                'cidade' => 'João Pessoa',
                'estado' => 'PB',
                'referencia' => 'Próximo à avenida principal',
                'ativo' => 1,
            ],
            [
                'nome' => 'Lucas Rodrigues',
                'perfil' => 'Atendente',
                'cpf' => '111.111.111-08',
                'celular' => '(83) 99000-0008',
                'email' => 'lucas.rodrigues@example.com',
                'password' => '123456',
                'cep' => '58000008',
                'logradouro' => 'Rua Antônio Lira',
                'numero' => '75',
                'complemento' => null,
                'bairro' => 'Tambaú',
                'cidade' => 'João Pessoa',
                'estado' => 'PB',
                'referencia' => 'Próximo à orla',
                'ativo' => 1,
            ],
            [
                'nome' => 'Fernanda Lima',
                'perfil' => 'Gestor',
                'cpf' => '111.111.111-09',
                'celular' => '(83) 99000-0009',
                'email' => 'fernanda.lima@example.com',
                'password' => '123456',
                'cep' => '58000009',
                'logradouro' => 'Rua José Américo de Almeida',
                'numero' => '190',
                'complemento' => 'Casa',
                'bairro' => 'Castelo Branco',
                'cidade' => 'João Pessoa',
                'estado' => 'PB',
                'referencia' => 'Próximo ao parque',
                'ativo' => 1,
            ],
            [
                'nome' => 'Rafael Martins',
                'perfil' => 'Entregador',
                'cpf' => '111.111.111-10',
                'celular' => '(83) 99000-0010',
                'email' => 'rafael.martins@example.com',
                'password' => '123456',
                'cep' => '58000010',
                'logradouro' => 'Avenida Hilton Souto Maior',
                'numero' => '630',
                'complemento' => null,
                'bairro' => 'Mangabeira',
                'cidade' => 'João Pessoa',
                'estado' => 'PB',
                'referencia' => 'Próximo ao mercado',
                'ativo' => 1,
            ],
        ];

        foreach ($dados as $dadosUsuario) {
            $perfil = $perfis
                ->where('nome', $dadosUsuario['perfil'])
                ->where('ativo', 1)
                ->first();

            if ($perfil === false) {
                throw new RuntimeException(
                    "Perfil ativo '{$dadosUsuario['perfil']}' não encontrado. Execute PerfilSeeder antes de UsuarioSeeder."
                );
            }

            $senhaHash = password_hash($dadosUsuario['password'], PASSWORD_DEFAULT);
            if ($senhaHash === false) {
                throw new RuntimeException("Não foi possível gerar o hash da senha de {$dadosUsuario['email']}.");
            }

            $email = $dadosUsuario['email'];
            $values = [
                'perfil_id' => (int) $perfil->id,
                'nome' => $dadosUsuario['nome'],
                'cpf' => $dadosUsuario['cpf'],
                'celular' => $dadosUsuario['celular'],
                'password' => $senhaHash,
                'cep' => $dadosUsuario['cep'],
                'logradouro' => $dadosUsuario['logradouro'],
                'numero' => $dadosUsuario['numero'],
                'complemento' => $dadosUsuario['complemento'],
                'bairro' => $dadosUsuario['bairro'],
                'cidade' => $dadosUsuario['cidade'],
                'estado' => $dadosUsuario['estado'],
                'referencia' => $dadosUsuario['referencia'],
                'email_verificado_em' => $verificadoEm,
                'ativo' => $dadosUsuario['ativo'],
            ];

            $usuarios->firstOrCreate(['email' => $email], $values);
        }
    }
}
