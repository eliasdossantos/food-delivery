<?php
$rota  = 'admin.banner';

/** Período de exibição → [rótulo, classe do status]. Datas vazias = sem limite naquele lado. */
$statusBanner = static function (object $b): array {
    if (empty($b->ativo)) {
        return ['Inativo', 'off'];
    }

    $agora = time();

    if (!empty($b->data_inicio) && strtotime($b->data_inicio) > $agora) {
        return ['Agendado', 'scheduled'];
    }

    if (!empty($b->data_fim) && strtotime($b->data_fim) < $agora) {
        return ['Expirado', 'inactive'];
    }

    return ['Vigente', 'active'];
};

/** Data e hora completas ("25/10/2026 14:30"). Retorna vazio quando não há data. */
$dataHoraBR = static fn(?string $d): string => $d ? date('d/m/Y H:i', strtotime($d)) : '';

[$rotuloStatus, $classeStatus] = $statusBanner($banner);

// Imagem: URL externa é usada direto; arquivo local passa pelo uploadUrl()
$imagemSrc = '';

if (!empty($banner->imagem)) {
    $imagemSrc = preg_match('#^https?://#i', $banner->imagem)
        ? $banner->imagem
        : uploadUrl('banners', $banner->imagem);
}
?>

<!-- Aqui enviamos para o template principal o título da página -->

<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Detalhes do Banner') ?> | Admin

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

    .show-status.scheduled {
        background: #d1ecf1;
        color: #0c5460;
    }

    .show-status.off {
        background: #e2e3e5;
        color: #383d41;
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
                            <?= e($subtitulo ?? 'Detalhes do Banner') ?>
                        </h4>
                        <p class="card-description mb-0 text-white">
                            <?= e($banner->titulo ?? '') ?>
                        </p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <a href="<?= route($rota . '.index') ?>" class="btn btn-secondary btn-sm text-white">
                            <i class="mdi mdi-arrow-left"></i>
                            Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Dados do banner -->
                <div class="show-section">
                    <div class="show-section-title">
                        <h5>Dados do banner</h5>
                    </div>

                    <div class="row">
                        <!-- Imagem -->
                        <div class="col-md-4 text-center">
                            <?php if ($imagemSrc !== ''): ?>
                                <img src="<?= e($imagemSrc) ?>" alt="Imagem de <?= e($banner->titulo ?? '') ?>"
                                    class="img-fluid shadow-sm mb-3"
                                    style="border-radius:12px;max-height:220px;object-fit:cover;">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center mx-auto mb-3 text-muted"
                                    style="width:160px;height:160px;border-radius:12px;background:#eef0f4;">
                                    <i class="mdi mdi-image-off" style="font-size:40px;"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-8">
                            <div class="row">
                                <!-- Título -->
                                <div class="col-md-12">
                                    <div class="show-item">
                                        <span class="show-item-label">Título</span>
                                        <div class="show-item-value">
                                            <?= e($banner->titulo ?? '-') ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Status (calculado pelo período) -->
                                <div class="col-md-6">
                                    <div class="show-item">
                                        <span class="show-item-label">Status</span>
                                        <div class="show-item-value">
                                            <span class="show-status <?= $classeStatus ?>">
                                                <?= e($rotuloStatus) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ordem -->
                                <div class="col-md-6">
                                    <div class="show-item">
                                        <span class="show-item-label">Ordem de exibição</span>
                                        <div class="show-item-value">
                                            <?= e($banner->ordem_exibicao ?? '-') ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Descrição -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="show-item">
                                <span class="show-item-label">Descrição</span>
                                <div class="show-item-value" style="white-space: pre-line;">
                                    <?= e($banner->descricao ?? '-') ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Selo de desconto -->
                <div class="show-section">
                    <div class="show-section-title">
                        <h5>Selo de desconto</h5>
                    </div>

                    <div class="row">
                        <!-- Valor do selo -->
                        <div class="col-md-6">
                            <div class="show-item">
                                <span class="show-item-label">Selo de desconto (valor)</span>
                                <div class="show-item-value">
                                    <?= !empty($banner->badge_destaque) ? e($banner->badge_destaque) : '-' ?>
                                </div>
                            </div>
                        </div>

                        <!-- Texto do selo -->
                        <div class="col-md-6">
                            <div class="show-item">
                                <span class="show-item-label">Selo de desconto (texto)</span>
                                <div class="show-item-value">
                                    <?= !empty($banner->badge_rotulo) ? e($banner->badge_rotulo) : '-' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botão -->
                <div class="show-section">
                    <div class="show-section-title">
                        <h5>Botão</h5>
                    </div>

                    <div class="row">
                        <!-- Texto do botão -->
                        <div class="col-md-4">
                            <div class="show-item">
                                <span class="show-item-label">Texto do botão</span>
                                <div class="show-item-value">
                                    <?= !empty($banner->botao_texto) ? e($banner->botao_texto) : '-' ?>
                                </div>
                            </div>
                        </div>

                        <!-- Link do botão -->
                        <div class="col-md-8">
                            <div class="show-item">
                                <span class="show-item-label">Link do botão</span>
                                <div class="show-item-value">
                                    <?= !empty($banner->botao_link) ? e($banner->botao_link) : '-' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Período de exibição -->
                <div class="show-section">
                    <div class="show-section-title">
                        <h5>Período de exibição</h5>
                    </div>

                    <div class="row">
                        <!-- Início -->
                        <div class="col-md-6">
                            <div class="show-item">
                                <span class="show-item-label">Início</span>
                                <div class="show-item-value">
                                    <?= e($dataHoraBR($banner->data_inicio ?? null) ?: 'Desde já') ?>
                                </div>
                            </div>
                        </div>

                        <!-- Fim -->
                        <div class="col-md-6">
                            <div class="show-item">
                                <span class="show-item-label">Fim</span>
                                <div class="show-item-value">
                                    <?= e($dataHoraBR($banner->data_fim ?? null) ?: 'Sem prazo') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Registro -->
                <div class="show-section">
                    <div class="show-section-title">
                        <h5>Registro</h5>
                    </div>

                    <div class="row">
                        <!-- Criado em -->
                        <div class="col-md-6">
                            <div class="show-item">
                                <span class="show-item-label">Criado em</span>
                                <div class="show-item-value">
                                    <?= !empty($banner->created_at) ? e(tempoRelativo($banner->created_at)) : '-' ?>
                                </div>
                            </div>
                        </div>

                        <!-- Atualizado em -->
                        <div class="col-md-6">
                            <div class="show-item">
                                <span class="show-item-label">Atualizado em</span>
                                <div class="show-item-value">
                                    <?= !empty($banner->updated_at) ? e(tempoRelativo($banner->updated_at)) : '-' ?>
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
                                <a href="<?= route($rota . '.edit', ['id' => $banner->id]) ?>"
                                    class="btn btn-success mr-2" title="Editar" aria-label="Editar banner">
                                    <i class="mdi mdi-pencil"></i>
                                    Editar
                                </a>
                                <a href="<?= route($rota . '.index') ?>" class="btn btn-danger" title="Voltar"
                                    aria-label="Voltar para banners">
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