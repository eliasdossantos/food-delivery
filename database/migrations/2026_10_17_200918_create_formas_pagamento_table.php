<?php

use Framework\Database\Migration;
use Framework\Database\Schema;
use Framework\Database\Blueprint;

/**
 * Migration — Create Formas Pagamento Table
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('formas_pagamento', function (Blueprint $table) {
            $table->id();

            // Identidade
            $table->string('nome', 250);

            // Controle de acesso
            $table->boolean('ativo')->default(1);

            // Auditoria
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->unique('nome', 'uniq_nome');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formas_pagamento');
    }
};