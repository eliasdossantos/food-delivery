<!-- resources/views/admin/home/index.php-->

<!-- Aqui enviamos para o template principal o título da página -->

<?php View::start('titulo'); ?>

<?= e($subtitulo ?? 'Admin') ?>

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os estilos -->

<?php View::start('styles'); ?>

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal o conteúdo -->

<?php View::start('content'); ?>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body dashboard-tabs p-0">
                <div class="tab-content py-0 px-0">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                        <div class="d-flex flex-wrap justify-content-xl-between">
                            <!-- Total de Usuários -->
                            <div
                                class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                                <i class="mdi mdi-account-multiple mr-3 icon-lg text-primary"></i>
                                <div class="d-flex flex-column justify-content-around">
                                    <small class="mb-1 text-muted">
                                        Total de Usuários
                                    </small>
                                    <h5 class="mr-2 mb-0">
                                        <?= number_format((int) ($totalUsuarios ?? 0), 0, ',', '.') ?>
                                    </h5>
                                </div>
                            </div>
                            <!-- Total de Clientes -->
                            <div
                                class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                                <i class="mdi mdi-account-group mr-3 icon-lg text-success"></i>
                                <div class="d-flex flex-column justify-content-around">
                                    <small class="mb-1 text-muted">
                                        Total de Clientes
                                    </small>
                                    <h5 class="mr-2 mb-0">
                                        <?= number_format((int) ($totalClientes ?? 0), 0, ',', '.') ?>
                                    </h5>
                                </div>
                            </div>
                            <div
                                class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                                <i class="mdi mdi-package-variant-closed mr-3 icon-lg text-success"></i>
                                <div class="d-flex flex-column justify-content-around">
                                    <small class="mb-1 text-muted">Total de Produtos</small>
                                    <h5 class="mr-2 mb-0">
                                        <?= number_format((int) ($totalProdutos ?? 0), 0, ',', '.') ?></h5>
                                </div>
                            </div>
                            <div
                                class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                                <i class="mdi mdi-download mr-3 icon-lg text-warning"></i>
                                <div class="d-flex flex-column justify-content-around">
                                    <small class="mb-1 text-muted">Downloads</small>
                                    <h5 class="mr-2 mb-0">2233783</h5>
                                </div>
                            </div>
                            <div
                                class="d-flex py-3 border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                                <i class="mdi mdi-flag mr-3 icon-lg text-danger"></i>
                                <div class="d-flex flex-column justify-content-around">
                                    <small class="mb-1 text-muted">Flagged</small>
                                    <h5 class="mr-2 mb-0">3497843</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os scripts -->

<?php View::start('scripts'); ?>


<?php View::end(); ?>