<?php

use Framework\Auth\Auth;

$menuUri = rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');

// Dashboard: só quando a URL termina em /admin. Os demais: a rota e as telas filhas.
$menuDashboard = str_ends_with($menuUri, '/admin');
$menuUsuarios  = str_contains($menuUri, '/admin/usuario');
$menuCategorias  = str_contains($menuUri, '/admin/categoria');
$menuExtras  = str_contains($menuUri, '/admin/extra');
$menuClientes  = str_contains($menuUri, '/admin/cliente');
$menuConfig    = str_contains($menuUri, '/admin/configuracoes');
$menuPerfis    = str_contains($menuUri, '/admin/perfil');

// Abre o submenu "Sistema" quando a tela atual pertence a ele
$menuSistema = $menuClientes || $menuConfig || $menuPerfis;
?>

<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        <li class="nav-item<?= $menuDashboard ? ' active' : '' ?>">
            <a class="nav-link" href="<?= url('/admin') ?>">
                <i class="mdi mdi-home menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <li class="nav-item<?= $menuUsuarios ? ' active' : '' ?>">
            <a class="nav-link" href="<?= url('/admin/usuario') ?>">
                <i class="mdi mdi-account-multiple menu-icon"></i>
                <span class="menu-title">Usuários</span>
            </a>
        </li>

        <li class="nav-item<?= $menuCategorias ? ' active' : '' ?>">
            <a class="nav-link" href="<?= url('/admin/categoria') ?>">
                <i class="mdi mdi-shape menu-icon"></i>
                <span class="menu-title">Categorias</span>
            </a>
        </li>

        <li class="nav-item<?= $menuExtras ? ' active' : '' ?>">
            <a class="nav-link" href="<?= url('/admin/extra') ?>">
                <i class="mdi mdi-plus-circle-outline menu-icon"></i>
                <span class="menu-title">Extras</span>
            </a>
        </li>

        <li class="nav-item<?= $menuSistema ? ' active' : '' ?>">
            <a class="nav-link" data-toggle="collapse" href="#sistema"
                aria-expanded="<?= $menuSistema ? 'true' : 'false' ?>" aria-controls="sistema">
                <i class="mdi mdi-archive menu-icon"></i>
                <span class="menu-title">Sistema</span>
                <i class="menu-arrow"></i>
            </a>

            <div class="collapse<?= $menuSistema ? ' show' : '' ?>" id="sistema">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link<?= $menuClientes ? ' active' : '' ?>" href="<?= url('/admin/cliente') ?>">
                            Clientes
                        </a>
                    </li>

                    <?php if (Auth::isAny('Super Administrador')): ?>
                        <li class="nav-item">
                            <a class="nav-link<?= $menuConfig ? ' active' : '' ?>"
                                href="<?= url('/admin/configuracoes') ?>">
                                Configurações
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link<?= $menuPerfis ? ' active' : '' ?>" href="<?= url('/admin/perfil') ?>">
                                Perfis
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="<?= url('/admin/logout') ?>">
                <i class="mdi mdi-logout menu-icon"></i>
                <span class="menu-title">Sair</span>
            </a>
        </li>

    </ul>
</nav>