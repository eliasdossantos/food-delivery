<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= e($titulo ?? 'Acesso do cliente') ?> — <?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= asset('admin/vendors/mdi/css/materialdesignicons.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('admin/vendors/base/vendor.bundle.base.css') ?>">
    <link rel="stylesheet" href="<?= asset('admin/css/style.css') ?>">

    <!-- Estilos customizados padronizados com o admin -->
    <style>
        .auth-bg-custom {
            /* Imagem de fundo de delivery */
            background-image: url('<?= asset("admin/images/banner-login/banner-delivery-clientes.jpeg") ?>');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            min-height: 100vh;
        }

        /* Camada escura/fosca sobre a imagem */
        .auth-bg-custom::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.65);
            backdrop-filter: blur(3px);
            z-index: 1;
        }

        /* Garante que o card fique visível acima do background */
        .auth-content-overlay {
            position: relative;
            z-index: 2;
            width: 100%;
        }

        /* Card do formulário limpo e destacado */
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        <div
            class="container-fluid page-body-wrapper full-page-wrapper auth-bg-custom d-flex align-items-center justify-content-center">

            <div class="auth-content-overlay container">
                <div class="row justify-content-center">
                    <div class="col-12 col-sm-10 col-md-8 col-lg-5">

                        <div class="login-card p-4 p-sm-5">
                            <!-- Logo da marca -->
                            <div class="brand-logo mb-4 text-center">
                                <img src="<?= asset('admin/images/banner-login/logo-login.svg') ?>"
                                    alt="<?= e(APP_NAME) ?>" style="max-height: 80px;">
                            </div>

                            <!-- Alertas de erro/sucesso -->
                            <?= \Framework\Support\View::render('components.alerts') ?>

                            <!-- Conteúdo dinâmico das views de cliente -->
                            <?= \Framework\Support\View::content($content ?? '') ?>
                        </div>

                        <!-- Rodapé -->
                        <div class="text-center mt-3 text-white-50 small">
                            &copy; <?= date('Y') ?> <?= e(APP_NAME) ?>. Todos os direitos reservados.
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="<?= asset('admin/vendors/base/vendor.bundle.base.js') ?>"></script>
</body>

</html>