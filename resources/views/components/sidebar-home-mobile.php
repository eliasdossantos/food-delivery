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
        <li><a class="page-scroll" href="#header">Início</a></li>
        <li><a class="page-scroll" href="#about_us">Sobre nós</a></li>
        <li><a class="page-scroll" href="#menu">Cardápio</a></li>
        <li><a class="page-scroll" href="#gallery">Galeria</a></li>
        <li><a class="page-scroll" href="#reservation">Reservas</a></li>
        <li><a class="page-scroll" href="#footer">Contato</a></li>
    </ul>
    <div class="right_menu_addr top_addr">
        <span><i class="fa fa-map-marker" aria-hidden="true"></i> Seu país, sua cidade, 12345</span>
        <span><i class="fa fa-phone" aria-hidden="true"></i> 123 456 789</span>
        <span><i class="fa fa-clock-o" aria-hidden="true"></i> 11:00 - 21:00</span>
    </div>
</nav>