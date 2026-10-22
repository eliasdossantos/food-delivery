<?php

use Framework\Database\Migration;
use Framework\Database\Schema;
use Framework\Database\Blueprint;

/**
 * Migration — Create Entregadores Table
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('entregadores', function (Blueprint $table) {

            // Chave
            $table->id();

            // Vínculo (fixo ou freelancer)
            $table->string('tipo_vinculo', 30)->nullable();   // fixo | freelancer

            // Identidade
            $table->string('nome', 250);
            $table->string('email', 180)->nullable();
            $table->string('celular', 20)->nullable();

            // Dados pessoais
            $table->string('cpf', 20)->nullable();
            $table->string('documento_identidade', 40)->nullable();
            $table->date('data_nascimento')->nullable();

            // Endereço
            $table->string('cep', 9)->nullable();
            $table->string('logradouro', 250)->nullable();
            $table->string('numero', 30)->nullable();
            $table->string('bairro', 200)->nullable();
            $table->string('cidade', 200)->nullable();
            $table->string('estado', 2)->nullable();

            // Autenticação
            $table->string('password_entregador', 255)->nullable();
            $table->dateTime('email_verificado_em')->nullable();
            $table->dateTime('ultimo_login_em')->nullable();
            $table->string('lembrar_entregador_token', 150)->nullable();
            $table->dateTime('token_entregador_lembrar_expira_em')->nullable();

            // Perfil
            $table->string('avatar', 254)->nullable();

            // CNH
            $table->string('numero_cnh', 40)->nullable();
            $table->string('categoria_cnh', 10)->nullable();
            $table->date('cnh_validade')->nullable();

            // Veículo utilizado nas entregas
            $table->string('tipo_veiculo', 40)->nullable();
            $table->string('marca_veiculo', 150)->nullable();
            $table->string('modelo_veiculo', 150)->nullable();
            $table->string('placa_veiculo', 20)->nullable();
            $table->string('cor_veiculo', 70)->nullable();

            // Remuneração
            $table->string('tipo_remuneracao', 30)->nullable();   // por_entrega | por_km | fixo
            $table->decimal('valor_remuneracao', 10, 2)->nullable();
            $table->string('chave_pix', 200)->nullable();

            // Operação
            $table->boolean('ativo')->default(1);
            $table->boolean('disponivel')->default(0);

            // Turnos em que aceita trabalhar
            $table->boolean('turno_manha')->default(0);
            $table->boolean('turno_tarde')->default(0);
            $table->boolean('turno_noite')->default(0);

            // Aprovação
            $table->string('status_aprovacao', 30)->default('pendente');  // pendente | aprovado | reprovado | bloqueado
            $table->string('motivo_reprovacao', 250)->nullable();
            $table->foreignId('aprovado_por')->nullable()->constrained('usuarios')->onDelete('SET NULL');
            $table->dateTime('aprovado_em')->nullable();

            // Admin
            $table->text('observacoes')->nullable();

            // Auditoria
            $table->timestamps();

            // Índices únicos (vários NULL são permitidos no MySQL)
            $table->unique('email', 'uniq_entregadores_email');
            $table->unique('cpf', 'uniq_entregadores_cpf');
            $table->unique('celular', 'uniq_entregadores_celular');
            $table->unique('numero_cnh', 'uniq_entregadores_cnh');

            // Placa: index simples (a mesma moto pode ter mais de um entregador)
            $table->index('placa_veiculo', 'idx_entregadores_placa');

            // Consultas frequentes
            $table->index('ativo', 'idx_entregadores_ativo');
            $table->index('status_aprovacao', 'idx_entregadores_status_aprovacao');
            $table->index('tipo_vinculo', 'idx_entregadores_tipo_vinculo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entregadores');
    }
};
