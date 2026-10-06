<h4 class="font-weight-bold text-dark">Acesso administrativo</h4>
<h6 class="font-weight-normal text-muted mb-4">Entre para gerenciar o estabelecimento.</h6>

<form method="POST" action="/admin/login" class="pt-2">
    <div class="form-group mb-3">
        <label class="font-weight-medium text-dark">E-mail</label>
        <input type="email" name="email" class="form-control form-control-lg" value="<?= e(old('email')) ?>"
            autocomplete="username" required placeholder="Ex: admin@seuapp.com">
    </div>

    <div class="form-group mb-3">
        <label class="font-weight-medium text-dark">Senha</label>
        <input type="password" name="senha" class="form-control form-control-lg" autocomplete="current-password"
            required placeholder="Digite sua senha">
    </div>

    <div class="my-4">
        <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn w-100" type="submit">ENTRAR NA
            ADMINISTRAÇÃO</button>
    </div>

    <div class="text-center mt-3 font-weight-light">
        <a href="/admin/esqueci-senha" class="text-primary font-weight-semibold">Esqueci minha senha</a>
    </div>

    <div class="text-center mt-4">
        <a href="/cliente/login" class="text-secondary small"><i class="mdi mdi-arrow-left"></i> Sou cliente</a>
    </div>
</form>