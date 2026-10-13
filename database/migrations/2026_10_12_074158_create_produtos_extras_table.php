<?php

use Framework\Database\Migration;
use Framework\Database\Schema;
use Framework\Database\Blueprint;

/**
 * Migration — Create Produtos Extras Table
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('produto_extras', function (Blueprint $table) {
            $table->id();

            $table->foreignId('produto_id')
                ->constrained('produtos')
                ->onDelete('CASCADE');

            $table->foreignId('extra_id')
                ->constrained('extras')
                ->onDelete('CASCADE');

            $table->timestamps();

            // Impede repetir o mesmo vínculo
            $table->unique(['produto_id', 'extra_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produto_extras');
    }
};
