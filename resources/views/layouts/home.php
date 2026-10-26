<!DOCTYPE html>
<html lang="pt-BR">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>

    <!-- Meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <meta name="description" content="Peça comida online com entrega rápida.">
    <meta name="author" content="Food Delivery">

    <title><?= e(APP_NAME) ?> - <?= e(\Framework\Support\View::title($titulo ?? 'Food Delivery')) ?></title>

    <!-- Stylesheets -->
    <link href="<?= asset('home/css/bootstrap.min.css') ?>" type="text/css" rel="stylesheet" media="all" />
    <link href="<?= asset('home/css/bootstrap-theme.min.css') ?>" type="text/css" rel="stylesheet" media="all" />
    <link href="<?= asset('home/css/fonts.css') ?>" type="text/css" rel="stylesheet" />
    <link href="<?= asset('home/css/font-awesome.min.css') ?>" type="text/css" rel="stylesheet" />
    <link href="<?= asset('home/css/slick.css') ?>" type="text/css" rel="stylesheet" />
    <link href="<?= asset('home/css/slick-theme.css') ?>" type="text/css" rel="stylesheet" />
    <link href="<?= asset('home/css/aos.css') ?>" type="text/css" rel="stylesheet" />
    <link href="<?= asset('home/css/scrolling-nav.css') ?>" type="text/css" rel="stylesheet" />
    <link href="<?= asset('home/css/bootstrap-datepicker.css') ?>" type="text/css" rel="stylesheet" />
    <link href="<?= asset('home/css/bootstrap-datetimepicker.css') ?>" type="text/css" rel="stylesheet" />
    <link href="<?= asset('home/css/touch-sideswipe.css') ?>" type="text/css" rel="stylesheet" />
    <link href="<?= asset('home/css/jquery.fancybox.css') ?>" type="text/css" rel="stylesheet" />
    <link href="<?= asset('home/css/main.css') ?>" type="text/css" rel="stylesheet" />
    <link href="<?= asset('home/css/responsive.css') ?>" type="text/css" rel="stylesheet" />

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= asset('home/img/favicon/apple-touch-icon.png') ?>" />
    <link rel="icon" type="image/png" sizes="256x256"
        href="<?= asset('home/img/favicon/android-chrome-256x256.png') ?>">
    <link rel="icon" type="image/png" sizes="192x192"
        href="<?= asset('home/img/favicon/android-chrome-192x192.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= asset('home/img/favicon/favicon-32x32.png') ?>" />
    <link rel="icon" type="image/png" sizes="16x16" href="<?= asset('home/img/favicon/favicon-16x16.png') ?>" />
    <link rel="icon" type="image/png" href="<?= asset('home/img/favicon/favicon.ico') ?>" />
    <link rel="manifest" href="<?= asset('home/img/site.html') ?>" />
    <link rel="mask-icon" href="<?= asset('home/img/favicon/safari-pinned-tab.svg') ?>" color="#5bbad5" />
    <meta name="msapplication-TileColor" content="#990100" />
    <meta name="theme-color" content="#ffffff" />

    <!-- CSS específico da página (opcional): View::start('styles') ... View::end() -->
    <?= \Framework\Support\View::section('styles') ?>
</head>

<body data-spy="scroll" data-target=".navbar" data-offset="50">

    <!-- BEGIN  Loading Section -->
    <!-- <div class="loading-overlay">
        <div class="spinner">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div> -->
    <!-- END Loading Section -->

    <!-- BEGIN body wrapper -->
    <div class="body-wrapper">

        <!-- Begin header-->
        <header id="header">

            <!-- Carousel principal do sistema -->
            <?= \Framework\Support\View::render('components.carousel-home') ?>
            <!-- END carousel -->

            <!-- BEGIN navigation -->
            <div class="navigation">

                <!-- Topbar principal do sistema -->
                <?= \Framework\Support\View::render('components.topbar-home') ?>

            </div>
            <!-- END navigation -->

        </header>
        <!-- End header -->

        <!-- Conteúdo principal da página -->
        <?= \Framework\Support\View::content($content ?? '') ?>
        <!-- end Conteúdo -->

        <!-- Conteúdo footer da página -->
        <?= \Framework\Support\View::render('components.footer-home') ?>
        <!-- end footer -->

    </div>
    <!-- END body-wrapper -->

    <!-- Sidebar mobile do sistema menu -->
    <?= \Framework\Support\View::render('components.sidebar-home-mobile') ?>
    <!-- end sidebar mobile -->

    <div class="cd-overlay"></div>
    <!-- /.cd-overlay -->

    <!-- JavaScript -->
    <script src="<?= asset('home/js/jquery-2.1.1.min.js') ?>"></script>
    <script src="<?= asset('home/js/bootstrap.min.js') ?>"></script>
    <script src="<?= asset('home/js/jquery.mousewheel.min.js') ?>"></script>
    <script src="<?= asset('home/js/jquery.easing.min.js') ?>"></script>
    <script src="<?= asset('home/js/scrolling-nav.js') ?>"></script>
    <script src="<?= asset('home/js/aos.js') ?>"></script>
    <script src="<?= asset('home/js/slick.min.js') ?>"></script>
    <script src="<?= asset('home/js/jquery.touchSwipe.min.js') ?>"></script>
    <script src="<?= asset('home/js/moment.js') ?>"></script>
    <script src="<?= asset('home/js/bootstrap-datepicker.js') ?>"></script>
    <script src="<?= asset('home/js/bootstrap-datetimepicker.js') ?>"></script>
    <script src="<?= asset('home/js/jquery.fancybox.js') ?>"></script>
    <script src="<?= asset('home/js/loadMoreResults.js') ?>"></script>
    <script src="<?= asset('home/js/main.js') ?>"></script>

    <!-- Mask plugin -->
    <script src="<?= asset('admin/js/app.js') ?>"></script>
    <script src="<?= asset('admin/js/jquery.mask.min.js') ?>"></script>
    <script src="<?= asset('admin/js/alert-autohide.js') ?>"></script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcg5Y2D1fpGI12T8wcbtPIsyGdw-_NV1Y&amp;callback=myMap">
    </script>

    <!-- JS específico da página (opcional): View::start('scripts') ... View::end() -->
    <?= \Framework\Support\View::section('scripts') ?>
</body>

</html>