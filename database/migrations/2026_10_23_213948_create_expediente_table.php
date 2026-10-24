<?php

use Framework\Database\Migration;
use Framework\Database\Schema;
use Framework\Database\Blueprint;

/**
 * Migration — Create Expediente Table
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('expediente', function (Blueprint $table) {
            $table->id();

            $table->string('dia_descricao', 200)->nullable();

            // 0 = domingo ... 6 = sábado
            $table->tinyInteger('dia_semana');

            // Se o estabelecimento é aberto ou não
            $table->boolean('aberto')->default(1)->nullable();

            // Horário de funcionamento (nulos quando fechado)
            $table->time('abertura')->nullable();
            $table->time('fechamento')->nullable();

            // Pausa opcional (ex.: almoço)
            $table->time('pausa_inicio')->nullable();
            $table->time('pausa_fim')->nullable();

            $table->boolean('ativo')->default(1);

            // Um registro por dia da semana
            $table->unique('dia_semana', 'uniq_expediente_dia');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expediente');
    }
};
