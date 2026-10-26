<nav class="cd-nav-container right_menu" id="cd-nav">
    <div class="header__open_menu">
        <a href="<?= url('/') ?>" class="rmenu_logo" title="yagmurmebel.az">
            <img src="<?= asset('home/img/logo.png') ?>" alt="logo" />
        </a>
    </div>
    <div class="right_menu_search">
        <form method="post">
            <input type="text" name="q" class="form-control search_input" value=""
                placeholder="Pesquise qualquer coisa">
            <button type="submit" class="search_icon"><i class="fa fa-search"></i></button>
        </form>
    </div>
    <ul class="rmenu_list">
        <li><a href="<?= url('/') ?>">Início</a></li>
        <li><a class="<?= homeAnchorClass() ?>" href="<?= homeAnchor('menu') ?>">Cardápio</a></li>
        <li><a class="<?= homeAnchorClass() ?>" href="<?= homeAnchor('gallery') ?>">Galeria</a></li>
        <li><a class="<?= homeAnchorClass() ?>" href="<?= homeAnchor('footer') ?>">Contato</a></li>
        <li><a href="<?= url('/produtos') ?>">Produtos</a></li>
    </ul>
    <div class="right_menu_addr top_addr">
        <?= \Framework\Support\View::render('components.contato-topo') ?>
    </div>
</nav>