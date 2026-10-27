<?php
$rota  = 'admin.produto';
$maxMb = (int) ($uploadMaxMb ?? 5);
?>

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

    .show-extras {
        max-height: 160px;
        overflow-y: auto;
        padding: 12px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 4px;
    }

    .show-extra-chip {
        display: inline-block;
        margin: 0 6px 6px 0;
        padding: 4px 12px;
        background: #e8f0fe;
        color: #1a4fa0;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .show-tabela {
        margin: 0;
        background: #f8f9fa;
    }

    .show-tabela th {
        font-size: 13px;
        color: #6c757d;
    }

    /* ── Medidas e preços com rolagem ─────────────────────────────────── */
    .show-tabela-box {
        max-height: 280px;
        overflow-y: auto;
        border: 1px solid #e9ecef;
        border-radius: 4px;
    }

    .show-tabela-box .show-tabela {
        border: 0;
    }

    .show-tabela-box .show-tabela thead th {
        position: sticky;
        top: 0;
        z-index: 1;
        background: #f1f3f5;
        box-shadow: 0 1px 0 #dee2e6;
    }

    .show-contador {
        margin-top: 6px;
        font-size: 13px;
        color: #6c757d;
        text-align: right;
    }
</style>

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal o conteúdo -->

<?php View::start('content'); ?>

<?php
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
                        <a href="<?= route($rota . '.index') ?>" class="btn btn-secondary btn-sm text-white">
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

                <!-- Extras -->
                <div class="show-section">
                    <div class="show-section-title">
                        <h5>Extras disponíveis</h5>
                    </div>

                    <?php if (empty($extras)): ?>
                        <div class="show-item-value">Nenhuma extra vinculada</div>
                    <?php else: ?>
                        <div class="show-extras">
                            <?php foreach ($extras as $extra): ?>
                                <span class="show-extra-chip"><?= e($extra->nome) ?> - $R:
                                    <?= e(moedaBR($extra->preco)) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Medidas e preços -->
                <div class="show-section">
                    <div class="show-section-title">
                        <h5>Medidas e preços</h5>
                    </div>

                    <?php if (empty($especificacoes)): ?>
                        <div class="show-item-value">Nenhuma medida cadastrada</div>
                    <?php else: ?>
                        <div class="show-tabela-box">
                            <table class="table table-bordered show-tabela">
                                <thead>
                                    <tr>
                                        <th>Medida</th>
                                        <th class="text-center">Preço</th>
                                        <th class="text-center">Customizável</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($especificacoes as $esp): ?>
                                        <tr>
                                            <td><?= e($esp->medida_nome) ?></td>
                                            <td class="text-center"><?= e(moedaBR($esp->preco)) ?></td>
                                            <td class="text-center">
                                                <?= !empty($esp->customizavel)
                                                    ? '<span class="show-status active">Sim</span>'
                                                    : '<span class="show-status inactive">Não</span>' ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="show-contador"><?= count($especificacoes) ?> medida(s)</div>
                    <?php endif; ?>
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
                        <h5>Tempo de preparo</h5>
                    </div>

                    <div class="row">

                        <!-- Tempo de preparo -->
                        <div class="col-md-6">
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
                                <a href="<?= route($rota . '.edit', ['id' => $produtos->id]) ?>"
                                    class="btn btn-success mr-2" title="Editar" aria-label="Editar produto">
                                    <i class="mdi mdi-pencil"></i>
                                    Editar
                                </a>
                                <a href="<?= route($rota . '.index') ?>" class="btn btn-danger" title="Voltar"
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