<style>
    .alert-close {
        background: none;
        border: none;
        padding: 0;
        margin-left: 12px;
        font-size: 16px;
        line-height: 1;
        color: inherit;
        opacity: 0.6;
        cursor: pointer;
    }

    .alert-close:hover {
        opacity: 1;
    }

    .alert-close:focus {
        outline: none;
        opacity: 1;
    }
</style>

<?php
// Tipos de flash aceitos (Bootstrap + cores extras)
$alertTypes = [
    'success',
    'error',
    'danger',
    'warning',
    'info',
    'primary',
    'secondary',
    'light',
    'dark',
    'orange',
    'purple',
    'pink',
    'teal',
    'indigo',
    'brown',
    'lime',
    'gray',
];

// Tipo do flash => classe Bootstrap 4 (ou classe própria para as cores extras)
$bootstrapClass = [
    'success'   => 'success',
    'error'     => 'danger',
    'danger'    => 'danger',
    'warning'   => 'warning',
    'info'      => 'info',
    'primary'   => 'primary',
    'secondary' => 'secondary',
    'light'     => 'light',
    'dark'      => 'dark',
    'orange'    => 'orange',
    'purple'    => 'purple',
    'pink'      => 'pink',
    'teal'      => 'teal',
    'indigo'    => 'indigo',
    'brown'     => 'brown',
    'lime'      => 'lime',
    'gray'      => 'gray',
];

// Cores extras (fundo, borda, texto). O CSS é gerado a partir daqui.
$customColors = [
    'orange' => ['bg' => '#fff1e6', 'border' => '#ffc999', 'text' => '#a14a00'],
    'purple' => ['bg' => '#efe8fb', 'border' => '#c9b6f0', 'text' => '#4a2a8a'],
    'pink'   => ['bg' => '#fde7f3', 'border' => '#f3a8d0', 'text' => '#8a1f5c'],
    'teal'   => ['bg' => '#e0f4f4', 'border' => '#95d5d5', 'text' => '#0b5555'],
    'indigo' => ['bg' => '#e8eaf9', 'border' => '#aab2e8', 'text' => '#2b3a8f'],
    'brown'  => ['bg' => '#f3e9e1', 'border' => '#d2b8a3', 'text' => '#5e3a1f'],
    'lime'   => ['bg' => '#f1f8dc', 'border' => '#cde58a', 'text' => '#4d6200'],
    'gray'   => ['bg' => '#eceff1', 'border' => '#c3cbd0', 'text' => '#37474f'],
];

// Tempo (ms) até fechar sozinho
$autoHideMs = 5000;
?>

<style>
    <?php foreach ($customColors as $name => $c): ?>.alert.alert-<?= $name ?> {
        background-color: <?= $c['bg'] ?>;
        border-color: <?= $c['border'] ?>;
        color: <?= $c['text'] ?>;
    }

    <?php endforeach;
    ?>
</style>

<?php
foreach ($alertTypes as $alertType):
    // Pega a mensagem simples do flash
    $alertMsg = \Framework\Support\Session::getFlash($alertType);

    // Se for tipo 'error', junta com os erros vindos do Validator ($_SESSION['_errors'])
    $validationErrors = ($alertType === 'error') ? (\Framework\Support\Session::get('_errors', [])) : [];

    // Se não tiver mensagem flash nem erros de validação, ignora
    if (!$alertMsg && empty($validationErrors)) {
        continue;
    }

    // Monta o array final de mensagens deste tipo
    $messages = [];

    if ($alertMsg) {
        if (is_array($alertMsg)) {
            $messages = array_merge($messages, $alertMsg);
        } else {
            $messages[] = $alertMsg;
        }
    }

    if (!empty($validationErrors)) {
        foreach ($validationErrors as $fieldErrors) {
            if (is_array($fieldErrors)) {
                foreach ($fieldErrors as $err) {
                    $messages[] = $err;
                }
            } else {
                $messages[] = $fieldErrors;
            }
        }
    }

    // Evita duplicados idênticos
    $messages = array_unique($messages);

    $cssClass = $bootstrapClass[$alertType] ?? 'info';
?>
    <div class="alert alert-<?= $cssClass ?> alert-dismissible fade show d-flex justify-content-between align-items-start mb-3"
        role="alert" aria-live="polite" data-autohide="<?= (int) $autoHideMs ?>">
        <div class="alert-body mb-0 w-100">
            <?php if (count($messages) > 1): ?>
                <ul class="mb-0 pl-3">
                    <?php foreach ($messages as $msg): ?>
                        <li><?= e($msg) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <?= e(reset($messages)) ?>
            <?php endif; ?>
        </div>
        <button type="button" class="alert-close mt-1" data-dismiss="alert" aria-label="Fechar">&#x2715;</button>
    </div>
<?php endforeach; ?>