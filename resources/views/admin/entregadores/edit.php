<?php $maxMb = (int) ($uploadMaxMb ?? 5); ?>

<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Editar Entregador') ?> | Admin

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

    /* Botão de mostrar/ocultar senha */
    .btn-toggle-senha {
        border: 1px solid #ced4da;
        background: #fff;
        color: #6c757d;
    }

    .btn-toggle-senha:hover,
    .btn-toggle-senha:focus {
        background: #f8f9fa;
        color: #212529;
        box-shadow: none;
    }

    /* Com input-group, a mensagem de erro fica fora do input: força a exibição */
    .input-group:has(.is-invalid)+.invalid-feedback {
        display: block;
    }

    /* ── Campo de upload da imagem ────────────────────────────────── */
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
// Avatar: URL externa é usada direto; arquivo local fica em uploads/entregadores/{id}/
$avatarAtual = $entregador->avatar ?? '';
$avatarSrc = '';

if ($avatarAtual !== '' && $avatarAtual !== null) {
    $avatarSrc = preg_match('#^https?://#i', $avatarAtual)
        ? $avatarAtual
        : uploadUrl('entregadores', $avatarAtual, $entregador->id);
}

// Campos de data: o input type="date" exige o formato Y-m-d
$dataNascimento = !empty($entregador->data_nascimento) ? substr((string) $entregador->data_nascimento, 0, 10) : '';
$cnhValidade    = !empty($entregador->cnh_validade) ? substr((string) $entregador->cnh_validade, 0, 10) : '';

// Valor da remuneração no padrão BR (a máscara .dinheiro continua funcionando)
$valorRemuneracao = isset($entregador->valor_remuneracao) && $entregador->valor_remuneracao !== ''
    ? number_format((float) $entregador->valor_remuneracao, 2, ',', '.')
    : '';
?>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">

            <!-- Cabeçalho -->
            <div class="card-header bg-primary text-white">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="card-title mb-1 text-white">
                            <?= e($titulo ?? 'Editar Entregador') ?>
                        </h4>
                        <p class="card-description mb-0 text-white">
                            <?= e($entregador->nome ?? '') ?>
                        </p>
                    </div>
                    <div class="col-md-4 text-md-right mt-3 mt-md-0">
                        <a href="<?= route('admin.entregador.index') ?>" class="btn btn-secondary btn-sm text-white">
                            <i class="fas fa-arrow-left"></i>
                            Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">

                <!-- Formulário -->
                <form method="POST" action="<?= route('admin.entregador.update', ['id' => $entregador->id]) ?>"
                    enctype="multipart/form-data" class="forms-sample">
                    <?= csrf_field() ?>
                    <?= method_field('PUT') ?>

                    <!-- Dados pessoais -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <h5>Dados pessoais</h5>
                        </div>

                        <div class="row">
                            <!-- Tipo Vínculo -->
                            <div class="form-group col-md-3">
                                <label for="tipo_vinculo">Tipo Vínculo</label>
                                <select class="form-control <?= hasError('tipo_vinculo') ? 'is-invalid' : '' ?>"
                                    id="tipo_vinculo" name="tipo_vinculo">
                                    <option value="">Selecione o Tipo do Vínculo</option>
                                    <option value="fixo"
                                        <?= old('tipo_vinculo', $entregador->tipo_vinculo ?? '') === 'fixo' ? 'selected' : '' ?>>
                                        Fixo
                                    </option>
                                    <option value="freelancer"
                                        <?= old('tipo_vinculo', $entregador->tipo_vinculo ?? '') === 'freelancer' ? 'selected' : '' ?>>
                                        Temporário
                                    </option>
                                </select>
                                <?= erroInput('tipo_vinculo') ?>
                            </div>

                            <!-- Nome -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nome">Nome</label>
                                    <input type="text" class="form-control <?= hasError('nome') ? 'is-invalid' : '' ?>"
                                        id="nome" name="nome" value="<?= old('nome', $entregador->nome ?? '') ?>"
                                        placeholder="Nome completo">
                                    <?= erroInput('nome') ?>
                                </div>
                            </div>

                            <!-- CPF -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cpf">CPF</label>
                                    <input type="text"
                                        class="form-control <?= hasError('cpf') ? 'is-invalid' : '' ?> cpf" id="cpf"
                                        name="cpf" value="<?= old('cpf', $entregador->cpf ?? '') ?>"
                                        placeholder="000.000.000-00">
                                    <?= erroInput('cpf') ?>
                                </div>
                            </div>

                            <!-- RG -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="documento_identidade">RG</label>
                                    <input type="text"
                                        class="form-control <?= hasError('documento_identidade') ? 'is-invalid' : '' ?> rg"
                                        id="documento_identidade" name="documento_identidade"
                                        value="<?= old('documento_identidade', $entregador->documento_identidade ?? '') ?>"
                                        placeholder="00.000.000-0">
                                    <?= erroInput('documento_identidade') ?>
                                </div>
                            </div>

                            <!-- Data de Nascimento -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="data_nascimento">Data de Nascimento</label>
                                    <input type="date"
                                        class="form-control <?= hasError('data_nascimento') ? 'is-invalid' : '' ?>"
                                        id="data_nascimento" name="data_nascimento"
                                        value="<?= old('data_nascimento', $dataNascimento) ?>"
                                        placeholder="dd/mm/aaaa" />
                                    <?= erroInput('data_nascimento') ?>
                                </div>
                            </div>

                            <!-- Celular -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="celular">Celular</label>
                                    <input type="text"
                                        class="form-control <?= hasError('celular') ? 'is-invalid' : '' ?> telefone"
                                        id="celular" name="celular"
                                        value="<?= old('celular', $entregador->celular ?? '') ?>"
                                        placeholder="(00) 00000-0000">
                                    <?= erroInput('celular') ?>
                                </div>
                            </div>

                            <!-- Imagem (avatar) -->
                            <div class="form-group col-md-4">
                                <label for="avatar">Imagem (avatar)</label>

                                <div class="upload-box <?= hasError('avatar') ? 'is-invalid' : '' ?>" id="uploadBox">
                                    <div class="upload-drop">
                                        <input type="file" class="upload-input" id="avatar" name="avatar"
                                            accept="image/*">

                                        <img id="uploadImg" class="upload-img <?= $avatarSrc === '' ? 'd-none' : '' ?>"
                                            src="<?= e($avatarSrc) ?>" data-atual="<?= e($avatarSrc) ?>"
                                            alt="Pré-visualização da imagem">

                                        <div id="uploadPlaceholder"
                                            class="upload-placeholder <?= $avatarSrc !== '' ? 'd-none' : '' ?>">
                                            <i class="mdi mdi-cloud-upload"></i>
                                            <span>Clique ou arraste uma imagem aqui</span>
                                            <small>JPG, PNG, GIF ou WEBP · até <?= $maxMb ?> MB</small>
                                        </div>
                                    </div>

                                    <div class="upload-info">
                                        <span class="upload-nome" id="uploadNome">
                                            <?= $avatarSrc !== '' ? 'Imagem atual (deixe como está para mantê-la)' : 'Nenhum arquivo selecionado' ?>
                                        </span>
                                        <button type="button" class="btn btn-outline-danger btn-sm d-none"
                                            id="uploadLimpar" title="Remover seleção" aria-label="Remover seleção">
                                            <i class="mdi mdi-close"></i> Remover
                                        </button>
                                    </div>
                                </div>

                                <small class="text-danger d-none" id="uploadErro"></small>
                                <?= erroInput('avatar') ?>
                            </div>
                        </div>
                    </div>

                    <!-- Autenticação -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <h5>Autenticação</h5>
                        </div>

                        <div class="row">
                            <!-- E-mail -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">E-mail</label>
                                    <input type="email"
                                        class="form-control <?= hasError('email') ? 'is-invalid' : '' ?>" id="email"
                                        name="email" value="<?= old('email', $entregador->email ?? '') ?>"
                                        placeholder="usuario@exemplo.com">
                                    <?= erroInput('email') ?>
                                </div>
                            </div>

                            <!-- Senha -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="password">Nova senha</label>
                                    <div class="input-group">
                                        <input type="password"
                                            class="form-control <?= hasError('password') ? 'is-invalid' : '' ?>"
                                            id="password" name="password" value=""
                                            placeholder="Em branco, mantém a senha atual" autocomplete="new-password">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-toggle-senha"
                                                data-toggle-senha="password" title="Mostrar senha"
                                                aria-label="Mostrar senha" aria-pressed="false">
                                                <i class="mdi mdi-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <?= erroInput('password') ?>
                                </div>
                            </div>

                            <!-- Confirmar senha -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirmar nova senha</label>
                                    <div class="input-group">
                                        <input type="password"
                                            class="form-control <?= hasError('password_confirmation') ? 'is-invalid' : '' ?>"
                                            id="password_confirmation" name="password_confirmation" value=""
                                            placeholder="Repita a nova senha" autocomplete="new-password">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-toggle-senha"
                                                data-toggle-senha="password_confirmation" title="Mostrar senha"
                                                aria-label="Mostrar confirmação de senha" aria-pressed="false">
                                                <i class="mdi mdi-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <?= erroInput('password_confirmation') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Endereço -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <h5>Endereço</h5>
                        </div>

                        <div class="row">
                            <!-- CEP -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cep">CEP</label>
                                    <input type="text"
                                        class="form-control <?= hasError('cep') ? 'is-invalid' : '' ?> cep" id="cep"
                                        name="cep" value="<?= old('cep', $entregador->cep ?? '') ?>"
                                        placeholder="00000-000">
                                    <?= erroInput('cep') ?>
                                </div>
                            </div>

                            <!-- Logradouro -->
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="logradouro">Logradouro</label>
                                    <input type="text"
                                        class="form-control <?= hasError('logradouro') ? 'is-invalid' : '' ?>"
                                        id="logradouro" name="logradouro"
                                        value="<?= old('logradouro', $entregador->logradouro ?? '') ?>"
                                        placeholder="Rua, avenida, praça...">
                                    <?= erroInput('logradouro') ?>
                                </div>
                            </div>

                            <!-- Número -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="numero">Número</label>
                                    <input type="text"
                                        class="form-control <?= hasError('numero') ? 'is-invalid' : '' ?>" id="numero"
                                        name="numero" value="<?= old('numero', $entregador->numero ?? '') ?>"
                                        placeholder="Nº">
                                    <?= erroInput('numero') ?>
                                </div>
                            </div>

                            <!-- Bairro -->
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="bairro">Bairro</label>
                                    <input type="text"
                                        class="form-control <?= hasError('bairro') ? 'is-invalid' : '' ?>" id="bairro"
                                        name="bairro" value="<?= old('bairro', $entregador->bairro ?? '') ?>"
                                        placeholder="Bairro">
                                    <?= erroInput('bairro') ?>
                                </div>
                            </div>

                            <!-- Cidade -->
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="cidade">Cidade</label>
                                    <input type="text"
                                        class="form-control <?= hasError('cidade') ? 'is-invalid' : '' ?>" id="cidade"
                                        name="cidade" value="<?= old('cidade', $entregador->cidade ?? '') ?>"
                                        placeholder="Cidade">
                                    <?= erroInput('cidade') ?>
                                </div>
                            </div>

                            <!-- Estado -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="estado">Estado</label>
                                    <input type="text"
                                        class="form-control <?= hasError('estado') ? 'is-invalid' : '' ?> uf"
                                        id="estado" name="estado" maxlength="2"
                                        value="<?= old('estado', $entregador->estado ?? '') ?>" placeholder="UF">
                                    <?= erroInput('estado') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CNH - Veículo -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <h5>CNH - Veículo</h5>
                        </div>

                        <div class="row">
                            <!-- Número CNH -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="numero_cnh">Número da CNH</label>
                                    <input type="text"
                                        class="form-control <?= hasError('numero_cnh') ? 'is-invalid' : '' ?> cnh"
                                        id="numero_cnh" name="numero_cnh"
                                        value="<?= old('numero_cnh', $entregador->numero_cnh ?? '') ?>"
                                        placeholder="Número CNH">
                                    <?= erroInput('numero_cnh') ?>
                                </div>
                            </div>

                            <!-- Categoria da CNH -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="categoria_cnh">Categoria da CNH</label>
                                    <input type="text"
                                        class="form-control <?= hasError('categoria_cnh') ? 'is-invalid' : '' ?>"
                                        id="categoria_cnh" name="categoria_cnh"
                                        value="<?= old('categoria_cnh', $entregador->categoria_cnh ?? '') ?>"
                                        placeholder="A, B, AB">
                                    <?= erroInput('categoria_cnh') ?>
                                </div>
                            </div>

                            <!-- Validade da CNH -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cnh_validade">Validade da CNH</label>
                                    <input type="date"
                                        class="form-control <?= hasError('cnh_validade') ? 'is-invalid' : '' ?>"
                                        id="cnh_validade" name="cnh_validade"
                                        value="<?= old('cnh_validade', $cnhValidade) ?>" placeholder="dd/mm/aaaa" />
                                    <?= erroInput('cnh_validade') ?>
                                </div>
                            </div>

                            <!-- Tipo de veículo -->
                            <div class="form-group col-md-3">
                                <label for="tipo_veiculo">Tipo de veículo</label>
                                <select class="form-control <?= hasError('tipo_veiculo') ? 'is-invalid' : '' ?>"
                                    id="tipo_veiculo" name="tipo_veiculo">
                                    <option value="">Selecione o Tipo de veículo</option>
                                    <option value="moto"
                                        <?= old('tipo_veiculo', $entregador->tipo_veiculo ?? '') === 'moto' ? 'selected' : '' ?>>
                                        Moto
                                    </option>
                                    <option value="carro"
                                        <?= old('tipo_veiculo', $entregador->tipo_veiculo ?? '') === 'carro' ? 'selected' : '' ?>>
                                        Carro
                                    </option>
                                </select>
                                <?= erroInput('tipo_veiculo') ?>
                            </div>

                            <!-- Marca do veículo -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="marca_veiculo">Marca do veículo</label>
                                    <input type="text"
                                        class="form-control <?= hasError('marca_veiculo') ? 'is-invalid' : '' ?>"
                                        id="marca_veiculo" name="marca_veiculo"
                                        value="<?= old('marca_veiculo', $entregador->marca_veiculo ?? '') ?>"
                                        placeholder="Marca do veículo">
                                    <?= erroInput('marca_veiculo') ?>
                                </div>
                            </div>

                            <!-- Modelo do veículo -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="modelo_veiculo">Modelo do veículo</label>
                                    <input type="text"
                                        class="form-control <?= hasError('modelo_veiculo') ? 'is-invalid' : '' ?>"
                                        id="modelo_veiculo" name="modelo_veiculo"
                                        value="<?= old('modelo_veiculo', $entregador->modelo_veiculo ?? '') ?>"
                                        placeholder="Modelo do veículo">
                                    <?= erroInput('modelo_veiculo') ?>
                                </div>
                            </div>

                            <!-- Placa do veículo -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="placa_veiculo">Placa do veículo</label>
                                    <input type="text"
                                        class="form-control <?= hasError('placa_veiculo') ? 'is-invalid' : '' ?> placa"
                                        id="placa_veiculo" name="placa_veiculo"
                                        value="<?= old('placa_veiculo', $entregador->placa_veiculo ?? '') ?>"
                                        placeholder="Placa do veículo">
                                    <?= erroInput('placa_veiculo') ?>
                                </div>
                            </div>

                            <!-- Cor do veículo -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cor_veiculo">Cor do veículo</label>
                                    <input type="text"
                                        class="form-control <?= hasError('cor_veiculo') ? 'is-invalid' : '' ?>"
                                        id="cor_veiculo" name="cor_veiculo"
                                        value="<?= old('cor_veiculo', $entregador->cor_veiculo ?? '') ?>"
                                        placeholder="Cor do veículo">
                                    <?= erroInput('cor_veiculo') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Remuneração -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <h5>Remuneração</h5>
                        </div>

                        <div class="row">
                            <!-- Tipo de Remuneração -->
                            <div class="form-group col-md-4">
                                <label for="tipo_remuneracao">Tipo de Remuneração</label>
                                <select class="form-control <?= hasError('tipo_remuneracao') ? 'is-invalid' : '' ?>"
                                    id="tipo_remuneracao" name="tipo_remuneracao">
                                    <option value="">Selecione o Tipo de Remuneração</option>
                                    <option value="por_entrega"
                                        <?= old('tipo_remuneracao', $entregador->tipo_remuneracao ?? '') === 'por_entrega' ? 'selected' : '' ?>>
                                        Por Entrega
                                    </option>
                                    <option value="por_km"
                                        <?= old('tipo_remuneracao', $entregador->tipo_remuneracao ?? '') === 'por_km' ? 'selected' : '' ?>>
                                        Por KM
                                    </option>
                                    <option value="fixo"
                                        <?= old('tipo_remuneracao', $entregador->tipo_remuneracao ?? '') === 'fixo' ? 'selected' : '' ?>>
                                        Fixo
                                    </option>
                                </select>
                                <?= erroInput('tipo_remuneracao') ?>
                            </div>

                            <!-- Valor da Remuneração -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="valor_remuneracao">Valor da Remuneração</label>
                                    <input type="text"
                                        class="form-control <?= hasError('valor_remuneracao') ? 'is-invalid' : '' ?> dinheiro"
                                        id="valor_remuneracao" name="valor_remuneracao"
                                        value="<?= old('valor_remuneracao', $valorRemuneracao) ?>"
                                        placeholder="Valor da Remuneração">
                                    <?= erroInput('valor_remuneracao') ?>
                                </div>
                            </div>

                            <!-- Chave PIX -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="chave_pix">Chave PIX</label>
                                    <input type="text"
                                        class="form-control <?= hasError('chave_pix') ? 'is-invalid' : '' ?>"
                                        id="chave_pix" name="chave_pix"
                                        value="<?= old('chave_pix', $entregador->chave_pix ?? '') ?>"
                                        placeholder="Chave PIX">
                                    <?= erroInput('chave_pix') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Controle de acesso -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <h5>Controle de acesso</h5>
                        </div>

                        <div class="row">
                            <!-- Status -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="ativo">Status</label>
                                    <select class="form-control <?= hasError('ativo') ? 'is-invalid' : '' ?>" id="ativo"
                                        name="ativo">
                                        <option value="1"
                                            <?= (string) old('ativo', $entregador->ativo ?? 1) === '1' ? 'selected' : '' ?>>
                                            Entregador Ativo
                                        </option>
                                        <option value="0"
                                            <?= (string) old('ativo', $entregador->ativo ?? 1) === '0' ? 'selected' : '' ?>>
                                            Entregador Inativo
                                        </option>
                                    </select>
                                    <?= erroInput('ativo') ?>
                                </div>
                            </div>

                            <!-- Disponibilidade -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="disponivel">Ativo para entregas</label>
                                    <select class="form-control <?= hasError('disponivel') ? 'is-invalid' : '' ?>"
                                        id="disponivel" name="disponivel">
                                        <option value="1"
                                            <?= (string) old('disponivel', $entregador->disponivel ?? 1) === '1' ? 'selected' : '' ?>>
                                            Disponível
                                        </option>
                                        <option value="0"
                                            <?= (string) old('disponivel', $entregador->disponivel ?? 1) === '0' ? 'selected' : '' ?>>
                                            Indisponível
                                        </option>
                                    </select>
                                    <?= erroInput('disponivel') ?>
                                </div>
                            </div>

                            <!-- Turno Manhã -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="turno_manha">Turno Manhã</label>
                                    <select class="form-control <?= hasError('turno_manha') ? 'is-invalid' : '' ?>"
                                        id="turno_manha" name="turno_manha">
                                        <option value="1"
                                            <?= (string) old('turno_manha', $entregador->turno_manha ?? 1) === '1' ? 'selected' : '' ?>>
                                            Disponível
                                        </option>
                                        <option value="0"
                                            <?= (string) old('turno_manha', $entregador->turno_manha ?? 1) === '0' ? 'selected' : '' ?>>
                                            Indisponível
                                        </option>
                                    </select>
                                    <?= erroInput('turno_manha') ?>
                                </div>
                            </div>

                            <!-- Turno Tarde -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="turno_tarde">Turno Tarde</label>
                                    <select class="form-control <?= hasError('turno_tarde') ? 'is-invalid' : '' ?>"
                                        id="turno_tarde" name="turno_tarde">
                                        <option value="1"
                                            <?= (string) old('turno_tarde', $entregador->turno_tarde ?? 1) === '1' ? 'selected' : '' ?>>
                                            Disponível
                                        </option>
                                        <option value="0"
                                            <?= (string) old('turno_tarde', $entregador->turno_tarde ?? 1) === '0' ? 'selected' : '' ?>>
                                            Indisponível
                                        </option>
                                    </select>
                                    <?= erroInput('turno_tarde') ?>
                                </div>
                            </div>

                            <!-- Turno Noite -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="turno_noite">Turno Noite</label>
                                    <select class="form-control <?= hasError('turno_noite') ? 'is-invalid' : '' ?>"
                                        id="turno_noite" name="turno_noite">
                                        <option value="1"
                                            <?= (string) old('turno_noite', $entregador->turno_noite ?? 1) === '1' ? 'selected' : '' ?>>
                                            Disponível
                                        </option>
                                        <option value="0"
                                            <?= (string) old('turno_noite', $entregador->turno_noite ?? 1) === '0' ? 'selected' : '' ?>>
                                            Indisponível
                                        </option>
                                    </select>
                                    <?= erroInput('turno_noite') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Controle de Aprovação -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <h5>Controle de Aprovação</h5>
                        </div>

                        <div class="row">
                            <!-- Status de aprovação -->
                            <div class="form-group col-md-4">
                                <label for="status_aprovacao">Controle de Aprovação</label>
                                <select class="form-control <?= hasError('status_aprovacao') ? 'is-invalid' : '' ?>"
                                    id="status_aprovacao" name="status_aprovacao">
                                    <option value="">Controle de Aprovação</option>
                                    <option value="pendente"
                                        <?= old('status_aprovacao', $entregador->status_aprovacao ?? '') === 'pendente' ? 'selected' : '' ?>>
                                        Pendente
                                    </option>
                                    <option value="aprovado"
                                        <?= old('status_aprovacao', $entregador->status_aprovacao ?? '') === 'aprovado' ? 'selected' : '' ?>>
                                        Aprovado
                                    </option>
                                    <option value="reprovado"
                                        <?= old('status_aprovacao', $entregador->status_aprovacao ?? '') === 'reprovado' ? 'selected' : '' ?>>
                                        Reprovado
                                    </option>
                                    <option value="bloqueado"
                                        <?= old('status_aprovacao', $entregador->status_aprovacao ?? '') === 'bloqueado' ? 'selected' : '' ?>>
                                        Bloqueado
                                    </option>
                                </select>
                                <?= erroInput('status_aprovacao') ?>
                            </div>

                            <!-- Motivo da Reprovação -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="motivo_reprovacao">Motivo da Reprovação</label>
                                    <textarea
                                        class="form-control <?= hasError('motivo_reprovacao') ? 'is-invalid' : '' ?>"
                                        id="motivo_reprovacao" name="motivo_reprovacao" rows="3"
                                        placeholder="Motivo da Reprovação"><?= old('motivo_reprovacao', $entregador->motivo_reprovacao ?? '') ?></textarea>
                                    <?= erroInput('motivo_reprovacao') ?>
                                </div>
                            </div>

                            <!-- Observações -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="observacoes">Observação</label>
                                    <textarea class="form-control <?= hasError('observacoes') ? 'is-invalid' : '' ?>"
                                        id="observacoes" name="observacoes" rows="3"
                                        placeholder="Observação"><?= old('observacoes', $entregador->observacoes ?? '') ?></textarea>
                                    <?= erroInput('observacoes') ?>
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
                                        aria-label="Atualizar entregador">
                                        <i class="mdi mdi-content-save"></i>
                                        Atualizar
                                    </button>
                                    <a href="<?= route('admin.entregador.show', ['id' => $entregador->id]) ?>"
                                        class="btn btn-primary mr-2" title="Visualizar"
                                        aria-label="Visualizar entregador">
                                        <i class="mdi mdi-eye"></i>
                                        Visualizar
                                    </a>
                                    <a href="<?= route('admin.entregador.index') ?>" class="btn btn-danger"
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
    // Mostrar/ocultar senha: um único listener delegado para todos os botões
    document.addEventListener('click', function(event) {
        var botao = event.target.closest('[data-toggle-senha]');
        if (!botao) {
            return;
        }

        var campo = document.getElementById(botao.getAttribute('data-toggle-senha'));
        var icone = botao.querySelector('i');
        if (!campo || !icone) {
            return;
        }

        var mostrar = campo.type === 'password';

        campo.type = mostrar ? 'text' : 'password';
        icone.className = mostrar ? 'mdi mdi-eye-off' : 'mdi mdi-eye';
        botao.setAttribute('aria-pressed', mostrar ? 'true' : 'false');
        botao.setAttribute('title', mostrar ? 'Ocultar senha' : 'Mostrar senha');
    });
</script>

<script nonce="<?= e(CSP_NONCE) ?>">
    // Campo de upload da imagem (avatar)
    (function() {
        var MAX_BYTES = <?= $maxMb ?> * 1024 * 1024;

        var $box = $('#uploadBox');
        var $input = $('#avatar');
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