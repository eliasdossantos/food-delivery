<?php

namespace Database\Seeders;

use App\Models\EntregadorModel;
use Framework\Database\Seeder;

/**
 * EntregadorSeeder — inicializa dados da tabela entregadores.
 */
class EntregadorSeeder extends Seeder
{
    public function run(): void
    {
        $records = new EntregadorModel();

        $entregadores = [
            // 1 — Fixo, moto, aprovado, tarde e noite
            [
                'tipo_vinculo' => 'fixo',

                'nome' => 'Carlos Eduardo Silva',
                'email' => 'carlos.entregador@example.com',
                'celular' => '(11) 98765-4001',

                'cpf' => '123.456.789-01',
                'documento_identidade' => '12.345.678-9',
                'data_nascimento' => '1990-05-12',

                'cep' => '01001-000',
                'logradouro' => 'Praça da Sé',
                'numero' => '100',
                'bairro' => 'Sé',
                'cidade' => 'São Paulo',
                'estado' => 'SP',

                'password_entregador' => password_hash('Entregador@123', PASSWORD_DEFAULT),
                'email_verificado_em' => '2026-01-10 10:00:00',
                'ultimo_login_em' => null,
                'lembrar_entregador_token' => null,
                'token_entregador_lembrar_expira_em' => null,

                'avatar' => null,

                'numero_cnh' => '01234567890',
                'categoria_cnh' => 'A',
                'cnh_validade' => '2028-05-12',

                'tipo_veiculo' => 'moto',
                'marca_veiculo' => 'Honda',
                'modelo_veiculo' => 'CG 160',
                'placa_veiculo' => 'ABC-1D23',
                'cor_veiculo' => 'Preta',

                'tipo_remuneracao' => 'por_entrega',
                'valor_remuneracao' => 6.00,
                'chave_pix' => 'carlos.entregador@example.com',

                'ativo' => 1,
                'disponivel' => 1,

                'turno_manha' => 0,
                'turno_tarde' => 1,
                'turno_noite' => 1,

                'status_aprovacao' => 'aprovado',
                'motivo_reprovacao' => null,
                'aprovado_por' => 1,
                'aprovado_em' => '2026-01-10 11:00:00',

                'observacoes' => 'Entregador fixo da região central.',
            ],

            // 2 — Fixo, moto, aprovado, manhã e tarde
            [
                'tipo_vinculo' => 'fixo',

                'nome' => 'Mariana Oliveira Santos',
                'email' => 'mariana.entregador@example.com',
                'celular' => '(11) 98765-4002',

                'cpf' => '234.567.890-12',
                'documento_identidade' => '23.456.789-0',
                'data_nascimento' => '1995-08-21',

                'cep' => '01310-100',
                'logradouro' => 'Avenida Paulista',
                'numero' => '1500',
                'bairro' => 'Bela Vista',
                'cidade' => 'São Paulo',
                'estado' => 'SP',

                'password_entregador' => password_hash('Entregador@123', PASSWORD_DEFAULT),
                'email_verificado_em' => '2026-02-01 09:00:00',
                'ultimo_login_em' => null,
                'lembrar_entregador_token' => null,
                'token_entregador_lembrar_expira_em' => null,

                'avatar' => null,

                'numero_cnh' => '12345678901',
                'categoria_cnh' => 'AB',
                'cnh_validade' => '2029-08-21',

                'tipo_veiculo' => 'moto',
                'marca_veiculo' => 'Yamaha',
                'modelo_veiculo' => 'Factor 150',
                'placa_veiculo' => 'DEF-2G34',
                'cor_veiculo' => 'Azul',

                'tipo_remuneracao' => 'por_km',
                'valor_remuneracao' => 1.80,
                'chave_pix' => '234.567.890-12',

                'ativo' => 1,
                'disponivel' => 1,

                'turno_manha' => 1,
                'turno_tarde' => 1,
                'turno_noite' => 0,

                'status_aprovacao' => 'aprovado',
                'motivo_reprovacao' => null,
                'aprovado_por' => 1,
                'aprovado_em' => '2026-02-01 10:00:00',

                'observacoes' => null,
            ],

            // 3 — Freelancer, cadastro parcial (só registro de segurança), pendente
            [
                'tipo_vinculo' => 'freelancer',

                'nome' => 'Rafael Costa Almeida',
                'email' => null,
                'celular' => '(11) 98765-4003',

                'cpf' => null,
                'documento_identidade' => null,
                'data_nascimento' => null,

                'cep' => null,
                'logradouro' => null,
                'numero' => null,
                'bairro' => null,
                'cidade' => null,
                'estado' => null,

                'password_entregador' => null,
                'email_verificado_em' => null,
                'ultimo_login_em' => null,
                'lembrar_entregador_token' => null,
                'token_entregador_lembrar_expira_em' => null,

                'avatar' => null,

                'numero_cnh' => null,
                'categoria_cnh' => null,
                'cnh_validade' => null,

                'tipo_veiculo' => 'carro',
                'marca_veiculo' => 'Fiat',
                'modelo_veiculo' => 'Uno',
                'placa_veiculo' => 'GHI-3J45',
                'cor_veiculo' => 'Branca',

                'tipo_remuneracao' => null,
                'valor_remuneracao' => null,
                'chave_pix' => null,

                'ativo' => 1,
                'disponivel' => 0,

                'turno_manha' => 0,
                'turno_tarde' => 0,
                'turno_noite' => 0,

                'status_aprovacao' => 'pendente',
                'motivo_reprovacao' => null,
                'aprovado_por' => null,
                'aprovado_em' => null,

                'observacoes' => 'Freelancer registrado por segurança, aguardando documentos.',
            ],

            // 4 — Fixo, moto, reprovado
            [
                'tipo_vinculo' => 'fixo',

                'nome' => 'Juliana Ferreira Lima',
                'email' => 'juliana.entregador@example.com',
                'celular' => '(11) 98765-4004',

                'cpf' => '456.789.012-34',
                'documento_identidade' => '45.678.901-2',
                'data_nascimento' => '1993-02-17',

                'cep' => '04001-000',
                'logradouro' => 'Rua Domingos de Morais',
                'numero' => '800',
                'bairro' => 'Vila Mariana',
                'cidade' => 'São Paulo',
                'estado' => 'SP',

                'password_entregador' => password_hash('Entregador@123', PASSWORD_DEFAULT),
                'email_verificado_em' => '2026-03-05 14:00:00',
                'ultimo_login_em' => null,
                'lembrar_entregador_token' => null,
                'token_entregador_lembrar_expira_em' => null,

                'avatar' => null,

                'numero_cnh' => '34567890123',
                'categoria_cnh' => 'A',
                'cnh_validade' => '2026-01-17',

                'tipo_veiculo' => 'moto',
                'marca_veiculo' => 'Honda',
                'modelo_veiculo' => 'Biz 125',
                'placa_veiculo' => 'JKL-4M56',
                'cor_veiculo' => 'Vermelha',

                'tipo_remuneracao' => null,
                'valor_remuneracao' => null,
                'chave_pix' => null,

                'ativo' => 1,
                'disponivel' => 0,

                'turno_manha' => 0,
                'turno_tarde' => 0,
                'turno_noite' => 0,

                'status_aprovacao' => 'reprovado',
                'motivo_reprovacao' => 'CNH vencida.',
                'aprovado_por' => 1,
                'aprovado_em' => null,

                'observacoes' => null,
            ],

            // 5 — Freelancer, bicicleta, aprovado, noite
            [
                'tipo_vinculo' => 'freelancer',

                'nome' => 'Pedro Henrique Souza',
                'email' => 'pedro.entregador@example.com',
                'celular' => '(11) 98765-4005',

                'cpf' => '567.890.123-45',
                'documento_identidade' => '56.789.012-3',
                'data_nascimento' => '1998-07-09',

                'cep' => '05001-000',
                'logradouro' => 'Rua Heitor Penteado',
                'numero' => '400',
                'bairro' => 'Pinheiros',
                'cidade' => 'São Paulo',
                'estado' => 'SP',

                'password_entregador' => password_hash('Entregador@123', PASSWORD_DEFAULT),
                'email_verificado_em' => '2026-04-12 08:00:00',
                'ultimo_login_em' => null,
                'lembrar_entregador_token' => null,
                'token_entregador_lembrar_expira_em' => null,

                'avatar' => null,

                'numero_cnh' => null,
                'categoria_cnh' => null,
                'cnh_validade' => null,

                'tipo_veiculo' => 'bicicleta',
                'marca_veiculo' => null,
                'modelo_veiculo' => null,
                'placa_veiculo' => null,
                'cor_veiculo' => 'Preta',

                'tipo_remuneracao' => 'por_entrega',
                'valor_remuneracao' => 5.00,
                'chave_pix' => '(11) 98765-4005',

                'ativo' => 1,
                'disponivel' => 1,

                'turno_manha' => 0,
                'turno_tarde' => 0,
                'turno_noite' => 1,

                'status_aprovacao' => 'aprovado',
                'motivo_reprovacao' => null,
                'aprovado_por' => 1,
                'aprovado_em' => '2026-04-12 09:00:00',

                'observacoes' => null,
            ],

            // 6 — Fixo, moto, aprovado porém inativo
            [
                'tipo_vinculo' => 'fixo',

                'nome' => 'Fernanda Martins Rocha',
                'email' => 'fernanda.entregador@example.com',
                'celular' => '(11) 98765-4006',

                'cpf' => '678.901.234-56',
                'documento_identidade' => '67.890.123-4',
                'data_nascimento' => '1992-12-28',

                'cep' => '03001-000',
                'logradouro' => 'Rua do Gasômetro',
                'numero' => '300',
                'bairro' => 'Brás',
                'cidade' => 'São Paulo',
                'estado' => 'SP',

                'password_entregador' => password_hash('Entregador@123', PASSWORD_DEFAULT),
                'email_verificado_em' => null,
                'ultimo_login_em' => null,
                'lembrar_entregador_token' => null,
                'token_entregador_lembrar_expira_em' => null,

                'avatar' => null,

                'numero_cnh' => '45678901234',
                'categoria_cnh' => 'AB',
                'cnh_validade' => '2029-12-28',

                'tipo_veiculo' => 'moto',
                'marca_veiculo' => 'Honda',
                'modelo_veiculo' => 'NXR 160',
                'placa_veiculo' => 'MNO-5P67',
                'cor_veiculo' => 'Cinza',

                'tipo_remuneracao' => 'fixo',
                'valor_remuneracao' => 1800.00,
                'chave_pix' => '678.901.234-56',

                'ativo' => 0,
                'disponivel' => 0,

                'turno_manha' => 1,
                'turno_tarde' => 0,
                'turno_noite' => 0,

                'status_aprovacao' => 'aprovado',
                'motivo_reprovacao' => null,
                'aprovado_por' => 1,
                'aprovado_em' => '2026-05-01 12:00:00',

                'observacoes' => 'Desativada temporariamente.',
            ],
        ];

        foreach ($entregadores as $entregador) {
            $records->create($entregador);
        }
    }
}
