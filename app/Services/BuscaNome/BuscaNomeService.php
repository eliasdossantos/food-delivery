<?php

namespace App\Services\BuscaNome;

use Framework\Core\Service;

/**
 * Formata o resultado da busca por nome para o autocomplete.
 * Não acessa o Model: usa o método procurar() do Repository recebido.
 *
 * Uso:
 *   new BuscaNomeService($this->usuarioModel);
 *   new BuscaNomeService(UsuarioRepository::class);
 *   new BuscaNomeService(ClienteRepository::class, ['cpf']);
 */
class BuscaNomeService extends Service
{
    private object $repository;

    /**
     * @param object|class-string $repository Repository (instância ou classe) que usa a trait BuscaPorNome
     * @param string[]            $colunasExtras Colunas extras pesquisadas e exibidas (ex: ['cpf'])
     */
    public function __construct(
        object|string $repository,
        private array $colunasExtras = []
    ) {
        $this->repository = is_string($repository) ? new $repository() : $repository;

        if (!method_exists($this->repository, 'procurar')) {
            throw new \RuntimeException(
                get_class($this->repository) . ' precisa usar a trait BuscaPorNome.'
            );
        }
    }

    /**
     * Retorna [['id' => 1, 'value' => 'Nome', 'label' => 'Nome - 123...'], ...]
     */
    public function buscar(?string $term, int $limite = 10): array
    {
        $registros = $this->repository->procurar($term, $this->colunasExtras, $limite);

        return array_map(function ($registro) {
            $label = $registro->nome;

            foreach ($this->colunasExtras as $coluna) {
                if (!empty($registro->$coluna)) {
                    $label .= ' - ' . $registro->$coluna;
                }
            }

            return [
                'id'    => $registro->id,
                'value' => $registro->nome,
                'label' => $label,
            ];
        }, $registros);
    }
}
