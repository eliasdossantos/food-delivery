<?php

use App\Repositories\BannerRepository;

// Busca aqui porque o componente é renderizado pelo layout, sem receber variáveis do controller
$banners = (new BannerRepository())->vigentes(5);
?>

<style>
.carousel-caption .item_info {
    height: auto;
    max-height: none;
    overflow: visible;
    white-space: normal;
    word-wrap: break-word;
}
</style>
<div id="main-carousel" class="carousel slide" data-ride="carousel">
    <div class="container pos_rel" style="min-height: 1vh !important;">

        <?php if (!empty($banners)): ?>

        <!-- Indicators -->
        <ol class="carousel-indicators">
            <?php foreach ($banners as $i => $banner): ?>
            <li data-target="#main-carousel" data-slide-to="<?= $i ?>" class="<?= $i === 0 ? 'active' : '' ?>"></li>
            <?php endforeach; ?>
        </ol>

        <?php if (count($banners) > 1): ?>
        <a class="left carousel-control" href="#main-carousel" role="button" data-slide="prev">
            <i class="fa fa-angle-left" aria-hidden="true"></i>
        </a>
        <a class="right carousel-control" href="#main-carousel" role="button" data-slide="next">
            <i class="fa fa-angle-right" aria-hidden="true"></i>
        </a>
        <?php endif; ?>

        <div class="carousel-inner" role="listbox">
            <?php foreach ($banners as $i => $banner): ?>
            <?php
                    $imagemUrl  = bannerImagemUrl($banner);
                    $temBadge   = trim((string) $banner->badge_destaque) !== '';
                    $temBotao   = trim((string) $banner->botao_texto) !== '';
                    $link       = trim((string) $banner->botao_link);
                    $classeLink = substr($link, 0, 1) === '#' ? 'item_link page-scroll' : 'item_link';
                    ?>
            <div class="item <?= $i === 0 ? 'active' : '' ?>">
                <div class="carousel-caption">

                    <?php if ($imagemUrl !== ''): ?>
                    <div class="fadeUp item_img">
                        <img src="<?= e($imagemUrl) ?>" alt="<?= e($banner->titulo) ?>" />
                        <?php if ($temBadge): ?>
                        <div class="item_badge">
                            <span class="badge_btext"><?= e($banner->badge_destaque) ?></span>
                            <?php if (trim((string) $banner->badge_rotulo) !== ''): ?>
                            <span class="badge_stext"><?= e($banner->badge_rotulo) ?></span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <div class="fadeUp fade-slow item_details">
                        <h4 class="item_name"><?= e($banner->titulo) ?></h4>
                        <?php if (!empty($banner->descricao)): ?>
                        <p class="item_info"><?= e($banner->descricao) ?></p>
                        <?php endif; ?>
                        <?php if ($temBotao && $link !== ''): ?>
                        <div class="item_link_box">
                            <a href="<?= e($link) ?>" class="<?= $classeLink ?>"><?= e($banner->botao_texto) ?></a>
                        </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php else: ?>

        <!-- Sem banner vigente: slide padrão para o layout não quebrar -->
        <div class="carousel-inner" role="listbox">
            <div class="item active">
                <div class="carousel-caption">
                    <div class="fadeUp item_img">
                        <img src="<?= asset('home/img/photos/pizza.png') ?>" alt="Bem-vindo(a)" />
                    </div>
                    <div class="fadeUp fade-slow item_details">
                        <h4 class="item_name">Bem-vindo(a)!</h4>
                        <p class="item_info">
                            Peça sua comida favorita com entrega rápida e saborosa, direto na sua casa.
                        </p>
                        <div class="item_link_box">
                            <a href="#menu" class="item_link page-scroll">Ver cardápio</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php endif; ?>

    </div>
</div>