<!-- Aqui enviamos para o template principal o título da página -->

<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Food Delivery') ?>

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os estilos -->
<?php View::start('styles'); ?>
<style>
/* Paginação: esconde os itens fora da página atual */
.menu-produto.pag-oculto {
    display: none !important;
}

/* Modal: fade + leve zoom */
#produtoModal.fade .modal-dialog {
    transform: scale(.92) translateY(10px);
    opacity: 0;
    transition: transform .3s ease, opacity .3s ease;
}

#produtoModal.in .modal-dialog {
    transform: none;
    opacity: 1;
}

#produtoModal {
    overflow-y: auto;
}

.modal-backdrop.in {
    opacity: .65;
}

.produto-modal-dialog {
    max-width: 520px;
    margin: 30px auto;
}

.produto-modal {
    position: relative;
    border: 0;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, .35);
}

.produto-modal-fechar {
    position: absolute;
    top: 12px;
    right: 12px;
    z-index: 5;
    width: 36px;
    height: 36px;
    border: 0;
    border-radius: 50%;
    background: rgba(0, 0, 0, .55);
    color: #fff;
    font-size: 22px;
    line-height: 34px;
    text-align: center;
    transition: background .2s, transform .2s;
}

.produto-modal-fechar:hover {
    background: rgba(0, 0, 0, .8);
    transform: rotate(90deg);
}

.produto-modal-img {
    background: #f3f3f3;
}

.produto-modal-img img {
    display: block;
    width: 100%;
    max-height: 320px;
    object-fit: cover;
}

.produto-modal-corpo {
    padding: 22px 26px 26px;
}

.produto-modal-corpo h3 {
    margin: 0 0 10px;
    font-weight: 700;
}

.produto-modal-texto {
    margin: 0;
    color: #666;
    white-space: pre-line;
}

.produto-modal-bloco {
    margin-top: 18px;
    padding-top: 14px;
    border-top: 1px solid #eee;
}

.produto-modal-bloco h5 {
    margin: 0 0 8px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #999;
}

#produtoModalPrecos li {
    display: flex;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px dashed #e5e5e5;
}

#produtoModalPrecos li:last-child {
    border-bottom: 0;
}

#produtoModalPrecos .preco-tag {
    margin-left: 8px;
    font-size: 11px;
    color: #999;
}

#produtoModalPrecos .preco-valor {
    margin-left: auto;
    font-weight: 700;
    white-space: nowrap;
}

@media (max-width: 767px) {
    .produto-modal-dialog {
        margin: 15px;
    }

    .produto-modal-img img {
        max-height: 240px;
    }
}
</style>
<?php View::end(); ?>


<!-- Aqui enviamos para o template principal o conteúdo -->

<?php View::start('content'); ?>

<!--    About Us    -->
<?php
// Dados da seção: do admin quando existir; senão, o conteúdo padrão do tema
$sobreTitulo = trim((string) ($sobreNos->titulo ?? '')) ?: 'Sobre nós';

$sobreTexto = trim((string) ($sobreNos->texto ?? '')) ?: 'Somos apaixonados por comida de verdade. '
    . 'Cada prato é preparado com ingredientes frescos e muito carinho, '
    . 'para você receber em casa o melhor sabor, com entrega rápida.';

$sobreImagem = trim((string) ($sobreNos->imagem ?? ''));

if ($sobreImagem === '') {
    $sobreImagemUrl = asset('home/img/photos/about-us.jpg');
} elseif (preg_match('#^https?://#i', $sobreImagem)) {
    $sobreImagemUrl = $sobreImagem;
} else {
    $sobreImagemUrl = uploadUrl('sobrenos', $sobreImagem);
}

$sobreImagemAlt = trim((string) ($sobreNos->imagem_alt ?? '')) ?: $sobreTitulo;
?>
<div class="container section" id="about_us">
    <div class="col-sm-12 d-flex flex-xs-column">
        <div class="col-sm-6 d-flex align-items-center padd_lr0" data-aos="fade-up">
            <div class="content">
                <h1 class="section-title title_sty1"><?= e($sobreTitulo) ?></h1>
                <p class="short"><?= nl2br(e($sobreTexto)) ?></p>
            </div>
        </div>
        <div class="col-sm-6 img text-center padd_lr0" data-aos="fade-down">
            <div class="border_on">
                <img src="<?= e($sobreImagemUrl) ?>" alt="<?= e($sobreImagemAlt) ?>" class="about_img" />
            </div>
        </div>
    </div>
</div>

<!--    Menus   -->
<div class="container section" id="menu" data-aos="fade-up">
    <div class="title-block">
        <h1 class="section-title">Nosso Cardápio</h1>
    </div>

    <!--    Menus filter    -->
    <div class="menu_filter text-center">
        <ul class="list-unstyled list-inline d-inline-block">
            <?php foreach ($categorias as $i => $categoria): ?>
            <li class="item<?= $i === 0 ? ' active' : '' ?>">
                <a href="javascript:;" class="filter-button" data-filter="<?= e($categoria->slug) ?>">
                    <?= e($categoria->nome) ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!--    Menus items     -->
    <div id="menu_items">

        <?php foreach ($categorias as $i => $categoria): ?>
        <?php $itens = $produtosPorCategoria[$categoria->id] ?? []; ?>

        <div class="filtr-item image filter <?= e($categoria->slug) ?><?= $i === 0 ? ' active' : '' ?>"
            data-categoria="<?= e($categoria->slug) ?>">
            <div class="row">

                <?php foreach ($itens as $produto): ?>
                <?php
                        $img          = $produto->imagem_url ?? '';
                        $imagemPadrao = asset('admin/images/sem-foto/sem-foto.jpg');
                        $imagem       = $img === ''
                            ? $imagemPadrao
                            : (preg_match('#^https?://#i', $img) ? $img : uploadUrl('produtos', $img));

                        $specs = $especificacoesPorProduto[(int) $produto->id] ?? [];

                        // Lista completa (usada no modal)
                        $listaEspec = array_map(fn($s) => [
                            'nome'         => $s->medida_nome,
                            'preco'        => moedaBR($s->preco),
                            'customizavel' => (int) $s->customizavel,
                        ], $specs);

                        // Só o menor preço (usado no card)
                        $precos = array_map(fn($s) => (float) $s->preco, $specs);
                        $menor  = $precos ? min($precos) : null;
                        $varios = $precos && min($precos) !== max($precos);
                        ?>

                <div class="col-sm-6 menu-produto">
                    <a href="javascript:;" class="block js-produto-modal" data-nome="<?= e($produto->nome) ?>"
                        data-descricao="<?= e($produto->descricao ?? '') ?>"
                        data-ingredientes="<?= e($produto->ingredientes ?? '') ?>" data-imagem="<?= e($imagem) ?>"
                        data-fallback="<?= e($imagemPadrao) ?>"
                        data-especificacoes="<?= e(json_encode($listaEspec, JSON_UNESCAPED_UNICODE)) ?>">
                        <div class="content">
                            <div class="filter_item_img">
                                <i class="fa fa-search-plus"></i>
                                <img src="<?= e($imagem) ?>" alt="<?= e($produto->nome) ?>"
                                    onerror="this.onerror=null;this.src='<?= e($imagemPadrao) ?>';" />
                            </div>
                            <div class="info">
                                <div class="name"><?= e($produto->nome) ?></div>
                                <div class="short"><?= e($produto->descricao ?? '') ?></div>
                                <?php if ($menor !== null): ?>
                                <span class="filter_item_price">
                                    <?php if ($varios): ?>
                                    <small style="display:block;font-size:11px;line-height:1.2;">a partir de</small>
                                    <?php endif; ?>
                                    <?= e(moedaBR($menor)) ?>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="text-center">
            <!-- Paginação (montada pelo JS) -->
            <ul class="pagination" id="menu_pagination"></ul>
        </div>

    </div>
</div>

<!--    Reservation    -->

<div class="fixed_layer section" id="reservation">
    <div class="fixed_layer_padd container">
        <div class="row">
            <div class="col-md-offset-6 col-md-6" data-aos="fade-down">
                <div class="reserv_box">
                    <h1 class="section-title title_sty1">online reservation</h1>
                    <p class="short">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
                    <form id="reserv_form" method="post">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group form_pos">
                                    <input type="text" name="name" required="" placeholder="Your name"
                                        class="form-control" onfocus="this.placeholder = ''"
                                        onblur="this.placeholder = 'Your name'" />
                                    <span class="form_icon"></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form_pos">
                                    <input type="email" name="email" required="" placeholder="Your email"
                                        class="form-control" onfocus="this.placeholder = ''"
                                        onblur="this.placeholder = 'Your email'" />
                                    <span class="form_icon"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group form_pos">
                                    <input type="text" name="phone" required="" placeholder="Phone" class="form-control"
                                        onfocus="this.placeholder = ''" onblur="this.placeholder = 'Phone'" />
                                    <span class="form_icon"></span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form_pos">
                                    <input type="text" name="date" required="" placeholder="Date" class="form-control"
                                        id="reserv_date" onfocus="this.placeholder = ''"
                                        onblur="this.placeholder = 'Date'" />
                                    <span class="form_icon"></span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form_pos">
                                    <input type="text" name="time" required="" placeholder="Time" class="form-control"
                                        id="reserv_time" onfocus="this.placeholder = ''"
                                        onblur="this.placeholder = 'Time'" />
                                    <span class="form_icon"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea rows="3" name="message" placeholder="Message" class="form-control"
                                onfocus="this.placeholder = ''" onblur="this.placeholder = 'Message'"></textarea>
                        </div>
                        <input type="submit" name="send" value="book now" class="btn btn-block" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!--    Gallery    -->
<div class="container section" id="gallery" data-aos="fade-up">
    <div class="title-block">
        <h1 class="section-title">Gallery</h1>
    </div>
    <div id="photo_gallery" class="list1">
        <div class="row loadMore">
            <div class="col-sm-4 col-md-3 item">
                <a href="<?= asset('home/img/photos/gallery-1.jpg') ?>" class="block fancybox"
                    data-fancybox-group="fancybox">
                    <div class="content">
                        <img src="<?= asset('home/img/photos/gallery-1.jpg') ?>" alt="sample" />
                        <div class="zoom">
                            <span class="zoom_icon"><i class="fa fa-search-plus"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4 col-md-3 item">
                <a href="<?= asset('home/img/photos/gallery-2.jpg') ?>" class="block fancybox"
                    data-fancybox-group="fancybox">
                    <div class="content">
                        <img src="<?= asset('home/img/photos/gallery-2.jpg') ?>" alt="sample" />
                        <div class="zoom">
                            <span class="zoom_icon"><i class="fa fa-search-plus"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4 col-md-3 item">
                <a href="<?= asset('home/img/photos/gallery-3.jpg') ?>" class="block fancybox"
                    data-fancybox-group="fancybox">
                    <div class="content">
                        <img src="<?= asset('home/img/photos/gallery-3.jpg') ?>" alt="sample" />
                        <div class="zoom">
                            <span class="zoom_icon"><i class="fa fa-search-plus"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4 col-md-3 item">
                <a href="<?= asset('home/img/photos/gallery-4.jpg') ?>" class="block fancybox"
                    data-fancybox-group="fancybox">
                    <div class="content">
                        <img src="<?= asset('home/img/photos/gallery-4.jpg') ?>" alt="sample" />
                        <div class="zoom">
                            <span class="zoom_icon"><i class="fa fa-search-plus"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4 col-md-3 item">
                <a href="<?= asset('home/img/photos/gallery-5.jpg') ?>" class="block fancybox"
                    data-fancybox-group="fancybox">
                    <div class="content">
                        <img src="<?= asset('home/img/photos/gallery-5.jpg') ?>" alt="sample" />
                        <div class="zoom">
                            <span class="zoom_icon"><i class="fa fa-search-plus"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4 col-md-3 item">
                <a href="<?= asset('home/img/photos/gallery-6.jpg') ?>" class="block fancybox"
                    data-fancybox-group="fancybox">
                    <div class="content">
                        <img src="<?= asset('home/img/photos/gallery-6.jpg') ?>" alt="sample" />
                        <div class="zoom">
                            <span class="zoom_icon"><i class="fa fa-search-plus"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4 col-md-3 item">
                <a href="<?= asset('home/img/photos/gallery-7.jpg') ?>" class="block fancybox"
                    data-fancybox-group="fancybox">
                    <div class="content">
                        <img src="<?= asset('home/img/photos/gallery-7.jpg') ?>" alt="sample" />
                        <div class="zoom">
                            <span class="zoom_icon"><i class="fa fa-search-plus"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4 col-md-3 item">
                <a href="<?= asset('home/img/photos/gallery-8.jpg') ?>" class="block fancybox"
                    data-fancybox-group="fancybox">
                    <div class="content">
                        <img src="<?= asset('home/img/photos/gallery-8.jpg') ?>" alt="sample" />
                        <div class="zoom">
                            <span class="zoom_icon"><i class="fa fa-search-plus"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4 col-md-3 item">
                <a href="<?= asset('home/img/photos/gallery-1.jpg') ?>" class="block fancybox"
                    data-fancybox-group="fancybox">
                    <div class="content">
                        <img src="<?= asset('home/img/photos/gallery-1.jpg') ?>" alt="sample" />
                        <div class="zoom">
                            <span class="zoom_icon"><i class="fa fa-search-plus"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4 col-md-3 item">
                <a href="<?= asset('home/img/photos/gallery-2.jpg') ?>" class="block fancybox"
                    data-fancybox-group="fancybox">
                    <div class="content">
                        <img src="<?= asset('home/img/photos/gallery-2.jpg') ?>" alt="sample" />
                        <div class="zoom">
                            <span class="zoom_icon"><i class="fa fa-search-plus"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4 col-md-3 item">
                <a href="<?= asset('home/img/photos/gallery-3.jpg') ?>" class="block fancybox"
                    data-fancybox-group="fancybox">
                    <div class="content">
                        <img src="<?= asset('home/img/photos/gallery-3.jpg') ?>" alt="sample" />
                        <div class="zoom">
                            <span class="zoom_icon"><i class="fa fa-search-plus"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4 col-md-3 item">
                <a href="<?= asset('home/img/photos/gallery-4.jpg') ?>" class="block fancybox"
                    data-fancybox-group="fancybox">
                    <div class="content">
                        <img src="<?= asset('home/img/photos/gallery-4.jpg') ?>" alt="sample" />
                        <div class="zoom">
                            <span class="zoom_icon"><i class="fa fa-search-plus"></i></span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- End Sections -->


<!-- Modal do produto -->
<div class="modal fade" id="produtoModal" tabindex="-1" role="dialog" aria-labelledby="produtoModalNome">
    <div class="modal-dialog produto-modal-dialog" role="document">
        <div class="modal-content produto-modal">

            <button type="button" class="produto-modal-fechar" data-dismiss="modal" aria-label="Fechar">
                <span aria-hidden="true">&times;</span>
            </button>

            <div class="produto-modal-img">
                <img id="produtoModalImg" src="" alt="">
            </div>

            <div class="produto-modal-corpo">
                <h3 id="produtoModalNome"></h3>
                <p id="produtoModalDescricao" class="produto-modal-texto"></p>

                <div id="produtoModalIngredientesBox" class="produto-modal-bloco">
                    <h5>Ingredientes</h5>
                    <p id="produtoModalIngredientes" class="produto-modal-texto"></p>
                </div>

                <div id="produtoModalPrecosBox" class="produto-modal-bloco">
                    <h5>Medidas e preços</h5>
                    <ul id="produtoModalPrecos" class="list-unstyled"></ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os scripts -->

<?php View::start('scripts'); ?>
<script nonce="<?= e(cspNonce()) ?>">
$(function() {

    // ── Modal do produto ──────────────────────────────────────────
    $(document).on('click', '.js-produto-modal', function() {
        var $b = $(this);
        var nome = $b.data('nome') || '';
        var descricao = $b.data('descricao') || '';
        var ingred = $b.data('ingredientes') || '';
        var specs = $b.data('especificacoes') || [];
        var fallback = $b.data('fallback') || '';

        $('#produtoModalNome').text(nome);
        $('#produtoModalDescricao').text(descricao).toggle(descricao !== '');

        $('#produtoModalIngredientes').text(ingred);
        $('#produtoModalIngredientesBox').toggle(ingred !== '');

        $('#produtoModalImg')
            .off('error')
            .on('error', function() {
                $(this).off('error');
                if (fallback && this.src !== fallback) this.src = fallback;
            })
            .attr('src', $b.data('imagem') || fallback)
            .attr('alt', nome);

        // Medidas e preços (moedaBR já devolve "R$ 14,99")
        var $lista = $('#produtoModalPrecos').empty();

        $.each(specs, function(i, s) {
            var $li = $('<li>').append($('<span>').text(s.nome));

            if (Number(s.customizavel) === 1) {
                $li.append($('<span class="preco-tag">').text('customizável'));
            }

            $li.append($('<span class="preco-valor">').text(s.preco)).appendTo($lista);
        });

        $('#produtoModalPrecosBox').toggle(specs.length > 0);

        $('#produtoModal').modal('show');
    });

    $('#produtoModal').on('hidden.bs.modal', function() {
        $('#produtoModalImg').off('error').attr('src', '');
    });

    // ── Paginação do cardápio (por categoria) ─────────────────────
    var POR_PAGINA = 4;
    var $pag = $('#menu_pagination');
    var slugAtual = null;

    function itensDaCategoria(slug) {
        return $('#menu_items .filtr-item')
            .filter(function() {
                return $(this).attr('data-categoria') === slug;
            })
            .find('.menu-produto');
    }

    function renderPagina(slug, pagina) {
        var $itens = itensDaCategoria(slug);
        var paginas = Math.ceil($itens.length / POR_PAGINA);

        pagina = Math.max(1, Math.min(pagina, paginas || 1));

        $itens.each(function(i) {
            $(this).toggleClass('pag-oculto',
                i < (pagina - 1) * POR_PAGINA || i >= pagina * POR_PAGINA);
        });

        $pag.empty();

        if (paginas <= 1) return;

        function li(texto, p, classe) {
            return $('<li>').addClass(classe || '')
                .append($('<a href="javascript:;">').attr('data-p', p).html(texto));
        }

        $pag.append(li('&laquo;', pagina - 1, pagina === 1 ? 'disabled' : ''));

        for (var p = 1; p <= paginas; p++) {
            $pag.append(li(p, p, p === pagina ? 'active' : ''));
        }

        $pag.append(li('&raquo;', pagina + 1, pagina === paginas ? 'disabled' : ''));
    }

    $pag.on('click', 'a', function() {
        var $li = $(this).parent();
        if ($li.hasClass('disabled') || $li.hasClass('active')) return;

        renderPagina(slugAtual, parseInt($(this).attr('data-p'), 10));

        $('html, body').animate({
            scrollTop: $('#menu').offset().top - 80
        }, 300);
    });

    // Troca de categoria: volta para a página 1
    $(document).on('click', '.filter-button', function() {
        slugAtual = $(this).attr('data-filter');
        renderPagina(slugAtual, 1);
    });

    // Estado inicial: categoria ativa
    slugAtual = $('.menu_filter .item.active .filter-button').attr('data-filter') ||
        $('.menu_filter .filter-button').first().attr('data-filter');

    if (slugAtual) renderPagina(slugAtual, 1);

});
</script>
<?php View::end(); ?>