<?php

use Framework\Database\Migration;
use Framework\Database\Schema;
use Framework\Database\Blueprint;

/**
 * Migration — Create Extras Table
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('extras', function (Blueprint $table) {
            $table->id();

            $table->string('nome', 200);
            $table->string('slug', 200);
            $table->text('descricao')->nullable();
            $table->string('imagem_url', 250)->nullable();

            // Valor adicional
            $table->decimal('preco', 10, 2)->default(0);

            // Controle de acesso
            $table->boolean('ativo')->default(1);
            $table->boolean('destaque')->default(0);
            $table->unsignedInteger('ordem_exibicao')->default(0);

            // Auditoria
            $table->timestamps();

            // Índices
            $table->unique('nome', 'uniq_nome');
            $table->unique('slug', 'uniq_slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extras');
    }
};
