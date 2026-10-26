<?php
$site = dadosSite();
$c    = $site['config'];

// ── Textos e contato ─────────────────────────────────────────────────────
$sobre = trim((string) ($c->descricao ?? ''));

$linhaEndereco = '';
if ($c) {
    $rua    = trim((string) ($c->logradouro ?? ''));
    $numero = trim((string) ($c->numero ?? ''));
    $compl  = trim((string) ($c->complemento ?? ''));
    $cidade = trim((string) ($c->cidade ?? ''));
    $uf     = trim((string) ($c->estado ?? ''));
    $cep    = trim((string) ($c->cep ?? ''));

    $partes = array_filter([
        $numero !== '' && $rua !== '' ? "$rua, $numero" : $rua,
        $compl,
        trim((string) ($c->bairro ?? '')),
        $uf !== '' && $cidade !== '' ? "$cidade/$uf" : $cidade,
        $cep !== '' ? "CEP $cep" : '',
    ], fn($p) => $p !== '');

    $linhaEndereco = implode(' - ', $partes);
}

$telefones = array_filter([
    trim((string) ($c->telefone ?? '')),
    trim((string) ($c->whatsapp ?? '')),
], fn($t) => $t !== '');
$telefones = array_unique($telefones);

$email = trim((string) ($c->email ?? ''));
$mapa  = trim((string) ($c->link_google_maps ?? ''));

// ── Redes sociais: aceita URL completa ou só o @usuario ──────────────────
$linkSocial = function (?string $valor, string $base): string {
    $valor = trim((string) $valor);

    if ($valor === '') {
        return '';
    }

    return preg_match('#^https?://#i', $valor) ? $valor : $base . ltrim($valor, '@/');
};

$redes = array_filter([
    ['icone' => 'fa-facebook',     'titulo' => 'Facebook',  'url' => $linkSocial($c->facebook ?? '',  'https://facebook.com/')],
    ['icone' => 'fa-instagram',    'titulo' => 'Instagram', 'url' => $linkSocial($c->instagram ?? '', 'https://instagram.com/')],
    ['icone' => 'fa-youtube-play', 'titulo' => 'YouTube',   'url' => $linkSocial($c->youtube ?? '',   'https://youtube.com/@')],
    ['icone' => 'fa-twitter',      'titulo' => 'Twitter',   'url' => $linkSocial($c->twitter ?? '',   'https://x.com/')],
], fn($r) => $r['url'] !== '');

// ── Horários da semana (segunda a domingo) ───────────────────────────────
$nomesDias = [
    1 => 'Segunda',
    2 => 'Terça',
    3 => 'Quarta',
    4 => 'Quinta',
    5 => 'Sexta',
    6 => 'Sábado',
    0 => 'Domingo',
];
$hoje = (int) date('w');

$hora = fn($t) => substr((string) $t, 0, 5);

$rodape = trim((string) ($c->texto_rodape ?? ''));
$nome   = trim((string) ($c->nome_fantasia ?? ''));
?>

<!--  Begin Footer  -->
<footer id="footer">

    <!--    Google map, Social links    -->
    <div class="section" id="contact">
        <div id="googleMap"></div>
        <div class="footer_pos">
            <div class="container">
                <div class="footer_content">
                    <div class="row">

                        <!-- Sobre -->
                        <div class="col-sm-6 col-md-4">
                            <h4 class="footer_ttl footer_ttl_padd">Sobre nós</h4>
                            <?php if ($sobre !== ''): ?>
                                <p class="footer_txt"><?= e($sobre) ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Horários -->
                        <div class="col-sm-6 col-md-5">
                            <h4 class="footer_ttl footer_ttl_padd">Horário de funcionamento</h4>
                            <div class="footer_border">
                                <?php foreach ($nomesDias as $num => $nomeDia): ?>
                                    <?php
                                    $dia    = $site['expediente'][$num] ?? null;
                                    $aberto = $dia && (int) $dia->ativo && (int) $dia->aberto
                                        && !empty($dia->abertura) && !empty($dia->fechamento);
                                    $estilo = $num === $hoje ? ' style="font-weight:700;"' : '';
                                    ?>
                                    <div class="week_row clearfix" <?= $estilo ?>>
                                        <div class="week_day"><?= e($nomeDia) ?></div>

                                        <?php if (!$aberto): ?>
                                            <div class="week_time text-right">Fechado</div>
                                        <?php else: ?>
                                            <div class="week_time">
                                                <span class="week_time_start"><?= e($hora($dia->abertura)) ?></span>
                                                <span class="week_time_node">-</span>
                                                <span class="week_time_end"><?= e($hora($dia->fechamento)) ?></span>
                                                <?php if (!empty($dia->pausa_inicio) && !empty($dia->pausa_fim)): ?>
                                                    <small style="display:block;font-weight:400;opacity:.8;">
                                                        pausa <?= e($hora($dia->pausa_inicio)) ?> - <?= e($hora($dia->pausa_fim)) ?>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Contato -->
                        <div class="col-sm-12 col-md-3">
                            <h4 class="footer_ttl footer_ttl_padd">Contato</h4>
                            <div class="footer_border">
                                <?php if ($linhaEndereco !== ''): ?>
                                    <div class="footer_cnt">
                                        <i class="fa fa-map-marker"></i>
                                        <span><?= e($linhaEndereco) ?></span>
                                        <?php if ($mapa !== ''): ?>
                                            <a href="<?= e($mapa) ?>" target="_blank" rel="noopener">Ver no mapa</a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($telefones): ?>
                                    <div class="footer_cnt">
                                        <i class="fa fa-phone"></i>
                                        <span><?= e(implode('; ', $telefones)) ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if ($email !== ''): ?>
                                    <div class="footer_cnt">
                                        <i class="fa fa-envelope"></i>
                                        <span><?= e($email) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="copyright">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="copy_text">
                                <?php if ($rodape !== ''): ?>
                                    <?= e($rodape) ?>
                                <?php else: ?>
                                    &copy; <?= date('Y') ?> <?= e($nome !== '' ? $nome : APP_NAME) ?>. Todos os direitos
                                    reservados.
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <?php if ($redes): ?>
                                <div class="social-links">
                                    <ul class="list-inline">
                                        <?php foreach ($redes as $rede): ?>
                                            <li class="list-inline-item">
                                                <a href="<?= e($rede['url']) ?>" title="<?= e($rede['titulo']) ?>"
                                                    target="_blank" rel="noopener">
                                                    <i class="fa <?= e($rede['icone']) ?>" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- End Footer -->