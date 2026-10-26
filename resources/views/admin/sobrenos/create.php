<!-- resources/views/admin/sobrenos/create.php -->

<?php
$rota  = 'admin.sobrenos';
$maxMb = (int) ($uploadMaxMb ?? 5);
?>

<!-- Aqui enviamos para o template principal o título da página -->
<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Novo registro') ?> | Admin

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os estilos -->
<?php View::start('styles'); ?>

<style>
/* ── Seções do formulário ─────────────────────────────────────────── */
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
            <div class="card-header bg-primary text-white">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="card-title mb-1 text-white">
                            <?= e($titulo ?? 'Novo registro') ?>
                        </h4>
                        <p class="card-description mb-0 text-white">
                            Cadastre o conteúdo da seção "Sobre nós" da home.
                        </p>
                    </div>
                    <div class="col-md-4 text-md-right mt-3 mt-md-0">
                        <a href="<?= route($rota . '.index') ?>" class="btn btn-secondary btn-sm text-white">
                            <i class="mdi mdi-arrow-left"></i>
                            Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">

                <!-- Formulário -->
                <form method="POST" action="<?= route($rota . '.store') ?>" enctype="multipart/form-data"
                    class="forms-sample">
                    <?= csrf_field() ?>

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
                                        name="titulo" maxlength="150" value="<?= old('titulo', '') ?>"
                                        placeholder="Ex.: Sobre nós" autofocus>
                                    <?= erroInput('titulo') ?>
                                </div>

                                <!-- Texto -->
                                <div class="form-group">
                                    <label for="texto">Texto</label>
                                    <textarea class="form-control <?= hasError('texto') ? 'is-invalid' : '' ?>"
                                        id="texto" name="texto" rows="8" maxlength="500"
                                        placeholder="Conte a história do seu negócio"><?= old('texto', '') ?></textarea>
                                    <small class="form-text text-muted">
                                        <span id="textoContador">0</span>/500 caracteres.
                                    </small>
                                    <?= erroInput('texto') ?>
                                </div>
                            </div>

                            <!-- Imagem -->
                            <div class="form-group col-md-6">
                                <label for="imagem">Imagem</label>

                                <div class="upload-box <?= hasError('imagem') ? 'is-invalid' : '' ?>" id="uploadBox">
                                    <div class="upload-drop">
                                        <input type="file" class="upload-input" id="imagem" name="imagem"
                                            accept="image/*">

                                        <img id="uploadImg" class="upload-img d-none" src="" data-atual=""
                                            alt="Pré-visualização da imagem">

                                        <div id="uploadPlaceholder" class="upload-placeholder">
                                            <i class="mdi mdi-cloud-upload"></i>
                                            <span>Clique ou arraste uma imagem aqui</span>
                                            <small>JPG, PNG, GIF ou WEBP · até <?= $maxMb ?> MB</small>
                                        </div>
                                    </div>

                                    <div class="upload-info">
                                        <span class="upload-nome" id="uploadNome">Nenhum arquivo selecionado</span>
                                        <button type="button" class="btn btn-outline-danger btn-sm d-none"
                                            id="uploadLimpar" title="Remover seleção" aria-label="Remover seleção">
                                            <i class="mdi mdi-close"></i> Remover
                                        </button>
                                    </div>
                                </div>

                                <small class="text-danger d-none" id="uploadErro"></small>
                                <?= erroInput('imagem') ?>

                                <!-- Texto alternativo -->
                                <div class="form-group mt-3">
                                    <label for="imagem_alt">Descrição da imagem</label>
                                    <input type="text"
                                        class="form-control <?= hasError('imagem_alt') ? 'is-invalid' : '' ?>"
                                        id="imagem_alt" name="imagem_alt" maxlength="150"
                                        value="<?= old('imagem_alt', '') ?>"
                                        placeholder="Ex.: Chef preparando um prato">
                                    <small class="form-text text-muted">
                                        Texto alternativo da imagem, usado por leitores de tela e quando a imagem
                                        não carrega. Opcional.
                                    </small>
                                    <?= erroInput('imagem_alt') ?>
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
                                        <option value="1" <?= (string) old('ativo', '1') === '1' ? 'selected' : '' ?>>
                                            Ativo
                                        </option>
                                        <option value="0" <?= (string) old('ativo', '1') === '0' ? 'selected' : '' ?>>
                                            Inativo
                                        </option>
                                    </select>
                                    <small class="form-text text-muted">
                                        Inativo não aparece na home.
                                    </small>
                                    <?= erroInput('ativo') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light d-flex justify-content-end">
                        <!-- Botões -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="d-flex flex-wrap align-items-center">
                                    <button type="submit" class="btn btn-success mr-2" title="Salvar"
                                        aria-label="Salvar registro">
                                        <i class="mdi mdi-content-save"></i>
                                        Cadastrar
                                    </button>
                                    <a href="<?= route($rota . '.index') ?>" class="btn btn-danger" title="Cancelar"
                                        aria-label="Cancelar cadastro">
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
// Contador de caracteres do texto
(function() {
    var texto = document.getElementById('texto');
    var contador = document.getElementById('textoContador');

    if (!texto || !contador) {
        return;
    }

    function atualizar() {
        contador.textContent = texto.value.length;
    }

    texto.addEventListener('input', atualizar);
    atualizar();
})();
</script>

<?php View::end(); ?>