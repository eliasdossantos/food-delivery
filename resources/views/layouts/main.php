<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">

    <title><?= e(APP_NAME) ?> - <?= e(\Framework\Support\View::title($titulo ?? 'Área administrativa')) ?></title>

    <!-- plugins:css -->
    <link rel="stylesheet" href="<?= asset('admin/vendors/mdi/css/materialdesignicons.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('admin/vendors/base/vendor.bundle.base.css') ?>">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <link rel="stylesheet" href="<?= asset('admin/vendors/datatables.net-bs4/dataTables.bootstrap4.css') ?>">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="<?= asset('admin/css/style.css') ?>">
    <!-- endinject -->
    <link rel="shortcut icon" href="<?= asset('admin/images/favicon.png') ?>" />

    <!-- CSS específico da página (opcional): View::start('styles') ... View::end() -->
    <?= \Framework\Support\View::section('styles') ?>
</head>

<body>
    <div class="container-scroller">

        <!-- Topbar principal do sistema -->
        <?= \Framework\Support\View::render('components.topbar') ?>

        <div class="container-fluid page-body-wrapper">

            <!-- Sidebar principal do sistema -->
            <?= \Framework\Support\View::render('components.sidebar') ?>

            <div class="main-panel">
                <div class="content-wrapper">

                    <!-- Alertas exibidos em todo o sistema -->
                    <?= \Framework\Support\View::render('components.alerts') ?>

                    <!-- Conteúdo principal da página -->
                    <?= \Framework\Support\View::content($content ?? '') ?>

                </div>

                <!-- Conteúdo footer da página -->
                <?= \Framework\Support\View::render('components.footer') ?>
            </div>
        </div>
    </div>

    <script src="<?= asset('admin/vendors/base/vendor.bundle.base.js') ?>"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <script src="<?= asset('admin/vendors/chart.js/Chart.min.js') ?>"></script>
    <script src="<?= asset('admin/vendors/datatables.net/jquery.dataTables.js') ?>"></script>
    <script src="<?= asset('admin/vendors/datatables.net-bs4/dataTables.bootstrap4.js') ?>"></script>
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="<?= asset('admin/js/off-canvas.js') ?>"></script>
    <script src="<?= asset('admin/js/hoverable-collapse.js') ?>"></script>
    <script src="<?= asset('admin/js/template.js') ?>"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="<?= asset('admin/js/dashboard.js') ?>"></script>
    <script src="<?= asset('admin/js/jquery.dataTables.js') ?>"></script>
    <script src="<?= asset('admin/js/dataTables.bootstrap4.js') ?>"></script>
    <script src="<?= asset('admin/js/data-table.js') ?>"></script>
    <!-- End custom js for this page-->
    <script src="<?= asset('admin/js/jquery.cookie.js') ?>" type="text/javascript"></script>

    <!-- Mask plugin -->
    <script src="<?= asset('admin/js/app.js') ?>"></script>
    <script src="<?= asset('admin/js/jquery.mask.min.js') ?>"></script>
    <script src="<?= asset('admin/js/alert-autohide.js') ?>"></script>

    <!-- JS específico da página (opcional): View::start('scripts') ... View::end() -->
    <?= \Framework\Support\View::section('scripts') ?>
</body>

</html>