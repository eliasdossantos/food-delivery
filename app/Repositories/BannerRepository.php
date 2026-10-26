<?php

namespace App\Repositories;

use Framework\Database\Repository;
use App\Models\BannerModel;

/**
 * BannerRepository
 * ─────────────────────────────────────────────────────────────────────────────
 * Encapsula todas as queries relacionadas à entidade Banner.
 *
 * Herda do Repository base:
 *   all(), findById(), create(), update(), delete(), paginate()
 *
 * Adicione aqui apenas queries específicas desta entidade.
 * Lógica de negócio → Service (nunca aqui).
 *
 * Uso no Controller ou Service:
 *   $repo = new BannerRepository();
 *   $item = $repo->findById(42);
 *   $list = $repo->paginate(15, (int)($_GET['page'] ?? 1));
 */
class BannerRepository extends Repository
{
    /** Model associado a este repository */
    protected string $modelClass = BannerModel::class;

   // ── Buscas customizadas ───────────────────────────────────────────────────

    /**
     * Admin: todos os banners (inclusive inativos, agendados e expirados),
     * na ordem de exibição.
     */
    public function todos(): array
    {
        return $this->model()
            ->orderBy('ordem_exibicao', 'ASC')
            ->get();
    }

    /**
     * Home: banners visíveis agora (ativos e dentro do período), na ordem de exibição.
     *
     * A regra de vigência fica só em statusBanner() (helper), a mesma usada pelo
     * admin. Assim, o que a home mostra e o que o admin chama de "Vigente"
     * nunca divergem.
     */
    public function vigentes(int $limite = 5): array
    {
        $ativos = $this->model()
            ->where('ativo', 1)
            ->orderBy('ordem_exibicao', 'ASC')
            ->get();

        $vigentes = array_values(array_filter(
            $ativos,
            static fn(object $b): bool => statusBanner($b)[0] === 'Vigente'
        ));

        return array_slice($vigentes, 0, $limite);
    }

    /**
     * Busca paginada com filtro de texto por título.
     * Usa o query builder do Model — evite escrever SQL manual aqui.
     *
     * @return array{data: array, total: int, page: int, per_page: int, last_page: int, from: int, to: int}
     */
    public function search(string $term = '', int $page = 1, int $perPage = 15): array
    {
        return $this->model()
            ->whereLike('titulo', $term)
            ->orderBy('id', 'DESC')
            ->paginate($perPage, $page);
    }
}