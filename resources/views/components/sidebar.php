<?php

use Framework\Auth\Auth;

/*
|--------------------------------------------------------------------------
| MENU LATERAL DA ÁREA ADMINISTRATIVA
|--------------------------------------------------------------------------
| 1. PERFIS  -> quem pode acessar (edite para mudar permissões)
| 2. MENU    -> o que existe no menu (edite para adicionar/remover telas)
| 3. LÓGICA  -> esconde o que o usuário não pode ver (não precisa mexer)
| 4. HTML    -> só desenha o menu (não precisa mexer)
|
| ATENÇÃO: o menu apenas ESCONDE links. O bloqueio real de acesso
| precisa existir também nos controllers.
*/

/*
|--------------------------------------------------------------------------
| 1. PERFIS
|--------------------------------------------------------------------------
| Cada grupo é uma lista de perfis, nomeada pelo que ele pode fazer.
| Os nomes devem ser idênticos aos da tabela perfis (inclusive acentos).
*/

$somenteSuper    = ['Super Administrador'];
$administradores = ['Super Administrador', 'Administrador'];
$gestao          = ['Super Administrador', 'Administrador', 'Gestor'];
$clientes        = ['Super Administrador', 'Administrador', 'Gestor', 'Atendente', 'Caixa'];
$produtos        = ['Super Administrador', 'Administrador', 'Gestor', 'Atendente', 'Cozinheiro'];

$pedidos           = ['Super Administrador', 'Administrador', 'Gestor', 'Atendente', 'Caixa'];
$cozinha           = ['Super Administrador', 'Administrador', 'Gestor', 'Cozinheiro'];
$caixa             = ['Super Administrador', 'Administrador', 'Gestor', 'Caixa'];
$somenteEntregador = ['Entregador'];

/*
|--------------------------------------------------------------------------
| 2. MENU
|--------------------------------------------------------------------------
| Item simples: titulo, icone, url, perfis
| Grupo:        id, titulo, icone, filhos (cada filho com titulo, url, perfis)
|
| - 'perfis' omitido = todos os perfis veem.
| - 'exato' => true: ativo só quando a URL TERMINA em 'url' (usado no Dashboard).
| - Um grupo só aparece se pelo menos um filho estiver visível.
| - Para adicionar: copie uma linha e ajuste. Para remover: apague a linha.
*/

$menu = [

    ['titulo' => 'Dashboard', 'icone' => 'mdi-home', 'url' => '/admin', 'exato' => true],

    // ['titulo' => 'Pedidos',         'icone' => 'mdi-receipt',       'url' => '/admin/pedido',          'perfis' => $pedidos],
    // ['titulo' => 'Cozinha',         'icone' => 'mdi-chef-hat',      'url' => '/admin/cozinha',         'perfis' => $cozinha],
    // ['titulo' => 'Minhas Entregas', 'icone' => 'mdi-bike-fast',     'url' => '/admin/minhas-entregas', 'perfis' => $somenteEntregador],
    // ['titulo' => 'Caixa',           'icone' => 'mdi-cash-register', 'url' => '/admin/caixa',           'perfis' => $caixa],

    ['titulo' => 'Clientes', 'icone' => 'mdi-account-group', 'url' => '/admin/cliente', 'perfis' => $clientes],

    [
        'id' => 'cardapio',
        'titulo' => 'Cardápio',
        'icone' => 'mdi-food',
        'filhos' => [
            ['titulo' => 'Produtos',   'url' => '/admin/produto',   'perfis' => $produtos],
            ['titulo' => 'Categorias', 'url' => '/admin/categoria', 'perfis' => $gestao],
            ['titulo' => 'Extras',     'url' => '/admin/extra',     'perfis' => $gestao],
            ['titulo' => 'Medidas',    'url' => '/admin/medida',    'perfis' => $gestao],
        ],
    ],

    [
        'id' => 'entregas',
        'titulo' => 'Entregas',
        'icone' => 'mdi-motorbike',
        'filhos' => [
            ['titulo' => 'Entregadores',      'url' => '/admin/entregador', 'perfis' => $gestao],
            ['titulo' => 'Bairros Atendidos', 'url' => '/admin/bairro',     'perfis' => $gestao],
        ],
    ],

    [
        'id' => 'estabelecimento',
        'titulo' => 'Estabelecimento',
        'icone' => 'mdi-store',
        'filhos' => [
            ['titulo' => 'Horário de Funcionamento', 'url' => '/admin/expediente', 'perfis' => $gestao],
            ['titulo' => 'Formas de Pagamento',      'url' => '/admin/pagamento',  'perfis' => $gestao],
            ['titulo' => 'Banners do sistema',       'url' => '/admin/banner',     'perfis' => $gestao],
            ['titulo' => 'Sobre nós',                'url' => '/admin/sobrenos',   'perfis' => $gestao],
        ],
    ],

    [
        'id' => 'administracao',
        'titulo' => 'Administração',
        'icone' => 'mdi-settings',
        'filhos' => [
            ['titulo' => 'Usuários',      'url' => '/admin/usuario',       'perfis' => $administradores],
            ['titulo' => 'Perfis',        'url' => '/admin/perfil',        'perfis' => $somenteSuper],
            ['titulo' => 'Configurações', 'url' => '/admin/configuracao',  'perfis' => $somenteSuper],
        ],
    ],
];

/*
|--------------------------------------------------------------------------
| 3. LÓGICA
|--------------------------------------------------------------------------
*/

// Caminho atual, sem barra final (ex.: '/admin/produto')
$uri = rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');

// O usuário logado tem algum destes perfis? (sem lista = liberado para todos)
$pode = fn(?array $perfis) => $perfis === null || array_filter($perfis, fn($p) => Auth::isAny($p)) !== [];

// A URL atual pertence a este item?
$ativo = fn(array $item) => !empty($item['exato'])
    ? str_ends_with($uri, $item['url'])
    : str_contains($uri, $item['url']);

// Remove do menu o que o usuário não pode ver
foreach ($menu as $chave => $item) {
    if (isset($item['filhos'])) {
        $menu[$chave]['filhos'] = array_filter($item['filhos'], fn($filho) => $pode($filho['perfis'] ?? null));

        if (!$menu[$chave]['filhos']) {
            unset($menu[$chave]); // grupo sem nenhum filho visível
        }
    } elseif (!$pode($item['perfis'] ?? null)) {
        unset($menu[$chave]);
    }
}
?>

<?php /* 4. HTML: só desenha o menu, sem regra de permissão */ ?>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        <?php foreach ($menu as $item): ?>

        <?php if (isset($item['filhos'])): ?>
        <?php $aberto = (bool) array_filter($item['filhos'], $ativo); ?>
        <li class="nav-item<?= $aberto ? ' active' : '' ?>">
            <a class="nav-link" data-toggle="collapse" href="#<?= e($item['id']) ?>"
                aria-expanded="<?= $aberto ? 'true' : 'false' ?>" aria-controls="<?= e($item['id']) ?>">
                <i class="mdi <?= e($item['icone']) ?> menu-icon"></i>
                <span class="menu-title"><?= e($item['titulo']) ?></span>
                <i class="menu-arrow"></i>
            </a>

            <div class="collapse<?= $aberto ? ' show' : '' ?>" id="<?= e($item['id']) ?>">
                <ul class="nav flex-column sub-menu">
                    <?php foreach ($item['filhos'] as $filho): ?>
                    <li class="nav-item">
                        <a class="nav-link<?= $ativo($filho) ? ' active' : '' ?>" href="<?= url($filho['url']) ?>">
                            <?= e($filho['titulo']) ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </li>

        <?php else: ?>
        <li class="nav-item<?= $ativo($item) ? ' active' : '' ?>">
            <a class="nav-link" href="<?= url($item['url']) ?>">
                <i class="mdi <?= e($item['icone']) ?> menu-icon"></i>
                <span class="menu-title"><?= e($item['titulo']) ?></span>
            </a>
        </li>
        <?php endif; ?>

        <?php endforeach; ?>

        <?php /* Sair: sempre visível, usa POST + CSRF */ ?>
        <li class="nav-item">
            <form method="POST" action="<?= url('/admin/logout') ?>" class="m-0">
                <?= csrf_field() ?>

                <button type="submit" class="nav-link w-100 border-0 bg-transparent text-left">
                    <i class="mdi mdi-logout menu-icon"></i>
                    <span class="menu-title">Sair</span>
                </button>
            </form>
        </li>

    </ul>
</nav>