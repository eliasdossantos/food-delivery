<?php View::start('titulo'); ?>Área do cliente | <?= e(APP_NAME) ?><?php View::end(); ?>
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Olá, <?= e($cliente->nome ?? 'cliente') ?>!</h4>
                <p class="card-description">Esta é sua área exclusiva para acompanhar pedidos e dados do cadastro.</p>
                <form method="POST" action="<?= url('/cliente/logout') ?>"><?= csrf_field() ?><button
                        class="btn btn-outline-primary" type="submit">Sair</button></form>
            </div>
        </div>
    </div>
</div>