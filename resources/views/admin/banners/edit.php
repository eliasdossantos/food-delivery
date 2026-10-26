<!-- resources/views/admin/banners/edit.php -->

<?php
$maxMb = (int) ($uploadMaxMb ?? 5);

// Imagem: URL externa é usada direto; arquivo local passa pelo uploadUrl()
$imagemAtual = $banner->imagem ?? '';
$imagemSrc = '';

if ($imagemAtual !== '') {
    $imagemSrc = preg_match('#^https?://#i', $imagemAtual)
        ? $imagemAtual
        : uploadUrl('banners', $imagemAtual);
}

/** Converte "2026-10-25 14:30:00" para o formato do campo datetime-local ("2026-10-25T14:30"). */
$paraInputData = static function (?string $valor): string {
    $ts = $valor ? strtotime($valor) : false;

    return $ts ? date('Y-m-d\TH:i', $ts) : '';
};
?>

<!-- Aqui enviamos para o template principal o título da página -->
<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Editar Banner') ?> | Admin

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os estilos -->
<?php View::start('styles'); ?>

<style>
.form-section {
    margin-top: 25px;
    margin-bottom: 20px;
}

.form-section-title {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.form-section-title::after {
    content: '';
    flex: 1;
    height: 2px;
    background: #878f96;
    margin-left: 15px;
}

.form-section-title h5 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    white-space: nowrap;
}

/* ── Campo de upload da imagem ────────────────────────────────────── */
.upload-box {
    border: 2px dashed #ced4da;
    border-radius: 12px;
    background: #f8f9fa;
    transition: border-color .2s, background .2s;
}

.upload-box.is-dragover,
.upload-box:hover {
    border-color: #4e73df;
    background: #eef3ff;
}

.upload-box.is-invalid {
    border-color: #dc3545;
}

.upload-drop {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 150px;
    padding: 12px;
    cursor: pointer;
}

/* O input cobre toda a área: clicar e arrastar funcionam nativamente */
.upload-input {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.upload-placeholder {
    text-align: center;
    color: #6c757d;
    pointer-events: none;
}

.upload-placeholder .mdi {
    display: block;
    font-size: 40px;
    line-height: 1.2;
    color: #4e73df;
}

.upload-placeholder small {
    display: block;
}

.upload-img {
    max-width: 100%;
    max-height: 180px;
    object-fit: cover;
    border-radius: 10px;
    pointer-events: none;
}

.upload-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 6px 12px 10px;
    font-size: 13px;
    color: #6c757d;
    min-height: 36px;
}

.upload-nome {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    margin-right: 10px;
}
</style>

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal o conteúdo -->

<?php View::start('content'); ?>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">

            <!-- Cabeçalho -->
            <div class="card-header bg-primary">
                <div class="row align-items-center mb-4">
                    <div class="col-md-8">
                        <h4 class="card-title mb-1 text-white">
                            <?= e($titulo ?? 'Editar Banner') ?>
                        </h4>
                        <p class="card-description mb-0 text-white">
                            <?= e($banner->titulo ?? '') ?>
                        </p>
                    </div>
                    <div class="col-md-4 text-md-right mt-3 mt-md-0">
                        <a href="<?= route('admin.banner.index') ?>" class="btn btn-secondary btn-sm text-white">
                            <i class="mdi mdi-arrow-left"></i>
                            Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">

                <!-- Formulário -->
                <form method="POST" action="<?= route('admin.banner.update', ['id' => $banner->id]) ?>"
                    enctype="multipart/form-data" class="forms-sample">
                    <?= csrf_field() ?>
                    <?= method_field('PUT') ?>

                    <!-- Conteúdo -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <h5>Conteúdo</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Título -->
                                <div class="form-group">
                                    <label for="titulo">Título</label>
                                    <input type="text"
                                        class="form-control <?= hasError('titulo') ? 'is-invalid' : '' ?>" id="titulo"
                                        name="titulo" maxlength="150"
                                        value="<?= old('titulo', $banner->titulo ?? '') ?>"
                                        placeholder="Título do banner" autofocus>
                                    <?= erroInput('titulo') ?>
                                </div>

                                <!-- Descrição -->
                                <div class="form-group">
                                    <label for="descricao">Descrição</label>
                                    <textarea class="form-control <?= hasError('descricao') ? 'is-invalid' : '' ?>"
                                        id="descricao" name="descricao" rows="4" maxlength="300"
                                        placeholder="Texto exibido abaixo do título"><?= old('descricao', $banner->descricao ?? '') ?></textarea>
                                    <?= erroInput('descricao') ?>
                                </div>
                            </div>

                            <!-- Imagem -->
                            <div class="form-group col-md-6">
                                <label for="imagem">Imagem</label>

                                <div class="upload-box <?= hasError('imagem') ? 'is-invalid' : '' ?>" id="uploadBox">
                                    <div class="upload-drop">
                                        <input type="file" class="upload-input" id="imagem" name="imagem"
                                            accept="image/*">

                                        <img id="uploadImg" class="upload-img <?= $imagemSrc === '' ? 'd-none' : '' ?>"
                                            src="<?= e($imagemSrc) ?>" data-atual="<?= e($imagemSrc) ?>"
                                            alt="Pré-visualização da imagem">

                                        <div id="uploadPlaceholder"
                                            class="upload-placeholder <?= $imagemSrc !== '' ? 'd-none' : '' ?>">
                                            <i class="mdi mdi-cloud-upload"></i>
                                            <span>Clique ou arraste uma imagem aqui</span>
                                            <small>JPG, PNG, GIF ou WEBP · até <?= $maxMb ?> MB</small>
                                        </div>
                                    </div>

                                    <div class="upload-info">
                                        <span class="upload-nome" id="uploadNome">
                                            <?= $imagemSrc !== '' ? 'Imagem atual (deixe como está para mantê-la)' : 'Nenhum arquivo selecionado' ?>
                                        </span>
                                        <button type="button" class="btn btn-outline-danger btn-sm d-none"
                                            id="uploadLimpar" title="Remover seleção" aria-label="Remover seleção">
                                            <i class="mdi mdi-close"></i> Remover
                                        </button>
                                    </div>
                                </div>

                                <small class="text-danger d-none" id="uploadErro"></small>
                                <?= erroInput('imagem') ?>
                            </div>
                        </div>
                    </div>

                    <!-- Selo de desconto -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <h5>Selo de desconto (opcional)</h5>
                        </div>

                        <div class="row">
                            <!-- Valor do selo -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="badge_destaque">Selo de desconto (valor)</label>
                                    <input type="text"
                                        class="form-control <?= hasError('badge_destaque') ? 'is-invalid' : '' ?>"
                                        id="badge_destaque" name="badge_destaque" maxlength="20"
                                        value="<?= old('badge_destaque', $banner->badge_destaque ?? '') ?>"
                                        placeholder="Ex.: 20%">
                                    <small class="form-text text-muted">
                                        Aparece sobre a imagem do banner. Em branco, o selo não é exibido.
                                    </small>
                                    <?= erroInput('badge_destaque') ?>
                                </div>
                            </div>

                            <!-- Texto do selo -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="badge_rotulo">Selo de desconto (texto)</label>
                                    <input type="text"
                                        class="form-control <?= hasError('badge_rotulo') ? 'is-invalid' : '' ?>"
                                        id="badge_rotulo" name="badge_rotulo" maxlength="20"
                                        value="<?= old('badge_rotulo', $banner->badge_rotulo ?? '') ?>"
                                        placeholder="Ex.: OFF">
                                    <?= erroInput('badge_rotulo') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botão -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <h5>Botão (opcional)</h5>
                        </div>

                        <div class="row">
                            <!-- Texto do botão -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="botao_texto">Texto do botão</label>
                                    <input type="text"
                                        class="form-control <?= hasError('botao_texto') ? 'is-invalid' : '' ?>"
                                        id="botao_texto" name="botao_texto" maxlength="100"
                                        value="<?= old('botao_texto', $banner->botao_texto ?? '') ?>"
                                        placeholder="Ex.: Fazer pedido">
                                    <small class="form-text text-muted">
                                        Em branco, o botão não é exibido.
                                    </small>
                                    <?= erroInput('botao_texto') ?>
                                </div>
                            </div>

                            <!-- Link do botão -->
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="botao_link">Link do botão</label>
                                    <input type="text"
                                        class="form-control <?= hasError('botao_link') ? 'is-invalid' : '' ?>"
                                        id="botao_link" name="botao_link" maxlength="500"
                                        value="<?= old('botao_link', $banner->botao_link ?? '') ?>"
                                        placeholder="Ex.: #reservation ou https://...">
                                    <?= erroInput('botao_link') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Período de exibição -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <h5>Período de exibição</h5>
                        </div>

                        <div class="row">
                            <!-- Início -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="data_inicio">Início</label>
                                    <input type="datetime-local"
                                        class="form-control <?= hasError('data_inicio') ? 'is-invalid' : '' ?>"
                                        id="data_inicio" name="data_inicio"
                                        value="<?= old('data_inicio', $paraInputData($banner->data_inicio ?? null)) ?>">
                                    <small class="form-text text-muted">
                                        Em branco, o banner vale desde já.
                                    </small>
                                    <?= erroInput('data_inicio') ?>
                                </div>
                            </div>

                            <!-- Fim -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="data_fim">Fim</label>
                                    <input type="datetime-local"
                                        class="form-control <?= hasError('data_fim') ? 'is-invalid' : '' ?>"
                                        id="data_fim" name="data_fim"
                                        value="<?= old('data_fim', $paraInputData($banner->data_fim ?? null)) ?>">
                                    <small class="form-text text-muted">
                                        Em branco, o banner não tem prazo para sair da home.
                                    </small>
                                    <?= erroInput('data_fim') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Exibição -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <h5>Exibição</h5>
                        </div>

                        <div class="row">
                            <!-- Status -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ativo">Status</label>
                                    <select class="form-control <?= hasError('ativo') ? 'is-invalid' : '' ?>" id="ativo"
                                        name="ativo">
                                        <option value="1"
                                            <?= (string) old('ativo', $banner->ativo ?? 1) === '1' ? 'selected' : '' ?>>
                                            Ativo
                                        </option>
                                        <option value="0"
                                            <?= (string) old('ativo', $banner->ativo ?? 1) === '0' ? 'selected' : '' ?>>
                                            Inativo
                                        </option>
                                    </select>
                                    <small class="form-text text-muted">
                                        Inativo não aparece na home, mesmo dentro do período.
                                    </small>
                                    <?= erroInput('ativo') ?>
                                </div>
                            </div>

                            <!-- Ordem de exibição -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ordem_exibicao">Ordem de exibição</label>
                                    <input type="number" min="0"
                                        class="form-control <?= hasError('ordem_exibicao') ? 'is-invalid' : '' ?>"
                                        id="ordem_exibicao" name="ordem_exibicao"
                                        value="<?= old('ordem_exibicao', $banner->ordem_exibicao ?? 0) ?>">
                                    <small class="form-text text-muted">
                                        O menor número aparece primeiro no carrossel.
                                    </small>
                                    <?= erroInput('ordem_exibicao') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light d-flex justify-content-end">
                        <!-- Botões -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="d-flex flex-wrap align-items-center">
                                    <button type="submit" class="btn btn-success mr-2" title="Atualizar"
                                        aria-label="Atualizar banner">
                                        <i class="mdi mdi-content-save"></i>
                                        Atualizar
                                    </button>
                                    <a href="<?= route('admin.banner.show', ['id' => $banner->id]) ?>"
                                        class="btn btn-primary mr-2" title="Visualizar" aria-label="Visualizar banner">
                                        <i class="mdi mdi-eye"></i>
                                        Visualizar
                                    </a>
                                    <a href="<?= route('admin.banner.index') ?>" class="btn btn-danger" title="Cancelar"
                                        aria-label="Cancelar edição">
                                        <i class="mdi mdi-close"></i>
                                        Cancelar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os scripts -->

<?php View::start('scripts'); ?>

<script nonce="<?= e(cspNonce()) ?>">
// Campo de upload da imagem
(function() {
    var MAX_BYTES = <?= $maxMb ?> * 1024 * 1024;

    var $box = $('#uploadBox');
    var $input = $('#imagem');
    var $img = $('#uploadImg');
    var $placeholder = $('#uploadPlaceholder');
    var $nome = $('#uploadNome');
    var $limpar = $('#uploadLimpar');
    var $erro = $('#uploadErro');

    var atual = $img.data('atual') || '';
    var textoInicial = $nome.text().trim();

    function mostrarImagem(src) {
        if (src) {
            $img.attr('src', src).removeClass('d-none');
            $placeholder.addClass('d-none');
        } else {
            $img.attr('src', '').addClass('d-none');
            $placeholder.removeClass('d-none');
        }
    }

    function restaurar() {
        $input.val('');
        mostrarImagem(atual);
        $nome.text(textoInicial);
        $limpar.addClass('d-none');
    }

    $input.on('change', function() {
        var arquivo = this.files && this.files[0];
        $erro.addClass('d-none').text('');

        if (!arquivo) {
            restaurar();
            return;
        }

        if (arquivo.type.indexOf('image/') !== 0) {
            $erro.text('Selecione um arquivo de imagem.').removeClass('d-none');
            restaurar();
            return;
        }

        if (arquivo.size > MAX_BYTES) {
            $erro.text('A imagem excede o tamanho máximo de <?= $maxMb ?> MB.').removeClass('d-none');
            restaurar();
            return;
        }

        var leitor = new FileReader();
        leitor.onload = function(e) {
            mostrarImagem(e.target.result);
        };
        leitor.readAsDataURL(arquivo);

        $nome.text(arquivo.name);
        $limpar.removeClass('d-none');
    });

    $limpar.on('click', restaurar);

    // Destaque visual ao arrastar um arquivo sobre a área
    $box.on('dragenter dragover', function() {
        $box.addClass('is-dragover');
    });
    $box.on('dragleave drop', function() {
        $box.removeClass('is-dragover');
    });
})();
</script>

<script nonce="<?= e(cspNonce()) ?>">
// A data fim não pode ser anterior à data início
(function() {
    var inicio = document.getElementById('data_inicio');
    var fim = document.getElementById('data_fim');

    if (!inicio || !fim) {
        return;
    }

    function sincronizar() {
        fim.min = inicio.value || '';
    }

    inicio.addEventListener('change', sincronizar);
    sincronizar();
})();
</script>

<?php View::end(); ?>