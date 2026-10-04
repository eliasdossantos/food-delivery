<?php

use Framework\Database\Migration;
use Framework\Database\Schema;
use Framework\Database\Blueprint;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('perfis', function (Blueprint $table) {
            $table->id();

            $table->string('nome', 150);
            $table->boolean('ativo')->default(1);

            $table->timestamps();
            $table->softDeletes();

            $table->unique('nome', 'uniq_perfis_nome');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perfis');
    }
};
