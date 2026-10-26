<?php
$configuracao = $configuracao ?? (($configuracoes[0] ?? null));

$valor = static function (string $campo, string $padrao = '') use ($configuracao): string {
    return (string) ($configuracao->{$campo} ?? $padrao);
};

$urlImagem = static function (string $arquivo): string {
    if ($arquivo === '') {
        return '/assets/admin/images/sem-foto/sem-foto.jpg';
    }

    if (preg_match('#^https?://#i', $arquivo) || str_starts_with($arquivo, '/')) {
        return $arquivo;
    }

    return '/uploads/configuracoes/' . rawurlencode($arquivo);
};

/** Campo somente leitura. $exibir sobrescreve o valor mostrado (ex.: Sim/Não). */
$campoTexto = static function (string $campo, string $rotulo, string $col = 'col-md-6', ?string $exibir = null) use ($valor): void {
?>
<div class="form-group <?= e($col) ?>">
    <label for="<?= e($campo) ?>"><?= e($rotulo) ?></label>
    <input type="text" class="form-control" id="<?= e($campo) ?>" value="<?= e($exibir ?? $valor($campo)) ?>" readonly>
</div>
<?php
};

$sim = static fn(string $campo): string => (int) $valor($campo, '0') === 1 ? 'Sim' : 'Não';
?>

<!-- Aqui enviamos para o template principal o título da página -->

<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Configurações') ?> | Admin

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os estilos -->

<?php View::start('styles'); ?>
<style>
.config-preview {
    max-width: 100%;
    max-height: 180px;
    object-fit: cover;
    border-radius: 10px;
    display: block;
}

.config-logo-box {
    border: 2px dashed #ced4da;
    border-radius: 12px;
    background: #f8f9fa;
    min-height: 150px;
    padding: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
<?php View::end(); ?>


<!-- Aqui enviamos para o template principal o conteúdo -->

<?php View::start('content'); ?>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <!-- Cabeçalho -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="card-title mb-1 text-white">
                            <?= e($titulo ?? 'Configurações') ?>
                        </h4>
                        <p class="card-description mb-0 text-white">
                            <?= e($valor('nome_fantasia')) ?>
                        </p>
                    </div>
                    <?php if ($configuracao): ?>
                    <div class="mt-3 mt-md-0">
                        <a href="<?= route('admin.configuracao.edit', ['id' => $configuracao->id]) ?>"
                            class="btn btn-warning btn-sm">
                            <i class="mdi mdi-pencil"></i>
                            Editar
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body">
                <?php if (!$configuracao): ?>
                <div class="alert alert-info mb-0">
                    Nenhuma configuração foi cadastrada ainda.
                </div>
                <?php else: ?>
                <div class="forms-sample">
                    <div class="row">
                        <?php $campoTexto('nome_fantasia', 'Nome fantasia'); ?>
                        <?php $campoTexto('razao_social', 'Razão social'); ?>
                    </div>

                    <!-- Descrição -->
                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <textarea class="form-control" id="descricao" rows="3"
                            readonly><?= e($valor('descricao')) ?></textarea>
                    </div>

                    <div class="row">
                        <?php $campoTexto('cnpj', 'CNPJ'); ?>

                        <!-- Logo -->
                        <div class="form-group col-md-6">
                            <label>Logo</label>
                            <div class="config-logo-box">
                                <img class="config-preview" src="<?= e($urlImagem($valor('logo'))) ?>" alt="Logo"
                                    onerror="this.onerror=null;this.src='/assets/admin/images/sem-foto/sem-foto.jpg';">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <?php $campoTexto('email', 'E-mail', 'col-md-4'); ?>
                        <?php $campoTexto('telefone', 'Telefone', 'col-md-4'); ?>
                        <?php $campoTexto('whatsapp', 'WhatsApp', 'col-md-4'); ?>
                    </div>

                    <div class="row">
                        <?php $campoTexto('cep', 'CEP', 'col-md-3'); ?>
                        <?php $campoTexto('logradouro', 'Logradouro', 'col-md-6'); ?>
                        <?php $campoTexto('numero', 'Número', 'col-md-3'); ?>
                    </div>

                    <div class="row">
                        <?php $campoTexto('complemento', 'Complemento', 'col-md-4'); ?>
                        <?php $campoTexto('bairro', 'Bairro', 'col-md-3'); ?>
                        <?php $campoTexto('cidade', 'Cidade', 'col-md-3'); ?>
                        <?php $campoTexto('estado', 'UF', 'col-md-2'); ?>
                    </div>

                    <div class="row">
                        <?php $campoTexto('latitude', 'Latitude', 'col-md-3'); ?>
                        <?php $campoTexto('longitude', 'Longitude', 'col-md-3'); ?>
                        <?php $campoTexto('link_google_maps', 'Link do Google Maps', 'col-md-6'); ?>
                    </div>

                    <div class="row">
                        <?php $campoTexto('instagram', 'Instagram'); ?>
                        <?php $campoTexto('facebook', 'Facebook'); ?>
                        <?php $campoTexto('youtube', 'YouTube'); ?>
                        <?php $campoTexto('twitter', 'Twitter / X'); ?>
                    </div>

                    <div class="row">
                        <?php $campoTexto('aceita_entrega', 'Aceita entrega', 'col-md-3', $sim('aceita_entrega')); ?>
                        <?php $campoTexto('aceita_retirada', 'Aceita retirada', 'col-md-3', $sim('aceita_retirada')); ?>
                        <?php $campoTexto('valor_pedido_minimo', 'Valor do pedido mínimo (R$)', 'col-md-6', moedaBR($valor('valor_pedido_minimo'))); ?>
                    </div>

                    <div class="row">
                        <?php $campoTexto('tempo_medio_entrega_min', 'Tempo médio de entrega (min)', 'col-md-6', descreverMinutos($valor('tempo_medio_entrega_min'))); ?>
                        <?php $campoTexto('tempo_medio_retirada_min', 'Tempo médio de retirada (min)', 'col-md-6', descreverMinutos($valor('tempo_medio_retirada_min'))); ?>
                    </div>

                    <div class="row">
                        <?php $campoTexto('taxa_entrega_padrao', 'Taxa de entrega padrão (R$)', 'col-md-4', moedaBR($valor('taxa_entrega_padrao'))); ?>
                        <?php $campoTexto('frete_gratis_acima_de', 'Frete grátis acima de (R$)', 'col-md-4', moedaBR($valor('frete_gratis_acima_de'))); ?>
                        <?php $campoTexto('raio_entrega_km', 'Raio de entrega (km)', 'col-md-4'); ?>
                    </div>

                    <div class="row">
                        <?php $campoTexto('chave_pix', 'Chave PIX'); ?>
                        <?php $campoTexto('texto_rodape', 'Texto do rodapé'); ?>
                    </div>

                    <div class="row">
                        <?php $campoTexto('meta_title', 'Meta title', 'col-md-12'); ?>
                    </div>

                    <!-- Meta description -->
                    <div class="form-group">
                        <label for="meta_description">Meta description</label>
                        <textarea class="form-control" id="meta_description" rows="2"
                            readonly><?= e($valor('meta_description')) ?></textarea>
                    </div>

                    <div class="row">
                        <?php $campoTexto('created_at', 'Criado em', 'col-md-6', tempoRelativo($valor('created_at'))); ?>
                        <?php $campoTexto('updated_at', 'Atualizado em', 'col-md-6', tempoRelativo($valor('updated_at'))); ?>
                    </div>

                    <div class="card-footer bg-light d-flex justify-content-end">
                        <!-- Botões -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="d-flex flex-wrap align-items-center">
                                    <a href="<?= route('admin.configuracao.edit', ['id' => $configuracao->id]) ?>"
                                        class="btn btn-warning" title="Editar" aria-label="Editar configuração">
                                        <i class="mdi mdi-pencil"></i>
                                        Editar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php View::end(); ?>