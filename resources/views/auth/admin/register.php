<h4 class="font-weight-bold text-dark">Criar acesso administrativo</h4>
<h6 class="font-weight-normal text-muted mb-4">Cadastre o responsável pelo estabelecimento.</h6>

<form method="POST" action="/admin/cadastro" class="pt-2">
    <div class="form-group mb-3">
        <label class="font-weight-medium text-dark">Nome completo</label>
        <input type="text" name="nome" class="form-control form-control-lg" value="<?= e(old('nome')) ?>" required
            placeholder="Ex: João Silva">
    </div>

    <div class="form-group mb-3">
        <label class="font-weight-medium text-dark">E-mail</label>
        <input type="email" name="email" class="form-control form-control-lg" value="<?= e(old('email')) ?>"
            autocomplete="username" required placeholder="Ex: admin@seuapp.com">
    </div>

    <div class="form-group mb-3">
        <label class="font-weight-medium text-dark">Senha</label>
        <input type="password" name="senha" class="form-control form-control-lg" autocomplete="new-password" required
            placeholder="Crie uma senha forte">
    </div>

    <div class="form-group mb-4">
        <label class="font-weight-medium text-dark">Confirme a senha</label>
        <input type="password" name="senha_confirmacao" class="form-control form-control-lg" autocomplete="new-password"
            required placeholder="Repita a senha">
    </div>

    <div class="my-3">
        <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn w-100" type="submit">
            CRIAR ACESSO
        </button>
    </div>

    <div class="text-center mt-4 font-weight-light text-muted">
        Já possui acesso? <a href="/admin/login" class="text-primary font-weight-semibold">Entrar</a>
    </div>
</form>