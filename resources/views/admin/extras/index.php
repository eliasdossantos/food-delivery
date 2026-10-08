<!-- resources/views/admin/extras/index.php -->

<!-- Aqui enviamos para o template principal o título da página -->

<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Extras') ?> | Admin

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
                    <?= e($tituloTabela ?? 'Lista dos Extras') ?>
                </h5>
                <a href="<?= route('admin.extra.create') ?>" class="btn btn-primary btn-sm float-right">
                    <i class="mdi mdi-plus"></i>
                    Novo Extra
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table-BR" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="d-none">ID</th>
                                <th>Nome</th>
                                <th class="text-center">Descrição</th>
                                <th class="text-center">Imagem</th>
                                <th class="text-center">Preço</th>
                                <th class="text-center">Destaque</th>
                                <th class="text-center">Ordem</th>
                                <th class="text-center">Status</th>
                                <th>Criado em</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($extras)): ?>
                            <?php foreach ($extras as $extra): ?>
                            <tr>
                                <td class="d-none"><?= e($extra->id ?? ''); ?></td>
                                <td>
                                    <?php if (!empty($extra->icone)): ?>
                                    <i class="mdi <?= e($extra->icone) ?>"></i>
                                    <?php endif; ?>
                                    <?= e($extra->nome ?? ''); ?>
                                </td>
                                <td class="text-center">
                                    <?php if (!empty($extra->descricao)): ?>
                                    <button type="button" class="btn btn-secondary btn-sm rounded-circle"
                                        style="width:32px;height:32px;padding:0;" title="Ver descrição"
                                        aria-label="Ver descrição de <?= e($extra->nome ?? '') ?>" data-toggle="modal"
                                        data-target="#extraModal" data-descricao="<?= e($extra->descricao) ?>"
                                        data-nome="<?= e($extra->nome ?? '') ?>">
                                        <i class="mdi mdi-information"></i>
                                    </button>
                                    <?php else: ?>
                                    <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if (!empty($extra->imagem_url)): ?>
                                    <button type="button" class="btn btn-info btn-sm rounded-circle"
                                        style="width:32px;height:32px;padding:0;" title="Ver imagem"
                                        aria-label="Ver imagem de <?= e($extra->nome ?? '') ?>" data-toggle="modal"
                                        data-target="#imagemModal"
                                        data-src="<?= e(uploadUrl('extras', $extra->imagem_url)) ?>"
                                        data-nome="<?= e($extra->nome ?? '') ?>">
                                        <i class="mdi mdi-image"></i>
                                    </button>
                                    <?php else: ?>
                                    <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?= e(moedaBR($extra->preco ?? '')); ?></td>
                                <td class="text-center"><?= e($extra->destaque ?? ''); ?></td>
                                <td class="text-center"><?= e($extra->ordem_exibicao ?? ''); ?></td>
                                <td class="text-center"><span style="border-radius: 5px;"
                                        class="badge <?= e($extra->ativo ? 'badge-success' : 'badge-danger') ?> mt-2">
                                        <?= e($extra->ativo ? 'Ativo' : 'Inativo') ?>
                                    </span>
                                </td>
                                <td><?= dateBR(e($extra->created_at ?? '')); ?></td>
                                <td>
                                    <a href="<?= route('admin.extra.show', ['id' => $extra->id]) ?>"
                                        class="btn btn-primary btn-sm" title="Visualizar" aria-label="Visualizar extra">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                    <a href="<?= route('admin.extra.edit', ['id' => $extra->id]) ?>"
                                        class="btn btn-warning btn-sm" title="Editar" aria-label="Editar extra">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm" title="Excluir"
                                        aria-label="Excluir extra" data-toggle="modal"
                                        data-target="#deleteModal<?= (int) $extra->id ?>">
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
<!-- Modal único para visualizar a descrição da extra -->
<div class="modal fade" id="extraModal" tabindex="-1" role="dialog" aria-labelledby="extraModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-body text-center pt-4 pb-3 px-4">
                <button type="button" class="close position-absolute" style="top:12px;right:16px;" data-dismiss="modal"
                    aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>

                <div class="d-flex align-items-center justify-content-center mx-auto mb-3" style="
                    width: 64px;
                    height: 64px;
                    border-radius: 50%;
                    background: #eef0f4;
                ">
                    <i class="mdi mdi-information" style="font-size: 26px; color: #6c757d;"></i>
                </div>

                <h4 class="font-weight-bold mb-2" id="extraModalLabel">Descrição</h4>

                <p class="text-muted mb-0" id="extraModalTexto" style="white-space: pre-line;"></p>
            </div>
            <div class="modal-footer border-0 justify-content-center pt-0 pb-4">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal único para visualizar a imagem da extra -->
<div class="modal fade" id="imagemModal" tabindex="-1" role="dialog" aria-labelledby="imagemModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-body text-center pt-4 pb-4 px-4">
                <button type="button" class="close position-absolute" style="top:12px;right:16px;" data-dismiss="modal"
                    aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>

                <div class="d-flex align-items-center justify-content-center mx-auto mb-3" style="
                    width: 64px;
                    height: 64px;
                    border-radius: 50%;
                    background: #e8f1fd;
                ">
                    <i class="mdi mdi-image" style="font-size: 26px; color: #4e73df;"></i>
                </div>

                <h5 class="font-weight-bold mb-3" id="imagemModalLabel">Imagem</h5>

                <img id="imagemModalImg" src="" alt="" class="img-fluid shadow-sm"
                    style="border-radius: 12px; max-height: 60vh; object-fit: cover;">
            </div>
            <div class="modal-footer border-0 justify-content-center pt-0 pb-4">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modais de confirmação de exclusão (um por extra, fora da tabela) -->
<?php if (!empty($extras)): ?>
<?php foreach ($extras as $extra): ?>
<div class="modal fade" id="deleteModal<?= (int) $extra->id ?>" tabindex="-1" role="dialog"
    aria-labelledby="deleteModalLabel<?= (int) $extra->id ?>" aria-hidden="true">
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

                <h5 class="font-weight-bold mb-2" id="deleteModalLabel<?= (int) $extra->id ?>">Confirmar exclusão
                </h5>

                <p class="text-muted mb-0">
                    Tem certeza que deseja excluir <strong
                        class="text-gray-800"><?= e($extra->nome ?? 'este registro') ?></strong>?
                    <br>
                    Esta ação não pode ser desfeita.
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-outline-secondary px-4" data-dismiss="modal">
                    Cancelar
                </button>
                <form method="POST" action="<?= route('admin.extra.destroy', ['id' => $extra->id]) ?>">
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
<script nonce="<?= e(cspNonce()) ?>">
// Modal da descrição
$('#extraModal').on('show.bs.modal', function(event) {
    var botao = $(event.relatedTarget);

    $('#extraModalLabel').text(botao.data('nome'));
    $('#extraModalTexto').text(botao.data('descricao'));
});

// Modal da imagem
$('#imagemModal').on('show.bs.modal', function(event) {
    var botao = $(event.relatedTarget);
    var nome = botao.data('nome');

    $('#imagemModalLabel').text(nome);
    $('#imagemModalImg').attr('src', botao.data('src')).attr('alt', nome);
});

// Limpa a imagem ao fechar para não mostrar a anterior ao abrir outra
$('#imagemModal').on('hidden.bs.modal', function() {
    $('#imagemModalImg').attr('src', '');
});
</script>
<?php View::end(); ?>