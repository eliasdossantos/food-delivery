<h4 class="font-weight-bold text-dark">Redefinir acesso administrativo</h4>
<h6 class="font-weight-normal text-muted mb-4">Escolha uma nova senha para sua conta.</h6>

<form method="POST" action="/admin/redefinir-senha" class="pt-2">
    <input type="hidden" name="token" value="<?= e($token ?? '') ?>">

    <div class="form-group mb-3">
        <label class="font-weight-medium text-dark">Nova senha</label>
        <input type="password" name="senha" class="form-control form-control-lg" autocomplete="new-password" required
            placeholder="Digite sua nova senha">
    </div>

    <div class="form-group mb-4">
        <label class="font-weight-medium text-dark">Confirme a senha</label>
        <input type="password" name="senha_confirmacao" class="form-control form-control-lg" autocomplete="new-password"
            required placeholder="Repita a nova senha">
    </div>

    <div class="my-3">
        <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn w-100" type="submit">
            SALVAR NOVA SENHA
        </button>
    </div>

    <div class="text-center mt-4 font-weight-light">
        <a href="/admin/login" class="text-primary font-weight-semibold">Voltar ao login</a>
    </div>
</form>