<!-- Aqui enviamos para o template principal o título da página -->

<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Detalhes do Usuário') ?> | Admin

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
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <!-- Cabeçalho -->
                <div class="row align-items-center mb-4">
                    <div class="col-md-8">
                        <h4 class="card-title mb-1 text-white">
                            <?= e($titulo ?? 'Detalhes do Usuário') ?>
                        </h4>
                        <p class="card-description mb-0 text-white">
                            <?= e($usuarios->nome ?? '') ?>
                        </p>
                    </div>
                    <div class="col-md-4 text-md-right mt-3 mt-md-0">
                        <a href="<?= route('admin.usuario.index') ?>" class="btn btn-secondary btn-sm text-white">
                            <i class="fas fa-arrow-left"></i>
                            Voltar
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Dados pessoais -->
                <div class="show-section">
                    <div class="show-section-title">
                        <h5>
                            Dados pessoais
                        </h5>
                    </div>

                    <div class="row">
                        <!-- Nome -->
                        <div class="col-md-6">
                            <div class="show-item">
                                <span class="show-item-label">
                                    Nome
                                </span>
                                <div class="show-item-value">
                                    <?= e($usuarios->nome ?? '-') ?>
                                </div>
                            </div>
                        </div>

                        <!-- CPF -->
                        <div class="col-md-6">
                            <div class="show-item">
                                <span class="show-item-label">
                                    CPF
                                </span>
                                <div class="show-item-value">
                                    <?= e($usuarios->cpf ?? '-') ?>
                                </div>
                            </div>
                        </div>

                        <!-- Celular -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">
                                    Celular
                                </span>
                                <div class="show-item-value">
                                    <?= e($usuarios->celular ?? '-') ?>
                                </div>
                            </div>
                        </div>

                        <!-- Perfil -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">
                                    Perfil
                                </span>
                                <div class="show-item-value">
                                    <?= e($usuarios->perfil_nome ?? 'Sem perfil') ?>
                                </div>
                            </div>
                        </div>

                        <!-- Criado em -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">
                                    Criado em
                                </span>
                                <div class="show-item-value">
                                    <?= e(tempoRelativo($usuarios->created_at ?? '')) ?>
                                </div>
                            </div>
                        </div>

                        <!-- Atualizado em -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">
                                    Atualizado em
                                </span>
                                <div class="show-item-value">
                                    <?= e(tempoRelativo($usuarios->updated_at ?? '')) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Autenticação -->
                <div class="show-section">
                    <div class="show-section-title">
                        <h5>
                            Autenticação
                        </h5>
                    </div>
                    <div class="row">
                        <!-- E-mail -->
                        <div class="col-md-12">
                            <div class="show-item">
                                <span class="show-item-label">
                                    E-mail
                                </span>
                                <div class="show-item-value">
                                    <?= e($usuarios->email ?? '-') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Endereço -->
                <div class="show-section">
                    <div class="show-section-title">
                        <h5>
                            Endereço
                        </h5>
                    </div>

                    <div class="row">
                        <!-- CEP -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">
                                    CEP
                                </span>
                                <div class="show-item-value">
                                    <?= e($usuarios->cep ?? '-') ?>
                                </div>
                            </div>
                        </div>

                        <!-- Logradouro -->
                        <div class="col-md-7">
                            <div class="show-item">
                                <span class="show-item-label">
                                    Logradouro
                                </span>
                                <div class="show-item-value">
                                    <?= e($usuarios->logradouro ?? '-') ?>
                                </div>
                            </div>
                        </div>

                        <!-- Número -->
                        <div class="col-md-2">
                            <div class="show-item">
                                <span class="show-item-label">
                                    Número
                                </span>
                                <div class="show-item-value">
                                    <?= e($usuarios->numero ?? '-') ?>
                                </div>
                            </div>
                        </div>

                        <!-- Complemento -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">
                                    Complemento
                                </span>
                                <div class="show-item-value">
                                    <?= e($usuarios->complemento ?? '-') ?>
                                </div>
                            </div>
                        </div>

                        <!-- Bairro -->
                        <div class="col-md-4">
                            <div class="show-item">
                                <span class="show-item-label">
                                    Bairro
                                </span>
                                <div class="show-item-value">
                                    <?= e($usuarios->bairro ?? '-') ?>
                                </div>
                            </div>
                        </div>

                        <!-- Cidade -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">
                                    Cidade
                                </span>
                                <div class="show-item-value">
                                    <?= e($usuarios->cidade ?? '-') ?>
                                </div>
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="col-md-2">
                            <div class="show-item">
                                <span class="show-item-label">
                                    Estado
                                </span>
                                <div class="show-item-value">
                                    <?= e($usuarios->estado ?? '-') ?>
                                </div>
                            </div>
                        </div>

                        <!-- Referência -->
                        <div class="col-md-12">
                            <div class="show-item">
                                <span class="show-item-label">
                                    Referência
                                </span>
                                <div class="show-item-value">
                                    <?= e($usuarios->referencia ?? '-') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Controle de acesso -->
                <div class="show-section">
                    <div class="show-section-title">
                        <h5>
                            Controle de acesso
                        </h5>
                    </div>

                    <div class="row">
                        <!-- Status -->
                        <div class="col-md-6">
                            <div class="show-item">
                                <span class="show-item-label">
                                    Status
                                </span>
                                <div class="show-item-value">
                                    <?php if ((int) ($usuarios->ativo ?? 0) === 1): ?>
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

                        <!-- Data de criação -->
                        <div class="col-md-6">
                            <div class="show-item">
                                <span class="show-item-label">
                                    Criado em
                                </span>
                                <div class="show-item-value">
                                    <?= e($usuarios->created_at ?? '-') ?>
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
                                <a href="<?= route('admin.usuario.edit', ['id' => $usuarios->id]) ?>"
                                    class="btn btn-success mr-2" title="Editar" aria-label="Editar usuário">
                                    <i class="mdi mdi-pencil"></i>
                                    Editar
                                </a>
                                <a href="<?= route('admin.usuario.index') ?>" class="btn btn-danger" title="Voltar"
                                    aria-label="Voltar para usuários">
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