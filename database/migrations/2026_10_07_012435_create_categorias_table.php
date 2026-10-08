<?php

use Framework\Database\Migration;
use Framework\Database\Schema;
use Framework\Database\Blueprint;

/**
 * Migration — Create Categorias Table
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();

            $table->string('nome', 200);
            $table->string('slug', 200);
            $table->string('descricao', 240)->nullable();
            $table->string('imagem_url', 250)->nullable();
            $table->string('icone', 200)->nullable();

            // Controle de acesso
            $table->boolean('ativo')->default(1);
            $table->boolean('destaque')->default(0);
            $table->unsignedInteger('ordem_exibicao')->default(0);

            // Auditoria
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->unique('nome', 'uniq_nome');
            $table->unique('slug', 'uniq_slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
