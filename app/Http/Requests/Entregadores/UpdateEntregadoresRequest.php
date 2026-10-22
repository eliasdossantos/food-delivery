<?php

namespace App\Http\Requests\Entregadores;

use App\Http\Requests\FormRequest;
use App\Services\ValidacaoCPF\CpfService;
use Framework\Http\Request;

/**
 * UpdateEntregadoresRequest
 * ─────────────────────────────────────────────────────────────────────────────
 * Centraliza validação, sanitização e autorização do formulário.
 *
 * Fluxo automático ao instanciar:
 *   new UpdateEntregadoresRequest()
 *     → authorize()   — verifica permissão
 *     → sanitize()    — limpa os dados
 *     → validate()    — aplica rules() com messages()
 *
 * Uso no Controller:
 *   $request = new UpdateEntregadoresRequest();
 *   if ($request->fails()) {
 *       Session::flash('error', $request->firstError());
 *       Session::flashInput($request->all());
 *       $this->back();
 *   }
 *   $data = $request->validated();
 */
class UpdateEntregadoresRequest extends FormRequest
{
    /**
     * Define quem pode realizar esta ação.
     *
     * Exemplos:
     *   return true;               // sempre permitido
     *   return Auth::check();      // apenas logados
     *   return Auth::is('admin');  // apenas admins
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação.
     * Sintaxe: 'campo' => 'regra1|regra2|regra3:param'
     *
     * Regras disponíveis:
     *   required, email, min:N, max:N, numeric, integer,
     *   confirmed, same:outro, different:outro,
     *   in:a,b,c, not_in:a,b,c, regex:/pattern/,
     *   unique:tabela,coluna, exists:tabela,coluna, nullable
     */
    public function rules(): array
    {
        return [
            // Vínculo
            'tipo_vinculo' => 'nullable|in:fixo,freelancer',

            // Identidade
            'nome'    => 'required|min:2|max:250',
            'email'   => 'nullable|email|max:180|unique:entregadores,email,{id}',
            'celular' => 'nullable|phone|max:20|unique:entregadores,celular,{id}',

            // Dados pessoais
            'cpf'                  => 'nullable|cpf|max:20|unique:entregadores,cpf,{id}',
            'documento_identidade' => 'nullable|max:40',
            'data_nascimento'      => 'nullable|date',

            // Endereço
            'cep'        => 'nullable|cep',
            'logradouro' => 'nullable|max:250',
            'numero'     => 'nullable|max:30',
            'bairro'     => 'nullable|max:200',
            'cidade'     => 'nullable|max:200',
            'estado'     => 'nullable|uf',

            // Autenticação (opcional na edição)
            'password'              => 'nullable|min:8|max:255',
            'password_confirmation' => 'nullable|same:password',

            // CNH
            'numero_cnh'    => 'nullable|max:40|unique:entregadores,numero_cnh,{id}',
            'categoria_cnh' => 'nullable|in:A,B,C,D,E,AB,AC,AD,AE',
            'cnh_validade'  => 'nullable|date',

            // Veículo
            'tipo_veiculo'   => 'nullable|in:moto,carro,bicicleta',
            'marca_veiculo'  => 'nullable|max:150',
            'modelo_veiculo' => 'nullable|max:150',
            'placa_veiculo'  => 'nullable|max:20|regex:/^[A-Z]{3}-[0-9][A-Z0-9][0-9]{2}$/',
            'cor_veiculo'    => 'nullable|max:70',

            // Remuneração
            'tipo_remuneracao'  => 'nullable|in:por_entrega,por_km,fixo',
            'valor_remuneracao' => 'nullable|numeric|min:0|max:99999999.99',
            'chave_pix'         => 'nullable|max:200',

            // Operação
            'ativo'      => 'required|in:0,1',
            'disponivel' => 'nullable|in:0,1',

            // Turnos
            'turno_manha' => 'nullable|in:0,1',
            'turno_tarde' => 'nullable|in:0,1',
            'turno_noite' => 'nullable|in:0,1',

            // Aprovação
            'status_aprovacao'  => 'required|in:pendente,aprovado,reprovado,bloqueado',
            'motivo_reprovacao' => 'nullable|max:250',

            // Admin
            'observacoes' => 'nullable|max:1000',

            // Arquivo (o nome salvo vai para a coluna `avatar`, definido no controller). max em KB.
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif',
        ];
    }

    public function messages(): array
    {
        return [
            'tipo_vinculo.in' => 'O vínculo deve ser "Fixo" ou "Freelancer".',

            'nome.required' => 'O nome do entregador é obrigatório.',
            'nome.min'      => 'O nome deve ter pelo menos 2 caracteres.',
            'nome.max'      => 'O nome deve ter no máximo 250 caracteres.',

            'email.email'  => 'Informe um e-mail válido.',
            'email.max'    => 'O e-mail deve ter no máximo 180 caracteres.',
            'email.unique' => 'Este e-mail já está em uso por outro entregador.',

            'celular.phone'  => 'Informe um celular válido.',
            'celular.max'    => 'O celular deve ter no máximo 20 caracteres.',
            'celular.unique' => 'Este celular já está em uso por outro entregador.',

            'cpf.cpf'    => 'Informe um CPF válido.',
            'cpf.max'    => 'O CPF deve ter no máximo 20 caracteres.',
            'cpf.unique' => 'Este CPF já está em uso por outro entregador.',

            'documento_identidade.max' => 'O documento de identidade deve ter no máximo 40 caracteres.',
            'data_nascimento.date'     => 'Informe uma data de nascimento válida.',

            'cep.cep'        => 'Informe um CEP válido.',
            'logradouro.max' => 'O logradouro deve ter no máximo 250 caracteres.',
            'numero.max'     => 'O número deve ter no máximo 30 caracteres.',
            'bairro.max'     => 'O bairro deve ter no máximo 200 caracteres.',
            'cidade.max'     => 'A cidade deve ter no máximo 200 caracteres.',
            'estado.uf'      => 'Informe uma UF válida.',

            'password.min'       => 'A senha deve ter pelo menos 8 caracteres.',
            'password.max'       => 'A senha deve ter no máximo 72 caracteres.',
            'password.confirmed' => 'A confirmação da senha não confere.',

            'numero_cnh.max'    => 'O número da CNH deve ter no máximo 40 caracteres.',
            'numero_cnh.unique' => 'Esta CNH já está em uso por outro entregador.',
            'categoria_cnh.in'  => 'A categoria da CNH é inválida.',
            'cnh_validade.date' => 'Informe uma validade de CNH válida.',

            'tipo_veiculo.in'     => 'O tipo de veículo deve ser moto, carro ou bicicleta.',
            'marca_veiculo.max'   => 'A marca deve ter no máximo 150 caracteres.',
            'modelo_veiculo.max'  => 'O modelo deve ter no máximo 150 caracteres.',
            'placa_veiculo.max'   => 'A placa deve ter no máximo 20 caracteres.',
            'placa_veiculo.regex' => 'Informe uma placa válida (ex.: ABC-1234 ou ABC-1D23).',
            'cor_veiculo.max'     => 'A cor deve ter no máximo 70 caracteres.',

            'tipo_remuneracao.in'       => 'O tipo de remuneração é inválido.',
            'valor_remuneracao.numeric' => 'O valor da remuneração deve ser numérico.',
            'valor_remuneracao.min'     => 'O valor da remuneração não pode ser negativo.',
            'valor_remuneracao.max'     => 'O valor da remuneração é muito alto.',
            'chave_pix.max'             => 'A chave PIX deve ter no máximo 200 caracteres.',

            'ativo.required' => 'O status do entregador é obrigatório.',
            'ativo.in'       => 'O status deve ser "Ativo" ou "Inativo".',
            'disponivel.in'  => 'A disponibilidade deve ser 0 ou 1.',
            'turno_manha.in' => 'O turno da manhã deve ser 0 ou 1.',
            'turno_tarde.in' => 'O turno da tarde deve ser 0 ou 1.',
            'turno_noite.in' => 'O turno da noite deve ser 0 ou 1.',

            'status_aprovacao.required' => 'O status de aprovação é obrigatório.',
            'status_aprovacao.in'       => 'O status de aprovação é inválido.',
            'motivo_reprovacao.max'     => 'O motivo deve ter no máximo 250 caracteres.',

            'observacoes.max' => 'As observações devem ter no máximo 1000 caracteres.',

            'avatar.image' => 'O arquivo enviado deve ser uma imagem válida.',
            'avatar.mimes' => 'A foto deve estar nos formatos JPG, JPEG, PNG, GIF ou WEBP.',
            'avatar.max'   => 'A foto deve ter no máximo 5MB.',
        ];
    }

    /**
     * Sanitiza os dados antes da validação.
     * Campos opcionais vazios viram NULL (importante nas colunas unique).
     * Senha: só trim(), sem sanitizeValue (o byte exato importa no hash).
     */
    public function sanitize(): array
    {
        $cpf = CpfService::formatar(Request::sanitizeValue($this->input['cpf'] ?? ''));

        $valor = trim((string) ($this->input['valor_remuneracao'] ?? ''));
        $valor = $valor === ''
            ? null
            : str_replace(',', '.', str_replace('.', '', $valor));

        return [
            // Vínculo
            'tipo_vinculo' => $this->nuloSeVazio(strtolower(trim($this->input['tipo_vinculo'] ?? ''))),

            // Identidade
            'nome'    => ucwords(mb_strtolower(trim(Request::sanitizeValue($this->input['nome'] ?? '')), 'UTF-8')),
            'email'   => $this->nuloSeVazio(strtolower(trim($this->input['email'] ?? ''))),
            'celular' => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['celular'] ?? ''))),

            // Dados pessoais
            'cpf'                  => $cpf === '' ? null : $cpf,
            'documento_identidade' => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['documento_identidade'] ?? ''))),
            'data_nascimento'      => $this->nuloSeVazio(trim($this->input['data_nascimento'] ?? '')),

            // Endereço
            'cep'        => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['cep'] ?? ''))),
            'logradouro' => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['logradouro'] ?? ''))),
            'numero'     => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['numero'] ?? ''))),
            'bairro'     => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['bairro'] ?? ''))),
            'cidade'     => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['cidade'] ?? ''))),
            'estado'     => $this->nuloSeVazio(strtoupper(trim($this->input['estado'] ?? ''))),

            // Autenticação
            'password'              => $this->nuloSeVazio(trim($this->input['password'] ?? '')),
            'password_confirmation' => $this->nuloSeVazio(trim($this->input['password_confirmation'] ?? '')),

            // CNH
            'numero_cnh'    => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['numero_cnh'] ?? ''))),
            'categoria_cnh' => $this->nuloSeVazio(strtoupper(trim($this->input['categoria_cnh'] ?? ''))),
            'cnh_validade'  => $this->nuloSeVazio(trim($this->input['cnh_validade'] ?? '')),

            // Veículo
            'tipo_veiculo'   => $this->nuloSeVazio(strtolower(trim($this->input['tipo_veiculo'] ?? ''))),
            'marca_veiculo'  => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['marca_veiculo'] ?? ''))),
            'modelo_veiculo' => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['modelo_veiculo'] ?? ''))),
            'placa_veiculo'  => $this->formatarPlaca($this->input['placa_veiculo'] ?? ''),
            'cor_veiculo'    => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['cor_veiculo'] ?? ''))),

            // Remuneração
            'tipo_remuneracao'  => $this->nuloSeVazio(strtolower(trim($this->input['tipo_remuneracao'] ?? ''))),
            'valor_remuneracao' => $valor,
            'chave_pix'         => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['chave_pix'] ?? ''))),

            // Operação
            'ativo'      => trim($this->input['ativo'] ?? '1'),
            'disponivel' => trim($this->input['disponivel'] ?? '0'),

            // Turnos
            'turno_manha' => trim($this->input['turno_manha'] ?? '0'),
            'turno_tarde' => trim($this->input['turno_tarde'] ?? '0'),
            'turno_noite' => trim($this->input['turno_noite'] ?? '0'),

            // Aprovação
            'status_aprovacao'  => trim($this->input['status_aprovacao'] ?? 'pendente'),
            'motivo_reprovacao' => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['motivo_reprovacao'] ?? ''))),

            // Admin
            'observacoes' => $this->nuloSeVazio(trim(Request::sanitizeValue($this->input['observacoes'] ?? ''))),
        ];
    }

    /** '' vira NULL, para não gravar string vazia em colunas nullable/unique */
    private function nuloSeVazio(string $valor): ?string
    {
        return $valor === '' ? null : $valor;
    }

    /**
     * Normaliza a placa para AAA-0A00 (aceita antiga e Mercosul, com ou sem hífen).
     * Se vier incompleta ou errada, devolve como está e o regex da validação acusa o erro.
     */
    private function formatarPlaca(string $placa): ?string
    {
        $placa = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $placa));

        if ($placa === '') {
            return null;
        }

        return strlen($placa) === 7
            ? substr($placa, 0, 3) . '-' . substr($placa, 3)
            : $placa;
    }
}
