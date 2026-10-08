<?php
// Quem vê os botões de ação (visualizar, editar, excluir, cadastrar)
$listaPodeGerir = \Framework\Auth\Auth::isAny('Super Administrador', 'Administrador');
$listaSouSuper  = \Framework\Auth\Auth::is('Super Administrador');
?>

<!-- Aqui enviamos para o template principal o título da página -->

<?php View::start('title'); ?>

<?= e($title ?? 'Usuarios') ?> | Admin

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
                <p class="card-title"><?= e($tituloTabela ?? 'Lista de Usuários') ?></p>

                <?php if ($listaPodeGerir): ?>
                    <a href="<?= route('admin.usuario.create') ?>" class="btn btn-primary btn-sm float-right mb-2"
                        title="Cadastrar Usuário" aria-label="Cadastrar Usuário">
                        <i class="mdi mdi-plus"></i>
                        Cadastrar Usuário
                    </a>
                <?php endif; ?>

                <div class="ui-widget mb-3">
                    <input id="query" name="query" class="form-control bg-light" placeholder="Digite o nome do usuário">
                </div>

                <div class="table-responsive">
                    <table id="table-BR" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="d-none">ID</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>CPF</th>
                                <th>Perfil</th>
                                <th>Status</th>
                                <?php if ($listaPodeGerir): ?>
                                    <th>Ações</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($usuarios)): ?>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <?php $listaLinhaSuper = \Framework\Auth\Auth::normalizarPerfil($usuario->perfil_nome ?? '') === 'super_administrador'; ?>
                                    <tr>
                                        <td class="d-none"><?= e($usuario->id ?? ''); ?></td>
                                        <td>
                                            <?php if ($listaPodeGerir): ?>
                                                <a href="<?= route('admin.usuario.show', ['id' => $usuario->id]) ?>">
                                                    <?= e($usuario->nome ?? '') ?>
                                                </a>
                                            <?php else: ?>
                                                <?= e($usuario->nome ?? '') ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= e($usuario->email ?? ''); ?></td>
                                        <td><?= e($usuario->cpf ?? ''); ?></td>
                                        <td>
                                            <?php if (!empty($usuario->perfil_nome)): ?>
                                                <?= e($usuario->perfil_nome) ?>
                                            <?php else: ?>
                                                <span class="badge badge-light mt-2">Sem perfil</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><span style="border-radius: 5px;"
                                                class="badge <?= e($usuario->ativo ? 'badge-success' : 'badge-secondary') ?> mt-2">
                                                <?= e($usuario->ativo ? 'Ativo' : 'Inativo') ?>
                                            </span></td>

                                        <?php if ($listaPodeGerir): ?>
                                            <td>
                                                <a href="<?= route('admin.usuario.show', ['id' => $usuario->id]) ?>"
                                                    class="btn btn-primary btn-sm" title="Visualizar"
                                                    aria-label="Visualizar Usuário">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>

                                                <?php if ($listaSouSuper || !$listaLinhaSuper): ?>
                                                    <a href="<?= route('admin.usuario.edit', ['id' => $usuario->id]) ?>"
                                                        class="btn btn-warning btn-sm" title="Editar" aria-label="Editar Usuário">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if (!$listaLinhaSuper): ?>
                                                    <button type="button" class="btn btn-danger btn-sm" title="Excluir"
                                                        aria-label="Excluir usuário" data-toggle="modal"
                                                        data-target="#deleteModal<?= (int) $usuario->id ?>">
                                                        <i class="mdi mdi-delete"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
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


<!-- Modais de confirmação de exclusão (um por usuário, fora da tabela) -->
<?php if ($listaPodeGerir && !empty($usuarios)): ?>
    <?php foreach ($usuarios as $usuario): ?>
        <?php if (\Framework\Auth\Auth::normalizarPerfil($usuario->perfil_nome ?? '') === 'super_administrador') continue; ?>
        <div class="modal fade" id="deleteModal<?= (int) $usuario->id ?>" tabindex="-1" role="dialog"
            aria-labelledby="deleteModalLabel<?= (int) $usuario->id ?>" aria-hidden="true">
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

                        <h5 class="font-weight-bold mb-2" id="deleteModalLabel<?= (int) $usuario->id ?>">Confirmar exclusão</h5>

                        <p class="text-muted mb-0">
                            Tem certeza que deseja excluir <strong
                                class="text-gray-800"><?= e($usuario->nome ?? 'este registro') ?></strong>?
                            <br>
                            Esta ação não pode ser desfeita.
                        </p>
                    </div>
                    <div class="modal-footer border-0 justify-content-center pb-4">
                        <button type="button" class="btn btn-outline-secondary px-4" data-dismiss="modal">
                            Cancelar
                        </button>
                        <form method="POST" action="<?= route('admin.usuario.destroy', ['id' => $usuario->id]) ?>">
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

<script src="<?= asset('admin/vendors/auto-complete/jquery-ui.js') ?>"></script>

<script nonce="<?= e(CSP_NONCE) ?>">
    $(function() {
        $("#query").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?= route('admin.usuario.procurar') ?>",
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
                    window.location.href = "<?= route('admin.usuario.index') ?>/" + ui.item.id
                }
            }
        });
    });
</script>

<?php View::end(); ?>