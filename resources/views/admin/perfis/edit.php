<!-- Aqui enviamos para o template principal o título da página -->

<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Editar Perfis') ?> | Admin

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os estilos -->

<?php View::start('styles'); ?>
<?php View::end(); ?>


<!-- Aqui enviamos para o template principal o conteúdo -->

<?php View::start('content'); ?>
<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <!-- Cabeçalho -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="card-title mb-1 text-white">
                            <?= e($titulo ?? 'Editar Perfil') ?>
                        </h4>
                        <p class="card-description mb-0 text-white">
                            <?= e($perfis->nome ?? '') ?>
                        </p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <a href="<?= url('admin/perfil') ?>" class="btn btn-secondary btn-sm text-white">
                            <i class="fas fa-arrow-left"></i>
                            Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Formulário -->
                <form method="POST" action="<?= route('admin.perfil.update', ['id' => $perfis->id]) ?>"
                    enctype="multipart/form-data" class="forms-sample">
                    <?= csrf_field() ?>
                    <?= method_field('PUT') ?>
                    <!-- Nome -->
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" class="form-control <?= hasError('nome') ? 'is-invalid' : '' ?>" id="nome"
                            name="nome" value="<?= old('nome', $perfis->nome ?? '') ?>" placeholder="Nome do perfil">
                        <?= erroInput('nome') ?>
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label for="ativo">Status do Registro</label>
                        <div class="form-check form-check-flat form-check-primary">
                            <label class="form-check-label">
                                <input type="hidden" name="ativo" value="0">
                                <input type="checkbox" class="form-check-input" id="ativo" name="ativo" value="1"
                                    <?= (int) old('ativo', $perfis->ativo ?? 0) === 1 ? 'checked' : '' ?>>

                                Ativo
                            </label>
                        </div>
                        <?= erroInput('ativo') ?>
                    </div>

                    <!-- Ações -->
                    <button type="submit" class="btn btn-success mr-2" title="Atualizar" aria-label="Atualizar perfil">
                        <i class="mdi mdi-content-save"></i>
                        Atualizar
                    </button>
                    <a href="<?= route('admin.perfil.show', ['id' => $perfis->id]) ?>" class="btn btn-primary mr-2"
                        title="Visualizar" aria-label="Visualizar perfil">
                        <i class="mdi mdi-eye"></i>
                        Visualizar
                    </a>
                    <a href="<?= route('admin.perfil.index') ?>" class="btn btn-danger" title="Cancelar"
                        aria-label="Cancelar edição">
                        <i class="mdi mdi-close"></i>
                        Cancelar
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os scripts -->

<?php View::start('scripts'); ?>

<?php View::end(); ?>