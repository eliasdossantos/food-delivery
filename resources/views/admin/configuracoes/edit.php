<?php
$maxMb = (int) ($uploadMaxMb ?? 5);

// Se o valor salvo for uma URL externa, ela é exibida como está.
$logoAtual = '';
if (!empty($configuracao->logo)) {
    $logoAtual = preg_match('#^https?://#i', $configuracao->logo)
        ? $configuracao->logo
        : uploadUrl('configuracao', $configuracao->logo);
}

$estados = [
    'AC',
    'AL',
    'AP',
    'AM',
    'BA',
    'CE',
    'DF',
    'ES',
    'GO',
    'MA',
    'MT',
    'MS',
    'MG',
    'PA',
    'PB',
    'PR',
    'PE',
    'PI',
    'RJ',
    'RN',
    'RS',
    'RO',
    'RR',
    'SC',
    'SP',
    'SE',
    'TO',
];

$redes = [
    'instagram' => ['Instagram', 'mdi-instagram', 'https://instagram.com/seuperfil'],
    'facebook'  => ['Facebook', 'mdi-facebook', 'https://facebook.com/suapagina'],
    'youtube'   => ['YouTube', 'mdi-youtube', 'https://youtube.com/@seucanal'],
    'twitter'   => ['Twitter / X', 'mdi-twitter', 'https://x.com/seuperfil'],
];

$estadoAtual    = (string) old('estado', $configuracao->estado ?? '');
$aceitaEntrega  = (string) old('aceita_entrega', (string) ($configuracao->aceita_entrega ?? 1));
$aceitaRetirada = (string) old('aceita_retirada', (string) ($configuracao->aceita_retirada ?? 1));
?>

<!-- Aqui enviamos para o template principal o título da página -->

<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Editar Configuração') ?> | Admin

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

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <!-- Cabeçalho -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="card-title mb-1 text-white">
                            <?= e($titulo ?? 'Editar Configuração') ?>
                        </h4>
                        <p class="card-description mb-0 text-white">
                            <?= e($configuracao->nome_fantasia ?? '') ?>
                        </p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <a href="<?= route('admin.configuracao.index') ?>" class="btn btn-secondary btn-sm text-white">
                            <i class="mdi mdi-arrow-left"></i>
                            Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Formulário -->
                <form method="POST" action="<?= route('admin.configuracao.update', ['id' => $configuracao->id]) ?>"
                    enctype="multipart/form-data" class="forms-sample">
                    <?= csrf_field() ?>
                    <?= method_field('PUT') ?>

                    <div class="row">
                        <!-- Nome fantasia -->
                        <div class="form-group col-md-6">
                            <label for="nome_fantasia">Nome fantasia</label>
                            <input type="text" maxlength="250"
                                class="form-control <?= hasError('nome_fantasia') ? 'is-invalid' : '' ?>"
                                id="nome_fantasia" name="nome_fantasia"
                                value="<?= old('nome_fantasia', $configuracao->nome_fantasia ?? '') ?>"
                                placeholder="Nome do estabelecimento" autofocus>
                            <?= erroInput('nome_fantasia') ?>
                        </div>

                        <!-- Razão social -->
                        <div class="form-group col-md-6">
                            <label for="razao_social">Razão social</label>
                            <input type="text" maxlength="250"
                                class="form-control <?= hasError('razao_social') ? 'is-invalid' : '' ?>"
                                id="razao_social" name="razao_social"
                                value="<?= old('razao_social', $configuracao->razao_social ?? '') ?>"
                                placeholder="Razão social">
                            <?= erroInput('razao_social') ?>
                        </div>
                    </div>

                    <!-- Descrição -->
                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <textarea class="form-control <?= hasError('descricao') ? 'is-invalid' : '' ?>" id="descricao"
                            name="descricao" rows="3"
                            placeholder="Breve descrição do estabelecimento"><?= old('descricao', $configuracao->descricao ?? '') ?></textarea>
                        <?= erroInput('descricao') ?>
                    </div>

                    <div class="row">
                        <!-- CNPJ -->
                        <div class="form-group col-md-6">
                            <label for="cnpj">CNPJ</label>
                            <input type="text" maxlength="25"
                                class="form-control <?= hasError('cnpj') ? 'is-invalid' : '' ?>" id="cnpj" name="cnpj"
                                value="<?= old('cnpj', $configuracao->cnpj ?? '') ?>" placeholder="00.000.000/0000-00">
                            <?= erroInput('cnpj') ?>
                        </div>

                        <!-- Logo -->
                        <div class="form-group col-md-6">
                            <label for="logo">Logo</label>

                            <div class="upload-box <?= hasError('logo') ? 'is-invalid' : '' ?>" id="uploadBox">
                                <div class="upload-drop">
                                    <input type="file" class="upload-input" id="logo" name="logo" accept="image/*">

                                    <img id="uploadImg" class="upload-img <?= $logoAtual === '' ? 'd-none' : '' ?>"
                                        src="<?= e($logoAtual) ?>" data-atual="<?= e($logoAtual) ?>"
                                        alt="Pré-visualização do logo">

                                    <div id="uploadPlaceholder"
                                        class="upload-placeholder <?= $logoAtual !== '' ? 'd-none' : '' ?>">
                                        <i class="mdi mdi-cloud-upload"></i>
                                        <span>Clique ou arraste o logo aqui</span>
                                        <small>JPG, PNG, GIF ou WEBP · até <?= $maxMb ?> MB</small>
                                    </div>
                                </div>

                                <div class="upload-info">
                                    <span class="upload-nome" id="uploadNome">
                                        <?= $logoAtual !== '' ? 'Logo atual (deixe como está para mantê-lo)' : 'Nenhum arquivo selecionado' ?>
                                    </span>
                                    <button type="button" class="btn btn-outline-danger btn-sm d-none" id="uploadLimpar"
                                        title="Remover seleção" aria-label="Remover seleção">
                                        <i class="mdi mdi-close"></i> Remover
                                    </button>
                                </div>
                            </div>

                            <small class="text-danger d-none" id="uploadErro"></small>
                            <?= erroInput('logo') ?>
                        </div>
                    </div>

                    <div class="row">
                        <!-- E-mail -->
                        <div class="form-group col-md-4">
                            <label for="email">E-mail</label>
                            <input type="email" maxlength="180"
                                class="form-control <?= hasError('email') ? 'is-invalid' : '' ?>" id="email"
                                name="email" value="<?= old('email', $configuracao->email ?? '') ?>"
                                placeholder="contato@exemplo.com">
                            <?= erroInput('email') ?>
                        </div>

                        <!-- Telefone -->
                        <div class="form-group col-md-4">
                            <label for="telefone">Telefone</label>
                            <input type="text" maxlength="20"
                                class="form-control <?= hasError('telefone') ? 'is-invalid' : '' ?>" id="telefone"
                                name="telefone" value="<?= old('telefone', $configuracao->telefone ?? '') ?>"
                                placeholder="(00) 0000-0000">
                            <?= erroInput('telefone') ?>
                        </div>

                        <!-- WhatsApp -->
                        <div class="form-group col-md-4">
                            <label for="whatsapp">WhatsApp</label>
                            <input type="text" maxlength="20"
                                class="form-control <?= hasError('whatsapp') ? 'is-invalid' : '' ?>" id="whatsapp"
                                name="whatsapp" value="<?= old('whatsapp', $configuracao->whatsapp ?? '') ?>"
                                placeholder="(00) 00000-0000">
                            <?= erroInput('whatsapp') ?>
                        </div>
                    </div>

                    <div class="row">
                        <!-- CEP -->
                        <div class="form-group col-md-3">
                            <label for="cep">CEP</label>
                            <input type="text" maxlength="9" autocomplete="off"
                                class="form-control <?= hasError('cep') ? 'is-invalid' : '' ?>" id="cep" name="cep"
                                value="<?= old('cep', $configuracao->cep ?? '') ?>" placeholder="00000-000"
                                data-cep-url="<?= e(route('admin.configuracao.cep')) ?>">
                            <small id="cep-feedback" class="form-text text-muted">Preenche o endereço</small>
                            <?= erroInput('cep') ?>
                        </div>

                        <!-- Logradouro -->
                        <div class="form-group col-md-6">
                            <label for="logradouro">Logradouro</label>
                            <input type="text" maxlength="250"
                                class="form-control <?= hasError('logradouro') ? 'is-invalid' : '' ?>" id="logradouro"
                                name="logradouro" value="<?= old('logradouro', $configuracao->logradouro ?? '') ?>"
                                placeholder="Rua, avenida...">
                            <?= erroInput('logradouro') ?>
                        </div>

                        <!-- Número -->
                        <div class="form-group col-md-3">
                            <label for="numero">Número</label>
                            <input type="text" maxlength="30"
                                class="form-control <?= hasError('numero') ? 'is-invalid' : '' ?>" id="numero"
                                name="numero" value="<?= old('numero', $configuracao->numero ?? '') ?>"
                                placeholder="Nº">
                            <?= erroInput('numero') ?>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Complemento -->
                        <div class="form-group col-md-4">
                            <label for="complemento">Complemento</label>
                            <input type="text" maxlength="150"
                                class="form-control <?= hasError('complemento') ? 'is-invalid' : '' ?>" id="complemento"
                                name="complemento" value="<?= old('complemento', $configuracao->complemento ?? '') ?>"
                                placeholder="Sala, bloco...">
                            <?= erroInput('complemento') ?>
                        </div>

                        <!-- Bairro -->
                        <div class="form-group col-md-3">
                            <label for="bairro">Bairro</label>
                            <input type="text" maxlength="200"
                                class="form-control <?= hasError('bairro') ? 'is-invalid' : '' ?>" id="bairro"
                                name="bairro" value="<?= old('bairro', $configuracao->bairro ?? '') ?>"
                                placeholder="Bairro">
                            <?= erroInput('bairro') ?>
                        </div>

                        <!-- Cidade -->
                        <div class="form-group col-md-3">
                            <label for="cidade">Cidade</label>
                            <input type="text" maxlength="200"
                                class="form-control <?= hasError('cidade') ? 'is-invalid' : '' ?>" id="cidade"
                                name="cidade" value="<?= old('cidade', $configuracao->cidade ?? '') ?>"
                                placeholder="Cidade">
                            <?= erroInput('cidade') ?>
                        </div>

                        <!-- UF -->
                        <div class="form-group col-md-2">
                            <label for="estado">UF</label>
                            <select class="form-control <?= hasError('estado') ? 'is-invalid' : '' ?>" id="estado"
                                name="estado">
                                <option value="">—</option>
                                <?php foreach ($estados as $uf): ?>
                                <option value="<?= $uf ?>" <?= $estadoAtual === $uf ? 'selected' : '' ?>>
                                    <?= $uf ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <?= erroInput('estado') ?>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Latitude -->
                        <div class="form-group col-md-3">
                            <label for="latitude">Latitude</label>
                            <input type="number" step="any" min="-90" max="90"
                                class="form-control <?= hasError('latitude') ? 'is-invalid' : '' ?>" id="latitude"
                                name="latitude" value="<?= old('latitude', $configuracao->latitude ?? '') ?>"
                                placeholder="-7.1195000">
                            <?= erroInput('latitude') ?>
                        </div>

                        <!-- Longitude -->
                        <div class="form-group col-md-3">
                            <label for="longitude">Longitude</label>
                            <input type="number" step="any" min="-180" max="180"
                                class="form-control <?= hasError('longitude') ? 'is-invalid' : '' ?>" id="longitude"
                                name="longitude" value="<?= old('longitude', $configuracao->longitude ?? '') ?>"
                                placeholder="-34.8450000">
                            <?= erroInput('longitude') ?>
                        </div>

                        <!-- Link do Google Maps -->
                        <div class="form-group col-md-6">
                            <label for="link_google_maps">Link do Google Maps</label>
                            <input type="url" maxlength="500"
                                class="form-control <?= hasError('link_google_maps') ? 'is-invalid' : '' ?>"
                                id="link_google_maps" name="link_google_maps"
                                value="<?= old('link_google_maps', $configuracao->link_google_maps ?? '') ?>"
                                placeholder="https://maps.google.com/...">
                            <?= erroInput('link_google_maps') ?>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Redes sociais -->
                        <?php foreach ($redes as $campo => [$rotulo, $icone, $exemplo]): ?>
                        <div class="form-group col-md-6">
                            <label for="<?= $campo ?>"><?= e($rotulo) ?></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="mdi <?= $icone ?>"></i></span>
                                </div>
                                <input type="text" maxlength="200"
                                    class="form-control <?= hasError($campo) ? 'is-invalid' : '' ?>" id="<?= $campo ?>"
                                    name="<?= $campo ?>" value="<?= old($campo, $configuracao->{$campo} ?? '') ?>"
                                    placeholder="<?= e($exemplo) ?>">
                            </div>
                            <?= erroInput($campo) ?>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="row">
                        <!-- Aceita entrega -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="aceita_entrega">Aceita entrega</label>
                                <select class="form-control <?= hasError('aceita_entrega') ? 'is-invalid' : '' ?>"
                                    id="aceita_entrega" name="aceita_entrega">
                                    <option value="1" <?= $aceitaEntrega === '1' ? 'selected' : '' ?>>Sim</option>
                                    <option value="0" <?= $aceitaEntrega === '0' ? 'selected' : '' ?>>Não</option>
                                </select>
                                <?= erroInput('aceita_entrega') ?>
                            </div>
                        </div>

                        <!-- Aceita retirada -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="aceita_retirada">Aceita retirada</label>
                                <select class="form-control <?= hasError('aceita_retirada') ? 'is-invalid' : '' ?>"
                                    id="aceita_retirada" name="aceita_retirada">
                                    <option value="1" <?= $aceitaRetirada === '1' ? 'selected' : '' ?>>Sim</option>
                                    <option value="0" <?= $aceitaRetirada === '0' ? 'selected' : '' ?>>Não</option>
                                </select>
                                <?= erroInput('aceita_retirada') ?>
                            </div>
                        </div>

                        <!-- Valor do pedido mínimo -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="valor_pedido_minimo">Valor do pedido mínimo (R$)</label>
                                <input type="number" step="0.01" min="0"
                                    class="form-control <?= hasError('valor_pedido_minimo') ? 'is-invalid' : '' ?>"
                                    id="valor_pedido_minimo" name="valor_pedido_minimo"
                                    value="<?= old('valor_pedido_minimo', $configuracao->valor_pedido_minimo ?? '') ?>"
                                    placeholder="0,00">
                                <?= erroInput('valor_pedido_minimo') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Tempo médio de entrega -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tempo_medio_entrega_min">Tempo médio de entrega (min)</label>
                                <input type="number" step="1" min="0"
                                    class="form-control <?= hasError('tempo_medio_entrega_min') ? 'is-invalid' : '' ?>"
                                    id="tempo_medio_entrega_min" name="tempo_medio_entrega_min"
                                    value="<?= old('tempo_medio_entrega_min', $configuracao->tempo_medio_entrega_min ?? '') ?>"
                                    placeholder="Ex.: 45">
                                <?= erroInput('tempo_medio_entrega_min') ?>
                            </div>
                        </div>

                        <!-- Tempo médio de retirada -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tempo_medio_retirada_min">Tempo médio de retirada (min)</label>
                                <input type="number" step="1" min="0"
                                    class="form-control <?= hasError('tempo_medio_retirada_min') ? 'is-invalid' : '' ?>"
                                    id="tempo_medio_retirada_min" name="tempo_medio_retirada_min"
                                    value="<?= old('tempo_medio_retirada_min', $configuracao->tempo_medio_retirada_min ?? '') ?>"
                                    placeholder="Ex.: 20">
                                <?= erroInput('tempo_medio_retirada_min') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Taxa de entrega padrão -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="taxa_entrega_padrao">Taxa de entrega padrão (R$)</label>
                                <input type="number" step="0.01" min="0"
                                    class="form-control <?= hasError('taxa_entrega_padrao') ? 'is-invalid' : '' ?>"
                                    id="taxa_entrega_padrao" name="taxa_entrega_padrao"
                                    value="<?= old('taxa_entrega_padrao', $configuracao->taxa_entrega_padrao ?? '') ?>"
                                    placeholder="0,00">
                                <?= erroInput('taxa_entrega_padrao') ?>
                            </div>
                        </div>

                        <!-- Frete grátis acima de -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="frete_gratis_acima_de">Frete grátis acima de (R$)</label>
                                <input type="number" step="0.01" min="0"
                                    class="form-control <?= hasError('frete_gratis_acima_de') ? 'is-invalid' : '' ?>"
                                    id="frete_gratis_acima_de" name="frete_gratis_acima_de"
                                    value="<?= old('frete_gratis_acima_de', $configuracao->frete_gratis_acima_de ?? '') ?>"
                                    placeholder="0,00">
                                <?= erroInput('frete_gratis_acima_de') ?>
                            </div>
                        </div>

                        <!-- Raio de entrega -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="raio_entrega_km">Raio de entrega (km)</label>
                                <input type="number" step="0.01" min="0"
                                    class="form-control <?= hasError('raio_entrega_km') ? 'is-invalid' : '' ?>"
                                    id="raio_entrega_km" name="raio_entrega_km"
                                    value="<?= old('raio_entrega_km', $configuracao->raio_entrega_km ?? '') ?>"
                                    placeholder="Ex.: 5">
                                <?= erroInput('raio_entrega_km') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Chave PIX -->
                        <div class="form-group col-md-6">
                            <label for="chave_pix">Chave PIX</label>
                            <input type="text" maxlength="200"
                                class="form-control <?= hasError('chave_pix') ? 'is-invalid' : '' ?>" id="chave_pix"
                                name="chave_pix" value="<?= old('chave_pix', $configuracao->chave_pix ?? '') ?>"
                                placeholder="CPF, CNPJ, e-mail, telefone ou chave aleatória">
                            <?= erroInput('chave_pix') ?>
                        </div>

                        <!-- Texto do rodapé -->
                        <div class="form-group col-md-6">
                            <label for="texto_rodape">Texto do rodapé</label>
                            <input type="text" maxlength="250"
                                class="form-control <?= hasError('texto_rodape') ? 'is-invalid' : '' ?>"
                                id="texto_rodape" name="texto_rodape"
                                value="<?= old('texto_rodape', $configuracao->texto_rodape ?? '') ?>"
                                placeholder="© Seu estabelecimento. Todos os direitos reservados.">
                            <?= erroInput('texto_rodape') ?>
                        </div>
                    </div>

                    <!-- Meta title -->
                    <div class="form-group">
                        <label for="meta_title">Meta title</label>
                        <input type="text" maxlength="150"
                            class="form-control <?= hasError('meta_title') ? 'is-invalid' : '' ?>" id="meta_title"
                            name="meta_title" value="<?= old('meta_title', $configuracao->meta_title ?? '') ?>"
                            placeholder="Título exibido nos buscadores">
                        <?= erroInput('meta_title') ?>
                    </div>

                    <!-- Meta description -->
                    <div class="form-group">
                        <label for="meta_description">Meta description</label>
                        <textarea class="form-control <?= hasError('meta_description') ? 'is-invalid' : '' ?>"
                            id="meta_description" name="meta_description" rows="2" maxlength="300"
                            placeholder="Descrição exibida nos buscadores"><?= old('meta_description', $configuracao->meta_description ?? '') ?></textarea>
                        <?= erroInput('meta_description') ?>
                    </div>

                    <div class="card-footer bg-light d-flex justify-content-end">
                        <!-- Botões -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="d-flex flex-wrap align-items-center">
                                    <button type="submit" class="btn btn-success mr-2" title="Atualizar"
                                        aria-label="Atualizar configuração">
                                        <i class="mdi mdi-content-save"></i>
                                        Atualizar
                                    </button>
                                    <a href="<?= route('admin.configuracao.index') ?>" class="btn btn-danger"
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
<script nonce="<?= e(cspNonce()) ?>">
// Campo de upload do logo
(function() {
    var MAX_BYTES = <?= $maxMb ?> * 1024 * 1024;

    var $box = $('#uploadBox');
    var $input = $('#logo');
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

// Busca de CEP: preenche logradouro, bairro, cidade e UF
(function() {
    const cepInput = document.getElementById('cep');
    const feedback = document.getElementById('cep-feedback');
    const campos = {
        logradouro: document.getElementById('logradouro'),
        bairro: document.getElementById('bairro'),
        cidade: document.getElementById('cidade'),
        estado: document.getElementById('estado')
    };
    const textoPadrao = feedback.textContent;
    let consulta = 0;

    const setMsg = (txt, cls) => {
        feedback.textContent = txt;
        feedback.className = 'form-text ' + cls;
    };

    cepInput.addEventListener('input', function() {
        const v = this.value.replace(/\D/g, '').slice(0, 8);
        this.value = v.length > 5 ? v.slice(0, 5) + '-' + v.slice(5) : v;

        if (v.length === 8) {
            buscar(v);
        } else {
            setMsg(textoPadrao, 'text-muted');
        }
    });

    async function buscar(cep) {
        const id = ++consulta;
        setMsg('Buscando CEP...', 'text-muted');

        try {
            const res = await fetch(cepInput.dataset.cepUrl + '?cep=' + cep, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });
            const data = await res.json();
            if (id !== consulta) return; // resposta antiga

            if (!data.ok) {
                setMsg(data.message || 'Não foi possível consultar o CEP.', 'text-danger');
                return;
            }

            // Só sobrescreve quando a API trouxe valor (CEP geral vem sem logradouro/bairro)
            if (data.logradouro) campos.logradouro.value = data.logradouro;
            if (data.bairro) campos.bairro.value = data.bairro;
            if (data.cidade) campos.cidade.value = data.cidade;
            if (data.uf) campos.estado.value = data.uf;

            setMsg('Endereço encontrado: ' + (data.cidade || '') + (data.uf ? ' - ' + data.uf : ''),
                'text-success');
        } catch (e) {
            if (id === consulta) setMsg('Erro de comunicação ao consultar o CEP.', 'text-danger');
        }
    }
})();
</script>
<?php View::end(); ?>