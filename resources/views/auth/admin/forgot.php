<h4 class="font-weight-bold text-dark">Recuperar acesso administrativo</h4>
<h6 class="font-weight-normal text-muted mb-4">Informe o e-mail cadastrado para receber as instruções.</h6>

<form method="POST" action="/admin/esqueci-senha" class="pt-2">
    <div class="form-group mb-4">
        <label class="font-weight-medium text-dark">E-mail</label>
        <input type="email" name="email" class="form-control form-control-lg" value="<?= e(old('email')) ?>"
            autocomplete="email" required placeholder="Ex: admin@seuapp.com">
    </div>

    <div class="my-3">
        <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn w-100" type="submit">
            ENVIAR INSTRUÇÕES
        </button>
    </div>

    <div class="text-center mt-4 font-weight-light">
        <a href="/admin/login" class="text-primary font-weight-semibold">Voltar ao login administrativo</a>
    </div>
</form>