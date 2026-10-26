<!-- resources/views/admin/banners/index.php -->

<?php
/** Período de exibição → [rótulo, cor do badge]. Datas vazias = sem limite naquele lado. */
$statusBanner = static function (object $b): array {
    if (empty($b->ativo)) {
        return ['Inativo', 'secondary'];
    }

    $agora = time();

    if (!empty($b->data_inicio) && strtotime($b->data_inicio) > $agora) {
        return ['Agendado', 'info'];
    }

    if (!empty($b->data_fim) && strtotime($b->data_fim) < $agora) {
        return ['Expirado', 'danger'];
    }

    return ['Vigente', 'success'];
};

/** Data e hora para a coluna Vigência. Retorna vazio quando não há data. */
$dataHora = static fn(?string $d): string => $d
    ? e(date('d/m/Y', strtotime($d))) . ' <span class="text-muted">' . e(date('H:i', strtotime($d))) . '</span>'
    : '';

$imagemPadrao = asset('admin/images/sem-foto/sem-foto.jpg');
?>

<?php View::start('titulo'); ?>
<?= e($titulo ?? 'Banners') ?> | Admin
<?php View::end(); ?>

<?php View::start('styles'); ?>

<style>
.tabela-banners {
    width: 100%;
    min-width: 900px;
    table-layout: fixed;
}

.tabela-banners th,
.tabela-banners td {
    padding-left: .5rem;
    padding-right: .5rem;
}

.tabela-banners td {
    vertical-align: middle;
    word-break: break-word;
}

/* Larguras em %: somam 100% e se ajustam à área disponível */
.tabela-banners .col-ordem {
    width: 7%;
}

.tabela-banners .col-banner {
    width: 19%;
}

.tabela-banners .col-imagem {
    width: 7%;
}

.tabela-banners .col-badge {
    width: 11%;
}

.tabela-banners .col-botao {
    width: 15%;
}

.tabela-banners .col-vigencia {
    width: 17%;
}

.tabela-banners .col-status {
    width: 12%;
}

.tabela-banners .col-acoes {
    width: 12%;
}

/* Reticências no título, na descrição, no selo e no link */
.tabela-banners .banner-titulo,
.tabela-banners .banner-info {
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Botões de ação mais compactos */
.tabela-banners .acoes .btn {
    padding: .25rem .45rem;
}

/* Vigência: rótulo à esquerda, data à direita */
.vigencia {
    display: grid;
    grid-template-columns: 40px 1fr;
    row-gap: 2px;
    margin: 0;
    font-size: 13px;
    line-height: 1.4;
}

.vigencia dt {
    font-size: 12px;
    font-weight: 400;
    color: #6c757d;
}

.vigencia dd {
    margin: 0;
}
</style>

<?php View::end(); ?>

<?php View::start('content'); ?>

<div class="row">
    <div class="col-md-12 stretch-card">
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                <h5 class="card-title mt-3 mb-0 text-gray-800">
                    <?= e($tituloTabela ?? 'Lista de Banners') ?>
                </h5>

                <a href="<?= route('admin.banner.create') ?>" class="btn btn-primary btn-sm">
                    <i class="mdi mdi-plus"></i>
                    Novo Banner
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="table-BR" class="table table-striped table-hover tabela-banners">
                        <thead>
                            <tr>
                                <th class="d-none">ID</th>
                                <th class="text-center col-ordem">Ordem</th>
                                <th class="col-banner">Banner</th>
                                <th class="text-center col-imagem">Imagem</th>
                                <th class="col-badge">Selo de desconto</th>
                                <th class="col-botao">Botão</th>
                                <th class="col-vigencia">Vigência</th>
                                <th class="text-center col-status">Status</th>
                                <th class="text-center col-acoes">Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (!empty($banners)): ?>

                            <?php foreach ($banners as $b): ?>
                            <?php [$rotuloStatus, $corStatus] = $statusBanner($b); ?>
                            <tr>
                                <td class="d-none">
                                    <?= e($b->id ?? '') ?>
                                </td>

                                <!-- Ordem de exibição -->
                                <td class="text-center">
                                    <span class="font-weight-bold"><?= (int) ($b->ordem_exibicao ?? 0) ?></span>
                                </td>

                                <!-- Banner: título + descrição -->
                                <td class="col-banner">
                                    <span class="banner-titulo font-weight-bold" title="<?= e($b->titulo ?? '') ?>">
                                        <?= e($b->titulo ?? '') ?>
                                    </span>
                                    <?php if (!empty($b->descricao)): ?>
                                    <small class="banner-info text-muted" title="<?= e($b->descricao) ?>">
                                        <?= e($b->descricao) ?>
                                    </small>
                                    <?php endif; ?>
                                </td>

                                <!-- Imagem -->
                                <td class="text-center">
                                    <?php
                                            $imagemBanner = !empty($b->imagem)
                                                ? (preg_match('#^https?://#i', $b->imagem) ? $b->imagem : uploadUrl('banners', $b->imagem))
                                                : $imagemPadrao;
                                            ?>

                                    <button type="button" class="btn btn-info btn-sm rounded-circle"
                                        style="width:32px;height:32px;padding:0;" title="Ver imagem"
                                        aria-label="Ver imagem de <?= e($b->titulo ?? '') ?>" data-toggle="modal"
                                        data-target="#imagemModal" data-src="<?= e($imagemBanner) ?>"
                                        data-fallback="<?= e($imagemPadrao) ?>" data-nome="<?= e($b->titulo ?? '') ?>">
                                        <i class="mdi mdi-image"></i>
                                    </button>
                                </td>

                                <!-- Selo de desconto: 20% + OFF -->
                                <td class="text-center col-badge">
                                    <?php if (!empty($b->badge_destaque)): ?>
                                    <span class="banner-titulo font-weight-bold" title="<?= e($b->badge_destaque) ?>">
                                        <?= e($b->badge_destaque) ?>
                                    </span>
                                    <?php if (!empty($b->badge_rotulo)): ?>
                                    <small class="banner-info text-muted" title="<?= e($b->badge_rotulo) ?>">
                                        <?= e($b->badge_rotulo) ?>
                                    </small>
                                    <?php endif; ?>
                                    <?php else: ?>
                                    <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Botão: texto + link -->
                                <td class="col-botao">
                                    <?php if (!empty($b->botao_texto)): ?>
                                    <span class="banner-titulo font-weight-bold" title="<?= e($b->botao_texto) ?>">
                                        <?= e($b->botao_texto) ?>
                                    </span>
                                    <?php if (!empty($b->botao_link)): ?>
                                    <small class="banner-info text-muted" title="<?= e($b->botao_link) ?>">
                                        <?= e($b->botao_link) ?>
                                    </small>
                                    <?php endif; ?>
                                    <?php else: ?>
                                    <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Vigência -->
                                <td>
                                    <dl class="vigencia">
                                        <dt>Início</dt>
                                        <dd><?= $dataHora($b->data_inicio) ?: '<span class="text-muted">Desde já</span>' ?>
                                        </dd>

                                        <dt>Fim</dt>
                                        <dd><?= $dataHora($b->data_fim) ?: '<span class="text-muted">Sem prazo</span>' ?>
                                        </dd>
                                    </dl>
                                </td>

                                <!-- Status (calculado pelo período) + data de criação -->
                                <td class="text-center">
                                    <span class="badge badge-<?= $corStatus ?>" style="border-radius: 5px;">
                                        <?= e($rotuloStatus) ?>
                                    </span>
                                    <?php if (!empty($b->created_at)): ?>
                                    <small class="font-weight-bold d-block text-muted mt-1">
                                        Criado em <?= e(dateBR($b->created_at)) ?>
                                    </small>
                                    <?php endif; ?>
                                </td>

                                <!-- Ações -->
                                <td class="text-center text-nowrap acoes">
                                    <a href="<?= route('admin.banner.show', ['id' => $b->id]) ?>"
                                        class="btn btn-primary btn-sm" title="Visualizar"
                                        aria-label="Visualizar banner">
                                        <i class="mdi mdi-eye"></i>
                                    </a>

                                    <a href="<?= route('admin.banner.edit', ['id' => $b->id]) ?>"
                                        class="btn btn-warning btn-sm" title="Editar" aria-label="Editar banner">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>

                                    <button type="button" class="btn btn-danger btn-sm" title="Excluir"
                                        aria-label="Excluir banner" data-toggle="modal"
                                        data-target="#deleteModal<?= (int) $b->id ?>">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">
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

<!-- Modal de imagem -->
<div class="modal fade" id="imagemModal" tabindex="-1" role="dialog" aria-labelledby="imagemModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow" style="border-radius:16px;overflow:hidden;">
            <div class="modal-body text-center pt-4 pb-4 px-4">
                <button type="button" class="close position-absolute" style="top:12px;right:16px;" data-dismiss="modal"
                    aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="d-flex align-items-center justify-content-center mx-auto mb-3"
                    style="width:64px;height:64px;border-radius:50%;background:#e8f1fd;">
                    <i class="mdi mdi-image" style="font-size:26px;color:#4e73df;"></i>
                </div>
                <h5 class="font-weight-bold mb-3" id="imagemModalLabel">
                    Imagem
                </h5>
                <img id="imagemModalImg" src="" alt="" class="img-fluid shadow-sm"
                    style="border-radius:12px;max-height:60vh;object-fit:contain;">
            </div>
            <div class="modal-footer border-0 justify-content-center pt-0 pb-4">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modais de confirmação de exclusão -->
<?php if (!empty($banners)): ?>
<?php foreach ($banners as $b): ?>
<div class="modal fade" id="deleteModal<?= (int) $b->id ?>" tabindex="-1" role="dialog"
    aria-labelledby="deleteModalLabel<?= (int) $b->id ?>" aria-hidden="true">
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
                <h5 class="font-weight-bold mb-2" id="deleteModalLabel<?= (int) $b->id ?>">
                    Confirmar exclusão
                </h5>
                <p class="text-muted mb-0">
                    Tem certeza que deseja excluir
                    <strong class="text-gray-800">
                        <?= e($b->titulo ?? 'este banner') ?>
                    </strong>?
                    <br>
                    Esta ação não pode ser desfeita.
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-outline-secondary px-4" data-dismiss="modal">
                    Cancelar
                </button>
                <form method="POST" action="<?= route('admin.banner.destroy', ['id' => $b->id]) ?>">
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


<?php View::start('scripts'); ?>

<script nonce="<?= e(cspNonce()) ?>">
$(function() {

    // Modal de imagem
    $('#imagemModal').on('show.bs.modal', function(event) {
        var botao = $(event.relatedTarget);
        var nome = botao.data('nome') || 'Banner';
        var src = botao.data('src') || '';
        var fallback = botao.data('fallback') || '';

        var imagem = $('#imagemModalImg');

        imagem.off('error');

        imagem.on('error', function() {
            $(this).off('error');

            if (fallback && this.src !== fallback) {
                this.src = fallback;
            }
        });

        $('#imagemModalLabel').text(nome);

        imagem
            .attr('src', src || fallback)
            .attr('alt', nome);
    });

    // Limpa a imagem ao fechar o modal
    $('#imagemModal').on('hidden.bs.modal', function() {
        $('#imagemModalImg')
            .off('error')
            .attr('src', '')
            .attr('alt', '');
    });

});
</script>

<?php View::end(); ?>