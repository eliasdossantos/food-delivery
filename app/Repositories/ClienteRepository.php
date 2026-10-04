<?php

namespace App\Repositories;

use Framework\Database\Repository;
use App\Models\ClienteModel;
use App\Models\EnderecoClienteModel;

/**
 * ClienteRepository
 * ─────────────────────────────────────────────────────────────────────────────
 * Encapsula todas as queries relacionadas à entidade Cliente.
 *
 * Herda do Repository base:
 *   all(), findById(), create(), update(), delete(), paginate()
 *
 * Adicione aqui apenas queries específicas desta entidade.
 * Lógica de negócio → Service (nunca aqui).
 *
 * Uso no Controller ou Service:
 *   $repo = new ClienteRepository();
 *   $item = $repo->findById(42);
 *   $list = $repo->paginate(15, (int)($_GET['page'] ?? 1));
 */
class ClienteRepository extends Repository
{
    /** Model associado a este repository */
    protected string $modelClass = ClienteModel::class;

    // ── Buscas customizadas ───────────────────────────────────────────────────

    /**
     * Busca por nome exato.
     */
    /**
     * Busca um cliente pelo e-mail.
     */
    public function findByEmail(string $email): object|false
    {
        return $this->model()->findBy('email', $email);
    }

    /**
     * Busca um cliente pelo celular.
     */
    public function findByCelular(string $celular): object|false
    {
        return $this->model()->findBy('celular', $celular);
    }

    /**
     * Busca um cliente pelo CPF.
     */
    public function findByCpf(string $cpf): object|false
    {
        return $this->model()->findBy('cpf', $cpf);
    }

    /**
     * Busca um cliente pelo ID com seu endereço principal.
     *
     * LEFT JOIN:
     * - cliente sem endereço continua sendo retornado;
     * - endereço principal é retornado quando existir.
     *
     * O endereço é exposto com o prefixo "endereco_" para evitar
     * conflito com os campos da tabela clientes.
     */
    public function findComEndereco(int $id): object|false
    {
        return $this->model()
            ->select(
                'clientes.*',
                'enderecos_clientes.id AS endereco_id',
                'enderecos_clientes.nome AS endereco_nome',
                'enderecos_clientes.cep AS endereco_cep',
                'enderecos_clientes.logradouro AS endereco_logradouro',
                'enderecos_clientes.numero AS endereco_numero',
                'enderecos_clientes.complemento AS endereco_complemento',
                'enderecos_clientes.bairro AS endereco_bairro',
                'enderecos_clientes.cidade AS endereco_cidade',
                'enderecos_clientes.estado AS endereco_estado',
                'enderecos_clientes.referencia AS endereco_referencia',
                'enderecos_clientes.principal AS endereco_principal'
            )
            ->leftJoin(
                'enderecos_clientes',
                'clientes.id',
                '=',
                'enderecos_clientes.cliente_id'
            )
            ->whereRaw(
                'clientes.id = :cliente_id
                 AND (
                     enderecos_clientes.principal = 1
                     OR enderecos_clientes.id IS NULL
                 )',
                [
                    'cliente_id' => $id,
                ]
            )
            ->first();
    }

    /**
     * Busca todos os endereços de um cliente.
     *
     * Retorna os endereços ordenados pelo principal primeiro
     * e depois pelo nome.
     */
    public function enderecos(int $clienteId): array
    {
        return $this->model()
            ->select(
                'enderecos_clientes.*'
            )
            ->leftJoin(
                'enderecos_clientes',
                'clientes.id',
                '=',
                'enderecos_clientes.cliente_id'
            )
            ->whereRaw(
                'clientes.id = :cliente_id',
                [
                    'cliente_id' => $clienteId,
                ]
            )
            ->orderBy('enderecos_clientes.principal', 'DESC')
            ->orderBy('enderecos_clientes.nome')
            ->get();
    }

    /**
     * Busca o endereço principal de um cliente.
     */
    public function enderecoPrincipal(int $clienteId): object|false
    {
        return $this->model()
            ->select(
                'enderecos_clientes.*'
            )
            ->leftJoin(
                'enderecos_clientes',
                'clientes.id',
                '=',
                'enderecos_clientes.cliente_id'
            )
            ->whereRaw(
                'clientes.id = :cliente_id
                 AND enderecos_clientes.principal = 1',
                [
                    'cliente_id' => $clienteId,
                ]
            )
            ->first();
    }

    /**
     * Busca clientes ativos.
     */
    public function getActive(): array
    {
        return $this->model()
            ->where('ativo', 1)
            ->orderBy('nome')
            ->get();
    }

    /**
     * Busca clientes paginados por nome, e-mail, celular ou CPF.
     */
    public function search(
        string $term = '',
        int $page = 1,
        int $perPage = 15
    ): array {
        $term = trim($term);
        $like = '%' . $term . '%';

        return $this->model()
            ->select(
                'clientes.id',
                'clientes.nome',
                'clientes.email',
                'clientes.celular',
                'clientes.cpf',
                'clientes.ativo',
                'clientes.created_at'
            )
            ->whereRaw(
                '(
                    clientes.nome LIKE :termo_nome
                    OR clientes.email LIKE :termo_email
                    OR clientes.celular LIKE :termo_celular
                    OR clientes.cpf LIKE :termo_cpf
                )',
                [
                    'termo_nome' => $like,
                    'termo_email' => $like,
                    'termo_celular' => $like,
                    'termo_cpf' => $like,
                ]
            )
            ->orderBy('clientes.nome')
            ->paginate($perPage, $page);
    }

    /**
     * Busca todos os clientes com seus endereços principais.
     *
     * Clientes sem endereço também são retornados.
     */
    public function tudoComEndereco(): array
    {
        return $this->model()
            ->select(
                'clientes.*',
                'enderecos_clientes.id AS endereco_id',
                'enderecos_clientes.nome AS endereco_nome',
                'enderecos_clientes.cep AS endereco_cep',
                'enderecos_clientes.logradouro AS endereco_logradouro',
                'enderecos_clientes.numero AS endereco_numero',
                'enderecos_clientes.complemento AS endereco_complemento',
                'enderecos_clientes.bairro AS endereco_bairro',
                'enderecos_clientes.cidade AS endereco_cidade',
                'enderecos_clientes.estado AS endereco_estado',
                'enderecos_clientes.referencia AS endereco_referencia',
                'enderecos_clientes.principal AS endereco_principal'
            )
            ->leftJoinSoft(
                'enderecos_clientes',
                'clientes.id',
                '=',
                'enderecos_clientes.cliente_id'
            )
            ->whereRaw(
                '(
                    enderecos_clientes.principal = 1
                    OR enderecos_clientes.id IS NULL
                )'
            )
            ->orderBy('clientes.nome')
            ->get();
    }

    /**
     * Busca clientes pelo nome para autocomplete/select.
     */
    public function procurar(?string $term): array
    {
        $term = trim((string) $term);

        if ($term === '') {
            return [];
        }

        $bindings = [
            'termo_nome' => "%{$term}%",
            'termo_cpf'  => "%{$term}%",
        ];

        $sql = '(nome LIKE :termo_nome OR cpf LIKE :termo_cpf';

        // Quem digita só os números ("12345678900") também encontra um CPF
        // gravado com máscara ("123.456.789-00").
        $digitos = preg_replace('/\D/', '', $term);

        if ($digitos !== '') {
            $sql .= " OR REPLACE(REPLACE(cpf, '.', ''), '-', '') LIKE :termo_digitos";
            $bindings['termo_digitos'] = "%{$digitos}%";
        }

        $sql .= ')';

        return $this->model()
            ->select('id', 'nome', 'cpf')
            ->whereRaw($sql, $bindings)
            ->orderBy('nome')
            ->get();
    }
}
