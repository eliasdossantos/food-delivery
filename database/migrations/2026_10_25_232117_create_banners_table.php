<?php

use Framework\Database\Migration;
use Framework\Database\Schema;
use Framework\Database\Blueprint;

/**
 * Migration — Create Banners Table
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            // Conteúdo do slide
            $table->string('titulo', 150);                         // .item_name
            $table->string('descricao', 300)->nullable();          // .item_info
            $table->string('imagem', 254)->nullable();             // .item_img

            // Badge: "20%" + "OFF" (ambos opcionais; sem destaque, o badge não é exibido)
            $table->string('badge_destaque', 20)->nullable();      // .badge_btext
            $table->string('badge_rotulo', 20)->nullable();        // .badge_stext

            // Botão (opcional; sem texto, o botão não é exibido)
            $table->string('botao_texto', 100)->nullable();        // .item_link
            $table->string('botao_link', 500)->nullable();

            // Exibição
            $table->integer('ordem_exibicao')->default(0);
            $table->boolean('ativo')->default(1);

            // Vigência da promoção
            $table->datetime('data_inicio')->nullable();
            $table->datetime('data_fim')->nullable();

            // Auditoria
            $table->timestamps();

            // Consulta da home: ativos dentro do período, em ordem
            $table->index(['ativo', 'data_inicio', 'data_fim', 'ordem_exibicao'], 'idx_banners_vigencia');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};