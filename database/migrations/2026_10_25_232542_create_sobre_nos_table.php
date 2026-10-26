<?php

use Framework\Database\Migration;
use Framework\Database\Schema;
use Framework\Database\Blueprint;

/**
 * Migration — Create Sobre Nos Table
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('sobre_nos', function (Blueprint $table) {
            $table->id();
            // Conteúdo
            $table->string('titulo', 150);
            $table->text('texto');

            // Imagem
            $table->string('imagem', 254)->nullable();
            $table->string('imagem_alt', 150)->nullable();

            // Exibição
            $table->boolean('ativo')->default(1)->nullable();

            // Auditoria
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sobre_nos');
    }
};