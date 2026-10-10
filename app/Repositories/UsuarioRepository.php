<?php

namespace App\Repositories;

use Framework\Database\Repository;
use App\Models\UsuarioModel;
use App\Repositories\Concerns\BuscaPorNomeRepository;

/**
 * UsuarioRepository
 * Encapsula o acesso a dados de usuários.
 * Estenda com métodos de busca específicos do seu projeto.
 */
class UsuarioRepository extends Repository
{
    use BuscaPorNomeRepository;

    protected string $modelClass = UsuarioModel::class;

    public function findByEmail(string $email): object|false
    {
        return $this->model()->findBy('email', $email);
    }

    /**
     * Busca um usuário pelo ID já com o nome do perfil (perfil_nome).
     * LEFT JOIN: usuário sem perfil retorna perfil_nome = NULL.
     *
     * Usa whereRaw() porque where() gera o nome do parâmetro a partir da
     * coluna, e "usuarios.id" produziria um placeholder inválido (com ponto).
     */
    public function findComPerfil(int $id): object|false
    {
        return $this->model()
            ->select(
                'usuarios.*',
                'perfis.nome AS perfil_nome'
            )
            ->leftJoin('perfis', 'usuarios.perfil_id', '=', 'perfis.id')
            ->whereRaw('usuarios.id = :usuario_id', ['usuario_id' => $id])
            ->first();
    }

    public function getActive(): array
    {
        return $this->model()->where('ativo', 1)->orderBy('nome')->get();
    }

    /**
     * Busca paginada por nome ou e-mail, com o nome do perfil.
     * whereRaw() pelo mesmo motivo de findComPerfil(); o OR fica entre
     * parênteses para não quebrar a precedência com outras condições.
     */
    public function search(string $term = '', int $page = 1, int $perPage = 15): array
    {
        $like = '%' . $term . '%';

        return $this->model()
            ->select(
                'usuarios.id',
                'usuarios.nome',
                'usuarios.email',
                'usuarios.perfil_id',
                'perfis.nome AS perfil_nome',
                'usuarios.ativo',
                'usuarios.created_at'
            )
            ->leftJoin(
                'perfis',
                'usuarios.perfil_id',
                '=',
                'perfis.id'
            )
            ->whereRaw(
                '(usuarios.nome LIKE :termo_nome OR usuarios.email LIKE :termo_email)',
                [
                    'termo_nome' => $like,
                    'termo_email' => $like,
                ]
            )
            ->orderBy('usuarios.nome')
            ->paginate($perPage, $page);
    }

    public function tudoComPerfil(): array
    {
        return $this->model()
            ->select(
                'usuarios.*',
                'perfis.nome AS perfil_nome'
            )
            ->leftJoin('perfis', 'usuarios.perfil_id', '=', 'perfis.id')
            ->orderBy('usuarios.nome')
            ->get();
    }
}
