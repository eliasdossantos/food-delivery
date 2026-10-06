<!-- Aqui enviamos para o template principal o título da página -->

<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Editar Usuários') ?> | Admin

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
            <div class="card-header bg-primary">
                <div class="row align-items-center mb-4">
                    <div class="col-md-8">
                        <h4 class="card-title mb-1 text-white">
                            <?= e($titulo ?? 'Editar Usuário') ?>
                        </h4>
                        <p class="card-description mb-0 text-white">
                            <?= e($usuarios->nome ?? '') ?>
                        </p>
                    </div>
                    <div class="col-md-4 text-md-right mt-3 mt-md-0">
                        <a href="<?= route('admin.usuario.index') ?>" class="btn btn-secondary btn-sm text-white">
                            <i class="fas fa-arrow-left"></i>
                            Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Formulário -->
                <form method="POST" action="<?= route('admin.usuario.update', ['id' => $usuarios->id]) ?>"
                    enctype="multipart/form-data" class="forms-sample">
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nome">
                                        Nome
                                    </label>
                                    <input type="text" class="form-control <?= hasError('nome') ? 'is-invalid' : '' ?>"
                                        id="nome" name="nome" value="<?= old('nome', $usuarios->nome ?? '') ?>"
                                        placeholder="Nome completo">
                                    <?= erroInput('nome') ?>
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
                                        name="cpf" value="<?= old('cpf', $usuarios->cpf ?? '') ?>"
                                        placeholder="000.000.000-00">
                                    <?= erroInput('cpf') ?>
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
                                        id="celular" name="celular"
                                        value="<?= old('celular', $usuarios->celular ?? '') ?>"
                                        placeholder="(00) 00000-0000">
                                    <?= erroInput('celular') ?>
                                </div>
                            </div>

                            <?php if (!empty($podeEditarPerfil)): ?>
                                <!-- Perfil -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="perfil_id">
                                            Perfil
                                        </label>
                                        <select class="form-control <?= hasError('perfil_id') ? 'is-invalid' : '' ?>"
                                            id="perfil_id" name="perfil_id">
                                            <option value="">
                                                Sem perfil
                                            </option>
                                            <?php if (!empty($perfis)): ?>
                                                <?php foreach ($perfis as $perfil): ?>
                                                    <option value="<?= e($perfil->id) ?>"
                                                        <?= (string) old('perfil_id', $usuarios->perfil_id ?? '') === (string) $perfil->id ? 'selected' : '' ?>>
                                                        <?= e($perfil->nome) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <?= erroInput('perfil_id') ?>
                                    </div>
                                </div>
                            <?php endif; ?>
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
                                        name="email" value="<?= old('email', $usuarios->email ?? '') ?>"
                                        placeholder="usuario@exemplo.com">
                                    <?= erroInput('email') ?>
                                </div>
                            </div>

                            <!-- Nova senha -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="password">
                                        Nova senha
                                    </label>
                                    <div class="input-group">
                                        <input type="password"
                                            class="form-control <?= hasError('password') ? 'is-invalid' : '' ?>"
                                            id="password" name="password" placeholder="Deixe vazio para manter a atual"
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

                            <!-- Confirmar nova senha -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="password_confirmation">
                                        Confirmar nova senha
                                    </label>
                                    <div class="input-group">
                                        <input type="password"
                                            class="form-control <?= hasError('password_confirmation') ? 'is-invalid' : '' ?>"
                                            id="password_confirmation" name="password_confirmation"
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
                            <h5>
                                Endereço
                            </h5>
                        </div>

                        <div class="row">
                            <!-- CEP -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cep">
                                        CEP
                                    </label>
                                    <input type="text"
                                        class="form-control <?= hasError('cep') ? 'is-invalid' : '' ?> cep" id="cep"
                                        name="cep" value="<?= old('cep', $usuarios->cep ?? '') ?>"
                                        placeholder="00000-000">
                                    <?= erroInput('cep') ?>
                                </div>
                            </div>

                            <!-- Logradouro -->
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="logradouro">
                                        Logradouro
                                    </label>
                                    <input type="text"
                                        class="form-control <?= hasError('logradouro') ? 'is-invalid' : '' ?>"
                                        id="logradouro" name="logradouro"
                                        value="<?= old('logradouro', $usuarios->logradouro ?? '') ?>"
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
                                        name="numero" value="<?= old('numero', $usuarios->numero ?? '') ?>"
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
                                        value="<?= old('complemento', $usuarios->complemento ?? '') ?>"
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
                                        name="bairro" value="<?= old('bairro', $usuarios->bairro ?? '') ?>"
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
                                        name="cidade" value="<?= old('cidade', $usuarios->cidade ?? '') ?>"
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
                                        value="<?= old('estado', $usuarios->estado ?? '') ?>" placeholder="UF">
                                    <?= erroInput('estado') ?>
                                </div>
                            </div>

                            <!-- Referência -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="referencia">
                                        Referência
                                    </label>
                                    <input type="text"
                                        class="form-control <?= hasError('referencia') ? 'is-invalid' : '' ?>"
                                        id="referencia" name="referencia"
                                        value="<?= old('referencia', $usuarios->referencia ?? '') ?>"
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
                                            <?= (string) old('ativo', $usuarios->ativo ?? 1) === '1' ? 'selected' : '' ?>>
                                            Ativo
                                        </option>
                                        <option value="0"
                                            <?= (string) old('ativo', $usuarios->ativo ?? 1) === '0' ? 'selected' : '' ?>>
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
                                        aria-label="Atualizar usuário">
                                        <i class="mdi mdi-content-save"></i>
                                        Atualizar
                                    </button>
                                    <a href="<?= route('admin.usuario.show', ['id' => $usuarios->id]) ?>"
                                        class="btn btn-primary mr-2" title="Visualizar" aria-label="Visualizar usuário">
                                        <i class="mdi mdi-eye"></i>
                                        Visualizar
                                    </a>
                                    <a href="<?= route('admin.usuario.index') ?>" class="btn btn-danger"
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

<?php View::end(); ?>