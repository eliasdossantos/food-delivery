<!-- Aqui enviamos para o template principal o título da página -->

<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Detalhes do Produto') ?> | Admin

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os estilos -->

<?php View::start('styles'); ?>

<style>
.show-section {
    margin-top: 25px;
    margin-bottom: 20px;
}

.show-section-title {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.show-section-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e9ecef;
    margin-left: 15px;
}

.show-section-title h5 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    white-space: nowrap;
}

.show-item {
    margin-bottom: 18px;
}

.show-item-label {
    display: block;
    margin-bottom: 5px;
    font-size: 13px;
    font-weight: 600;
    color: #6c757d;
}

.show-item-value {
    min-height: 38px;
    padding: 9px 12px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 4px;
    color: #212529;
    word-break: break-word;
    white-space: pre-line;
}

.show-status {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.show-status.active {
    background: #d4edda;
    color: #155724;
}

.show-status.inactive {
    background: #f8d7da;
    color: #721c24;
}

.show-status.featured {
    background: #fff3cd;
    color: #856404;
}

.show-imagem {
    max-width: 220px;
    max-height: 220px;
    border: 1px solid #e9ecef;
    border-radius: 4px;
    padding: 4px;
    background: #fff;
}

.preco-antigo {
    text-decoration: line-through;
    color: #6c757d;
}
</style>

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal o conteúdo -->

<?php View::start('content'); ?>

<?php
$temPromo = isset($produtos->preco_promocional) && $produtos->preco_promocional !== null
    && $produtos->preco_promocional !== '';

$imagemAtual = $produtos->imagem_url ?? '';
$imagemSrc = '';

if ($imagemAtual !== '') {
    $imagemSrc = preg_match('#^https?://#i', $imagemAtual)
        ? $imagemAtual
        : uploadUrl('produtos', $imagemAtual);
}
?>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">

            <!-- Cabeçalho -->
            <div class="card-header bg-primary text-white">
                <div class="row align-items-center mb-4">
                    <div class="col-md-8">
                        <h4 class="card-title mb-1 text-white">
                            <?= e($titulo ?? 'Detalhes do Produto') ?>
                        </h4>
                        <p class="card-description mb-0 text-white">
                            <?= e($produtos->nome ?? '') ?>
                        </p>
                    </div>
                    <div class="col-md-4 text-md-right mt-3 mt-md-0">
                        <a href="<?= route('admin.produto.index') ?>" class="btn btn-secondary btn-sm text-white">
                            <i class="fas fa-arrow-left"></i>
                            Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">

                <!-- Identificação -->
                <div class="show-section">
                    <div class="show-section-title">
                        <h5>Identificação</h5>
                    </div>

                    <div class="row">
                        <!-- Nome -->
                        <div class="col-md-6">
                            <div class="show-item">
                                <span class="show-item-label">Nome</span>
                                <div class="show-item-value"><?= e($produtos->nome ?? '-') ?></div>
                            </div>
                        </div>

                        <!-- Slug -->
                        <div class="col-md-6">
                            <div class="show-item">
                                <span class="show-item-label">Slug</span>
                                <div class="show-item-value"><?= e($produtos->slug ?? '-') ?></div>
                            </div>
                        </div>

                        <!-- Categoria -->
                        <div class="col-md-6">
                            <div class="show-item">
                                <span class="show-item-label">Categoria</span>
                                <div class="show-item-value"><?= e($produtos->categoria_nome ?? 'Sem categoria') ?>
                                </div>
                            </div>
                        </div>

                        <!-- Código -->
                        <div class="col-md-6">
                            <div class="show-item">
                                <span class="show-item-label">Código (SKU)</span>
                                <div class="show-item-value">
                                    <?= e(($produtos->codigo ?? '') !== '' ? $produtos->codigo : '-') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Conteúdo -->
                <div class="show-section">
                    <div class="show-section-title">
                        <h5>Conteúdo</h5>
                    </div>

                    <div class="row">
                        <!-- Descrição -->
                        <div class="col-md-6">
                            <div class="show-item">
                                <span class="show-item-label">Descrição</span>
                                <div class="show-item-value">
                                    <?= e(($produtos->descricao ?? '') !== '' ? $produtos->descricao : '-') ?>
                                </div>
                            </div>
                        </div>

                        <!-- Ingredientes -->
                        <div class="col-md-6">
                            <div class="show-item">
                                <span class="show-item-label">Ingredientes</span>
                                <div class="show-item-value">
                                    <?= e(($produtos->ingredientes ?? '') !== '' ? $produtos->ingredientes : '-') ?>
                                </div>
                            </div>
                        </div>

                        <!-- Imagem -->
                        <div class="col-md-12">
                            <div class="show-item">
                                <span class="show-item-label">Imagem</span>
                                <?php if ($imagemSrc !== ''): ?>
                                <img src="<?= e($imagemSrc) ?>" alt="<?= e($produtos->nome ?? '') ?>"
                                    class="show-imagem">
                                <?php else: ?>
                                <div class="show-item-value">Sem imagem</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preços -->
                <div class="show-section">
                    <div class="show-section-title">
                        <h5>Preços</h5>
                    </div>

                    <div class="row">
                        <!-- Preço -->
                        <div class="col-md-4">
                            <div class="show-item">
                                <span class="show-item-label">Preço</span>
                                <div class="show-item-value <?= $temPromo ? 'preco-antigo' : '' ?>">
                                    R$ <?= number_format((float) ($produtos->preco ?? 0), 2, ',', '.') ?>
                                </div>
                            </div>
                        </div>

                        <!-- Preço promocional -->
                        <div class="col-md-4">
                            <div class="show-item">
                                <span class="show-item-label">Preço promocional</span>
                                <div class="show-item-value">
                                    <?= $temPromo
                                        ? 'R$ ' . number_format((float) $produtos->preco_promocional, 2, ',', '.')
                                        : '-' ?>
                                </div>
                            </div>
                        </div>

                        <!-- Tempo de preparo -->
                        <div class="col-md-4">
                            <div class="show-item">
                                <span class="show-item-label">Tempo de preparo</span>
                                <div class="show-item-value">
                                    <?= ($produtos->tempo_preparo ?? null) !== null && $produtos->tempo_preparo !== ''
                                        ? e($produtos->tempo_preparo) . ' min'
                                        : '-' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estoque -->
                <div class="show-section">
                    <div class="show-section-title">
                        <h5>Estoque</h5>
                    </div>

                    <div class="row">
                        <!-- Controla estoque -->
                        <div class="col-md-6">
                            <div class="show-item">
                                <span class="show-item-label">Controla estoque?</span>
                                <div class="show-item-value">
                                    <?= (int) ($produtos->controla_estoque ?? 0) === 1 ? 'Sim' : 'Não' ?>
                                </div>
                            </div>
                        </div>

                        <!-- Quantidade em estoque -->
                        <div class="col-md-6">
                            <div class="show-item">
                                <span class="show-item-label">Quantidade em estoque</span>
                                <div class="show-item-value">
                                    <?= (int) ($produtos->controla_estoque ?? 0) === 1
                                        ? e($produtos->estoque ?? 0)
                                        : '-' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Exibição -->
                <div class="show-section">
                    <div class="show-section-title">
                        <h5>Exibição</h5>
                    </div>

                    <div class="row">
                        <!-- Status -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">Status</span>
                                <div class="show-item-value">
                                    <?php if ((int) ($produtos->ativo ?? 0) === 1): ?>
                                    <span class="show-status active">Ativo</span>
                                    <?php else: ?>
                                    <span class="show-status inactive">Inativo</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Destaque -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">Destaque</span>
                                <div class="show-item-value">
                                    <?php if ((int) ($produtos->destaque ?? 0) === 1): ?>
                                    <span class="show-status featured">Em destaque</span>
                                    <?php else: ?>
                                    Normal
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Ordem de exibição -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">Ordem de exibição</span>
                                <div class="show-item-value"><?= e($produtos->ordem_exibicao ?? 0) ?></div>
                            </div>
                        </div>

                        <!-- Criado em -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">Criado em</span>
                                <div class="show-item-value">
                                    <?= e(tempoRelativo($produtos->created_at ?? '')) ?>
                                </div>
                            </div>
                        </div>

                        <!-- Atualizado em -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">Atualizado em</span>
                                <div class="show-item-value">
                                    <?= e(tempoRelativo($produtos->updated_at ?? '')) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light d-flex justify-content-end">
                    <!-- Botões -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-flex flex-wrap align-items-center">
                                <a href="<?= route('admin.produto.edit', ['id' => $produtos->id]) ?>"
                                    class="btn btn-success mr-2" title="Editar" aria-label="Editar produto">
                                    <i class="mdi mdi-pencil"></i>
                                    Editar
                                </a>
                                <a href="<?= route('admin.produto.index') ?>" class="btn btn-danger" title="Voltar"
                                    aria-label="Voltar para produtos">
                                    <i class="mdi mdi-close"></i>
                                    Voltar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os scripts -->

<?php View::start('scripts'); ?>

<?php View::end(); ?>