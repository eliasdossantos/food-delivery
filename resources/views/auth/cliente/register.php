<h4 class="font-weight-bold text-dark">Novo cliente</h4>
<h6 class="font-weight-normal text-muted mb-4">Cadastre-se para fazer seus pedidos.</h6>

<form method="POST" action="/cliente/cadastro" class="pt-2">
    <div class="form-group mb-3">
        <label class="font-weight-medium text-dark">Nome completo</label>
        <input type="text" name="nome" class="form-control form-control-lg" value="<?= e(old('nome')) ?>" required
            placeholder="Ex: Maria Silva">
    </div>

    <div class="form-group mb-3">
        <label class="font-weight-medium text-dark">Celular</label>
        <input type="tel" name="celular" class="form-control form-control-lg" value="<?= e(old('celular')) ?>" required
            placeholder="Ex: (83) 99999-9999">
    </div>

    <div class="form-group mb-3">
        <label class="font-weight-medium text-dark">E-mail</label>
        <input type="email" name="email" class="form-control form-control-lg" value="<?= e(old('email')) ?>"
            autocomplete="username" required placeholder="Ex: seuemail@email.com">
    </div>

    <div class="form-group mb-3">
        <label class="font-weight-medium text-dark">Senha</label>
        <input type="password" name="senha" class="form-control form-control-lg" autocomplete="new-password" required
            placeholder="Crie uma senha de acesso">
    </div>

    <div class="form-group mb-4">
        <label class="font-weight-medium text-dark">Confirme a senha</label>
        <input type="password" name="senha_confirmacao" class="form-control form-control-lg" autocomplete="new-password"
            required placeholder="Repita a senha">
    </div>

    <div class="my-3">
        <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn w-100" type="submit">
            CRIAR CONTA
        </button>
    </div>

    <div class="text-center mt-4 font-weight-light text-muted">
        Já possui conta? <a href="/cliente/login" class="text-primary font-weight-semibold">Entrar</a>
    </div>
</form>