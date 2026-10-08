<?php $maxMb = (int) ($uploadMaxMb ?? 5); ?>
<!-- resources/views/admin/medidas/edit.php -->

<!-- Aqui enviamos para o template principal o título da página -->

<?php View::start('title'); ?>

<?= e($titulo ?? 'Editar Medida') ?> | Admin

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os estilos -->

<?php View::start('styles'); ?>
<style>
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

<?php
// Valor no formato brasileiro (o banco guarda 2165.65; a máscara espera 2.165,65).
// Se vier de um old() já normalizado ("2165.65"), formata também.
$valorAtual = old('valor', $medidas->valor ?? '');
if ($valorAtual !== '' && is_numeric($valorAtual)) {
    $valorAtual = number_format((float) $valorAtual, 2, ',', '.');
}

$unidadeAtual = (string) old('unidade', $medidas->unidade ?? '');
$unidades = [
    'cm' => 'cm — Centímetro',
    'ml' => 'ml — Mililitro',
    'l'  => 'l — Litro',
    'g'  => 'g — Grama',
    'kg' => 'kg — Quilograma',
    'un' => 'un — Unidade',
];

$imagemAtual = !empty($medidas->imagem_url) ? uploadUrl('medidas', $medidas->imagem_url) : '';
?>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <!-- Cabeçalho -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="card-title mb-1 text-white">
                            <?= e($titulo ?? 'Editar Medida') ?>
                        </h4>
                        <p class="card-description mb-0 text-white">
                            <?= e($medidas->nome ?? '') ?>
                        </p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <a href="<?= route('admin.medida.index') ?>" class="btn btn-secondary btn-sm text-white">
                            <i class="mdi mdi-arrow-left"></i>
                            Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Formulário -->
                <form method="POST" action="<?= route('admin.medida.update', ['id' => $medidas->id]) ?>"
                    enctype="multipart/form-data" class="forms-sample">
                    <?= csrf_field() ?>
                    <?= method_field('PUT') ?>

                    <div class="row">
                        <!-- Nome -->
                        <div class="form-group col-md-6">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control <?= hasError('nome') ? 'is-invalid' : '' ?>"
                                id="nome" name="nome" value="<?= old('nome', $medidas->nome ?? '') ?>"
                                placeholder="Nome da medida" autofocus>
                            <?= erroInput('nome') ?>
                        </div>

                        <!-- Descrição -->
                        <div class="form-group col-md-6">
                            <label for="descricao">Descrição</label>
                            <textarea class="form-control <?= hasError('descricao') ? 'is-invalid' : '' ?>"
                                id="descricao" name="descricao" rows="3"
                                placeholder="Descrição da medida"><?= old('descricao', $medidas->descricao ?? '') ?></textarea>
                            <?= erroInput('descricao') ?>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Ordem de exibição -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ordem_exibicao">
                                    Ordem de exibição
                                </label>
                                <input type="number" min="1"
                                    class="form-control <?= hasError('ordem_exibicao') ? 'is-invalid' : '' ?>"
                                    id="ordem_exibicao" name="ordem_exibicao"
                                    value="<?= old('ordem_exibicao', $medidas->ordem_exibicao ?? 1) ?>">
                                <?= erroInput('ordem_exibicao') ?>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ativo">
                                    Status
                                </label>
                                <select class="form-control <?= hasError('ativo') ? 'is-invalid' : '' ?>" id="ativo"
                                    name="ativo">
                                    <option value="1"
                                        <?= (string) old('ativo', (string) ($medidas->ativo ?? 1)) === '1' ? 'selected' : '' ?>>
                                        Ativo
                                    </option>
                                    <option value="0"
                                        <?= (string) old('ativo', (string) ($medidas->ativo ?? 1)) === '0' ? 'selected' : '' ?>>
                                        Inativo
                                    </option>
                                </select>
                                <?= erroInput('ativo') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Unidade -->
                        <div class="form-group col-md-6">
                            <label for="unidade">Unidade</label>
                            <select class="form-control <?= hasError('unidade') ? 'is-invalid' : '' ?>" id="unidade"
                                name="unidade">
                                <option value="">Selecione a unidade</option>
                                <?php foreach ($unidades as $sigla => $rotulo): ?>
                                    <option value="<?= e($sigla) ?>" <?= $unidadeAtual === $sigla ? 'selected' : '' ?>>
                                        <?= e($rotulo) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?= erroInput('unidade') ?>
                        </div>

                        <!-- Imagem -->
                        <div class="form-group col-md-6">
                            <label for="imagem">Imagem</label>

                            <div class="upload-box <?= hasError('imagem') ? 'is-invalid' : '' ?>" id="uploadBox">
                                <div class="upload-drop">
                                    <input type="file" class="upload-input" id="imagem" name="imagem" accept="image/*">

                                    <img id="uploadImg" class="upload-img <?= $imagemAtual === '' ? 'd-none' : '' ?>"
                                        src="<?= e($imagemAtual) ?>" data-atual="<?= e($imagemAtual) ?>"
                                        alt="Pré-visualização da imagem">

                                    <div id="uploadPlaceholder"
                                        class="upload-placeholder <?= $imagemAtual !== '' ? 'd-none' : '' ?>">
                                        <i class="mdi mdi-cloud-upload"></i>
                                        <span>Clique ou arraste uma imagem aqui</span>
                                        <small>JPG, PNG, GIF ou WEBP · até <?= $maxMb ?> MB</small>
                                    </div>
                                </div>

                                <div class="upload-info">
                                    <span class="upload-nome" id="uploadNome">
                                        <?= $imagemAtual !== '' ? 'Imagem atual (deixe como está para mantê-la)' : 'Nenhum arquivo selecionado' ?>
                                    </span>
                                    <button type="button" class="btn btn-outline-danger btn-sm d-none" id="uploadLimpar"
                                        title="Remover seleção" aria-label="Remover seleção">
                                        <i class="mdi mdi-close"></i> Remover
                                    </button>
                                </div>
                            </div>

                            <small class="text-danger d-none" id="uploadErro"></small>
                            <?= erroInput('imagem') ?>
                        </div>
                    </div>

                    <div class="card-footer bg-light d-flex justify-content-end">
                        <!-- Botões -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="d-flex flex-wrap align-items-center">
                                    <button type="submit" class="btn btn-success mr-2" title="Atualizar"
                                        aria-label="Atualizar medida">
                                        <i class="mdi mdi-content-save"></i>
                                        Atualizar
                                    </button>
                                    <a href="<?= route('admin.medida.show', ['id' => $medidas->id]) ?>"
                                        class="btn btn-primary mr-2" title="Visualizar" aria-label="Visualizar medida">
                                        <i class="mdi mdi-eye"></i>
                                        Visualizar
                                    </a>
                                    <a href="<?= route('admin.medida.index') ?>" class="btn btn-danger" title="Cancelar"
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
<?php View::end(); ?>