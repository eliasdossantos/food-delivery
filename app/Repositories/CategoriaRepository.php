<?php

namespace App\Repositories;

use Framework\Database\Repository;
use App\Models\CategoriaModel;

/**
 * CategoriaRepository
 * ─────────────────────────────────────────────────────────────────────────────
 * Encapsula todas as queries relacionadas à entidade Categoria.
 *
 * Herda do Repository base:
 *   all(), findById(), create(), update(), delete(), paginate()
 *
 * Adicione aqui apenas queries específicas desta entidade.
 * Lógica de negócio → Service (nunca aqui).
 *
 * Uso no Controller ou Service:
 *   $repo = new CategoriaRepository();
 *   $item = $repo->findById(42);
 *   $list = $repo->paginate(15, (int)($_GET['page'] ?? 1));
 */
class CategoriaRepository extends Repository
{
    /** Model associado a este repository */
    protected string $modelClass = CategoriaModel::class;

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
     * Retorna apenas categorias ativas, ordenadas pela ordem de exibição.
     */
    public function getAtivas(): array
    {
        return $this->model()
            ->where('ativo', 1)
            ->orderBy('ordem_exibicao', 'ASC')
            ->orderBy('nome', 'ASC')
            ->get();
    }

    /**
     * Retorna apenas as categorias em destaque.
     * Permite filtrar opcionalmente apenas as que também estão ativas.
     */
    public function getDestaques(bool $apenasAtivas = true): array
    {
        $query = $this->model()->where('destaque', 1);

        if ($apenasAtivas) {
            $query->where('ativo', 1);
        }

        return $query->orderBy('ordem_exibicao', 'ASC')
            ->orderBy('nome', 'ASC')
            ->get();
    }

    /**
     * Retorna todas as categorias ordenadas pelo campo 'ordem_exibicao'.
     */
    public function getOrdenadas(string $direction = 'ASC'): array
    {
        return $this->model()
            ->orderBy('ordem_exibicao', $direction)
            ->get();
    }

    /**
     * Busca paginada com filtro de texto por nome.
     *
     * @return array{data: array, total: int, page: int, per_page: int, last_page: int, from: int, to: int}
     */
    public function search(string $term = '', int $page = 1, int $perPage = 15): array
    {
        return $this->model()
            ->whereLike('nome', $term)
            ->orderBy('ordem_exibicao', 'ASC')
            ->paginate($perPage, $page);
    }

    // ── Slug ──────────────────────────────────────────────────────────────────

    /**
     * Verifica se o slug já está em uso por outra categoria.
     *
     * @param int|null $ignorarId ID da categoria que está sendo editada (ela não conta como duplicada)
     */
    public function slugExiste(string $slug, ?int $ignorarId = null): bool
    {
        $encontrada = $this->findBySlug($slug);

        if (!$encontrada) {
            return false;
        }

        return $ignorarId === null || (int) $encontrada->id !== $ignorarId;
    }

    /**
     * Gera um slug único a partir de um texto.
     *
     * Exemplos (já existindo "pizzas"):
     *   "Pizzas"           → "pizzas-2"
     *   "Açaí & Sorvetes"  → "acai-sorvetes"
     *
     * @param string   $texto     Slug digitado ou, na falta dele, o nome da categoria
     * @param int|null $ignorarId ID da categoria em edição (evita conflito consigo mesma)
     */
    public function gerarSlugUnico(string $texto, ?int $ignorarId = null): string
    {
        $base = slug($texto);

        if ($base === '') {
            $base = 'categoria';
        }

        $slug   = $base;
        $contador = 2;

        while ($this->slugExiste($slug, $ignorarId)) {
            $slug = $base . '-' . $contador;
            $contador++;
        }

        return $slug;
    }


    // ── Atualizações de Status e Ordem ────────────────────────────────────────

    /**
     * Alterna o status 'ativo' de uma categoria (Ativa/Inativa).
     */
    public function toggleAtivo(int $id): bool
    {
        $categoria = $this->findById($id);

        if (!$categoria) {
            return false;
        }

        $novoStatus = $categoria->ativo ? 0 : 1;

        return $this->update($id, ['ativo' => $novoStatus]);
    }

    /**
     * Alterna o status 'destaque' de uma categoria (Em Destaque/Normal).
     */
    public function toggleDestaque(int $id): bool
    {
        $categoria = $this->findById($id);

        if (!$categoria) {
            return false;
        }

        $novoDestaque = $categoria->destaque ? 0 : 1;

        return $this->update($id, ['destaque' => $novoDestaque]);
    }

    /**
     * Atualiza o valor do campo 'ordem_exibicao' de uma categoria específica.
     */
    public function updateOrdem(int $id, int $novaOrdem): bool
    {
        return $this->update($id, ['ordem_exibicao' => $novaOrdem]);
    }

    /**
     * Atualiza a ordem de múltiplas categorias em lote (reordenação por drag-and-drop).
     *
     * @param array<int, int> $ordens Exemplo: [id_categoria => posicao_ordem]
     */
    public function reordenarLote(array $ordens): bool
    {
        foreach ($ordens as $id => $ordem) {
            $this->updateOrdem((int) $id, (int) $ordem);
        }

        return true;
    }
}
