<!-- Aqui enviamos para o template principal o título da página -->

<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Novo Cliente') ?> | Admin

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
                            <?= e($titulo ?? 'Novo Cliente') ?>
                        </h4>
                        <p class="card-description mb-0 text-white">
                            Cadastre um novo cliente no sistema.
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
                <form method="POST" action="<?= route('admin.cliente.store') ?>" class="forms-sample">
                    <?= csrf_field() ?>

                    <!-- Dados pessoais -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <h5>
                                Dados pessoais
                            </h5>
                        </div>

                        <div class="row">
                            <!-- Nome -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nome">
                                        Nome
                                    </label>
                                    <input type="text" class="form-control <?= hasError('nome') ? 'is-invalid' : '' ?>"
                                        id="nome" name="nome" value="<?= old('nome', '') ?>"
                                        placeholder="Nome completo">
                                    <?= erroInput('nome') ?>
                                </div>
                            </div>

                            <!-- Celular -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="celular">
                                        Celular
                                    </label>
                                    <input type="text"
                                        class="form-control <?= hasError('celular') ? 'is-invalid' : '' ?> telefone"
                                        id="celular" name="celular" value="<?= old('celular', '') ?>"
                                        placeholder="(00) 00000-0000">
                                    <?= erroInput('celular') ?>
                                </div>
                            </div>

                            <!-- CPF -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cpf">
                                        CPF
                                    </label>
                                    <input type="text"
                                        class="form-control <?= hasError('cpf') ? 'is-invalid' : '' ?> cpf" id="cpf"
                                        name="cpf" value="<?= old('cpf', '') ?>" placeholder="000.000.000-00">
                                    <?= erroInput('cpf') ?>
                                </div>
                            </div>

                            <!-- Data de nascimento -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="data_nascimento">
                                        Data de nascimento
                                    </label>
                                    <input type="date"
                                        class="form-control <?= hasError('data_nascimento') ? 'is-invalid' : '' ?>"
                                        id="data_nascimento" name="data_nascimento"
                                        value="<?= old('data_nascimento', '') ?>">
                                    <?= erroInput('data_nascimento') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Autenticação -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <h5>
                                Autenticação
                            </h5>
                        </div>

                        <div class="row">
                            <!-- E-mail -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">
                                        E-mail
                                    </label>
                                    <input type="email"
                                        class="form-control <?= hasError('email') ? 'is-invalid' : '' ?>" id="email"
                                        name="email" value="<?= old('email', '') ?>" placeholder="cliente@exemplo.com">
                                    <?= erroInput('email') ?>
                                </div>
                            </div>

                            <!-- Senha -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="password">
                                        Senha
                                    </label>
                                    <div class="input-group">
                                        <input type="password"
                                            class="form-control <?= hasError('password') ? 'is-invalid' : '' ?>"
                                            id="password" name="password" value="" placeholder="Digite a senha"
                                            autocomplete="new-password">
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
                                    <label for="password_confirmation">
                                        Confirmar senha
                                    </label>
                                    <div class="input-group">
                                        <input type="password"
                                            class="form-control <?= hasError('password_confirmation') ? 'is-invalid' : '' ?>"
                                            id="password_confirmation" name="password_confirmation" value=""
                                            placeholder="Repita a senha" autocomplete="new-password">
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

                    <!-- Endereço principal -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <h5>
                                Endereço principal
                            </h5>
                        </div>

                        <!-- O primeiro endereço do cliente é sempre o principal -->
                        <input type="hidden" name="principal" value="1">

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
                                        value="<?= old('endereco_nome', 'Principal') ?>"
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
                                        name="cep" value="<?= old('cep', '') ?>" placeholder="00000-000">
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
                                        id="logradouro" name="logradouro" value="<?= old('logradouro', '') ?>"
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
                                        name="numero" value="<?= old('numero', '') ?>" placeholder="Nº">
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
                                        id="complemento" name="complemento" value="<?= old('complemento', '') ?>"
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
                                        name="bairro" value="<?= old('bairro', '') ?>" placeholder="Bairro">
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
                                        name="cidade" value="<?= old('cidade', '') ?>" placeholder="Cidade">
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
                                        id="estado" name="estado" maxlength="2" value="<?= old('estado', '') ?>"
                                        placeholder="UF">
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
                                        id="referencia" name="referencia" value="<?= old('referencia', '') ?>"
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
                        </div>
                    </div>

                    <div class="card-footer bg-light d-flex justify-content-end">
                        <!-- Botões -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="d-flex flex-wrap align-items-center">
                                    <button type="submit" class="btn btn-success mr-2" title="Salvar"
                                        aria-label="Salvar cliente">
                                        <i class="mdi mdi-content-save"></i>
                                        Cadastrar
                                    </button>
                                    <a href="<?= route('admin.cliente.index') ?>" class="btn btn-danger"
                                        title="Cancelar" aria-label="Cancelar cadastro">
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

<?php View::end(); ?>