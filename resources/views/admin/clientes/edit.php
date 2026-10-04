<!-- Aqui enviamos para o template principal o título da página -->

<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Editar Cliente') ?> | Admin

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
                            <?= e($titulo ?? 'Editar Cliente') ?>
                        </h4>
                        <p class="card-description mb-0 text-white">
                            <?= e($cliente->nome ?? '') ?>
                        </p>
                    </div>
                    <div class="col-md-4 text-md-right mt-3 mt-md-0">
                        <a href="<?= route('admin.cliente.index') ?>" class="btn btn-secondary btn-sm text-white">
                            <i class="fas fa-arrow-left"></i>
                            Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Formulário -->
                <form method="POST" action="<?= route('admin.cliente.update', ['id' => $cliente->id]) ?>"
                    class="forms-sample">
                    <?= csrf_field() ?>
                    <?= method_field('PUT') ?>

                    <!-- Dados pessoais -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <h5>
                                Dados pessoais
                            </h5>
                        </div>

                        <div class="row">
                            <!-- Nome -->
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="nome">
                                        Nome
                                    </label>
                                    <input type="text" class="form-control <?= hasError('nome') ? 'is-invalid' : '' ?>"
                                        id="nome" name="nome" value="<?= old('nome', $cliente->nome ?? '') ?>"
                                        placeholder="Nome completo">
                                    <?= erroInput('nome') ?>
                                </div>
                            </div>

                            <!-- E-mail -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">
                                        E-mail
                                    </label>
                                    <input type="email"
                                        class="form-control <?= hasError('email') ? 'is-invalid' : '' ?>" id="email"
                                        name="email" value="<?= old('email', $cliente->email ?? '') ?>"
                                        placeholder="cliente@exemplo.com">
                                    <?= erroInput('email') ?>
                                </div>
                            </div>

                            <!-- Celular -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="celular">
                                        Celular
                                    </label>
                                    <input type="text"
                                        class="form-control <?= hasError('celular') ? 'is-invalid' : '' ?> phone_with_ddd"
                                        id="celular" name="celular"
                                        value="<?= old('celular', $cliente->celular ?? '') ?>"
                                        placeholder="(00) 00000-0000">
                                    <?= erroInput('celular') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Endereço principal -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <h5>
                                Endereço principal
                            </h5>
                        </div>

                        <div class="row">
                            <!-- Nome do endereço -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="endereco_nome">
                                        Nome do endereço
                                    </label>
                                    <input type="text"
                                        class="form-control <?= hasError('endereco_nome') ? 'is-invalid' : '' ?>"
                                        id="endereco_nome" name="endereco_nome"
                                        value="<?= old('endereco_nome', $cliente->endereco_nome ?? 'Principal') ?>"
                                        placeholder="Casa, trabalho...">
                                    <?= erroInput('endereco_nome') ?>
                                </div>
                            </div>

                            <!-- CEP -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cep">
                                        CEP
                                    </label>
                                    <input type="text"
                                        class="form-control <?= hasError('cep') ? 'is-invalid' : '' ?> cep" id="cep"
                                        name="cep" value="<?= old('cep', $cliente->endereco_cep ?? '') ?>"
                                        placeholder="00000-000">
                                    <?= erroInput('cep') ?>
                                </div>
                            </div>

                            <!-- Logradouro -->
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="logradouro">
                                        Logradouro
                                    </label>
                                    <input type="text"
                                        class="form-control <?= hasError('logradouro') ? 'is-invalid' : '' ?>"
                                        id="logradouro" name="logradouro"
                                        value="<?= old('logradouro', $cliente->endereco_logradouro ?? '') ?>"
                                        placeholder="Rua, avenida, praça...">
                                    <?= erroInput('logradouro') ?>
                                </div>
                            </div>

                            <!-- Número -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="numero">
                                        Número
                                    </label>
                                    <input type="text"
                                        class="form-control <?= hasError('numero') ? 'is-invalid' : '' ?>" id="numero"
                                        name="numero" value="<?= old('numero', $cliente->endereco_numero ?? '') ?>"
                                        placeholder="Nº">
                                    <?= erroInput('numero') ?>
                                </div>
                            </div>

                            <!-- Complemento -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="complemento">
                                        Complemento
                                    </label>
                                    <input type="text"
                                        class="form-control <?= hasError('complemento') ? 'is-invalid' : '' ?>"
                                        id="complemento" name="complemento"
                                        value="<?= old('complemento', $cliente->endereco_complemento ?? '') ?>"
                                        placeholder="Apartamento, bloco...">
                                    <?= erroInput('complemento') ?>
                                </div>
                            </div>

                            <!-- Bairro -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bairro">
                                        Bairro
                                    </label>
                                    <input type="text"
                                        class="form-control <?= hasError('bairro') ? 'is-invalid' : '' ?>" id="bairro"
                                        name="bairro" value="<?= old('bairro', $cliente->endereco_bairro ?? '') ?>"
                                        placeholder="Bairro">
                                    <?= erroInput('bairro') ?>
                                </div>
                            </div>

                            <!-- Cidade -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cidade">
                                        Cidade
                                    </label>
                                    <input type="text"
                                        class="form-control <?= hasError('cidade') ? 'is-invalid' : '' ?>" id="cidade"
                                        name="cidade" value="<?= old('cidade', $cliente->endereco_cidade ?? '') ?>"
                                        placeholder="Cidade">
                                    <?= erroInput('cidade') ?>
                                </div>
                            </div>

                            <!-- Estado -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="estado">
                                        Estado
                                    </label>
                                    <input type="text"
                                        class="form-control <?= hasError('estado') ? 'is-invalid' : '' ?> uf"
                                        id="estado" name="estado" maxlength="2"
                                        value="<?= old('estado', $cliente->endereco_estado ?? '') ?>" placeholder="UF">
                                    <?= erroInput('estado') ?>
                                </div>
                            </div>

                            <!-- Referência -->
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="referencia">
                                        Referência
                                    </label>
                                    <input type="text"
                                        class="form-control <?= hasError('referencia') ? 'is-invalid' : '' ?>"
                                        id="referencia" name="referencia"
                                        value="<?= old('referencia', $cliente->endereco_referencia ?? '') ?>"
                                        placeholder="Ponto de referência">
                                    <?= erroInput('referencia') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Controle de acesso -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <h5>
                                Controle de acesso
                            </h5>
                        </div>

                        <div class="row">
                            <!-- Status -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ativo">
                                        Status
                                    </label>
                                    <select class="form-control <?= hasError('ativo') ? 'is-invalid' : '' ?>" id="ativo"
                                        name="ativo">
                                        <option value="1"
                                            <?= (string) old('ativo', $cliente->ativo ?? 1) === '1' ? 'selected' : '' ?>>
                                            Ativo
                                        </option>
                                        <option value="0"
                                            <?= (string) old('ativo', $cliente->ativo ?? 1) === '0' ? 'selected' : '' ?>>
                                            Inativo
                                        </option>
                                    </select>
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
                                    <button type="submit" class="btn btn-success mr-2" title="Atualizar"
                                        aria-label="Atualizar cliente">
                                        <i class="mdi mdi-content-save"></i>
                                        Atualizar
                                    </button>
                                    <a href="<?= route('admin.cliente.show', ['id' => $cliente->id]) ?>"
                                        class="btn btn-primary mr-2" title="Visualizar" aria-label="Visualizar cliente">
                                        <i class="mdi mdi-eye"></i>
                                        Visualizar
                                    </a>
                                    <a href="<?= route('admin.cliente.index') ?>" class="btn btn-danger"
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

<?php View::end(); ?>