<?php

use Framework\Database\Migration;
use Framework\Database\Schema;
use Framework\Database\Blueprint;

/**
 * Migration — Create Medidas Table
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('medidas', function (Blueprint $table) {
            $table->id();

            // Identificação
            $table->string('nome', 200);
            $table->text('descricao')->nullable();

            // Medida física
            // Ex.: 20 cm, 30 cm, 500 ml, 1 kg
            $table->string('unidade', 20)->nullable();

            // Imagem
            $table->string('imagem_url', 250)->nullable();

            // Controle
            $table->boolean('ativo')->default(1);
            $table->unsignedInteger('ordem_exibicao')->default(0);

            // Auditoria
            $table->timestamps();

            // Índices
            $table->unique('nome', 'uniq_medidas_nome');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medidas');
    }
};
