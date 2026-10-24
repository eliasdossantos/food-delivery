<!-- resources/views/admin/expediente/show.php -->

<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Detalhes') ?> | Admin

<?php View::end(); ?>


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
</style>

<?php View::end(); ?>


<?php View::start('content'); ?>

<?php
$aberto     = (int) ($expediente->aberto ?? 0) === 1;
$temHorario = !empty($expediente->abertura) && !empty($expediente->fechamento);
$temPausa   = !empty($expediente->pausa_inicio) && !empty($expediente->pausa_fim);
?>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <!-- Cabeçalho -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="card-title mb-1 text-white">
                            <?= e($subtitulo ?? 'Detalhes do Expediente') ?>
                        </h4>
                        <p class="card-description mb-0 text-white">
                            <?= e($expediente->dia_descricao ?? '') ?>
                        </p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <a href="<?= route('admin.expediente.index') ?>" class="btn btn-secondary btn-sm text-white">
                            <i class="fas fa-arrow-left"></i>
                            Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Dados do Expediente -->
                <div class="show-section">
                    <div class="show-section-title">
                        <h5>Dados do Expediente</h5>
                    </div>

                    <div class="row">
                        <!-- Dia -->
                        <div class="col-md-4">
                            <div class="show-item">
                                <span class="show-item-label">Dia da Semana</span>
                                <div class="show-item-value">
                                    <?= e($expediente->dia_descricao ?? '-') ?>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-4">
                            <div class="show-item">
                                <span class="show-item-label">Funcionamento</span>
                                <div class="show-item-value">
                                    <?php if ($aberto): ?>
                                        <span class="show-status active">Aberto</span>
                                    <?php else: ?>
                                        <span class="show-status inactive">Fechado</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Horário -->
                        <div class="col-md-4">
                            <div class="show-item">
                                <span class="show-item-label">Horário</span>
                                <div class="show-item-value">
                                    <?php if ($temHorario): ?>
                                        <i class="mdi mdi-clock-outline <?= $aberto ? 'text-success' : 'text-muted' ?>"></i>
                                        <?= e(horaBonita($expediente->abertura)) ?>
                                        <span class="text-muted">às</span>
                                        <?= e(horaBonita($expediente->fechamento)) ?>
                                        <?php if (!$aberto): ?>
                                            <small class="text-muted">(dia fechado)</small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Pausa -->
                        <div class="col-md-12">
                            <div class="show-item">
                                <span class="show-item-label">Pausa</span>
                                <div class="show-item-value">
                                    <?php if ($temPausa): ?>
                                        <i
                                            class="mdi mdi-coffee-outline <?= $aberto ? 'text-warning' : 'text-muted' ?>"></i>
                                        <?= e(horaBonita($expediente->pausa_inicio)) ?>
                                        <span class="text-muted">às</span>
                                        <?= e(horaBonita($expediente->pausa_fim)) ?>
                                    <?php else: ?>
                                        Sem pausa
                                    <?php endif; ?>
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
                                    <?= !empty($expediente->created_at) ? e(tempoRelativo($expediente->created_at)) : '-' ?>
                                </div>
                            </div>
                        </div>

                        <!-- Atualizado em -->
                        <div class="col-md-6">
                            <div class="show-item">
                                <span class="show-item-label">Atualizado em</span>
                                <div class="show-item-value">
                                    <?= !empty($expediente->updated_at) ? e(tempoRelativo($expediente->updated_at)) : '-' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="card-footer bg-light d-flex justify-content-end">
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-flex flex-wrap align-items-center">
                                <a href="<?= route('admin.expediente.edit', ['id' => $expediente->id]) ?>"
                                    class="btn btn-success mr-2" title="Editar" aria-label="Editar expediente">
                                    <i class="mdi mdi-pencil"></i>
                                    Editar
                                </a>
                                <a href="<?= route('admin.expediente.index') ?>" class="btn btn-danger" title="Voltar"
                                    aria-label="Voltar para expediente">
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


<?php View::start('scripts'); ?>

<?php View::end(); ?>