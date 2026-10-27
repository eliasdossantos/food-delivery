<?php
$rota  = 'admin.expediente';
?>

<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Editar Expediente') ?> | Admin

<?php View::end(); ?>


<?php View::start('styles'); ?>

<?php View::end(); ?>


<?php View::start('content'); ?>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <!-- Cabeçalho -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="card-title mb-1 text-white">
                            <?= e($titulo ?? 'Editar Expediente') ?>
                        </h4>
                        <p class="card-description mb-0 text-white">
                            <?= e($expediente->dia_descricao ?? '') ?>
                        </p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <a href="<?= route($rota . '.index') ?>" class="btn btn-secondary btn-sm text-white">
                            <i class="fas fa-arrow-left"></i>
                            Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Formulário -->
                <form method="POST" action="<?= route($rota . '.update', ['id' => $expediente->id]) ?>"
                    class="forms-sample">
                    <?= csrf_field() ?>
                    <?= method_field('PUT') ?>

                    <div class="row">
                        <!-- Dia (somente leitura) -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dia_descricao">Dia da Semana</label>
                                <input type="text" class="form-control" id="dia_descricao"
                                    value="<?= e($expediente->dia_descricao ?? '') ?>" readonly>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="aberto">Funcionamento</label>
                                <select class="form-control <?= hasError('aberto') ? 'is-invalid' : '' ?>" id="aberto"
                                    name="aberto">
                                    <option value="1"
                                        <?= (string) old('aberto', $expediente->aberto ?? 1) === '1' ? 'selected' : '' ?>>
                                        Aberto
                                    </option>
                                    <option value="0"
                                        <?= (string) old('aberto', $expediente->aberto ?? 1) === '0' ? 'selected' : '' ?>>
                                        Fechado
                                    </option>
                                </select>
                                <?= erroInput('aberto') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="linha-horarios">
                        <!-- Abertura -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="abertura">Abertura</label>
                                <input type="time"
                                    class="form-control campo-horario <?= hasError('abertura') ? 'is-invalid' : '' ?>"
                                    id="abertura" name="abertura"
                                    value="<?= e(old('abertura', horaBR($expediente->abertura ?? null, ''))) ?>">
                                <?= erroInput('abertura') ?>
                            </div>
                        </div>

                        <!-- Fechamento -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fechamento">Fechamento</label>
                                <input type="time"
                                    class="form-control campo-horario <?= hasError('fechamento') ? 'is-invalid' : '' ?>"
                                    id="fechamento" name="fechamento"
                                    value="<?= e(old('fechamento', horaBR($expediente->fechamento ?? null, ''))) ?>">
                                <?= erroInput('fechamento') ?>
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i>
                                    Se fechar depois da meia-noite (ex.: 02:00), pode informar normalmente.
                                </small>
                            </div>
                        </div>

                        <!-- Pausa início -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="pausa_inicio">Início da Pausa</label>
                                <input type="time"
                                    class="form-control campo-horario <?= hasError('pausa_inicio') ? 'is-invalid' : '' ?>"
                                    id="pausa_inicio" name="pausa_inicio"
                                    value="<?= e(old('pausa_inicio', horaBR($expediente->pausa_inicio ?? null, ''))) ?>">
                                <?= erroInput('pausa_inicio') ?>
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i>
                                    Opcional. Deixe em branco se não houver pausa.
                                </small>
                            </div>
                        </div>

                        <!-- Pausa fim -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="pausa_fim">Fim da Pausa</label>
                                <input type="time"
                                    class="form-control campo-horario <?= hasError('pausa_fim') ? 'is-invalid' : '' ?>"
                                    id="pausa_fim" name="pausa_fim"
                                    value="<?= e(old('pausa_fim', horaBR($expediente->pausa_fim ?? null, ''))) ?>">
                                <?= erroInput('pausa_fim') ?>
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i>
                                    Opcional. Deixe em branco se não houver pausa.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light d-flex justify-content-end">
                        <!-- Botões -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="d-flex flex-wrap align-items-center">
                                    <button type="submit" class="btn btn-success mr-2" title="Atualizar"
                                        aria-label="Atualizar expediente">
                                        <i class="mdi mdi-content-save"></i>
                                        Atualizar
                                    </button>
                                    <a href="<?= route($rota . '.show', ['id' => $expediente->id]) ?>"
                                        class="btn btn-primary mr-2" title="Visualizar"
                                        aria-label="Visualizar expediente">
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


<?php View::start('scripts'); ?>
<script>
    (function() {
        const aberto = document.getElementById('aberto');
        const linha = document.getElementById('linha-horarios');

        function atualizar() {
            linha.style.display = aberto.value === '0' ? 'none' : '';
        }

        aberto.addEventListener('change', atualizar);
        atualizar();
    })();
</script>
<?php View::end(); ?>