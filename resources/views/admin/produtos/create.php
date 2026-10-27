<?php
$rota  = 'admin.produto';
$maxMb = (int) ($uploadMaxMb ?? 5);
?>
<!-- Aqui enviamos para o template principal o título da página -->
<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Novo Produto') ?> | Admin

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

    /* ── Lista de extras com rolagem ──────────────────────────────────── */
    .extras-box {
        border: 1px solid #ced4da;
        border-radius: 8px;
        background: #fff;
    }

    .extras-toolbar {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 12px;
        border-bottom: 1px solid #e9ecef;
    }

    .extras-toolbar .form-control {
        max-width: 260px;
    }

    .extras-contador {
        margin-left: auto;
        font-size: 13px;
        color: #6c757d;
        white-space: nowrap;
    }

    .extras-lista {
        max-height: 220px;
        overflow-y: auto;
        padding: 12px 12px 4px;
    }

    /* ── Medidas e preços ─────────────────────────────────────────────── */
    .espec-box {
        border: 1px solid #ced4da;
        border-radius: 8px;
        background: #fff;
    }

    .espec-lista {
        max-height: 320px;
        overflow-y: auto;
        padding: 12px 12px 0;
    }

    .espec-linha {
        padding-bottom: 10px;
        margin-bottom: 10px;
        border-bottom: 1px solid #e9ecef;
    }

    .espec-linha:last-child {
        border-bottom: 0;
    }

    .espec-rodape {
        padding: 8px 12px;
        border-top: 1px solid #e9ecef;
        background: #f8f9fa;
        border-radius: 0 0 8px 8px;
    }

    /* ── Busca da medida (campo + lista de opções) ────────────────────── */
    .medida-combo {
        position: relative;
    }

    .medida-combo .form-control {
        padding-right: 32px;
    }

    .medida-combo .mdi {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        pointer-events: none;
    }

    /* A lista fica fora da área com rolagem (position: fixed) para não ser cortada */
    .medida-painel {
        display: none;
        position: fixed;
        z-index: 1080;
        max-height: 240px;
        overflow-y: auto;
        background: #fff;
        border: 1px solid #ced4da;
        border-radius: 8px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, .15);
    }

    .medida-painel.aberto {
        display: block;
    }

    .medida-painel .item {
        padding: 8px 12px;
        font-size: 14px;
        cursor: pointer;
    }

    .medida-painel .item:hover,
    .medida-painel .item.ativo {
        background: #eef3ff;
    }

    .medida-painel .item.selecionado {
        font-weight: 600;
        color: #4e73df;
    }

    .medida-painel .vazio {
        padding: 8px 12px;
        font-size: 13px;
        color: #6c757d;
    }
</style>

<?php View::end(); ?>

<?php View::start('content'); ?>

<?php
// Medidas e especificações iniciais (no create, o controller envia lista vazia)
$medidas = $medidas ?? [];
$especificacoes = $especificacoes ?? [];

// medida_id pode ser NULL (produto só com preço)
$especIniciais = array_map(fn($e) => [
    'medida_id'    => !empty($e->medida_id) ? (int) $e->medida_id : null,
    'preco'        => number_format((float) $e->preco, 2, ',', '.'),
    'customizavel' => (int) $e->customizavel,
], $especificacoes);
?>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">

            <!-- Cabeçalho -->
            <div class="card-header bg-primary text-white">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="card-title mb-1 text-white">
                            <?= e($titulo ?? 'Novo Produto') ?>
                        </h4>
                        <p class="card-description mb-0 text-white">
                            Cadastre um novo produto.
                        </p>
                    </div>
                    <div class="col-md-4 text-md-right mt-3 mt-md-0">
                        <a href="<?= route($rota . '.index') ?>" class="btn btn-secondary btn-sm text-white">
                            <i class="fas fa-arrow-left"></i>
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
                                        id="nome" name="nome" maxlength="200" value="<?= old('nome', '') ?>"
                                        placeholder="Nome do produto">
                                    <?= erroInput('nome') ?>
                                </div>
                            </div>

                            <!-- Slug -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="slug">Slug</label>
                                    <input type="text" class="form-control <?= hasError('slug') ? 'is-invalid' : '' ?>"
                                        id="slug" name="slug" maxlength="200" value="<?= old('slug', '') ?>"
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
                                                    <?= (string) old('categoria_id', '') === (string) $categoria->id ? 'selected' : '' ?>>
                                                    <?= e($categoria->nome) ?>
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
                                        name="codigo" maxlength="100" value="<?= old('codigo', '') ?>"
                                        placeholder="Em branco, é gerado automaticamente (ex.: PIZ-001)">
                                    <?= erroInput('codigo') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Extras -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <h5>Extras disponíveis (opcional)</h5>
                        </div>

                        <?php if (empty($extras)): ?>
                            <p class="text-muted mb-0">Nenhuma extra ativa cadastrada.</p>
                        <?php else: ?>
                            <div class="extras-box">
                                <div class="extras-toolbar">
                                    <input type="search" class="form-control form-control-sm" id="extrasBusca"
                                        placeholder="Buscar extra..." autocomplete="off">
                                    <span class="extras-contador" id="extrasContador"></span>
                                </div>

                                <div class="extras-lista" id="extrasLista">
                                    <div class="row">
                                        <?php foreach ($extras as $extra): ?>
                                            <?php
                                            $extraId = (int) $extra->id;
                                            $selecionada = in_array($extraId, $extraIds ?? [], true);
                                            ?>
                                            <div class="col-md-4 col-sm-6 extra-item" data-nome="<?= e($extra->nome) ?>">
                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox" class="custom-control-input extra-check"
                                                        id="extra_<?= $extraId ?>" name="extra_ids[]" value="<?= $extraId ?>"
                                                        <?= $selecionada ? 'checked' : '' ?>>
                                                    <label class="custom-control-label" for="extra_<?= $extraId ?>">
                                                        <?= e($extra->nome) ?> - $R: <?= e(moedaBR($extra->preco)) ?>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <p class="text-muted d-none" id="extrasVazio">Nenhuma extra encontrada.</p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?= erroInput('extra_ids') ?>
                    </div>

                    <!-- Preços e medidas -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <h5>Preços e medidas <small class="text-danger">*</small></h5>
                        </div>

                        <?php /* A medida é opcional: o bloco aparece mesmo sem medidas cadastradas */ ?>
                        <div class="espec-box">
                            <div class="espec-lista" id="especLista"
                                data-iniciais="<?= e(json_encode($especIniciais, JSON_UNESCAPED_UNICODE)) ?>">
                            </div>

                            <div class="espec-rodape">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="especAdd">
                                    <i class="mdi mdi-plus"></i> Adicionar preço / medida
                                </button>
                            </div>
                        </div>

                        <small class="form-text text-muted mt-2">
                            Informe ao menos um preço. A medida é opcional: para um produto sem medida,
                            deixe a medida em branco e preencha apenas o preço (use somente uma linha).
                        </small>

                        <small class="form-text text-muted mt-2">
                            <i class="mdi mdi-information-outline"></i>
                            <strong>Customizável:</strong> use para medidas como Pizza P, M, G etc.
                            Vale para todos os produtos que tiverem essas especificações.
                            Fica desativado quando a linha não tem medida.
                        </small>

                        <template id="especTemplate">
                            <div class="row align-items-center espec-linha">
                                <div class="col-md-5 mb-2 mb-md-0">
                                    <!-- Campo de busca: o select escondido guarda o valor enviado -->
                                    <div class="medida-combo">
                                        <input type="text" class="form-control espec-busca"
                                            placeholder="Medida (opcional) — buscar..." autocomplete="off"
                                            role="combobox" aria-autocomplete="list" aria-expanded="false">
                                        <i class="mdi mdi-magnify"></i>

                                        <select class="espec-medida d-none" tabindex="-1" aria-hidden="true">
                                            <option value="">Sem medida</option>
                                            <?php foreach ($medidas as $medida): ?>
                                                <option value="<?= (int) $medida->id ?>">
                                                    <?= e($medida->nome) ?><?= empty($medida->ativo) ? ' (inativa)' : '' ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-2 mb-md-0">
                                    <input type="text" class="form-control espec-preco" inputmode="decimal"
                                        placeholder="0,00" required>
                                </div>

                                <div class="col-md-3 mb-2 mb-md-0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input espec-custom" value="1">
                                        <label class="custom-control-label">Customizável</label>
                                    </div>
                                </div>

                                <div class="col-md-1 text-md-right">
                                    <button type="button" class="btn btn-outline-danger btn-sm espec-remover"
                                        title="Remover linha" aria-label="Remover linha">
                                        <i class="mdi mdi-close"></i>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <?= erroInput('especificacoes') ?>
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
                                        placeholder="Descrição do produto"><?= old('descricao', '') ?></textarea>
                                    <?= erroInput('descricao') ?>
                                </div>

                                <!-- Ingredientes -->
                                <div class="form-group">
                                    <label for="ingredientes">Ingredientes</label>
                                    <textarea class="form-control <?= hasError('ingredientes') ? 'is-invalid' : '' ?>"
                                        id="ingredientes" name="ingredientes" rows="3" maxlength="500"
                                        placeholder="Ingredientes do produto"><?= old('ingredientes', '') ?></textarea>
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
                            </div>
                        </div>
                    </div>

                    <!-- Tempo de preparo -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <h5>Tempo de preparo</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tempo_preparo">Tempo de preparo (min)</label>
                                    <input type="number" min="0" max="65535"
                                        class="form-control <?= hasError('tempo_preparo') ? 'is-invalid' : '' ?>"
                                        id="tempo_preparo" name="tempo_preparo" value="<?= old('tempo_preparo', '') ?>"
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
                                            <?= (string) old('controla_estoque', '0') === '0' ? 'selected' : '' ?>>
                                            Não
                                        </option>
                                        <option value="1"
                                            <?= (string) old('controla_estoque', '0') === '1' ? 'selected' : '' ?>>
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
                                        name="estoque" value="<?= old('estoque', '0') ?>">
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
                                        <option value="1" <?= (string) old('ativo', '1') === '1' ? 'selected' : '' ?>>
                                            Ativo
                                        </option>
                                        <option value="0" <?= (string) old('ativo', '1') === '0' ? 'selected' : '' ?>>
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
                                            <?= (string) old('destaque', '0') === '0' ? 'selected' : '' ?>>
                                            Normal
                                        </option>
                                        <option value="1"
                                            <?= (string) old('destaque', '0') === '1' ? 'selected' : '' ?>>
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
                                        value="<?= old('ordem_exibicao', '0') ?>">
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
                                    <button type="submit" class="btn btn-success mr-2" title="Salvar"
                                        aria-label="Salvar produto">
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

<script nonce="<?= e(CSP_NONCE) ?>">
    // Busca e contador da lista de extras
    (function() {
        var busca = document.getElementById('extrasBusca');
        var contador = document.getElementById('extrasContador');
        var vazio = document.getElementById('extrasVazio');
        var itens = document.querySelectorAll('.extra-item');

        if (!busca || !itens.length) {
            return;
        }

        function normalizar(texto) {
            return (texto || '')
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '');
        }

        function atualizarContador() {
            var marcadas = document.querySelectorAll('.extra-check:checked').length;
            contador.textContent = marcadas + ' de ' + itens.length + ' selecionadas';
        }

        function filtrar() {
            var termo = normalizar(busca.value.trim());
            var visiveis = 0;

            itens.forEach(function(item) {
                var mostra = normalizar(item.dataset.nome).indexOf(termo) !== -1;
                item.classList.toggle('d-none', !mostra);
                if (mostra) {
                    visiveis++;
                }
            });

            vazio.classList.toggle('d-none', visiveis > 0);
        }

        busca.addEventListener('input', filtrar);

        // Enter na busca não deve enviar o formulário
        busca.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });

        document.querySelectorAll('.extra-check').forEach(function(check) {
            check.addEventListener('change', atualizarContador);
        });

        atualizarContador();
    })();
</script>

<script nonce="<?= e(CSP_NONCE) ?>">
    // Linhas de medida (opcional) + preço + customizável (com busca na medida)
    (function() {
        var lista = document.getElementById('especLista');
        var modelo = document.getElementById('especTemplate');
        var botaoAdd = document.getElementById('especAdd');

        if (!lista || !modelo) {
            return;
        }

        var indice = 0;
        var ativo = null; // linha com a lista de opções aberta: { busca, select, preco }
        var itemAtivo = -1; // item destacado pelo teclado

        // Lista de opções única, fora da área com rolagem para não ser cortada
        var painel = document.createElement('div');
        painel.className = 'medida-painel';
        painel.setAttribute('role', 'listbox');
        document.body.appendChild(painel);

        // Evita que o campo perca o foco antes do clique no item
        painel.addEventListener('mousedown', function(e) {
            e.preventDefault();
        });

        function normalizar(texto) {
            return (texto || '')
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '');
        }

        function textoOpcao(opcao) {
            return (opcao.textContent || '').replace(/\s+/g, ' ').trim();
        }

        function textoSelecionado(select) {
            var opcao = select.options[select.selectedIndex];

            return (select.value && opcao) ? textoOpcao(opcao) : '';
        }

        // Opções que ainda podem ser escolhidas (as usadas em outras linhas ficam de fora)
        function opcoesFiltradas(select, termo) {
            var t = normalizar(termo.trim());
            var saida = [];

            Array.prototype.forEach.call(select.options, function(o) {
                if (o.value === '' || o.disabled) {
                    return;
                }

                if (t !== '' && normalizar(textoOpcao(o)).indexOf(t) === -1) {
                    return;
                }

                saida.push(o);
            });

            return saida;
        }

        function renderizar(termo) {
            var select = ativo.select;
            var opcoes = opcoesFiltradas(select, termo);

            painel.innerHTML = '';
            itemAtivo = -1;

            if (!opcoes.length) {
                var vazio = document.createElement('div');
                vazio.className = 'vazio';
                vazio.textContent = 'Nenhuma medida encontrada.';
                painel.appendChild(vazio);
                return;
            }

            opcoes.forEach(function(o) {
                var item = document.createElement('div');
                item.className = 'item' + (o.value === select.value ? ' selecionado' : '');
                item.setAttribute('role', 'option');
                item.dataset.valor = o.value;
                item.textContent = textoOpcao(o);
                item.addEventListener('click', function() {
                    escolher(o.value);
                });
                painel.appendChild(item);
            });
        }

        function posicionar() {
            if (!ativo) {
                return;
            }

            var r = ativo.busca.getBoundingClientRect();

            painel.style.width = r.width + 'px';
            painel.style.left = r.left + 'px';
            painel.style.top = (r.bottom + 2) + 'px';

            // Sem espaço embaixo: abre para cima
            var altura = painel.offsetHeight;

            if (r.bottom + altura + 8 > window.innerHeight && r.top > altura + 8) {
                painel.style.top = (r.top - altura - 2) + 'px';
            }
        }

        function abrir(contexto) {
            if (ativo && ativo.busca === contexto.busca) {
                return;
            }

            if (ativo) {
                fechar();
            }

            ativo = contexto;
            contexto.busca.setAttribute('aria-expanded', 'true');
            painel.classList.add('aberto');
            renderizar('');
            posicionar();
        }

        function fechar() {
            painel.classList.remove('aberto');

            if (!ativo) {
                return;
            }

            var anterior = ativo;

            ativo = null;
            itemAtivo = -1;

            anterior.busca.setAttribute('aria-expanded', 'false');
            // Mostra de novo a medida realmente escolhida (descarta texto digitado pela metade)
            anterior.busca.value = textoSelecionado(anterior.select);
        }

        function escolher(valor) {
            if (!ativo) {
                return;
            }

            var linha = ativo;

            linha.select.value = valor;
            linha.select.dispatchEvent(new Event('change', {
                bubbles: true
            }));

            fechar();
            linha.preco.focus();
        }

        function mover(passo) {
            var itens = painel.querySelectorAll('.item');
            var total = itens.length;

            if (!total) {
                return;
            }

            if (itemAtivo === -1) {
                itemAtivo = passo > 0 ? 0 : total - 1;
            } else {
                itemAtivo = (itemAtivo + passo + total) % total;
            }

            itens.forEach(function(item, i) {
                item.classList.toggle('ativo', i === itemAtivo);
            });

            var atual = itens[itemAtivo];

            if (atual.offsetTop < painel.scrollTop) {
                painel.scrollTop = atual.offsetTop;
            } else if (atual.offsetTop + atual.offsetHeight > painel.scrollTop + painel.clientHeight) {
                painel.scrollTop = atual.offsetTop + atual.offsetHeight - painel.clientHeight;
            }
        }

        // Mantém a lista junto do campo ao rolar a página ou redimensionar a janela
        window.addEventListener('scroll', posicionar, true);
        window.addEventListener('resize', posicionar);

        // Desabilita, em cada select, as medidas já escolhidas nas outras linhas
        function atualizarOpcoes() {
            var selects = lista.querySelectorAll('.espec-medida');
            var usadas = [];

            selects.forEach(function(s) {
                if (s.value) {
                    usadas.push(s.value);
                }
            });

            selects.forEach(function(s) {
                s.querySelectorAll('option').forEach(function(o) {
                    o.disabled = o.value !== '' && o.value !== s.value && usadas.indexOf(o.value) !== -
                        1;
                });
            });
        }

        function adicionarLinha(dados, focar) {
            var i = indice++;
            var linha = modelo.content.firstElementChild.cloneNode(true);

            var busca = linha.querySelector('.espec-busca');
            var medida = linha.querySelector('.espec-medida');
            var preco = linha.querySelector('.espec-preco');
            var custom = linha.querySelector('.espec-custom');
            var rotulo = linha.querySelector('.custom-control-label');

            var contexto = {
                busca: busca,
                select: medida,
                preco: preco
            };

            medida.name = 'especificacoes[' + i + '][medida_id]';
            preco.name = 'especificacoes[' + i + '][preco]';
            custom.name = 'especificacoes[' + i + '][customizavel]';
            custom.id = 'espec_custom_' + i;
            rotulo.setAttribute('for', custom.id);

            // "Customizável" só faz sentido com medida: sem medida fica desmarcado e desabilitado
            // (campo desabilitado não é enviado, e o controller grava 0)
            function sincronizarCustom() {
                if (medida.value === '') {
                    custom.checked = false;
                    custom.disabled = true;
                } else if (custom.disabled) {
                    custom.disabled = false;
                    custom.checked = true; // padrão da coluna: 1
                }
            }

            if (dados) {
                medida.value = dados.medida_id ? String(dados.medida_id) : '';
                busca.value = textoSelecionado(medida);
                preco.value = dados.preco;
                custom.checked = Number(dados.customizavel) === 1;
            }

            sincronizarCustom();

            medida.addEventListener('change', atualizarOpcoes);
            medida.addEventListener('change', sincronizarCustom);

            // ── Busca da medida ──
            busca.addEventListener('focus', function() {
                abrir(contexto);

                // Seleciona o texto para que digitar substitua a medida atual
                setTimeout(function() {
                    busca.select();
                }, 0);
            });

            busca.addEventListener('click', function() {
                abrir(contexto);
            });

            busca.addEventListener('input', function() {
                abrir(contexto);

                // Campo apagado: libera a medida para outras linhas (linha vira "só preço")
                if (busca.value.trim() === '' && medida.value !== '') {
                    medida.value = '';
                    medida.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                }

                renderizar(busca.value);
                posicionar();
            });

            busca.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                    e.preventDefault();
                    abrir(contexto);
                    mover(e.key === 'ArrowDown' ? 1 : -1);
                    return;
                }

                if (e.key === 'Enter') {
                    // Enter na busca não deve enviar o formulário
                    e.preventDefault();

                    if (ativo && ativo.busca === busca) {
                        var itens = painel.querySelectorAll('.item');

                        if (itemAtivo >= 0 && itens[itemAtivo]) {
                            escolher(itens[itemAtivo].dataset.valor);
                        } else if (itens.length === 1) {
                            escolher(itens[0].dataset.valor);
                        }
                    }
                    return;
                }

                if (e.key === 'Escape' && ativo) {
                    e.preventDefault();
                    fechar();
                    return;
                }

                if (e.key === 'Tab') {
                    fechar();
                }
            });

            busca.addEventListener('blur', function() {
                if (ativo && ativo.busca === busca) {
                    fechar();
                }
            });

            mascaraDinheiro(preco);

            linha.querySelector('.espec-remover').addEventListener('click', function() {
                if (ativo && ativo.busca === busca) {
                    fechar();
                }

                linha.remove();
                atualizarOpcoes();
            });

            lista.appendChild(linha);
            atualizarOpcoes();

            // Linha nova (botão): rola até ela e já deixa a busca pronta
            if (focar) {
                lista.scrollTop = lista.scrollHeight;
                busca.focus();
            }
        }

        // Máscara de dinheiro no padrão BR (1.234,56), aplicada a cada linha criada
        function mascaraDinheiro(input) {
            function formatar() {
                var digitos = input.value.replace(/\D/g, '');

                if (digitos === '') {
                    input.value = '';
                    return;
                }

                while (digitos.length < 3) {
                    digitos = '0' + digitos;
                }

                var inteiro = digitos.slice(0, -2).replace(/^0+(?=\d)/, '');
                var centavos = digitos.slice(-2);

                input.value = inteiro.replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ',' + centavos;
            }

            input.addEventListener('input', formatar);
            formatar(); // formata também o valor que já vem preenchido na edição
        }

        var iniciais = [];

        try {
            iniciais = JSON.parse(lista.dataset.iniciais || '[]');
        } catch (e) {
            iniciais = [];
        }

        if (iniciais.length) {
            iniciais.forEach(function(dados) {
                adicionarLinha(dados, false);
            });
        } else {
            adicionarLinha(null, false);
        }

        botaoAdd.addEventListener('click', function() {
            adicionarLinha(null, true);
        });
    })();
</script>

<?php View::end(); ?>