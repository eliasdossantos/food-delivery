<?php
$rota  = 'admin.pagamento';
?>
<!-- Aqui enviamos para o template principal o título da página -->

<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Formas de Pagamento') ?> | Admin

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os estilos -->

<?php View::start('styles'); ?>

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal o conteúdo -->

<?php View::start('content'); ?>

<div class="row">
    <div class="col-md-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <p class="card-title"><?= e($titulo ?? 'Lista as Formas de Pagamento') ?></p>

                <a href="<?= route($rota . '.create') ?>" class="btn btn-primary btn-sm float-right mb-2"
                    title="Cadastrar Formas de Pagamento" aria-label="Cadastrar Formas de Pagamento">
                    <i class="mdi mdi-plus"></i>
                    Cadastrar Forma de Pagamento
                </a>

                <div class="ui-widget mb-3">
                    <input id="query" class="form-control bg-light" placeholder="Digite o nome do pagamento"
                        data-busca-url="<?= e(route($rota . '.procurar')) ?>"
                        data-busca-destino="<?= e(route($rota . '.index')) ?>">
                </div>
                <div class="alert alert-info d-flex align-items-start" role="alert">
                    <i class="mdi mdi-information-outline mr-2" style="font-size:20px;"></i>
                    <div>
                        <strong>Atenção:</strong>
                        a forma de pagamento <strong>Dinheiro</strong> é padrão do sistema e não pode ser
                        editada nem excluída.
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="table-BR" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Status</th>
                                <th>Criado em</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pagamentos)): ?>
                                <?php foreach ($pagamentos as $pagamento): ?>
                                    <tr>
                                        <td><?= e($pagamento->id ?? ''); ?></td>
                                        <td><?= e($pagamento->nome ?? '') ?></td>

                                        <td><span style="border-radius: 5px;"
                                                class="badge <?= e($pagamento->ativo ? 'badge-success' : 'badge-danger') ?> mt-2">
                                                <?= e($pagamento->ativo ? 'Ativo' : 'Inativo') ?></span>
                                        </td>
                                        <td><?= dateBR(e($pagamento->created_at ?? '')); ?></td>

                                        <!-- Ações -->
                                        <td class="text-nowrap">
                                            <a href="<?= route($rota . '.show', ['id' => $pagamento->id]) ?>"
                                                class="btn btn-primary btn-sm" title="Visualizar"
                                                aria-label="Visualizar Formas de Pagamento">
                                                <i class="mdi mdi-eye"></i>
                                            </a>

                                            <?php if (\App\Repositories\FormaPagamentoRepository::ehProtegida($pagamento)): ?>
                                                <span class="badge badge-secondary ml-1" style="border-radius:5px;"
                                                    title="Forma de pagamento padrão do sistema">
                                                    <i class="mdi mdi-lock"></i> Protegida
                                                </span>
                                            <?php else: ?>
                                                <a href="<?= route($rota . '.edit', ['id' => $pagamento->id]) ?>"
                                                    class="btn btn-warning btn-sm" title="Editar"
                                                    aria-label="Editar Formas de Pagamento">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>

                                                <button type="button" class="btn btn-danger btn-sm" title="Excluir"
                                                    aria-label="Excluir Forma de Pagamento" data-toggle="modal"
                                                    data-target="#deleteModal<?= (int) $pagamento->id ?>">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            <?php endif; ?>
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

<?php if (!empty($pagamentos)): ?>

    <?php foreach ($pagamentos as $pagamento): ?>
        <?php if (\App\Repositories\FormaPagamentoRepository::ehProtegida($pagamento)) continue; ?>
        <div class="modal fade" id="deleteModal<?= (int) $pagamento->id ?>" tabindex="-1" role="dialog"
            aria-labelledby="deleteModalLabel<?= (int) $pagamento->id ?>" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow">

                    <div class="modal-body text-center pt-4 pb-3 px-4">

                        <button type="button" class="close position-absolute" style="top:12px;right:16px;" data-dismiss="modal"
                            aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>

                        <div class="d-flex align-items-center justify-content-center mx-auto mb-3"
                            style="width:64px;height:64px;border-radius:50%;background:#fdecea;">
                            <i class="mdi mdi-alert" style="font-size:26px;color:#e74a3b;"></i>
                        </div>

                        <h5 class="font-weight-bold mb-2" id="deleteModalLabel<?= (int) $pagamento->id ?>">
                            Confirmar exclusão
                        </h5>

                        <p class="text-muted mb-0">
                            Tem certeza que deseja excluir
                            <strong class="text-gray-800">
                                <?= e($pagamento->nome ?? 'esta forma de pagamento') ?>
                            </strong>?
                            <br>
                            Esta ação não pode ser desfeita.
                        </p>
                    </div>

                    <div class="modal-footer border-0 justify-content-center pb-4">

                        <button type="button" class="btn btn-outline-secondary px-4" data-dismiss="modal">
                            Cancelar
                        </button>

                        <form method="POST" action="<?= route($rota . '.destroy', ['id' => $pagamento->id]) ?>">

                            <?= csrf_field() ?>
                            <?= method_field('DELETE') ?>

                            <button type="submit" class="btn btn-danger px-4">
                                <i class="mdi mdi-delete mr-1"></i>
                                Excluir
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