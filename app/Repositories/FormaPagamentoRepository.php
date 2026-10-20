<?php

namespace App\Repositories;

use Framework\Database\Repository;
use App\Models\FormaPagamentoModel;
use App\Repositories\Concerns\BuscaPorNomeRepository;

/**
 * FormaPagamentoRepository
 * ─────────────────────────────────────────────────────────────────────────────
 * Encapsula todas as queries relacionadas à entidade FormaPagamento.
 *
 * Herda do Repository base:
 *   all(), findById(), create(), update(), delete(), paginate()
 *
 * Adicione aqui apenas queries específicas desta entidade.
 * Lógica de negócio → Service (nunca aqui).
 *
 * Uso no Controller ou Service:
 *   $repo = new FormaPagamentoRepository();
 *   $item = $repo->findById(42);
 *   $list = $repo->paginate(15, (int)($_GET['page'] ?? 1));
 */
class FormaPagamentoRepository extends Repository
{
    use BuscaPorNomeRepository;

    /** Model associado a este repository */
    protected string $modelClass = FormaPagamentoModel::class;

    // ── Buscas customizadas ───────────────────────────────────────────────────

    /** Nome da forma de pagamento que não pode ser editada nem excluída */
    public const NOME_PROTEGIDO = 'Dinheiro';

    public const MENSAGEM_PROTEGIDA =
    'A forma de pagamento "Dinheiro" é padrão do sistema e não pode ser editada nem excluída, '
        . 'pois é utilizada em pedidos e no fluxo de pagamento na entrega.';

    /**
     * Indica se a forma de pagamento é protegida (somente leitura).
     */
    public static function ehProtegida(object|array|null $forma): bool
    {
        if (!$forma) {
            return false;
        }

        $nome = is_array($forma) ? ($forma['nome'] ?? '') : ($forma->nome ?? '');

        return mb_strtolower(trim((string) $nome)) === mb_strtolower(self::NOME_PROTEGIDO);
    }

    /**
     * Busca por nome exato.
     */
    public function findByNome(string $nome): object|false
    {
        return $this->model()->findBy('nome', $nome);
    }

    /**
     * Retorna registros ativos (se a tabela tiver campo 'ativo').
     */
    public function getActive(): array
    {
        return $this->model()
            ->where('ativo', 1)
            ->orderBy('nome', 'ASC')
            ->get();
    }

    /**
     * Busca paginada com filtro de texto por nome.
     * Usa o query builder do Model — evite escrever SQL manual aqui.
     *
     * @return array{data: array, total: int, page: int, per_page: int, last_page: int, from: int, to: int}
     */
    public function search(string $term = '', int $page = 1, int $perPage = 15): array
    {
        return $this->model()
            ->whereLike('nome', $term)
            ->orderBy('id', 'DESC')
            ->paginate($perPage, $page);
    }
}
