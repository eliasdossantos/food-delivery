<?php

use Framework\Database\Migration;
use Framework\Database\Schema;
use Framework\Database\Blueprint;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('api_tokens', function (Blueprint $table) {

            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('CASCADE');
            $table->string('hash_token', 64); // SHA-256 hex — nunca o token em texto puro
            $table->string('nome', 200)->default('api');
            $table->dateTime('ultimo_uso_em')->nullable();
            $table->dateTime('expira_em')->nullable();
            $table->timestamps();

            $table->unique('hash_token');
            $table->index('usuario_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_tokens');
    }
};
