<?php $maxMb = (int) ($uploadMaxMb ?? 5); ?>
<!-- Aqui enviamos para o template principal o título da página -->
<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Editar Produto') ?> | Admin

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os estilos -->
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

<?php
// Preços do banco chegam como 19.90: formata para o padrão BR (19,90) antes de exibir
$precoAtual = isset($produtos->preco) && $produtos->preco !== ''
    ? number_format((float) $produtos->preco, 2, ',', '.')
    : '';

$promoAtual = isset($produtos->preco_promocional) && $produtos->preco_promocional !== ''
    ? number_format((float) $produtos->preco_promocional, 2, ',', '.')
    : '';

// Imagem: URL externa (registros antigos) é usada direto; arquivo local passa pelo uploadUrl()
$imagemAtual = $produtos->imagem_url ?? '';
$imagemSrc = '';

if ($imagemAtual !== '') {
    $imagemSrc = preg_match('#^https?://#i', $imagemAtual)
        ? $imagemAtual
        : uploadUrl('produtos', $imagemAtual);
}
?>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">

            <!-- Cabeçalho -->
            <div class="card-header bg-primary">
                <div class="row align-items-center mb-4">
                    <div class="col-md-8">
                        <h4 class="card-title mb-1 text-white">
                            <?= e($titulo ?? 'Editar Produto') ?>
                        </h4>
                        <p class="card-description mb-0 text-white">
                            <?= e($produtos->nome ?? '') ?>
                        </p>
                    </div>
                    <div class="col-md-4 text-md-right mt-3 mt-md-0">
                        <a href="<?= route('admin.produto.index') ?>" class="btn btn-secondary btn-sm text-white">
                            <i class="fas fa-arrow-left"></i>
                            Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">

                <!-- Formulário -->
                <form method="POST" action="<?= route('admin.produto.update', ['id' => $produtos->id]) ?>"
                    enctype="multipart/form-data" class="forms-sample">
                    <?= csrf_field() ?>
                    <?= method_field('PUT') ?>

                    <!-- Identificação -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <h5>Identificação</h5>
                        </div>

                        <div class="row">
                            <!-- Nome -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nome">Nome</label>
                                    <input type="text" class="form-control <?= hasError('nome') ? 'is-invalid' : '' ?>"
                                        id="nome" name="nome" maxlength="200"
                                        value="<?= old('nome', $produtos->nome ?? '') ?>" placeholder="Nome do produto">
                                    <?= erroInput('nome') ?>
                                </div>
                            </div>

                            <!-- Slug -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="slug">Slug</label>
                                    <input type="text" class="form-control <?= hasError('slug') ? 'is-invalid' : '' ?>"
                                        id="slug" name="slug" maxlength="200"
                                        value="<?= old('slug', $produtos->slug ?? '') ?>"
                                        placeholder="Em branco, é gerado pelo nome">
                                    <?= erroInput('slug') ?>
                                </div>
                            </div>

                            <!-- Categoria -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="categoria_id">Categoria</label>
                                    <select class="form-control <?= hasError('categoria_id') ? 'is-invalid' : '' ?>"
                                        id="categoria_id" name="categoria_id">
                                        <option value="">Sem categoria</option>
                                        <?php if (!empty($categorias)): ?>
                                        <?php foreach ($categorias as $categoria): ?>
                                        <option value="<?= e($categoria->id) ?>"
                                            <?= (string) old('categoria_id', $produtos->categoria_id ?? '') === (string) $categoria->id ? 'selected' : '' ?>>
                                            <?= e($categoria->nome) ?>
                                            <?= empty($categoria->ativo) ? '(inativa)' : '' ?>
                                        </option>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <?= erroInput('categoria_id') ?>
                                </div>
                            </div>

                            <!-- Código -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codigo">Código (SKU)</label>
                                    <input type="text"
                                        class="form-control <?= hasError('codigo') ? 'is-invalid' : '' ?>" id="codigo"
                                        name="codigo" maxlength="100"
                                        value="<?= old('codigo', $produtos->codigo ?? '') ?>"
                                        placeholder="Em branco, é gerado automaticamente (ex.: PIZ-001)">
                                    <small class="form-text text-muted">
                                        Em branco, mantém o código atual.
                                    </small>
                                    <?= erroInput('codigo') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Conteúdo -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <h5>Conteúdo</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Descrição -->
                                <div class="form-group">
                                    <label for="descricao">Descrição</label>
                                    <textarea class="form-control <?= hasError('descricao') ? 'is-invalid' : '' ?>"
                                        id="descricao" name="descricao" rows="3" maxlength="500"
                                        placeholder="Descrição do produto"><?= old('descricao', $produtos->descricao ?? '') ?></textarea>
                                    <?= erroInput('descricao') ?>
                                </div>

                                <!-- Ingredientes -->
                                <div class="form-group">
                                    <label for="ingredientes">Ingredientes</label>
                                    <textarea class="form-control <?= hasError('ingredientes') ? 'is-invalid' : '' ?>"
                                        id="ingredientes" name="ingredientes" rows="3" maxlength="500"
                                        placeholder="Ingredientes do produto"><?= old('ingredientes', $produtos->ingredientes ?? '') ?></textarea>
                                    <?= erroInput('ingredientes') ?>
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

                    <!-- Preços -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <h5>Preços</h5>
                        </div>

                        <div class="row">
                            <!-- Preço -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="preco">Preço</label>
                                    <input type="text"
                                        class="form-control moeda <?= hasError('preco') ? 'is-invalid' : '' ?>"
                                        id="preco" name="preco" value="<?= old('preco', $precoAtual) ?>"
                                        placeholder="0,00">
                                    <?= erroInput('preco') ?>
                                </div>
                            </div>

                            <!-- Preço promocional -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="preco_promocional">Preço promocional</label>
                                    <input type="text"
                                        class="form-control moeda <?= hasError('preco_promocional') ? 'is-invalid' : '' ?>"
                                        id="preco_promocional" name="preco_promocional"
                                        value="<?= old('preco_promocional', $promoAtual) ?>"
                                        placeholder="0,00 (opcional)">
                                    <?= erroInput('preco_promocional') ?>
                                </div>
                            </div>

                            <!-- Tempo de preparo -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tempo_preparo">Tempo de preparo (min)</label>
                                    <input type="number" min="0" max="65535"
                                        class="form-control <?= hasError('tempo_preparo') ? 'is-invalid' : '' ?>"
                                        id="tempo_preparo" name="tempo_preparo"
                                        value="<?= old('tempo_preparo', $produtos->tempo_preparo ?? '') ?>"
                                        placeholder="Ex.: 30">
                                    <?= erroInput('tempo_preparo') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estoque -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <h5>Estoque</h5>
                        </div>

                        <div class="row">
                            <!-- Controla estoque -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="controla_estoque">Controla estoque?</label>
                                    <select class="form-control <?= hasError('controla_estoque') ? 'is-invalid' : '' ?>"
                                        id="controla_estoque" name="controla_estoque">
                                        <option value="0"
                                            <?= (string) old('controla_estoque', $produtos->controla_estoque ?? 0) === '0' ? 'selected' : '' ?>>
                                            Não
                                        </option>
                                        <option value="1"
                                            <?= (string) old('controla_estoque', $produtos->controla_estoque ?? 0) === '1' ? 'selected' : '' ?>>
                                            Sim
                                        </option>
                                    </select>
                                    <?= erroInput('controla_estoque') ?>
                                </div>
                            </div>

                            <!-- Quantidade em estoque -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estoque">Quantidade em estoque</label>
                                    <input type="number" min="0" max="65535"
                                        class="form-control <?= hasError('estoque') ? 'is-invalid' : '' ?>" id="estoque"
                                        name="estoque" value="<?= old('estoque', $produtos->estoque ?? 0) ?>">
                                    <?= erroInput('estoque') ?>
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ativo">Status</label>
                                    <select class="form-control <?= hasError('ativo') ? 'is-invalid' : '' ?>" id="ativo"
                                        name="ativo">
                                        <option value="1"
                                            <?= (string) old('ativo', $produtos->ativo ?? 1) === '1' ? 'selected' : '' ?>>
                                            Ativo
                                        </option>
                                        <option value="0"
                                            <?= (string) old('ativo', $produtos->ativo ?? 1) === '0' ? 'selected' : '' ?>>
                                            Inativo
                                        </option>
                                    </select>
                                    <?= erroInput('ativo') ?>
                                </div>
                            </div>

                            <!-- Destaque -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="destaque">Destaque</label>
                                    <select class="form-control <?= hasError('destaque') ? 'is-invalid' : '' ?>"
                                        id="destaque" name="destaque">
                                        <option value="0"
                                            <?= (string) old('destaque', $produtos->destaque ?? 0) === '0' ? 'selected' : '' ?>>
                                            Normal
                                        </option>
                                        <option value="1"
                                            <?= (string) old('destaque', $produtos->destaque ?? 0) === '1' ? 'selected' : '' ?>>
                                            Em destaque
                                        </option>
                                    </select>
                                    <?= erroInput('destaque') ?>
                                </div>
                            </div>

                            <!-- Ordem de exibição -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ordem_exibicao">Ordem de exibição</label>
                                    <input type="number" min="0" max="65535"
                                        class="form-control <?= hasError('ordem_exibicao') ? 'is-invalid' : '' ?>"
                                        id="ordem_exibicao" name="ordem_exibicao"
                                        value="<?= old('ordem_exibicao', $produtos->ordem_exibicao ?? 0) ?>">
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
                                        aria-label="Atualizar produto">
                                        <i class="mdi mdi-content-save"></i>
                                        Atualizar
                                    </button>
                                    <a href="<?= route('admin.produto.show', ['id' => $produtos->id]) ?>"
                                        class="btn btn-primary mr-2" title="Visualizar" aria-label="Visualizar produto">
                                        <i class="mdi mdi-eye"></i>
                                        Visualizar
                                    </a>
                                    <a href="<?= route('admin.produto.index') ?>" class="btn btn-danger"
                                        title="Cancelar" aria-label="Cancelar edição">
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

<script nonce="<?= e(CSP_NONCE) ?>">
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
<script nonce="<?= e(CSP_NONCE) ?>">
// Só habilita a quantidade em estoque quando o produto controla estoque
(function() {
    var controla = document.getElementById('controla_estoque');
    var estoque = document.getElementById('estoque');

    if (!controla || !estoque) {
        return;
    }

    function atualizar() {
        estoque.disabled = controla.value !== '1';
    }

    controla.addEventListener('change', atualizar);
    atualizar();
})();
</script>

<?php View::end(); ?>