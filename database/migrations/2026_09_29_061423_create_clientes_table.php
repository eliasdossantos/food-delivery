<?php

use Framework\Database\Migration;
use Framework\Database\Schema;
use Framework\Database\Blueprint;

/**
 * Migration — Create Clientes Table
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {

            // Identidade
            $table->id();
            $table->string('nome', 250);
            $table->string('email', 180)->nullable();
            $table->string('celular', 20);

            // Dados pessoais
            $table->string('cpf', 20)->nullable();
            $table->date('data_nascimento')->nullable();

            // Autenticação
            $table->string('password_cliente', 255)->nullable();
            $table->dateTime('email_verificado_em')->nullable();
            $table->dateTime('ultimo_login_em')->nullable();
            $table->string('lembrar_cliente_token', 150)->nullable();
            $table->dateTime('token_cliente_lembrar_expira_em')->nullable();

            // Perfil
            $table->string('avatar', 255)->nullable();
            $table->boolean('ativo')->default(1);

            // Auditoria
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->unique('email', 'uniq_clientes_email');
            $table->unique('cpf', 'uniq_clientes_cpf');
            $table->unique('celular', 'uniq_clientes_celular');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
