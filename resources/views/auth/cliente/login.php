<h4 class="font-weight-bold text-dark">Bem-vindo!</h4>
<h6 class="font-weight-normal text-muted mb-4">Entre para acompanhar seus pedidos.</h6>

<form method="POST" action="/cliente/login" class="pt-2">
    <div class="form-group mb-3">
        <label class="font-weight-medium text-dark">E-mail</label>
        <input type="email" name="email" class="form-control form-control-lg" value="<?= e(old('email')) ?>"
            autocomplete="username" required placeholder="Ex: seuemail@email.com">
    </div>

    <div class="form-group mb-3">
        <label class="font-weight-medium text-dark">Senha</label>
        <input type="password" name="senha" class="form-control form-control-lg" autocomplete="current-password"
            required placeholder="Digite sua senha">
    </div>

    <div class="my-4">
        <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn w-100"
            type="submit">ENTRAR</button>
    </div>

    <div class="text-center mt-3 font-weight-light">
        <a href="/cliente/esqueci-senha" class="text-primary font-weight-semibold">Esqueci minha senha</a>
    </div>

    <div class="text-center mt-3 font-weight-light text-muted">
        Ainda não possui conta? <a href="/cliente/cadastro" class="text-primary font-weight-semibold">Criar cadastro</a>
    </div>

    <div class="text-center mt-4">
        <a href="/admin/login" class="text-secondary small"><i class="mdi mdi-shield-account"></i> Acesso
            administrativo</a>
    </div>
</form>