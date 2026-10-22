<!-- resources/views/admin/entregadores/index.php -->

<!-- Aqui enviamos para o template principal o título da página -->

<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Entregadores') ?> | Admin

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os estilos -->

<?php View::start('styles'); ?>
<style>
    .tabela-entregador {
        width: 100%;
    }

    .tabela-entregador td {
        vertical-align: middle;
    }

    .tabela-entregador .col-acoes {
        white-space: nowrap;
    }

    .avatar-entregador {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
    }

    .avatar-entregador-inicial {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #e9ecef;
        color: #6c757d;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Vínculo: freelancer em laranja (fixo usa badge-primary do tema) */
    .badge-freelancer {
        background-color: #fd7e14;
        color: #fff;
    }
</style>
<?php View::end(); ?>


<!-- Aqui enviamos para o template principal o conteúdo -->

<?php View::start('content'); ?>

<?php
// Aparência dos badges (definido uma vez, usado em todas as linhas)
$badgesVinculo = [
    'fixo'       => ['badge-primary',    'Fixo'],
    'freelancer' => ['badge-freelancer', 'Temporário'],
];

$badgesAprovacao = [
    'pendente'  => ['badge-warning', 'Pendente'],
    'aprovado'  => ['badge-success', 'Aprovado'],
    'reprovado' => ['badge-danger',  'Reprovado'],
    'bloqueado' => ['badge-dark',    'Bloqueado'],
];
?>

<div class="row">
    <div class="col-md-12 stretch-card">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h5 class="card-title mt-3 mb-0 text-gray-800">
                    <?= e($subtitulo ?? 'Lista de Entregadores') ?>
                </h5>
                <a href="<?= route('admin.entregador.create') ?>" class="btn btn-primary btn-sm float-right">
                    <i class="mdi mdi-plus"></i>
                    Novo Entregador
                </a>
            </div>
            <div class="card-body">
                <div class="ui-widget mb-3">
                    <input id="query" class="form-control bg-light"
                        placeholder="Digite Nome, Vínculo, Marca-Veículo, Placa-Veículo"
                        data-busca-url="<?= e(route('admin.entregador.procurar')) ?>"
                        data-busca-destino="<?= e(route('admin.entregador.index')) ?>">
                </div>
                <div class="table-responsive">
                    <table id="table-BR" class="table table-striped table-hover tabela-entregador">
                        <thead>
                            <tr>
                                <th class="d-none">ID</th>
                                <th>Foto</th>
                                <th>Nome</th>
                                <th>Vínculo</th>
                                <th>Celular</th>
                                <th>Aprovação</th>
                                <th>Disponível</th>
                                <th>Turnos</th>
                                <th>Status</th>
                                <th>Criado em</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($entregadores)): ?>
                                <?php foreach ($entregadores as $entregador): ?>
                                    <?php
                                    [$classeVinculo, $rotuloVinculo]     = $badgesVinculo[$entregador->tipo_vinculo ?? ''] ?? ['badge-light', '—'];
                                    [$classeAprovacao, $rotuloAprovacao] = $badgesAprovacao[$entregador->status_aprovacao ?? ''] ?? ['badge-secondary', '—'];
                                    $inicial = mb_strtoupper(mb_substr(trim($entregador->nome ?? '?'), 0, 1, 'UTF-8'), 'UTF-8');
                                    ?>
                                    <tr>
                                        <td class="d-none"><?= e($entregador->id ?? ''); ?></td>

                                        <td>
                                            <?php
                                            // URL externa é usada direto; arquivo local fica em uploads/entregadores/{id}/
                                            $avatarSrc = '';

                                            if (!empty($entregador->avatar)) {
                                                $avatarSrc = preg_match('#^https?://#i', $entregador->avatar)
                                                    ? $entregador->avatar
                                                    : uploadUrl('entregadores', $entregador->avatar, $entregador->id);
                                            }
                                            ?>

                                            <?php if ($avatarSrc !== ''): ?>
                                                <img src="<?= e($avatarSrc) ?>" alt="Foto de <?= e($entregador->nome ?? '') ?>"
                                                    class="avatar-entregador">
                                            <?php else: ?>
                                                <span class="avatar-entregador-inicial"><?= e($inicial) ?></span>
                                            <?php endif; ?>
                                        </td>

                                        <td><?= e($entregador->nome ?? ''); ?></td>

                                        <td>
                                            <span style="border-radius: 5px;" class="badge <?= e($classeVinculo) ?> mt-2">
                                                <?= e($rotuloVinculo) ?>
                                            </span>
                                        </td>

                                        <td><?= e($entregador->celular ?: '—'); ?></td>

                                        <td>
                                            <span style="border-radius: 5px;" class="badge <?= e($classeAprovacao) ?> mt-2">
                                                <?= e($rotuloAprovacao) ?>
                                            </span>
                                        </td>

                                        <td>
                                            <span style="border-radius: 5px;"
                                                class="badge <?= e($entregador->disponivel ? 'badge-success' : 'badge-secondary') ?> mt-2">
                                                <?= e($entregador->disponivel ? 'Online' : 'Offline') ?>
                                            </span>
                                        </td>

                                        <td>
                                            <span style="border-radius: 7px;"
                                                class="badge <?= e($entregador->turno_manha ? 'badge-info' : 'badge-light') ?>"
                                                title="Manhã">M</span>
                                            <span style="border-radius: 7px;"
                                                class="badge <?= e($entregador->turno_tarde ? 'badge-info' : 'badge-light') ?>"
                                                title="Tarde">T</span>
                                            <span style="border-radius: 7px;"
                                                class="badge <?= e($entregador->turno_noite ? 'badge-info' : 'badge-light') ?>"
                                                title="Noite">N</span>
                                        </td>

                                        <td>
                                            <span style="border-radius: 5px;"
                                                class="badge <?= e($entregador->ativo ? 'badge-success' : 'badge-danger') ?> mt-2">
                                                <?= e($entregador->ativo ? 'Ativo' : 'Inativo') ?>
                                            </span>
                                        </td>

                                        <td><?= dateBR(e($entregador->created_at ?? '')); ?></td>

                                        <td class="col-acoes">
                                            <a href="<?= route('admin.entregador.show', ['id' => $entregador->id]) ?>"
                                                class="btn btn-primary btn-sm" title="Visualizar"
                                                aria-label="Visualizar Entregador">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            <a href="<?= route('admin.entregador.edit', ['id' => $entregador->id]) ?>"
                                                class="btn btn-warning btn-sm" title="Editar" aria-label="Editar Entregador">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" title="Excluir"
                                                aria-label="Excluir Entregador" data-toggle="modal"
                                                data-target="#deleteModal<?= (int) $entregador->id ?>">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="11" class="text-center">
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

<!-- Modais de confirmação de exclusão (um por entregador, fora da tabela) -->
<?php if (!empty($entregadores)): ?>
    <?php foreach ($entregadores as $entregador): ?>
        <div class="modal fade" id="deleteModal<?= (int) $entregador->id ?>" tabindex="-1" role="dialog"
            aria-labelledby="deleteModalLabel<?= (int) $entregador->id ?>" aria-hidden="true">
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

                        <h5 class="font-weight-bold mb-2" id="deleteModalLabel<?= (int) $entregador->id ?>">Confirmar exclusão
                        </h5>

                        <p class="text-muted mb-0">
                            Tem certeza que deseja excluir <strong
                                class="text-gray-800"><?= e($entregador->nome ?? 'este registro') ?></strong>?
                            <br>
                            Esta ação não pode ser desfeita.
                        </p>
                    </div>
                    <div class="modal-footer border-0 justify-content-center pb-4">
                        <button type="button" class="btn btn-outline-secondary px-4" data-dismiss="modal">
                            Cancelar
                        </button>
                        <form method="POST" action="<?= route('admin.entregador.destroy', ['id' => $entregador->id]) ?>">
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