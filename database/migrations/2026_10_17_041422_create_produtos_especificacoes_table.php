<?php

use Framework\Database\Migration;
use Framework\Database\Schema;
use Framework\Database\Blueprint;

/**
 * Migration — Create Produtos Especificacoes Table
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('produtos_especificacoes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('produto_id')
                ->constrained('produtos')
                ->onDelete('CASCADE');

            $table->foreignId('medida_id')
                ->constrained('medidas')
                ->onDelete('CASCADE');

            $table->decimal('preco', 10, 2)->default(0);

            $table->boolean('customizavel')->default(1);

            $table->timestamps();

            // Impede repetir o mesmo vínculo
            $table->unique(['produto_id', 'medida_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produtos_especificacoes');
    }
};