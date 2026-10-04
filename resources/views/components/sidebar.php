<?php
$caminhoAtual = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

// Remove o caminho base da aplicação, se necessário.
// Exemplo: /meu-projeto/admin/perfil -> /admin/perfil
$caminhoBase = parse_url(url('/'), PHP_URL_PATH) ?? '';

if ($caminhoBase !== '' && $caminhoBase !== '/' && str_starts_with($caminhoAtual, $caminhoBase)) {
    $caminhoAtual = substr($caminhoAtual, strlen($caminhoBase));
}

$caminhoAtual = '/' . trim($caminhoAtual, '/');
$caminhoAtual = $caminhoAtual === '/' ? '/' : rtrim($caminhoAtual, '/');

$menuAtivo = static function (string $rota) use ($caminhoAtual): string {
    $rota = '/' . trim($rota, '/');
    $rota = $rota === '/' ? '/' : rtrim($rota, '/');

    return $caminhoAtual === $rota ? ' active' : '';
};
?>

<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        <li class="nav-item">
            <a class="nav-link<?= $menuAtivo('/admin') ?>" href="<?= url('/admin') ?>">
                <i class="mdi mdi-home menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link<?= $menuAtivo('/admin/perfil') ?>" href="<?= url('/admin/perfil') ?>">
                <i class="mdi mdi-account-card-details menu-icon"></i>
                <span class="menu-title">Perfis</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link<?= $menuAtivo('/admin/usuario') ?>" href="<?= url('/admin/usuario') ?>">
                <i class="mdi mdi-account-multiple menu-icon"></i>
                <span class="menu-title">Usuários</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        <li class="nav-item nav-category">
            <span class="nav-link">Área dos Clientes</span>
        </li>

        <li class="nav-item">
            <a class="nav-link<?= $menuAtivo('/admin/cliente') ?>" href="<?= url('/admin/cliente') ?>">
                <i class="mdi mdi-account-switch menu-icon"></i>
                <span class="menu-title">Clientes</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        <li class="nav-item nav-category">
            <span class="nav-link">Configurações do Sistema</span>
        </li>

        <li class="nav-item">
            <a class="nav-link<?= $menuAtivo('/admin/configuracoes') ?>" href="<?= url('/admin/configuracoes') ?>">
                <i class="mdi mdi-alert-decagram menu-icon"></i>
                <span class="menu-title">Configurações</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="<?= url('/admin/logout') ?>">
                <i class="mdi mdi-logout menu-icon"></i>
                <span class="menu-title">Sair</span>
            </a>
        </li>

    </ul>
</nav>