<?php
$rota  = 'admin.bairro';
?>
<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Bairros') ?> | Admin

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
                    <?= e($subtitulo ?? 'Lista dos Bairros') ?>
                </h5>
                <a href="<?= route($rota . '.create') ?>" class="btn btn-primary btn-sm float-right">
                    <i class="mdi mdi-plus"></i>
                    Novo Bairro
                </a>
            </div>
            <div class="card-body">
                <div class="ui-widget mb-3">
                    <input id="query" class="form-control bg-light" placeholder="Digite o nome do Bairro"
                        data-busca-url="<?= e(route($rota . '.procurar')) ?>"
                        data-busca-destino="<?= e(route($rota . '.index')) ?>">
                </div>
                <div class="table-responsive">
                    <table id="table-BR" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="d-none">ID</th>
                                <th>Cidade</th>
                                <th>Bairro</th>
                                <th>Valor da Entrega</th>
                                <th>Status</th>
                                <th>Criado em</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($bairros)): ?>
                                <?php foreach ($bairros as $bairro): ?>
                                    <tr>
                                        <td class="d-none"><?= e($bairro->id ?? ''); ?></td>
                                        <td><?= e($bairro->cidade ?? ''); ?></td>
                                        <td><?= e($bairro->nome ?? ''); ?></td>
                                        <td><?= e(moedaBR($bairro->valor_entrega ?? '')); ?></td>
                                        <td><span style="border-radius: 5px;"
                                                class="badge <?= e($bairro->ativo ? 'badge-success' : 'badge-danger') ?> mt-2">
                                                <?= e($bairro->ativo ? 'Ativo' : 'Inativo') ?>
                                            </span></td>
                                        <td><?= dateBR(e($bairro->created_at ?? '')); ?></td>
                                        <td>
                                            <a href="<?= route($rota . '.show', ['id' => $bairro->id]) ?>"
                                                class="btn btn-primary btn-sm" title="Visualizar"
                                                aria-label="Visualizar bairro">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            <a href="<?= route($rota . '.edit', ['id' => $bairro->id]) ?>"
                                                class="btn btn-warning btn-sm" title="Editar" aria-label="Editar bairro">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" title="Excluir"
                                                aria-label="Excluir bairro" data-toggle="modal"
                                                data-target="#deleteModal<?= (int) $bairro->id ?>">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center">
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

<!-- Modais de confirmação de exclusão -->
<?php if (!empty($bairros)): ?>
    <?php foreach ($bairros as $bairro): ?>
        <div class="modal fade" id="deleteModal<?= (int) $bairro->id ?>" tabindex="-1" role="dialog"
            aria-labelledby="deleteModalLabel<?= (int) $bairro->id ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow">
                    <div class="modal-body text-center pt-4 pb-3 px-4">
                        <button type="button" class="close position-absolute" style="top:12px;right:16px;" data-dismiss="modal"
                            aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>

                        <div class="d-flex align-items-center justify-content-center mx-auto mb-3" style="
                    width: 64px;
                    height: 64px;
                    border-radius: 50%;
                    background: #fdecea;
                ">
                            <i class="mdi mdi-alert" style="font-size: 26px; color: #e74a3b;"></i>
                        </div>

                        <h5 class="font-weight-bold mb-2" id="deleteModalLabel<?= (int) $bairro->id ?>">Confirmar exclusão</h5>

                        <p class="text-muted mb-0">
                            Tem certeza que deseja excluir <strong
                                class="text-gray-800"><?= e($bairro->nome ?? 'este registro') ?></strong>?
                            <br>
                            Esta ação não pode ser desfeita.
                        </p>
                    </div>
                    <div class="modal-footer border-0 justify-content-center pb-4">
                        <button type="button" class="btn btn-outline-secondary px-4" data-dismiss="modal">
                            Cancelar
                        </button>
                        <form method="POST" action="<?= route($rota . '.destroy', ['id' => $bairro->id]) ?>">
                            <?= csrf_field() ?>
                            <?= method_field('DELETE') ?>
                            <button type="submit" class="btn btn-danger px-4">
                                <i class="mdi mdi-delete mr-1"></i> Excluir
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os scripts -->

<?php View::start('scripts'); ?>

<?php View::end(); ?>