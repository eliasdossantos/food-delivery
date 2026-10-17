<!-- resources/views/admin/produtos/index.php -->

<?php View::start('titulo'); ?>
<?= e($titulo ?? 'Produtos') ?> | Admin
<?php View::end(); ?>

<?php View::start('styles'); ?>

<style>
.tabela-produtos {
    width: 100%;
    table-layout: fixed;
}

.tabela-produtos td {
    vertical-align: middle;
    word-break: break-word;
}

.tabela-produtos .col-codigo {
    width: 45px;
}

.tabela-produtos .col-produto {
    width: 200px;
}

.tabela-produtos .col-imagem {
    width: 80px;
}

.tabela-produtos .col-detalhes {
    width: 170px;
}

.tabela-produtos .col-estoque {
    width: 90px;
}

.tabela-produtos .col-status {
    width: 150px;
}

.tabela-produtos .col-acoes {
    width: 150px;
}

/* Reticências no nome e na linha de categoria/código */
.tabela-produtos .produto-nome,
.produto-codigo,
.tabela-produtos .produto-info {
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* ── Botões da coluna Detalhes ───────────────────────────────────── */
.detalhes-botoes {
    display: flex;
    justify-content: center;
    gap: 6px;
}

.btn-detalhe {
    position: relative;
    width: 32px;
    height: 32px;
    padding: 0;
}

.btn-detalhe .contagem {
    position: absolute;
    top: -6px;
    right: -6px;
    min-width: 16px;
    height: 16px;
    padding: 0 4px;
    border-radius: 8px;
    background: #4e73df;
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    line-height: 16px;
}

/* ── Modais de extras e medidas ──────────────────────────────────── */
.extras-modal-lista {
    max-height: 300px;
    overflow-y: auto;
    margin: 0;
    padding: 0;
    text-align: left;
}

.extras-modal-lista li {
    display: flex;
    align-items: center;
    padding: 9px 12px;
    border-bottom: 1px solid #e9ecef;
    font-size: 14px;
}

.extras-modal-lista li:last-child {
    border-bottom: 0;
}

.extras-modal-lista .extra-num {
    flex: 0 0 28px;
    font-size: 12px;
    font-weight: 600;
    color: #6c757d;
}

.extras-modal-box {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    background: #f8f9fa;
}

.espec-modal-preco {
    margin-left: auto;
    font-weight: 600;
    white-space: nowrap;
}

.espec-modal-tag {
    margin-left: 10px;
    font-size: 11px;
    color: #6c757d;
}

/* ── Coluna Detalhes ─────────────────────────────────────────────── */
.tabela-produtos .col-detalhes {
    width: 190px;
}

.detalhes-botoes {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 8px;
}

.btn-detalhe {
    --cor: #6c757d;
    --fundo: #eef0f4;

    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    padding: 0;
    border: 0;
    border-radius: 50%;
    background: var(--fundo);
    color: var(--cor);
    font-size: 18px;
    transition: background .15s, color .15s, transform .15s, box-shadow .15s;
}

.btn-detalhe:hover,
.btn-detalhe:focus {
    background: var(--cor);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, .15);
    outline: 0;
}

.btn-detalhe:focus-visible {
    box-shadow: 0 0 0 3px var(--fundo), 0 0 0 5px var(--cor);
}

.btn-detalhe--descricao {
    --cor: #2f80ed;
    --fundo: #e3effd;
}

.btn-detalhe--ingredientes {
    --cor: #27ae60;
    --fundo: #e0f5e9;
}

.btn-detalhe--extras {
    --cor: #8e44ad;
    --fundo: #f0e6f6;
}

.btn-detalhe--medidas {
    --cor: #e67e22;
    --fundo: #fdeedd;
}

.btn-detalhe .contagem {
    position: absolute;
    top: -6px;
    right: -6px;
    min-width: 17px;
    height: 17px;
    padding: 0 4px;
    border: 2px solid #fff;
    border-radius: 9px;
    box-sizing: content-box;
    background: var(--cor);
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    line-height: 17px;
}

/* ── Coluna Preços ───────────────────────────────────────────────── */
.tabela-produtos .col-preco {
    width: 110px;
}

.preco-prefixo {
    display: block;
    font-size: 11px;
    line-height: 1.1;
    color: #6c757d;
}

.preco-valor {
    font-weight: 600;
    white-space: nowrap;
}
</style>

<?php View::end(); ?>

<?php View::start('content'); ?>

<div class="row">
    <div class="col-md-12 stretch-card">
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                <h5 class="card-title mt-3 mb-0 text-gray-800">
                    <?= e($tituloTabela ?? 'Lista de Produtos') ?>
                </h5>

                <a href="<?= route('admin.produto.create') ?>" class="btn btn-primary btn-sm">
                    <i class="mdi mdi-plus"></i>
                    Novo Produto
                </a>
            </div>

            <div class="card-body">
                <div class="ui-widget mb-3">
                    <input id="query" class="form-control bg-light" placeholder="Digite o nome do produto"
                        data-busca-url="<?= e(route('admin.produto.procurar')) ?>"
                        data-busca-destino="<?= e(route('admin.produto.index')) ?>">
                </div>
                <div class="table-responsive">
                    <table id="table-BR" class="table table-striped table-hover tabela-produtos">
                        <thead>
                            <tr>
                                <th class="d-none">ID</th>
                                <th class="col-codigo">Código</th>
                                <th class="col-produto">Produto</th>
                                <th class="text-center col-imagem">Imagem</th>
                                <th class="text-center col-detalhes">Detalhes</th>
                                <th class="text-center col-codigo">Preços</th>
                                <th class="text-center col-estoque">Estoque</th>
                                <th class="text-center col-status">Status</th>
                                <th class="text-center col-acoes">Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (!empty($produtos)): ?>

                            <?php foreach ($produtos as $p): ?>
                            <tr>
                                <td class="d-none">
                                    <?= e($p->id ?? '') ?>
                                </td>
                                <td class="col-codigo">
                                    <span class="produto-codigo font-weight-bold" title="<?= e($p->codigo ?? '') ?>">
                                        <?= e($p->codigo) ?>
                                    </span>
                                </td>

                                <!-- Produto: nome + categoria -->
                                <td class="col-produto">
                                    <span class="produto-nome font-weight-bold" title="<?= e($p->nome ?? '') ?>">
                                        <?= e($p->nome ?? '') ?>
                                    </span>
                                    <small class="produto-info text-muted">
                                        Categoria <?= e($p->categoria_nome ?? 'Sem categoria') ?>
                                    </small>
                                </td>

                                <!-- Imagem -->
                                <td class="text-center col-imagem">
                                    <?php
                                            $imagemPadrao = asset('admin/images/sem-foto/sem-foto.jpg');
                                            $imagemProduto = !empty($p->imagem_url) ? (preg_match('#^https?://#i', $p->imagem_url) ? $p->imagem_url : uploadUrl('produtos', $p->imagem_url)) : $imagemPadrao;
                                            ?>

                                    <button type="button" class="btn btn-info btn-sm rounded-circle"
                                        style="width:32px;height:32px;padding:0;" title="Ver imagem"
                                        aria-label="Ver imagem de <?= e($p->nome ?? '') ?>" data-toggle="modal"
                                        data-target="#imagemModal" data-src="<?= e($imagemProduto) ?>"
                                        data-fallback="<?= e($imagemPadrao) ?>" data-nome="<?= e($p->nome ?? '') ?>">
                                        <i class="mdi mdi-image"></i>
                                    </button>
                                </td>

                                <!-- Detalhes: descrição + ingredientes + extras + medidas -->
                                <td class="text-center col-detalhes">
                                    <?php
                                            $extrasDoProduto = $extrasPorProduto[(int) $p->id] ?? [];
                                            $nomesExtras = array_map(fn($x) => $x->nome, $extrasDoProduto);

                                            $especDoProduto = $especificacoesPorProduto[(int) $p->id] ?? [];
                                            $listaEspec = array_map(fn($s) => [
                                                'nome'         => $s->medida_nome,
                                                'preco'        => moedaBR($s->preco),
                                                'customizavel' => (int) $s->customizavel,
                                            ], $especDoProduto);

                                            $temDetalhes = !empty($p->descricao)
                                                || !empty($p->ingredientes)
                                                || !empty($nomesExtras)
                                                || !empty($listaEspec);
                                            ?>

                                    <?php if (!$temDetalhes): ?>
                                    <span class="text-muted">—</span>
                                    <?php else: ?>
                                    <div class="detalhes-botoes">

                                        <?php if (!empty($p->descricao)): ?>
                                        <button type="button" class="btn-detalhe btn-detalhe--descricao"
                                            title="Ver descrição" aria-label="Ver descrição de <?= e($p->nome ?? '') ?>"
                                            data-toggle="modal" data-target="#descricaoModal"
                                            data-descricao="<?= e($p->descricao) ?>"
                                            data-nome="<?= e($p->nome ?? '') ?>">
                                            <i class="mdi mdi-text"></i>
                                        </button>
                                        <?php endif; ?>

                                        <?php if (!empty($p->ingredientes)): ?>
                                        <button type="button" class="btn-detalhe btn-detalhe--ingredientes"
                                            title="Ver ingredientes"
                                            aria-label="Ver ingredientes de <?= e($p->nome ?? '') ?>"
                                            data-toggle="modal" data-target="#ingredientesModal"
                                            data-descricao="<?= e($p->ingredientes) ?>"
                                            data-nome="<?= e($p->nome ?? '') ?>">
                                            <i class="mdi mdi-food-apple"></i>
                                        </button>
                                        <?php endif; ?>

                                        <?php if (!empty($nomesExtras)): ?>
                                        <button type="button" class="btn-detalhe btn-detalhe--extras" title="Ver extras"
                                            aria-label="Ver extras de <?= e($p->nome ?? '') ?>" data-toggle="modal"
                                            data-target="#extrasModal" data-nome="<?= e($p->nome ?? '') ?>"
                                            data-extras="<?= e(json_encode($nomesExtras, JSON_UNESCAPED_UNICODE)) ?>">
                                            <i class="mdi mdi-playlist-plus"></i>
                                            <span class="contagem"><?= count($nomesExtras) ?></span>
                                        </button>
                                        <?php endif; ?>

                                        <?php if (!empty($listaEspec)): ?>
                                        <button type="button" class="btn-detalhe btn-detalhe--medidas"
                                            title="Ver medidas e preços"
                                            aria-label="Ver medidas e preços de <?= e($p->nome ?? '') ?>"
                                            data-toggle="modal" data-target="#especModal"
                                            data-nome="<?= e($p->nome ?? '') ?>"
                                            data-especificacoes="<?= e(json_encode($listaEspec, JSON_UNESCAPED_UNICODE)) ?>">
                                            <i class="mdi mdi-tape-measure"></i>
                                            <span class="contagem"><?= count($listaEspec) ?></span>
                                        </button>
                                        <?php endif; ?>

                                    </div>
                                    <?php endif; ?>
                                </td>

                                <!-- Preços (menor valor entre as medidas) -->
                                <td class="text-center">
                                    <?php $precos = array_map(fn($s) => (float) $s->preco, $especDoProduto); ?>

                                    <?php if (empty($precos)): ?>
                                    <span class="text-muted">—</span>
                                    <?php elseif (min($precos) === max($precos)): ?>
                                    <span class="preco-valor">R$ <?= e(moedaBR(min($precos))) ?></span>
                                    <?php else: ?>
                                    <small class="preco-prefixo">a partir de</small>
                                    <span class="preco-valor">R$ <?= e(moedaBR(min($precos))) ?></span>
                                    <?php endif; ?>
                                </td>

                                <!-- Estoque -->
                                <td class="text-center">
                                    <?= !empty($p->controla_estoque) ? e($p->estoque ?? 0) : '<span class="text-muted">—</span>' ?>
                                </td>

                                <!-- Status + destaque -->
                                <td class="text-center">
                                    <span class="badge <?= !empty($p->ativo) ? 'badge-success' : 'badge-danger' ?>"
                                        style="border-radius: 5px;">
                                        <?= !empty($p->ativo) ? 'Ativo' : 'Inativo' ?>
                                    </span>

                                    <?php if (!empty($p->destaque)): ?>
                                    <span class="badge badge-warning" style="border-radius: 5px;">
                                        Destaque
                                    </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Ações -->
                                <td class="text-center text-nowrap">
                                    <a href="<?= route('admin.produto.show', ['id' => $p->id]) ?>"
                                        class="btn btn-primary btn-sm" title="Visualizar"
                                        aria-label="Visualizar produto">
                                        <i class="mdi mdi-eye"></i>
                                    </a>

                                    <a href="<?= route('admin.produto.edit', ['id' => $p->id]) ?>"
                                        class="btn btn-warning btn-sm" title="Editar" aria-label="Editar produto">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>

                                    <button type="button" class="btn btn-danger btn-sm" title="Excluir"
                                        aria-label="Excluir produto" data-toggle="modal"
                                        data-target="#deleteModal<?= (int) $p->id ?>">
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

<!-- Modal de descrição -->
<div class="modal fade" id="descricaoModal" tabindex="-1" role="dialog" aria-labelledby="descricaoModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow" style="border-radius:16px;overflow:hidden;">

            <div class="modal-body text-center pt-4 pb-3 px-4">

                <button type="button" class="close position-absolute" style="top:12px;right:16px;" data-dismiss="modal"
                    aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>

                <div class="d-flex align-items-center justify-content-center mx-auto mb-3"
                    style="width:64px;height:64px;border-radius:50%;background:#eef0f4;">
                    <i class="mdi mdi-information" style="font-size:26px;color:#6c757d;"></i>
                </div>

                <h4 class="font-weight-bold mb-2" id="descricaoModalLabel">
                    Descrição
                </h4>

                <p class="text-muted mb-0" id="descricaoModalTexto" style="white-space:pre-line;"></p>
            </div>

            <div class="modal-footer border-0 justify-content-center pt-0 pb-4">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de ingredientes -->
<div class="modal fade" id="ingredientesModal" tabindex="-1" role="dialog" aria-labelledby="ingredientesModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow" style="border-radius:16px;overflow:hidden;">

            <div class="modal-body text-center pt-4 pb-3 px-4">

                <button type="button" class="close position-absolute" style="top:12px;right:16px;" data-dismiss="modal"
                    aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>

                <div class="d-flex align-items-center justify-content-center mx-auto mb-3"
                    style="width:64px;height:64px;border-radius:50%;background:#eef0f4;">
                    <i class="mdi mdi-information" style="font-size:26px;color:#6c757d;"></i>
                </div>

                <h4 class="font-weight-bold mb-2" id="ingredientesModalLabel">
                    Ingredientes
                </h4>

                <p class="text-muted mb-0" id="ingredientesModalTexto" style="white-space:pre-line;"></p>
            </div>

            <div class="modal-footer border-0 justify-content-center pt-0 pb-4">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                    Fechar
                </button>
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

<!-- Modal de extras -->
<div class="modal fade" id="extrasModal" tabindex="-1" role="dialog" aria-labelledby="extrasModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow" style="border-radius:16px;overflow:hidden;">

            <div class="modal-body text-center pt-4 pb-3 px-4">

                <button type="button" class="close position-absolute" style="top:12px;right:16px;" data-dismiss="modal"
                    aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>

                <div class="d-flex align-items-center justify-content-center mx-auto mb-3"
                    style="width:64px;height:64px;border-radius:50%;background:#eef0f4;">
                    <i class="mdi mdi-playlist-plus" style="font-size:26px;color:#6c757d;"></i>
                </div>

                <h4 class="font-weight-bold mb-1" id="extrasModalLabel">Extras</h4>
                <p class="text-muted mb-3" id="extrasModalContagem"></p>

                <div class="extras-modal-box">
                    <ul class="list-unstyled extras-modal-lista" id="extrasModalLista"></ul>
                </div>
            </div>

            <div class="modal-footer border-0 justify-content-center pt-0 pb-4">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de medidas e preços -->
<div class="modal fade" id="especModal" tabindex="-1" role="dialog" aria-labelledby="especModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow" style="border-radius:16px;overflow:hidden;">

            <div class="modal-body text-center pt-4 pb-3 px-4">

                <button type="button" class="close position-absolute" style="top:12px;right:16px;" data-dismiss="modal"
                    aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>

                <div class="d-flex align-items-center justify-content-center mx-auto mb-3"
                    style="width:64px;height:64px;border-radius:50%;background:#eef0f4;">
                    <i class="mdi mdi-tape-measure" style="font-size:26px;color:#6c757d;"></i>
                </div>

                <h4 class="font-weight-bold mb-1" id="especModalLabel">Medidas e preços</h4>
                <p class="text-muted mb-3" id="especModalContagem"></p>

                <div class="extras-modal-box">
                    <ul class="list-unstyled extras-modal-lista" id="especModalLista"></ul>
                </div>
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
<?php if (!empty($produtos)): ?>

<?php foreach ($produtos as $p): ?>

<div class="modal fade" id="deleteModal<?= (int) $p->id ?>" tabindex="-1" role="dialog"
    aria-labelledby="deleteModalLabel<?= (int) $p->id ?>" aria-hidden="true">

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

                <h5 class="font-weight-bold mb-2" id="deleteModalLabel<?= (int) $p->id ?>">
                    Confirmar exclusão
                </h5>

                <p class="text-muted mb-0">
                    Tem certeza que deseja excluir
                    <strong class="text-gray-800">
                        <?= e($p->nome ?? 'este produto') ?>
                    </strong>?
                    <br>
                    Esta ação não pode ser desfeita.
                </p>
            </div>

            <div class="modal-footer border-0 justify-content-center pb-4">

                <button type="button" class="btn btn-outline-secondary px-4" data-dismiss="modal">
                    Cancelar
                </button>

                <form method="POST" action="<?= route('admin.produto.destroy', ['id' => $p->id]) ?>">

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

    // Modal de descrição
    $('#descricaoModal').on('show.bs.modal', function(event) {
        var botao = $(event.relatedTarget);

        $('#descricaoModalLabel').text(
            botao.data('nome') || 'Descrição'
        );

        $('#descricaoModalTexto').text(
            botao.data('descricao') || ''
        );
    });

    // Modal de ingredientes
    $('#ingredientesModal').on('show.bs.modal', function(event) {
        var botao = $(event.relatedTarget);

        $('#ingredientesModalLabel').text(
            'Ingredientes de ' + (botao.data('nome') || 'produto')
        );

        $('#ingredientesModalTexto').text(
            botao.data('descricao') || ''
        );
    });

    // Modal de imagem
    $('#imagemModal').on('show.bs.modal', function(event) {
        var botao = $(event.relatedTarget);
        var nome = botao.data('nome') || 'Produto';
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

    // Modal de extras
    $('#extrasModal').on('show.bs.modal', function(event) {
        var botao = $(event.relatedTarget);
        var extras = botao.data('extras') || [];
        var lista = $('#extrasModalLista').empty();

        $('#extrasModalLabel').text('Extras de ' + (botao.data('nome') || 'produto'));
        $('#extrasModalContagem').text(
            extras.length + (extras.length === 1 ? ' extra vinculada' : ' extras vinculadas')
        );

        $.each(extras, function(i, nome) {
            $('<li>')
                .append($('<span class="extra-num">').text(i + 1))
                .append($('<span>').text(nome))
                .appendTo(lista);
        });
    });

    // Modal de medidas e preços
    $('#especModal').on('show.bs.modal', function(event) {
        var botao = $(event.relatedTarget);
        var itens = botao.data('especificacoes') || [];
        var lista = $('#especModalLista').empty();

        $('#especModalLabel').text('Medidas de ' + (botao.data('nome') || 'produto'));
        $('#especModalContagem').text(
            itens.length + (itens.length === 1 ? ' medida cadastrada' : ' medidas cadastradas')
        );

        $.each(itens, function(i, item) {
            var li = $('<li>')
                .append($('<span class="extra-num">').text(i + 1))
                .append($('<span>').text(item.nome));

            if (Number(item.customizavel) === 1) {
                li.append($('<span class="espec-modal-tag">').text('customizável'));
            }

            li.append($('<span class="espec-modal-preco">').text(item.preco))
                .appendTo(lista);
        });
    });

});
</script>

<?php View::end(); ?>