<?php

use Framework\Database\Migration;
use Framework\Database\Schema;
use Framework\Database\Blueprint;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            // Identidade
            $table->id();

            // Perfil opcional: NULL = "Sem perfil".
            // Ao excluir o perfil, o usuário permanece e perfil_id vira NULL.
            $table->foreignId('perfil_id')
                ->nullable()
                ->constrained('perfis')
                ->onDelete('SET NULL');

            $table->string('nome', 150);

            // Informações complementares
            $table->string('cpf', 20)->nullable();
            $table->string('celular', 20)->nullable();

            // Autenticação
            $table->string('email', 180);
            $table->string('password', 255);

            //Enderecos
            $table->string('cep', 9)->nullable();
            $table->string('logradouro', 250)->nullable();
            $table->string('numero', 30)->nullable();
            $table->string('complemento', 200)->nullable();
            $table->string('bairro', 200)->nullable();
            $table->string('cidade', 200)->nullable();
            $table->string('estado', 2)->nullable();
            $table->string('referencia', 200)->nullable();

            // Verificação e acesso
            $table->dateTime('email_verificado_em')->nullable();
            $table->dateTime('ultimo_login_em')->nullable();
            $table->string('lembrar_token', 150)->nullable();

            // Controle de acesso
            $table->boolean('ativo')->default(1);

            // Auditoria
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->unique('cpf', 'uniq_cpf');
            $table->unique('celular', 'uniq_celular');
            $table->unique('email', 'uniq_email');
            $table->index('perfil_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
