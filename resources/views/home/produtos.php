<!-- Aqui enviamos para o template principal o título da página -->

<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Todos os produtos') ?>

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os estilos -->
<?php View::start('styles'); ?>
<style>
    /* ── Lista de produtos: 2 colunas + barra de rolagem ── */
    .cardapio-lista {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 24px;
        align-items: start;
        max-height: 75vh;
        overflow-y: auto;
        padding: 4px 10px 4px 4px;
    }

    /* Mensagens ("nenhum produto") ocupam a linha toda */
    .cardapio-lista>p {
        grid-column: 1 / -1;
    }

    .cardapio-lista::-webkit-scrollbar {
        width: 8px;
    }

    .cardapio-lista::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 8px;
    }

    .cardapio-lista::-webkit-scrollbar-thumb {
        background: #cfcfcf;
        border-radius: 8px;
    }

    .cardapio-lista::-webkit-scrollbar-thumb:hover {
        background: #990100;
    }

    /* ── Produto ──────────────────────────────────────── */
    .detalhe-produto {
        display: flex;
        gap: 16px;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, .08);
        padding: 16px;
        margin-bottom: 0;
        /* o espaçamento agora vem do gap do grid */
    }

    .detalhe-produto .foto {
        flex: 0 0 150px;
    }

    .detalhe-produto .foto img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 10px;
    }

    .detalhe-produto .corpo {
        flex: 1;
        min-width: 0;
    }

    .detalhe-produto h3 {
        margin: 0 0 6px;
        font-weight: 700;
        font-size: 20px;
    }

    .detalhe-meta span {
        display: inline-block;
        margin-right: 12px;
        font-size: 12px;
        color: #888;
    }

    .detalhe-bloco {
        margin-top: 14px;
    }

    .detalhe-bloco h5 {
        margin: 0 0 6px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #999;
    }

    .detalhe-precos li {
        display: flex;
        padding: 5px 0;
        border-bottom: 1px dashed #e5e5e5;
    }

    .detalhe-precos li:last-child {
        border-bottom: 0;
    }

    .detalhe-precos .valor {
        margin-left: auto;
        font-weight: 700;
    }

    /* ── Aviso de adicionais ──────────────────────────── */
    .tem-adicionais {
        display: inline-block;
        margin-top: 14px;
        padding: 5px 12px;
        background: #fdf3f3;
        color: #990100;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .tem-adicionais i {
        margin-right: 4px;
    }

    /* Tablet: 1 coluna */
    @media (max-width: 991px) {
        .cardapio-lista {
            grid-template-columns: 1fr;
        }
    }

    /* Celular: foto em cima do texto */
    @media (max-width: 767px) {
        .detalhe-produto {
            flex-direction: column;
        }

        .detalhe-produto .foto {
            flex: none;
        }

        .detalhe-produto .foto img {
            height: 180px;
        }

        .cardapio-lista {
            max-height: 70vh;
        }
    }
</style>
<?php View::end(); ?>


<!-- Aqui enviamos para o template principal o conteúdo -->

<?php View::start('content'); ?>

<?php
$semFoto = asset('admin/images/sem-foto/sem-foto.jpg');

// Renderiza um produto (usado na aba "Todos" e nas abas por categoria)
$renderProduto = function ($p) use ($semFoto, $especificacoesPorProduto, $extrasPorProduto) {
    $img    = $p->imagem_url ?? '';
    $imagem = $img === ''
        ? $semFoto
        : (preg_match('#^https?://#i', $img) ? $img : uploadUrl('produtos', $img));

    $specs  = $especificacoesPorProduto[(int) $p->id] ?? [];
    $extras = $extrasPorProduto[(int) $p->id] ?? [];
?>
    <div class="detalhe-produto">
        <div class="foto">
            <img src="<?= e($imagem) ?>" alt="<?= e($p->nome) ?>"
                onerror="this.onerror=null;this.src='<?= e($semFoto) ?>';">
        </div>

        <div class="corpo">
            <h3><?= e($p->nome) ?></h3>

            <div class="detalhe-meta">
                <?php if (!empty($p->categoria_nome)): ?>
                    <span><i class="fa fa-tag"></i> <?= e($p->categoria_nome) ?></span>
                <?php endif; ?>
                <?php if (!empty($p->tempo_preparo)): ?>
                    <span><i class="fa fa-clock-o"></i> <?= e($p->tempo_preparo) ?> min</span>
                <?php endif; ?>
                <?php if (!empty($p->codigo)): ?>
                    <span>Cód. <?= e($p->codigo) ?></span>
                <?php endif; ?>
                <?php if (!empty($p->controla_estoque) && (int) $p->estoque <= 0): ?>
                    <span style="color:#c00;">Indisponível</span>
                <?php endif; ?>
            </div>

            <?php if (!empty($p->descricao)): ?>
                <p style="margin-top:8px;"><?= e($p->descricao) ?></p>
            <?php endif; ?>

            <?php if (!empty($p->ingredientes)): ?>
                <div class="detalhe-bloco">
                    <h5>Ingredientes</h5>
                    <p><?= e($p->ingredientes) ?></p>
                </div>
            <?php endif; ?>

            <?php if ($specs): ?>
                <div class="detalhe-bloco">
                    <h5>Medidas e preços</h5>
                    <ul class="list-unstyled detalhe-precos">
                        <?php foreach ($specs as $s): ?>
                            <li>
                                <span><?= e($s->medida_nome) ?>
                                    <?php if ((int) $s->customizavel === 1): ?>
                                        <small class="text-muted">(customizável)</small>
                                    <?php endif; ?>
                                </span>
                                <span class="valor"><?= e(moedaBR($s->preco)) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($extras): ?>
                <span class="tem-adicionais">
                    <i class="fa fa-plus-circle"></i> Tem adicionais
                </span>
            <?php endif; ?>
        </div>
    </div>
<?php
};
?>

<!--    Produtos   -->
<div class="container section" id="menu" data-aos="fade-up" style="margin-top: 3em">
    <div class="title-block">
        <h1 class="section-title">Todos os produtos</h1>
    </div>

    <!--    Abas (mesmo visual do filtro da home)    -->
    <div class="menu_filter text-center">
        <ul class="list-unstyled list-inline d-inline-block" role="tablist">
            <li class="item active" role="presentation">
                <a href="#tab-todos" role="tab" data-toggle="tab">Todos</a>
            </li>
            <?php foreach ($categorias as $categoria): ?>
                <li class="item" role="presentation">
                    <a href="#tab-cat-<?= (int) $categoria->id ?>" role="tab" data-toggle="tab">
                        <?= e($categoria->nome) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!--    Conteúdo das abas    -->
    <div class="tab-content">

        <div role="tabpanel" class="tab-pane active" id="tab-todos">
            <div class="cardapio-lista">
                <?php if (empty($produtos)): ?>
                    <p class="text-center text-muted">Nenhum produto disponível no momento.</p>
                <?php endif; ?>
                <?php foreach ($produtos as $p) {
                    $renderProduto($p);
                } ?>
            </div>
        </div>

        <?php foreach ($categorias as $categoria): ?>
            <div role="tabpanel" class="tab-pane" id="tab-cat-<?= (int) $categoria->id ?>">
                <div class="cardapio-lista">
                    <?php $itens = $produtosPorCategoria[(int) $categoria->id] ?? []; ?>
                    <?php if (empty($itens)): ?>
                        <p class="text-center text-muted">Nenhum produto nesta categoria.</p>
                    <?php endif; ?>
                    <?php foreach ($itens as $p) {
                        $renderProduto($p);
                    } ?>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</div>

<?php View::end(); ?>