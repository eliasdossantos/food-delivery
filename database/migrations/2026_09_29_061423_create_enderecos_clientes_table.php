<?php

use Framework\Database\Migration;
use Framework\Database\Schema;
use Framework\Database\Blueprint;

/**
 * Migration — Create Enderecos Clientes Table
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('enderecos_clientes', function (Blueprint $table) {

            $table->id();

            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('CASCADE');

            $table->string('nome', 200)->default('Principal');

            $table->string('cep', 20);
            $table->string('logradouro', 200);
            $table->string('numero', 20);
            $table->string('complemento', 200)->nullable();
            $table->string('bairro', 200);
            $table->string('cidade', 200);
            $table->string('estado', 2);

            $table->string('referencia', 200)->nullable();

            $table->boolean('principal')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index('cliente_id');
            $table->index('cep');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enderecos_clientes');
    }
};
