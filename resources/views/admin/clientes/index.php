<!-- Aqui enviamos para o template principal o título da página -->

<?php View::start('title'); ?>

<?= e($title ?? 'Clientes') ?> | Admin

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os estilos -->

<?php View::start('styles'); ?>
<link rel="stylesheet" href="<?= asset('admin/vendors/auto-complete/jquery-ui.css') ?>">
<?php View::end(); ?>


<!-- Aqui enviamos para o template principal o conteúdo -->

<?php View::start('content'); ?>

<div class="row">
    <div class="col-md-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <p class="card-title"><?= e($tituloTabela ?? 'Lista de Clientes') ?></p>
                <a href="<?= route('admin.cliente.create') ?>" class="btn btn-primary btn-sm float-right mb-2"
                    title="Cadastrar Cliente" aria-label="Cadastrar Cliente">
                    <i class="mdi mdi-plus"></i>
                    Cadastrar Cliente
                </a>

                <div class="ui-widget mb-3">
                    <input id="query" name="query" class="form-control bg-light"
                        placeholder="Digite o nome ou CPF do cliente">
                </div>

                <div class="table-responsive">
                    <table id="table-BR" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="d-none">ID</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>CPF</th>
                                <th>Celular</th>
                                <th>Bairro</th>
                                <th>Endereço</th>
                                <th>Referência</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($clientes)): ?>
                                <?php foreach ($clientes as $cliente): ?>
                                    <tr>
                                        <td class="d-none"><?= e($cliente->id ?? ''); ?></td>
                                        <td><a href="<?= route('admin.cliente.show', ['id' => $cliente->id]) ?>">
                                                <?= e($cliente->nome ?? '') ?> </a>
                                        </td>
                                        <td><?= e($cliente->email ?? ''); ?></td>
                                        <td><?= e($cliente->cpf ?? ''); ?></td>
                                        <td><?= e($cliente->celular ?? ''); ?></td>
                                        <td><?= e($cliente->endereco_bairro ?? ''); ?></td>
                                        <td><?= e($cliente->endereco_nome ?? ''); ?></td>
                                        <td><?= e($cliente->endereco_referencia ?? ''); ?></td>
                                        <td><span
                                                class="badge <?= e($cliente->ativo ? 'badge-success' : 'badge-secondary') ?> mt-2">
                                                <?= e($cliente->ativo ? 'Ativo' : 'Inativo') ?>
                                            </span></td>

                                        <td>
                                            <a href="<?= route('admin.cliente.show', ['id' => $cliente->id]) ?>"
                                                class="btn btn-primary btn-sm" title="Visualizar"
                                                aria-label="Visualizar Usuário">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            <a href="<?= route('admin.cliente.edit', ['id' => $cliente->id]) ?>"
                                                class="btn btn-warning btn-sm" title="Editar" aria-label="Editar Usuário">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
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
<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os scripts -->

<?php View::start('scripts'); ?>

<script src="<?= asset('admin/vendors/auto-complete/jquery-ui.js') ?>"></script>

<script nonce="<?= e(CSP_NONCE) ?>">
    $(function() {
        $("#query").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?= route('admin.cliente.procurar') ?>",
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        if (data.length < 1) {
                            var data = [{
                                label: 'Nenhum resultado encontrado',
                                value: -1
                            }];
                        }
                        response(data);
                    },
                });
            },
            minLength: 1,
            select: function(event, ui) {
                if (ui.item.value == -1) {
                    $(this).val("");
                    return false;
                } else {
                    window.location.href = "<?= route('admin.cliente.index') ?>/" + ui.item.id
                }
            }
        });
    });
</script>

<?php View::end(); ?>