<?php

use Framework\Database\Migration;
use Framework\Database\Schema;
use Framework\Database\Blueprint;

/**
 * Migration — Create Bairros Table
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('bairros', function (Blueprint $table) {
            $table->id();

            $table->string('nome', 200);
            $table->string('slug', 200);
            $table->string('cidade', 200)->default('João Pessoa');

            // Valor entrega
            $table->decimal('valor_entrega', 10, 2)->default(0);

            $table->boolean('ativo')->default(1);

            // Um bairro não se repete dentro da mesma cidade
            $table->unique(['cidade', 'nome'], 'uniq_cidade_nome');

            // Slug único por cidade (permite "Centro" em cidades diferentes)
            $table->unique(['cidade', 'slug'], 'uniq_cidade_slug');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bairros');
    }
};
