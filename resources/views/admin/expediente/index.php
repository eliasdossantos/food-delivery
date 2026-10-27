<?php
$rota  = 'admin.expediente';
?>

<!-- Aqui enviamos para o template principal o título da página -->

<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Expediente') ?> | Admin

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os estilos -->

<?php View::start('styles'); ?>

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal o conteúdo -->

<?php View::start('content'); ?>

<div class="row">
    <div class="col-md-12 stretch-card">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h5 class="card-title mt-3 mb-0 text-gray-800">
                    <?= e($subtitulo ?? 'Horário de Funcionamento') ?>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table-BR" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="d-none">ID</th>
                                <th>Dia da Semana</th>
                                <th>Status</th>
                                <th>Abertura</th>
                                <th>Fechamento</th>
                                <th>Pausa</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($expediente)): ?>
                                <?php foreach ($expediente as $dia): ?>
                                    <?php
                                    $aberto = (int) ($dia->aberto ?? 0) === 1;
                                    $hm = static fn($t) => !empty($t) ? substr((string) $t, 0, 5) : '-';
                                    ?>
                                    <tr>
                                        <td class="d-none"><?= e($dia->id ?? '') ?></td>
                                        <td><strong><?= e($dia->dia_descricao ?? '') ?></strong></td>
                                        <td>
                                            <span style="border-radius: 5px;"
                                                class="badge <?= $aberto ? 'badge-success' : 'badge-danger' ?> mt-2">
                                                <?= $aberto ? 'Aberto' : 'Fechado' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($aberto): ?>
                                                <span>
                                                    <i class="mdi mdi-clock-outline"></i>
                                                    <?= e(horaBonita($dia->abertura ?? null)) ?>
                                                </span>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($aberto): ?>
                                                <span>
                                                    <i class="mdi mdi-clock-outline"></i>
                                                    <?= e(horaBonita($dia->fechamento ?? null)) ?>
                                                </span>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($aberto && !empty($dia->pausa_inicio) && !empty($dia->pausa_fim)): ?>
                                                <span>
                                                    <i class="mdi mdi-coffee-outline"></i> <?= e(horaBonita($dia->pausa_inicio)) ?>
                                                    às <?= e(horaBonita($dia->pausa_fim)) ?>
                                                </span>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= route($rota . '.show', ['id' => $dia->id]) ?>"
                                                class="btn btn-primary btn-sm" title="Visualizar"
                                                aria-label="Visualizar expediente">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            <a href="<?= route($rota . '.edit', ['id' => $dia->id]) ?>"
                                                class="btn btn-warning btn-sm" title="Editar" aria-label="Editar expediente">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <?= emptyDataMessage() ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os scripts -->

<?php View::start('scripts'); ?>

<?php View::end(); ?>