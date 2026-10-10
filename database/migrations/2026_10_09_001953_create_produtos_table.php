<?php

use Framework\Database\Migration;
use Framework\Database\Schema;
use Framework\Database\Blueprint;

/**
 * Migration — Create Produtos Table
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();

            // Relacionamento
            $table->foreignId('categoria_id')
                ->nullable()
                ->constrained('categorias')
                ->onDelete('SET NULL');

            // Identificação
            $table->string('nome', 200);
            $table->string('slug', 200);
            $table->string('codigo', 100)->nullable();          // SKU / código interno

            // Conteúdo
            $table->text('descricao')->nullable();
            $table->text('ingredientes')->nullable();
            $table->string('imagem_url', 255)->nullable();

            // Preço base (quando o produto tem medidas, o preço vem de produto_medidas)
            $table->decimal('preco', 10, 2);
            $table->decimal('preco_promocional', 10, 2)->nullable();

            // Operação
            $table->unsignedSmallInteger('tempo_preparo')->nullable();   // minutos
            $table->boolean('controla_estoque')->default(0);
            $table->unsignedSmallInteger('estoque')->default(0);

            // Exibição
            $table->boolean('ativo')->default(1);
            $table->boolean('destaque')->default(0);
            $table->unsignedSmallInteger('ordem_exibicao')->default(0);

            $table->softDeletes();
            $table->timestamps();

            // Índices
            $table->unique('nome', 'uniq_produtos_nome');
            $table->unique('slug', 'uniq_produtos_slug');
            $table->unique('codigo', 'uniq_produtos_codigo');
            $table->index('ativo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
