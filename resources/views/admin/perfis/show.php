<!-- Aqui enviamos para o template principal o título da página -->

<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Detalhes do Perfil') ?> | Admin

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
</style>

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal o conteúdo -->

<?php View::start('content'); ?>

<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <!-- Cabeçalho -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="card-title mb-1 text-white">
                            <?= e($subtitulo ?? 'Detalhes do Perfil') ?>
                        </h4>
                        <p class="card-description mb-0 text-white">
                            <?= e($perfis->nome ?? '') ?>
                        </p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <a href="<?= route('admin.perfil.index') ?>" class="btn btn-secondary btn-sm text-white">
                            <i class="fas fa-arrow-left"></i>
                            Voltar
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Dados do perfil -->
                <div class="show-section">
                    <div class="show-section-title">
                        <h5>
                            Dados do perfil
                        </h5>
                    </div>

                    <div class="row">
                        <!-- Nome -->
                        <div class="col-md-8">
                            <div class="show-item">
                                <span class="show-item-label">
                                    Nome
                                </span>
                                <div class="show-item-value">
                                    <?= e($perfis->nome ?? '-') ?>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-4">
                            <div class="show-item">
                                <span class="show-item-label">
                                    Status
                                </span>
                                <div class="show-item-value">
                                    <?php if ((int) ($perfis->ativo ?? 0) === 1): ?>
                                    <span class="show-status active">
                                        Ativo
                                    </span>
                                    <?php else: ?>
                                    <span class="show-status inactive">
                                        Inativo
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Registro -->
                <div class="show-section">
                    <div class="show-section-title">
                        <h5>
                            Registro
                        </h5>
                    </div>

                    <div class="row">
                        <!-- Criado em -->
                        <div class="col-md-6">
                            <div class="show-item">
                                <span class="show-item-label">
                                    Criado em
                                </span>
                                <div class="show-item-value">
                                    <?= !empty($perfis->created_at) ? e(tempoRelativo($perfis->created_at)) : '-' ?>
                                </div>
                            </div>
                        </div>

                        <!-- Atualizado em -->
                        <div class="col-md-6">
                            <div class="show-item">
                                <span class="show-item-label">
                                    Atualizado em
                                </span>
                                <div class="show-item-value">
                                    <?= !empty($perfis->updated_at) ? e(tempoRelativo($perfis->updated_at)) : '-' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="d-flex flex-wrap align-items-center">
                            <a href="<?= route('admin.perfil.edit', ['id' => $perfis->id]) ?>"
                                class="btn btn-success mr-2" title="Editar" aria-label="Editar perfil">
                                <i class="mdi mdi-pencil"></i>
                                Editar
                            </a>
                            <a href="<?= route('admin.perfil.index') ?>" class="btn btn-danger" title="Voltar"
                                aria-label="Voltar para perfis">
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

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os scripts -->

<?php View::start('scripts'); ?>

<?php View::end(); ?>