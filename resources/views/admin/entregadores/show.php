<!-- Aqui enviamos para o template principal o título da página -->

<?php View::start('titulo'); ?>

<?= e($titulo ?? 'Detalhes do Entregador') ?> | Admin

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os estilos -->

<?php View::start('styles'); ?>

<style>
    .show-section {
        margin-top: 25px;
        margin-bottom: 20px;
    }

    .show-section-title {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .show-section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e9ecef;
        margin-left: 15px;
    }

    .show-section-title h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        white-space: nowrap;
    }

    .show-item {
        margin-bottom: 18px;
    }

    .show-item-label {
        display: block;
        margin-bottom: 5px;
        font-size: 13px;
        font-weight: 600;
        color: #6c757d;
    }

    .show-item-value {
        min-height: 38px;
        padding: 9px 12px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 4px;
        color: #212529;
        word-break: break-word;
        white-space: pre-line;
    }

    .show-status {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .show-status.active {
        background: #d4edda;
        color: #155724;
    }

    .show-status.inactive {
        background: #f8d7da;
        color: #721c24;
    }

    .show-status.pending {
        background: #fff3cd;
        color: #856404;
    }

    .show-status.blocked {
        background: #d6d8d9;
        color: #1b1e21;
    }

    .show-status.freelancer {
        background: #ffe8d1;
        color: #a04d00;
    }

    .show-status.fixo {
        background: #e8f0fe;
        color: #1a4fa0;
    }

    .show-imagem {
        max-width: 220px;
        max-height: 220px;
        border: 1px solid #e9ecef;
        border-radius: 4px;
        padding: 4px;
        background: #fff;
        object-fit: cover;
    }
</style>

<?php View::end(); ?>


<!-- Aqui enviamos para o template principal o conteúdo -->

<?php View::start('content'); ?>

<?php
// Avatar: URL externa é usada direto; arquivo local fica em uploads/entregadores/{id}/
$avatarAtual = $entregador->avatar ?? '';
$avatarSrc = '';

if ($avatarAtual !== '' && $avatarAtual !== null) {
    $avatarSrc = preg_match('#^https?://#i', $avatarAtual)
        ? $avatarAtual
        : uploadUrl('entregadores', $avatarAtual, $entregador->id);
}

// Valor ou "-" quando vazio
$v = static fn($valor) => ($valor === null || $valor === '') ? '-' : $valor;

// Rótulos
$vinculos = [
    'fixo'       => ['fixo', 'Fixo'],
    'freelancer' => ['freelancer', 'Temporário'],
];

$aprovacoes = [
    'pendente'  => ['pending',  'Pendente'],
    'aprovado'  => ['active',   'Aprovado'],
    'reprovado' => ['inactive', 'Reprovado'],
    'bloqueado' => ['blocked',  'Bloqueado'],
];

$remuneracoes = [
    'por_entrega' => 'Por Entrega',
    'por_km'      => 'Por KM',
    'fixo'        => 'Fixo',
];

$veiculos = [
    'moto'      => 'Moto',
    'carro'     => 'Carro',
    'bicicleta' => 'Bicicleta',
];

[$classeVinculo, $rotuloVinculo]     = $vinculos[$entregador->tipo_vinculo ?? ''] ?? [null, null];
[$classeAprovacao, $rotuloAprovacao] = $aprovacoes[$entregador->status_aprovacao ?? ''] ?? [null, null];

// Turnos: [rótulo, valor]
$turnos = [
    'Manhã' => (int) ($entregador->turno_manha ?? 0),
    'Tarde' => (int) ($entregador->turno_tarde ?? 0),
    'Noite' => (int) ($entregador->turno_noite ?? 0),
];
?>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">

            <!-- Cabeçalho -->
            <div class="card-header bg-primary text-white">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="card-title mb-1 text-white">
                            <?= e($titulo ?? 'Detalhes do Entregador') ?>
                        </h4>
                        <p class="card-description mb-0 text-white">
                            <?= e($entregador->nome ?? '') ?>
                        </p>
                    </div>
                    <div class="col-md-4 text-md-right mt-3 mt-md-0">
                        <a href="<?= route('admin.entregador.index') ?>" class="btn btn-secondary btn-sm text-white">
                            <i class="fas fa-arrow-left"></i>
                            Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">

                <!-- Dados pessoais -->
                <div class="show-section">
                    <div class="show-section-title">
                        <h5>Dados pessoais</h5>
                    </div>

                    <div class="row">
                        <!-- Tipo Vínculo -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">Tipo Vínculo</span>
                                <div class="show-item-value">
                                    <?php if ($rotuloVinculo !== null): ?>
                                        <span class="show-status <?= e($classeVinculo) ?>"><?= e($rotuloVinculo) ?></span>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Nome -->
                        <div class="col-md-6">
                            <div class="show-item">
                                <span class="show-item-label">Nome</span>
                                <div class="show-item-value"><?= e($v($entregador->nome ?? '')) ?></div>
                            </div>
                        </div>

                        <!-- CPF -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">CPF</span>
                                <div class="show-item-value"><?= e($v($entregador->cpf ?? '')) ?></div>
                            </div>
                        </div>

                        <!-- RG -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">RG</span>
                                <div class="show-item-value"><?= e($v($entregador->documento_identidade ?? '')) ?></div>
                            </div>
                        </div>

                        <!-- Data de Nascimento -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">Data de Nascimento</span>
                                <div class="show-item-value">
                                    <?= !empty($entregador->data_nascimento) ? e(dateBR($entregador->data_nascimento)) : '-' ?>
                                </div>
                            </div>
                        </div>

                        <!-- Celular -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">Celular</span>
                                <div class="show-item-value"><?= e($v($entregador->celular ?? '')) ?></div>
                            </div>
                        </div>

                        <!-- E-mail -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">E-mail</span>
                                <div class="show-item-value"><?= e($v($entregador->email ?? '')) ?></div>
                            </div>
                        </div>

                        <!-- Imagem (avatar) -->
                        <div class="col-md-12">
                            <div class="show-item">
                                <span class="show-item-label">Imagem (avatar)</span>
                                <?php if ($avatarSrc !== ''): ?>
                                    <img src="<?= e($avatarSrc) ?>" alt="Foto de <?= e($entregador->nome ?? '') ?>"
                                        class="show-imagem">
                                <?php else: ?>
                                    <div class="show-item-value">Sem imagem</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Endereço -->
                <div class="show-section">
                    <div class="show-section-title">
                        <h5>Endereço</h5>
                    </div>

                    <div class="row">
                        <!-- CEP -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">CEP</span>
                                <div class="show-item-value"><?= e($v($entregador->cep ?? '')) ?></div>
                            </div>
                        </div>

                        <!-- Logradouro -->
                        <div class="col-md-7">
                            <div class="show-item">
                                <span class="show-item-label">Logradouro</span>
                                <div class="show-item-value"><?= e($v($entregador->logradouro ?? '')) ?></div>
                            </div>
                        </div>

                        <!-- Número -->
                        <div class="col-md-2">
                            <div class="show-item">
                                <span class="show-item-label">Número</span>
                                <div class="show-item-value"><?= e($v($entregador->numero ?? '')) ?></div>
                            </div>
                        </div>

                        <!-- Bairro -->
                        <div class="col-md-5">
                            <div class="show-item">
                                <span class="show-item-label">Bairro</span>
                                <div class="show-item-value"><?= e($v($entregador->bairro ?? '')) ?></div>
                            </div>
                        </div>

                        <!-- Cidade -->
                        <div class="col-md-5">
                            <div class="show-item">
                                <span class="show-item-label">Cidade</span>
                                <div class="show-item-value"><?= e($v($entregador->cidade ?? '')) ?></div>
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="col-md-2">
                            <div class="show-item">
                                <span class="show-item-label">Estado</span>
                                <div class="show-item-value"><?= e($v($entregador->estado ?? '')) ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CNH - Veículo -->
                <div class="show-section">
                    <div class="show-section-title">
                        <h5>CNH - Veículo</h5>
                    </div>

                    <div class="row">
                        <!-- Número CNH -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">Número da CNH</span>
                                <div class="show-item-value"><?= e($v($entregador->numero_cnh ?? '')) ?></div>
                            </div>
                        </div>

                        <!-- Categoria da CNH -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">Categoria da CNH</span>
                                <div class="show-item-value"><?= e($v($entregador->categoria_cnh ?? '')) ?></div>
                            </div>
                        </div>

                        <!-- Validade da CNH -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">Validade da CNH</span>
                                <div class="show-item-value">
                                    <?= !empty($entregador->cnh_validade) ? e(dateBR($entregador->cnh_validade)) : '-' ?>
                                </div>
                            </div>
                        </div>

                        <!-- Tipo de veículo -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">Tipo de veículo</span>
                                <div class="show-item-value">
                                    <?= e($veiculos[$entregador->tipo_veiculo ?? ''] ?? '-') ?>
                                </div>
                            </div>
                        </div>

                        <!-- Marca do veículo -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">Marca do veículo</span>
                                <div class="show-item-value"><?= e($v($entregador->marca_veiculo ?? '')) ?></div>
                            </div>
                        </div>

                        <!-- Modelo do veículo -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">Modelo do veículo</span>
                                <div class="show-item-value"><?= e($v($entregador->modelo_veiculo ?? '')) ?></div>
                            </div>
                        </div>

                        <!-- Placa do veículo -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">Placa do veículo</span>
                                <div class="show-item-value"><?= e($v($entregador->placa_veiculo ?? '')) ?></div>
                            </div>
                        </div>

                        <!-- Cor do veículo -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">Cor do veículo</span>
                                <div class="show-item-value"><?= e($v($entregador->cor_veiculo ?? '')) ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Remuneração -->
                <div class="show-section">
                    <div class="show-section-title">
                        <h5>Remuneração</h5>
                    </div>

                    <div class="row">
                        <!-- Tipo de Remuneração -->
                        <div class="col-md-4">
                            <div class="show-item">
                                <span class="show-item-label">Tipo de Remuneração</span>
                                <div class="show-item-value">
                                    <?= e($remuneracoes[$entregador->tipo_remuneracao ?? ''] ?? '-') ?>
                                </div>
                            </div>
                        </div>

                        <!-- Valor da Remuneração -->
                        <div class="col-md-4">
                            <div class="show-item">
                                <span class="show-item-label">Valor da Remuneração</span>
                                <div class="show-item-value">
                                    <?= ($entregador->valor_remuneracao ?? null) !== null && $entregador->valor_remuneracao !== ''
                                        ? 'R$ ' . e(moedaBR($entregador->valor_remuneracao))
                                        : '-' ?>
                                </div>
                            </div>
                        </div>

                        <!-- Chave PIX -->
                        <div class="col-md-4">
                            <div class="show-item">
                                <span class="show-item-label">Chave PIX</span>
                                <div class="show-item-value"><?= e($v($entregador->chave_pix ?? '')) ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Controle de acesso -->
                <div class="show-section">
                    <div class="show-section-title">
                        <h5>Controle de acesso</h5>
                    </div>

                    <div class="row">
                        <!-- Status -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">Status</span>
                                <div class="show-item-value">
                                    <?php if ((int) ($entregador->ativo ?? 0) === 1): ?>
                                        <span class="show-status active">Entregador Ativo</span>
                                    <?php else: ?>
                                        <span class="show-status inactive">Entregador Inativo</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Disponibilidade -->
                        <div class="col-md-3">
                            <div class="show-item">
                                <span class="show-item-label">Ativo para entregas</span>
                                <div class="show-item-value">
                                    <?php if ((int) ($entregador->disponivel ?? 0) === 1): ?>
                                        <span class="show-status active">Disponível</span>
                                    <?php else: ?>
                                        <span class="show-status inactive">Indisponível</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Turnos -->
                        <?php foreach ($turnos as $rotuloTurno => $valorTurno): ?>
                            <div class="col-md-2">
                                <div class="show-item">
                                    <span class="show-item-label">Turno <?= e($rotuloTurno) ?></span>
                                    <div class="show-item-value">
                                        <?php if ($valorTurno === 1): ?>
                                            <span class="show-status active">Disponível</span>
                                        <?php else: ?>
                                            <span class="show-status inactive">Indisponível</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Controle de Aprovação -->
                <div class="show-section">
                    <div class="show-section-title">
                        <h5>Controle de Aprovação</h5>
                    </div>

                    <div class="row">
                        <!-- Status de aprovação -->
                        <div class="col-md-4">
                            <div class="show-item">
                                <span class="show-item-label">Controle de Aprovação</span>
                                <div class="show-item-value">
                                    <?php if ($rotuloAprovacao !== null): ?>
                                        <span
                                            class="show-status <?= e($classeAprovacao) ?>"><?= e($rotuloAprovacao) ?></span>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Motivo da Reprovação -->
                        <div class="col-md-4">
                            <div class="show-item">
                                <span class="show-item-label">Motivo da Reprovação</span>
                                <div class="show-item-value"><?= e($v($entregador->motivo_reprovacao ?? '')) ?></div>
                            </div>
                        </div>

                        <!-- Observações -->
                        <div class="col-md-4">
                            <div class="show-item">
                                <span class="show-item-label">Observação</span>
                                <div class="show-item-value"><?= e($v($entregador->observacoes ?? '')) ?></div>
                            </div>
                        </div>

                        <!-- Criado em -->
                        <div class="col-md-4">
                            <div class="show-item">
                                <span class="show-item-label">Criado em</span>
                                <div class="show-item-value">
                                    <?= e(tempoRelativo($entregador->created_at ?? '')) ?>
                                </div>
                            </div>
                        </div>

                        <!-- Atualizado em -->
                        <div class="col-md-4">
                            <div class="show-item">
                                <span class="show-item-label">Atualizado em</span>
                                <div class="show-item-value">
                                    <?= e(tempoRelativo($entregador->updated_at ?? '')) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light d-flex justify-content-end">
                    <!-- Botões -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-flex flex-wrap align-items-center">
                                <a href="<?= route('admin.entregador.edit', ['id' => $entregador->id]) ?>"
                                    class="btn btn-success mr-2" title="Editar" aria-label="Editar entregador">
                                    <i class="mdi mdi-pencil"></i>
                                    Editar
                                </a>
                                <a href="<?= route('admin.entregador.index') ?>" class="btn btn-danger" title="Voltar"
                                    aria-label="Voltar para entregadores">
                                    <i class="mdi mdi-close"></i>
                                    Voltar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php View::end(); ?>


<!-- Aqui enviamos para o template principal os scripts -->

<?php View::start('scripts'); ?>

<?php View::end(); ?>