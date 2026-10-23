<!-- Aqui enviamos para o template principal o título da página -->

<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Novo Bairros') ?> | Admin

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os estilos -->

<?php View::start('styles'); ?>

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal o conteúdo -->

<?php View::start('content'); ?>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <!-- Cabeçalho -->

                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="card-title mb-1 text-white">
                            <?= e($titulo ?? 'Novo Bairro') ?>
                        </h4>
                        <p class="card-description mb-0 text-white">
                            <?= e($subtitulo ?? 'Criar novo Bairro') ?>
                        </p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <a href="<?= url('admin/bairro') ?>" class="btn btn-secondary btn-sm text-white">
                            <i class="fas fa-arrow-left"></i>
                            Voltar
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Formulário -->
                <form method="POST" action="<?= route('admin.bairro.store') ?>" class="forms-sample">
                    <?= csrf_field() ?>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cep">CEP (preenche cidade e bairro)</label>
                                <input type="text" class="form-control" id="cep" maxlength="9" placeholder="00000-000"
                                    autocomplete="off" data-cep-url="<?= e(route('admin.bairro.cep')) ?>">
                                <small id="cep-feedback" class="form-text text-muted"></small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Cidade -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cidade">Cidade</label>
                                <input type="text" class="form-control <?= hasError('cidade') ? 'is-invalid' : '' ?>"
                                    id="cidade" name="cidade" value="<?= old('cidade', '') ?>"
                                    placeholder="Nome da Cidade" autofocus>
                                <?= erroInput('cidade') ?>
                            </div>
                        </div>

                        <!-- Bairro -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nome">Bairro</label>
                                <input type="text" class="form-control <?= hasError('nome') ? 'is-invalid' : '' ?>"
                                    id="nome" name="nome" value="<?= old('nome', '') ?>" placeholder="Nome do Bairro"
                                    autofocus>
                                <?= erroInput('nome') ?>
                            </div>
                        </div>

                        <!-- Slug -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <input type="text" class="form-control <?= hasError('slug') ? 'is-invalid' : '' ?>"
                                    id="slug" name="slug" value="<?= old('slug', '') ?>"
                                    placeholder="Em branco, é gerado pelo nome do bairro">
                                <?= erroInput('slug') ?>
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i>
                                    <strong>Customizável:</strong> Em branco, é gerado pelo nome do bairro
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="valor_entrega">Valor da Entrega</label>
                                <input type="text"
                                    class="form-control <?= hasError('valor_entrega') ? 'is-invalid' : '' ?> dinheiro"
                                    id="valor_entrega" name="valor_entrega" value="<?= old('valor_entrega', '') ?>"
                                    placeholder="Valor da Entrega do Bairro">
                                <?= erroInput('valor_entrega') ?>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ativo">
                                    Status do Registro
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

                    <div class="card-footer bg-light d-flex justify-content-end">
                        <!-- Botões -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="d-flex flex-wrap align-items-center">
                                    <!-- Ações -->
                                    <button type="submit" class="btn btn-success mr-2" title="Cadastrar"
                                        aria-label="Cadastrar bairro">
                                        <i class="mdi mdi-content-save"></i>
                                        Cadastrar
                                    </button>
                                    <a href="<?= route('admin.bairro.index') ?>" class="btn btn-danger" title="Cancelar"
                                        aria-label="Cancelar criação">
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
<script>
(function() {
    const cepInput = document.getElementById('cep');
    const feedback = document.getElementById('cep-feedback');
    const cidade = document.getElementById('cidade');
    const nome = document.getElementById('nome');
    const slug = document.getElementById('slug');
    const valor = document.getElementById('valor_entrega');

    const setMsg = (txt, cls) => {
        feedback.textContent = txt;
        feedback.className = 'form-text ' + cls;
    };

    cepInput.addEventListener('input', function() {
        let v = this.value.replace(/\D/g, '').slice(0, 8);
        this.value = v.length > 5 ? v.slice(0, 5) + '-' + v.slice(5) : v;
        if (v.length === 8) buscar(v);
    });

    async function buscar(cep) {
        setMsg('Buscando CEP...', 'text-muted');
        try {
            const res = await fetch(cepInput.dataset.cepUrl + '?cep=' + cep, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });
            const data = await res.json();

            if (!data.ok) {
                setMsg(data.message || 'Não foi possível consultar o CEP.', 'text-danger');
                return;
            }

            cidade.value = data.cidade;
            nome.value = data.bairro;
            slug.value = data.slug;

            if (data.cadastrado) {
                valor.value = String(data.cadastrado.valor_entrega).replace('.', ',');
                setMsg('Atenção: este bairro já está cadastrado.', 'text-warning');
            } else {
                setMsg('Bairro encontrado: ' + data.bairro + ' - ' + data.uf, 'text-success');
            }
        } catch (e) {
            setMsg('Erro de comunicação ao consultar o CEP.', 'text-danger');
        }
    }
})();
</script>
<?php View::end(); ?>