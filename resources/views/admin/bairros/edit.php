<?php
$rota  = 'admin.bairro';
$rotaVoltar = 'admin/bairro';
?>
<!-- Aqui enviamos para o template principal o título da página -->

<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Editar Bairros') ?> | Admin

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os estilos -->

<?php View::start('styles'); ?>

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
                            <?= e($titulo ?? 'Editar Bairro') ?>
                        </h4>
                        <p class="card-description mb-0 text-white">
                            <?= e($bairros->nome ?? '') ?>
                        </p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <a href="<?= url($rotaVoltar) ?>" class="btn btn-secondary btn-sm text-white">
                            <i class="fas fa-arrow-left"></i>
                            Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Formulário -->
                <form method="POST" action="<?= route($rota . '.update', ['id' => $bairros->id]) ?>"
                    enctype="multipart/form-data" class="forms-sample">
                    <?= csrf_field() ?>
                    <?= method_field('PUT') ?>

                    <div class="row">
                        <!-- Bairro -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cidade">Cidade</label>
                                <input type="text" class="form-control <?= hasError('cidade') ? 'is-invalid' : '' ?>"
                                    id="cidade" name="cidade" value="<?= old('cidade', $bairros->cidade ?? '') ?>"
                                    placeholder="Nome da Cidade">
                                <?= erroInput('cidade') ?>
                            </div>
                        </div>

                        <!-- Bairro -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nome">Bairro</label>
                                <input type="text" class="form-control <?= hasError('nome') ? 'is-invalid' : '' ?>"
                                    id="nome" name="nome" value="<?= old('nome', $bairros->nome ?? '') ?>"
                                    placeholder="Nome do Bairro">
                                <?= erroInput('nome') ?>
                            </div>
                        </div>

                        <!-- Slug -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="slug">Slug de <strong><?= e($bairros->nome ?? '') ?></strong></label>
                                <input type="text" class="form-control <?= hasError('slug') ? 'is-invalid' : '' ?>"
                                    id="slug" name="slug" value="<?= old('slug', $bairros->slug ?? '') ?>"
                                    placeholder="Em branco, é gerado pelo nome">
                                <?= erroInput('slug') ?>
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i>
                                    <strong>Customizável:</strong> Em branco, é gerado pelo nome do bairro
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="valor_entrega">Valor da Entrega</label>
                                <input type="text"
                                    class="form-control <?= hasError('valor_entrega') ? 'is-invalid' : '' ?> dinheiro"
                                    id="valor_entrega" name="valor_entrega"
                                    value="<?= old('valor_entrega', $bairros->valor_entrega ?? '') ?>"
                                    placeholder="Valor da Entrega do Bairro">
                                <?= erroInput('valor_entrega') ?>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ativo">Status do Registro</label>
                                <select class="form-control <?= hasError('ativo') ? 'is-invalid' : '' ?>" id="ativo"
                                    name="ativo">
                                    <option value="1"
                                        <?= (string) old('ativo', $bairros->ativo ?? 1) === '1' ? 'selected' : '' ?>>
                                        Ativo
                                    </option>
                                    <option value="0"
                                        <?= (string) old('ativo', $bairros->ativo ?? 1) === '0' ? 'selected' : '' ?>>
                                        Inativo
                                    </option>
                                </select>
                                <?= erroInput('ativo') ?>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light d-flex justify-content-end">
                        <!-- Botões -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="d-flex flex-wrap align-items-center">
                                    <button type="submit" class="btn btn-success mr-2" title="Atualizar"
                                        aria-label="Atualizar bairro">
                                        <i class="mdi mdi-content-save"></i>
                                        Atualizar
                                    </button>
                                    <a href="<?= route($rota . '.show', ['id' => $bairros->id]) ?>"
                                        class="btn btn-primary mr-2" title="Visualizar" aria-label="Visualizar bairro">
                                        <i class="mdi mdi-eye"></i>
                                        Visualizar
                                    </a>
                                    <a href="<?= route($rota . '.index') ?>" class="btn btn-danger" title="Cancelar"
                                        aria-label="Cancelar edição">
                                        <i class="mdi mdi-close"></i>
                                        Cancelar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os scripts -->

<?php View::start('scripts'); ?>

<?php View::end(); ?>