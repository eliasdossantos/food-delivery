<?php

namespace App\Repositories;

use Framework\Database\Repository;
use App\Models\EntregadorModel;
use App\Repositories\Concerns\BuscaPorNomeRepository;

/**
 * EntregadorRepository
 * ─────────────────────────────────────────────────────────────────────────────
 * Encapsula todas as queries relacionadas à entidade Entregador.
 *
 * Herda do Repository base:
 *   all(), findById(), create(), update(), delete(), paginate()
 *
 * Adicione aqui apenas queries específicas desta entidade.
 * Lógica de negócio → Service (nunca aqui).
 *
 * Uso no Controller ou Service:
 *   $repo = new EntregadorRepository();
 *   $item = $repo->findById(42);
 *   $list = $repo->paginate(15, (int)($_GET['page'] ?? 1));
 */
class EntregadorRepository extends Repository
{
    use BuscaPorNomeRepository;

    /** Model associado a este repository */
    protected string $modelClass = EntregadorModel::class;

    /** Colunas de turno permitidas (evita montar nome de coluna com entrada do usuário) */
    private const TURNOS = [
        'manha' => 'turno_manha',
        'tarde' => 'turno_tarde',
        'noite' => 'turno_noite',
    ];

    // ── Buscas customizadas ───────────────────────────────────────────────────

    /**
     * Busca por nome exato.
     */
    public function findByNome(string $nome): object|false
    {
        return $this->model()->findBy('nome', $nome);
    }

    public function findByEmail(string $email): object|false
    {
        return $this->model()->findBy('email', $email);
    }

    public function findByCelular(string $celular): object|false
    {
        return $this->model()->findBy('celular', $celular);
    }

    public function findByCpf(string $cpf): object|false
    {
        return $this->model()->findBy('cpf', $cpf);
    }

    /** Entregadores ativos, ordenados por nome (selects e filtros) */
    public function getActive(): array
    {
        return $this->model()
            ->where('ativo', 1)
            ->orderBy('nome', 'ASC')
            ->get();
    }

    /** Fila de aprovação do admin */
    public function getPendentes(): array
    {
        return $this->model()
            ->where('status_aprovacao', 'pendente')
            ->orderBy('id', 'ASC')
            ->get();
    }

    /**
     * Quem pode receber pedido agora: ativo, aprovado, online e no turno informado.
     * $turno: 'manha' | 'tarde' | 'noite'
     */
    public function getDisponiveisNoTurno(string $turno): array
    {
        $coluna = self::TURNOS[$turno] ?? null;

        if ($coluna === null) {
            throw new \InvalidArgumentException("Turno inválido: {$turno}");
        }

        return $this->model()
            ->where('ativo', 1)
            ->where('status_aprovacao', 'aprovado')
            ->where('disponivel', 1)
            ->where($coluna, 1)
            ->orderBy('nome', 'ASC')
            ->get();
    }

    /**
     * Entregadores com CNH vencida (para o admin bloquear ou cobrar renovação).
     * Considera só quem tem validade informada e segue ativo.
     */
    public function getCnhVencida(): array
    {
        return $this->model()
            ->where('ativo', 1)
            ->whereNotNull('cnh_validade')
            ->where('cnh_validade', date('Y-m-d'), '<')
            ->orderBy('cnh_validade', 'ASC')
            ->get();
    }

    /** Entregadores cuja CNH vence nos próximos $dias dias (aviso antecipado) */
    public function getCnhVencendo(int $dias = 30): array
    {
        return $this->model()
            ->where('ativo', 1)
            ->whereBetween(
                'cnh_validade',
                date('Y-m-d'),
                date('Y-m-d', strtotime("+{$dias} days"))
            )
            ->orderBy('cnh_validade', 'ASC')
            ->get();
    }

    /**
     * Listagem do admin: busca textual + filtros opcionais, paginada.
     *
     * $term busca em: nome, e-mail, celular, CPF e placa (qualquer um dos cinco).
     * $filtros aceitos: status_aprovacao, tipo_vinculo, ativo
     *
     * @return array{data: array, total: int, page: int, per_page: int, last_page: int, from: int, to: int}
     */
    public function search(string $term = '', array $filtros = [], int $page = 1, int $perPage = 15): array
    {
        $query = $this->model();

        // Só agrupa se houver termo: LIKE '%%' não casa com colunas NULL
        // (celular, cpf, email e placa são nullable) e esconderia registros.
        if (trim($term) !== '') {
            $query = $query->whereGroup(function ($q) use ($term) {
                $q->whereLike('nome', $term)
                    ->whereLike('email', $term, 'or')
                    ->whereLike('celular', $term, 'or')
                    ->whereLike('cpf', $term, 'or')
                    ->whereLike('placa_veiculo', $term, 'or');
            });
        }

        foreach (['status_aprovacao', 'tipo_vinculo', 'ativo'] as $campo) {
            if (isset($filtros[$campo]) && $filtros[$campo] !== '') {
                $query = $query->where($campo, $filtros[$campo]);
            }
        }

        return $query
            ->orderBy('id', 'DESC')
            ->paginate($perPage, $page);
    }

    // ── Atualizações pontuais (só persistência) ──────────────────────────────

    public function setDisponivel(int $id, bool $disponivel): bool
    {
        return $this->update($id, ['disponivel' => $disponivel ? 1 : 0]);
    }

    public function registrarLogin(int $id): bool
    {
        return $this->update($id, ['ultimo_login_em' => date('Y-m-d H:i:s')]);
    }
}
