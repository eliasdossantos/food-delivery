<div class="navbar-container" data-spy="affix" data-offset-top="400">
    <div class="container">

        <div class="navbar_top hidden-xs">
            <div class="top_addr">
                <span><i class="fa fa-map-marker" aria-hidden="true"></i> Seu país, sua cidade,
                    12345</span>
                <span><i class="fa fa-phone" aria-hidden="true"></i> 123 456 789</span>
                <span><i class="fa fa-clock-o" aria-hidden="true"></i> 11:00 - 21:00</span>
                <div class="pull-right search-block">
                    <i class="fa fa-search" id="search" aria-hidden="true"></i>
                </div>
            </div>
            <div id="navbar_search">
                <form method="post">
                    <input type="text" name="q" class="form-control pull-left" value=""
                        placeholder="Pesquise qualquer coisa">
                    <button type="submit" class="pull-right close" id="search_close"><i
                            class="fa fa-close"></i></button>
                </form>
            </div>
        </div>
        <!-- /.navbar_top -->

        <!-- INÍCIO navbar -->
        <nav class="navbar">
            <div id="navbar_content">
                <!-- Marca e botão de menu agrupados para melhor exibição no mobile -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="<?= url('/') ?>">
                        <img src="<?= asset('home/img/logo.png') ?>" alt="logo" />
                    </a>
                    <a href="#cd-nav" class="cd-nav-trigger right_menu_icon">
                        <span><i class="fa fa-bars" aria-hidden="true"></i></span>
                    </a>
                </div>

                <!-- Links de navegação, formulários e outros conteúdos do menu -->
                <div class="collapse navbar-collapse" id="navbar">
                    <div class="navbar-right">
                        <ul class="nav navbar-nav">
                            <li><a class="page-scroll" href="#header">Início</a></li>
                            <li><a class="page-scroll" href="#about_us">Sobre nós</a></li>
                            <li><a class="page-scroll" href="#menu">Cardápio</a></li>
                            <li><a class="page-scroll" href="#gallery">Galeria</a></li>
                            <li><a class="page-scroll" href="#reservation">Reservas</a></li>
                            <li><a class="page-scroll" href="#footer">Contato</a></li>
                        </ul>
                    </div>
                </div>
                <!-- /.navbar-collapse -->
            </div>
        </nav>
    </div>
    <!-- FIM navbar -->
</div>