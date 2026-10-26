<?php $info = infoTopo(); ?>

<?php if ($info['endereco'] !== ''): ?>
    <span><i class="fa fa-map-marker" aria-hidden="true"></i> <?= e($info['endereco']) ?></span>
<?php endif; ?>

<?php if ($info['telefone'] !== ''): ?>
    <span><i class="fa fa-phone" aria-hidden="true"></i> <?= e($info['telefone']) ?></span>
<?php endif; ?>

<?php if ($info['horario'] !== ''): ?>
    <span><i class="fa fa-clock-o" aria-hidden="true"></i> <?= e($info['horario']) ?></span>
<?php endif; ?>