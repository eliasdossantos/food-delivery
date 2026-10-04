<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\BaseController;
use App\Http\Requests\Clientes\StoreClientesRequest;
use App\Http\Requests\Clientes\UpdateClientesRequest;
use App\Repositories\ClienteRepository;
use App\Repositories\EnderecoRepository;
use Framework\Support\Session;

/**
 * ClientesController
 * ─────────────────────────────────────────────────────────────────────────────
 * Responsável por receber as requisições HTTP, delegar para o Service/Repository
 * e retornar a resposta adequada (View ou JSON).
 *
 * Regra: controllers devem ser finos.
 * Lógica de negócio → Service | Acesso a dados → Repository
 *
 * Upload de arquivo (se o Model tiver algum campo de arquivo):
 *   Os blocos de upload em store()/update() estão comentados por padrão.
 *   Descomente e ajuste o nome do campo (ex: 'imagem', 'avatar', 'anexo').
 *   Ver Framework\Support\Upload e BaseController::saveUploadedFile()/replaceUploadedFile()
 *   para detalhes.
 */
class ClientesController extends BaseController
{
    private ClienteRepository $clienteModel;
    private EnderecoRepository $enderecoModel;

    public function __construct()
    {
        parent::__construct();

        $this->clienteModel = new ClienteRepository();
        $this->enderecoModel = new EnderecoRepository();
    }

    /**
     * Lista todos os registros.
     * GET /admin/cliente
     */
    public function index(): void
    {
        $data = [
            'titulo' => 'Clientes do Sistema',
            'tituloTabela' => 'Lista de Clientes',
            'clientes' => $this->clienteModel->tudoComEndereco(),
        ];

        $this->view('admin.clientes.index', $data, 'main');
    }

    /**
     * Exibe um registro específico.
     * GET /admin/cliente/{id}
     */
    public function show(int $id): void
    {
        $cliente = $this->clienteModel->findComEndereco($id);
        $this->abortUnless((bool) $cliente, 404, 'Cliente não encontrado.');

        $data = [
            'titulo' => 'Detalhes do Cliente',
            'cliente' => $cliente,
        ];

        $this->view('admin.clientes.show', $data, 'main');
    }

    /**
     * Exibe o formulário de criação.
     * GET /admin/cliente/create
     */
    public function create(): void
    {
        $data = [
            'titulo' => 'Cadastrar Cliente',
        ];

        $this->view('admin.clientes.create', $data, 'main');
    }

    /**
     * Processa a criação de um novo registro.
     * POST /admin/cliente
     */
    public function store(StoreClientesRequest $request): void
    {
        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $validated = $request->validated();

        $senha = $validated['password'] ?? '';
        $confirmacao = $validated['password_confirmation'] ?? '';

        // Senha informada: a confirmação precisa ser idêntica.
        // (O nullable do Validator ignora a confirmação vazia, então checamos aqui.)
        if ($senha !== '' && $senha !== $confirmacao) {
            $mensagem = 'A confirmação da senha não confere.';

            Session::flash('error', $mensagem);
            Session::flashErrors(['password_confirmation' => [$mensagem]]);
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $cliente = $this->extrairCliente($validated);
        $endereco = $this->extrairEndereco($validated);

        $id = $this->clienteModel->create($cliente);

        if (!$id) {
            $this->redirectWith('admin/cliente', 'error', 'Não foi possível criar o cliente.');
            return;
        }

        // O primeiro endereço informado é sempre o principal
        if ($this->temEndereco($endereco)) {
            $this->enderecoModel->create($endereco + [
                'cliente_id' => (int) $id,
                'principal' => 1,
            ]);
        }

        // Após criar, vai direto para a página (show) do cliente criado
        $this->redirectWith(
            'admin/cliente/' . (int) $id,
            'success',
            "Cliente {$cliente['nome']} criado com sucesso!"
        );
    }

    /**
     * Exibe o formulário de edição.
     * GET /admin/cliente/{id}/edit
     */
    public function edit(int $id): void
    {
        $cliente = $this->clienteModel->findComEndereco($id);
        $this->abortUnless((bool) $cliente, 404, 'Cliente não encontrado.');

        $data = [
            'titulo' => 'Editar Cliente',
            'cliente' => $cliente,
        ];

        $this->view('admin.clientes.edit', $data, 'main');
    }

    /**
     * Processa a atualização de um registro.
     * PUT /admin/cliente/{id}
     */
    public function update(UpdateClientesRequest $request, int $id): void
    {
        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $validated = $request->validated();

        $senha = $validated['password'] ?? '';
        $confirmacao = $validated['password_confirmation'] ?? '';

        // Senha nova informada: a confirmação precisa ser idêntica.
        // (O nullable do Validator ignora a confirmação vazia, então checamos aqui.)
        if ($senha !== '' && $senha !== $confirmacao) {
            $mensagem = 'A confirmação da senha não confere.';

            Session::flash('error', $mensagem);
            Session::flashErrors(['password_confirmation' => [$mensagem]]);
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $cliente = $this->clienteModel->findComEndereco($id);
        $this->abortUnless((bool) $cliente, 404, 'Cliente não encontrado.');

        // O UpdateClientesRequest não valida unicidade (a regra unique não
        // ignora o próprio registro), então checamos aqui.
        $duplicados = $this->camposDuplicados($validated, $id);

        if ($duplicados !== []) {
            Session::flash('error', reset($duplicados)[0]);
            Session::flashErrors($duplicados);
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $dadosCliente = $this->extrairCliente($validated);
        $dadosEndereco = $this->extrairEndereco($validated);

        $atualizado = $this->clienteModel->update($id, $dadosCliente);

        if (!$atualizado) {
            $this->redirectWith(
                'admin/cliente',
                'error',
                "Não foi possível atualizar o cliente {$cliente->nome}."
            );
            return;
        }

        // Endereço principal: atualiza o existente ou cria o primeiro
        if ($this->temEndereco($dadosEndereco)) {
            if (!empty($cliente->endereco_id)) {
                $this->enderecoModel->update((int) $cliente->endereco_id, $dadosEndereco);
            } else {
                $this->enderecoModel->create($dadosEndereco + [
                    'cliente_id' => $id,
                    'principal' => 1,
                ]);
            }
        }

        $this->redirectWith(
            'admin/cliente',
            'success',
            "Cliente {$cliente->nome} atualizado com sucesso!"
        );
    }

    /**
     * Remove um registro.
     * DELETE /admin/cliente/{id}
     */
    public function destroy(int $id): void
    {
        $cliente = $this->clienteModel->findById($id);
        $this->abortUnless((bool) $cliente, 404, 'Cliente não encontrado.');

        // Como os dois usam soft delete, o ON DELETE CASCADE do banco não
        // dispara: removemos os endereços junto, para não ficarem órfãos.
        $this->clienteModel->delete($id);
        $this->enderecoModel->deleteByCliente($id);

        $this->redirectWith('admin/cliente', 'success', 'Cliente removido com sucesso.');
    }

    public function procurar(): never
    {
        $term = $this->request->get('term', '');

        $clientes = $this->clienteModel->procurar($term);

        $resultados = [];

        foreach ($clientes as $cliente) {
            $resultados[] = [
                'id'    => $cliente->id,
                'value' => $cliente->nome,
            ];
        }

        $this->json($resultados);
    }

    // ── Auxiliares ────────────────────────────────────────────────────────────

    /**
     * Monta os dados da tabela `clientes`.
     *
     * - email, cpf e data_nascimento vazios viram NULL: email e cpf têm índice
     *   UNIQUE, e dois clientes com '' violariam a unicidade.
     * - senha vazia = não grava (na edição, mantém a senha atual).
     * - o campo do formulário `password` é gravado em `password_cliente`.
     */
    private function extrairCliente(array $data): array
    {
        $cliente = [
            'nome'            => $data['nome'],
            'celular'         => $data['celular'],
            'email'           => $this->nuloSeVazio($data['email'] ?? null),
            'cpf'             => $this->nuloSeVazio($data['cpf'] ?? null),
            'data_nascimento' => $this->nuloSeVazio($data['data_nascimento'] ?? null),
            'ativo'           => (int) ($data['ativo'] ?? 1),
        ];

        $senha = $data['password'] ?? '';

        if ($senha !== '') {
            $cliente['password_cliente'] = $this->hashSenha($senha);
        }

        return $cliente;
    }

    /**
     * Monta os dados da tabela `enderecos_clientes` (sem cliente_id/principal,
     * que dependem do contexto). `endereco_nome` do formulário vira `nome`.
     */
    private function extrairEndereco(array $data): array
    {
        return [
            'nome'        => $this->nuloSeVazio($data['endereco_nome'] ?? null) ?? 'Principal',
            'cep'         => trim((string) ($data['cep'] ?? '')),
            'logradouro'  => trim((string) ($data['logradouro'] ?? '')),
            'numero'      => trim((string) ($data['numero'] ?? '')),
            'complemento' => $this->nuloSeVazio($data['complemento'] ?? null),
            'bairro'      => trim((string) ($data['bairro'] ?? '')),
            'cidade'      => trim((string) ($data['cidade'] ?? '')),
            'estado'      => strtoupper(trim((string) ($data['estado'] ?? ''))),
            'referencia'  => $this->nuloSeVazio($data['referencia'] ?? null),
        ];
    }

    /**
     * Considera que há endereço quando qualquer campo obrigatório foi
     * preenchido. A exigência de todos os campos fica no Request.
     */
    private function temEndereco(array $endereco): bool
    {
        foreach (['cep', 'logradouro', 'numero', 'bairro', 'cidade', 'estado'] as $campo) {
            if ($endereco[$campo] !== '') {
                return true;
            }
        }

        return false;
    }

    /**
     * Procura celular, CPF e e-mail já usados por OUTRO cliente.
     *
     * @return array<string, string[]> campo => [mensagem], no formato do flashErrors()
     */
    private function camposDuplicados(array $data, int $id): array
    {
        $verificacoes = [
            'celular' => ['findByCelular', 'Este celular já está cadastrado.'],
            'cpf'     => ['findByCpf',     'Este CPF já está cadastrado.'],
            'email'   => ['findByEmail',   'Este e-mail já está cadastrado.'],
        ];

        $erros = [];

        foreach ($verificacoes as $campo => [$metodo, $mensagem]) {
            $valor = $data[$campo] ?? null;

            if ($valor === null || $valor === '') {
                continue;
            }

            $existente = $this->clienteModel->{$metodo}($valor);

            if ($existente && (int) $existente->id !== $id) {
                $erros[$campo] = [$mensagem];
            }
        }

        return $erros;
    }

    private function nuloSeVazio(mixed $valor): ?string
    {
        $valor = trim((string) $valor);

        return $valor === '' ? null : $valor;
    }

    private function hashSenha(string $senha): string
    {
        return password_hash($senha, PASSWORD_BCRYPT, ['cost' => 12]);
    }
}
