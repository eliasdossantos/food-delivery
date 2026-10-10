<?php

namespace App\Repositories\Concerns;

/**
 * Busca por nome (e colunas extras opcionais) para autocomplete.
 * Use em qualquer repository que estenda Framework\Database\Repository
 * e cuja tabela tenha as colunas id e nome.
 */
trait BuscaPorNomeRepository
{
    /**
     * @param string[] $colunasExtras Ex: ['cpf']
     */
    public function procurar(?string $term, array $colunasExtras = [], int $limite = 10): array
    {
        $term = trim((string) $term);

        if ($term === '') {
            return [];
        }

        foreach ($colunasExtras as $coluna) {
            if (!preg_match('/^[a-z_][a-z0-9_]*$/i', $coluna)) {
                throw new \InvalidArgumentException("Coluna inválida: {$coluna}");
            }
        }

        $like      = '%' . $term . '%';
        $colunas   = array_merge(['nome'], $colunasExtras);
        $condicoes = [];
        $params    = [];

        foreach ($colunas as $i => $coluna) {
            $condicoes[]          = "{$coluna} LIKE :termo_{$i}";
            $params["termo_{$i}"] = $like;
        }

        $registros = $this->model()
            ->select(...array_merge(['id'], $colunas))
            ->whereRaw('(' . implode(' OR ', $condicoes) . ')', $params)
            ->orderBy('nome')
            ->get();

        return array_slice($registros, 0, $limite);
    }
}
