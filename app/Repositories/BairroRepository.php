<?php

namespace App\Repositories;

use Framework\Database\Repository;
use App\Models\BairroModel;
use App\Repositories\Concerns\BuscaPorNomeRepository;

/**
 * BairroRepository
 * ─────────────────────────────────────────────────────────────────────────────
 * Encapsula todas as queries relacionadas à entidade BairroModel.
 *
 * Herda do Repository base:
 *   all(), findById(), create(), update(), delete(), paginate()
 *
 * Adicione aqui apenas queries específicas desta entidade.
 * Lógica de negócio → Service (nunca aqui).
 *
 * Uso no Controller ou Service:
 *   $repo = new BairroRepository();
 *   $item = $repo->findById(42);
 *   $list = $repo->paginate(15, (int)($_GET['page'] ?? 1));
 */
class BairroRepository extends Repository
{
    use BuscaPorNomeRepository;

    /** Model associado a este repository */
    protected string $modelClass = BairroModel::class;

    // ── Buscas customizadas ───────────────────────────────────────────────────

    /**
     * Busca por nome exato.
     */
    public function findByNome(string $nome): object|false
    {
        return $this->model()->findBy('nome', $nome);
    }

    /**
     * Busca por slug exato.
     */
    public function findBySlug(string $slug): object|false
    {
        return $this->model()->findBy('slug', $slug);
    }

    /**
     * Busca por cidade exato.
     */
    public function findByCidade(string $cidade): object|false
    {
        return $this->model()->findBy('cidade', $cidade);
    }

    /**
     * Busca um bairro pela combinação cidade + slug
     * (mesma regra de unicidade da migration: uniq_cidade_slug).
     */
    public function findByCidadeESlug(string $cidade, string $slug): object|false
    {
        $rows = $this->model()
            ->where('cidade', $cidade)
            ->where('slug', $slug)
            ->get();

        return $rows[0] ?? false;
    }

    /**
     * Retorna registros ativos (se a tabela tiver campo 'ativo').
     */
    public function getAtiva(): array
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

    // ── Slug ──────────────────────────────────────────────────────────────────

    /**
     * Verifica se o slug já está em uso por outro bairro.
     *
     * @param int|null    $ignorarId ID do bairro que está sendo editado (ele não conta como duplicado)
     * @param string|null $cidade    Quando informada, a verificação é feita só dentro da cidade
     *                               (o slug é único por cidade). Sem ela, verifica em todas.
     */
    public function slugExiste(string $slug, ?int $ignorarId = null, ?string $cidade = null): bool
    {
        $encontrada = ($cidade !== null && $cidade !== '')
            ? $this->findByCidadeESlug($cidade, $slug)
            : $this->findBySlug($slug);

        if (!$encontrada) {
            return false;
        }

        return $ignorarId === null || (int) $encontrada->id !== $ignorarId;
    }

    /**
     * Gera um slug único a partir de um texto.
     *
     * Exemplos (já existindo "joao-pessoa" na mesma cidade):
     *   "João Pessoa"      → "joao-pessoa-2"
     *   "Açaí & Sorvetes"  → "acai-sorvetes"
     *
     * @param string      $texto     Slug digitado ou, na falta dele, o nome do bairro
     * @param int|null    $ignorarId ID do bairro em edição (evita conflito consigo mesmo)
     * @param string|null $cidade    Cidade do bairro: o slug só precisa ser único dentro dela
     */
    public function gerarSlugUnico(string $texto, ?int $ignorarId = null, ?string $cidade = null): string
    {
        $base = slug($texto);

        if ($base === '') {
            $base = 'bairro';
        }

        $slug     = $base;
        $contador = 2;

        while ($this->slugExiste($slug, $ignorarId, $cidade)) {
            $slug = $base . '-' . $contador;
            $contador++;
        }

        return $slug;
    }

    // ── Atualizações de Status e Ordem ────────────────────────────────────────

    /**
     * Alterna o status 'ativo' de um bairro (Ativa/Inativa).
     */
    public function toggleAtivo(int $id): bool
    {
        $bairro = $this->findById($id);

        if (!$bairro) {
            return false;
        }

        $novoStatus = $bairro->ativo ? 0 : 1;

        return $this->update($id, ['ativo' => $novoStatus]);
    }
}
