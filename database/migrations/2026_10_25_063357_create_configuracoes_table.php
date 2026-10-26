<?php

use Framework\Database\Migration;
use Framework\Database\Schema;
use Framework\Database\Blueprint;

/**
 * Migration — Create Configuracoes Table
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('configuracoes', function (Blueprint $table) {

            // Chave
            $table->id();

            // Identidade do estabelecimento
            $table->string('nome_fantasia', 250);
            $table->string('razao_social', 250)->nullable();
            $table->string('cnpj', 25)->nullable();
            $table->text('descricao')->nullable();

            // Identidade visual
            $table->string('logo', 254)->nullable();

            // Contato
            $table->string('email', 180)->nullable();
            $table->string('telefone', 20)->nullable();
            $table->string('whatsapp', 20)->nullable();

            // Endereço
            $table->string('cep', 9)->nullable();
            $table->string('logradouro', 250)->nullable();
            $table->string('numero', 30)->nullable();
            $table->string('complemento', 150)->nullable();
            $table->string('bairro', 200)->nullable();
            $table->string('cidade', 200)->nullable();
            $table->string('estado', 2)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('link_google_maps', 500)->nullable();

            // Redes sociais
            $table->string('instagram', 200)->nullable();
            $table->string('facebook', 200)->nullable();
            $table->string('youtube', 200)->nullable();
            $table->string('twitter', 200)->nullable();

            // Pedidos
            $table->boolean('aceita_entrega')->default(1)->nullable();
            $table->boolean('aceita_retirada')->default(1)->nullable();
            $table->decimal('valor_pedido_minimo', 10, 2)->nullable();
            $table->integer('tempo_medio_entrega_min')->nullable();
            $table->integer('tempo_medio_retirada_min')->nullable();

            // Entrega
            $table->decimal('taxa_entrega_padrao', 10, 2)->nullable();
            $table->decimal('frete_gratis_acima_de', 10, 2)->nullable();
            $table->string('raio_entrega_km', 100)->nullable();

            // Pagamento
            $table->string('chave_pix', 200)->nullable();

            // SEO e rodapé
            $table->string('meta_title', 150)->nullable();
            $table->string('meta_description', 300)->nullable();
            $table->string('texto_rodape', 250)->nullable();

            // Auditoria
            $table->timestamps();

            // Consultas frequentes
            $table->index('nome_fantasia', 'idx_configuracoes_nome_fantasia');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracoes');
    }
};