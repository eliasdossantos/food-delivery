<?php

namespace App\Http\Requests\Clientes;

use App\Http\Requests\FormRequest;
use Framework\Auth\Auth;
use Framework\Http\Request;

/**
 * StoreClientesRequest
 * ─────────────────────────────────────────────────────────────────────────────
 * Centraliza validação, sanitização e autorização do formulário.
 *
 * Fluxo automático ao instanciar:
 *   new StoreClientesRequest()
 *     → authorize()   — verifica permissão
 *     → sanitize()    — limpa os dados
 *     → validate()    — aplica rules() com messages()
 *
 * Uso no Controller:
 *   $request = new StoreClientesRequest();
 *   if ($request->fails()) {
 *       Session::flash('error', $request->firstError());
 *       Session::flashInput($request->all());
 *       $this->back();
 *   }
 *   $data = $request->validated();
 */
class StoreClientesRequest extends FormRequest
{
    /**
     * Define quem pode realizar esta ação.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação.
     * Sintaxe: 'campo' => 'regra1|regra2|regra3:param'
     */
    public function rules(): array
    {
        return [
            // Dados pessoais
            'nome'            => 'required|min:2|max:250',
            'celular'         => 'required|max:20|unique:clientes,celular',
            'cpf'             => 'nullable|max:20|unique:clientes,cpf',
            'data_nascimento' => 'nullable|regex:/^\d{4}-\d{2}-\d{2}$/',

            // Autenticação
            'email'                 => 'nullable|email|max:180|unique:clientes,email',
            'password'              => 'nullable|min:8|max:255',
            'password_confirmation' => 'nullable|same:password',

            // Endereço principal
            'endereco_nome' => 'nullable|max:200',
            'cep'           => 'required|max:9',
            'logradouro'    => 'required|max:200',
            'numero'        => 'required|max:20',
            'complemento'   => 'nullable|max:200',
            'bairro'        => 'required|max:200',
            'cidade'        => 'required|max:200',
            'estado'        => 'required|min:2|max:2',
            'referencia'    => 'nullable|max:200',

            // Controle de acesso
            'ativo' => 'required|in:0,1',
        ];
    }

    /**
     * Mensagens personalizadas de validação.
     */
    public function messages(): array
    {
        return [
            // Dados pessoais
            'nome.required' => 'O nome do cliente é obrigatório.',
            'nome.min'      => 'O nome do cliente deve ter pelo menos 2 caracteres.',
            'nome.max'      => 'O nome do cliente deve ter no máximo 250 caracteres.',

            'celular.required' => 'O celular é obrigatório.',
            'celular.max'      => 'O celular deve ter no máximo 20 caracteres.',
            'celular.unique'   => 'Este celular já está cadastrado.',

            'cpf.max'    => 'O CPF deve ter no máximo 20 caracteres.',
            'cpf.unique' => 'Este CPF já está cadastrado.',

            'data_nascimento.regex' => 'Informe uma data de nascimento válida.',

            // Autenticação
            'email.email'  => 'Informe um e-mail válido.',
            'email.max'    => 'O e-mail deve ter no máximo 180 caracteres.',
            'email.unique' => 'Este e-mail já está cadastrado.',

            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.max' => 'A senha deve ter no máximo 255 caracteres.',

            'password_confirmation.same' => 'A confirmação da senha não confere.',

            // Endereço principal
            'endereco_nome.max' => 'O nome do endereço deve ter no máximo 200 caracteres.',

            'cep.required' => 'O CEP é obrigatório.',
            'cep.max'      => 'O CEP deve ter no máximo 9 caracteres.',

            'logradouro.required' => 'O logradouro é obrigatório.',
            'logradouro.max'      => 'O logradouro deve ter no máximo 200 caracteres.',

            'numero.required' => 'O número é obrigatório.',
            'numero.max'      => 'O número deve ter no máximo 20 caracteres.',

            'complemento.max' => 'O complemento deve ter no máximo 200 caracteres.',

            'bairro.required' => 'O bairro é obrigatório.',
            'bairro.max'      => 'O bairro deve ter no máximo 200 caracteres.',

            'cidade.required' => 'A cidade é obrigatória.',
            'cidade.max'      => 'A cidade deve ter no máximo 200 caracteres.',

            'estado.required' => 'O estado é obrigatório.',
            'estado.min'      => 'O estado deve ter 2 caracteres (UF).',
            'estado.max'      => 'O estado deve ter 2 caracteres (UF).',

            'referencia.max' => 'A referência deve ter no máximo 200 caracteres.',

            // Controle de acesso
            'ativo.required' => 'O status do cliente é obrigatório.',
            'ativo.in'       => 'O status do cliente deve ser "Ativo" ou "Inativo".',
        ];
    }

    /**
     * Sanitiza os dados recebidos antes da validação/processamento.
     *
     * - cpf, e-mail e data de nascimento vazios viram NULL: cpf e e-mail têm
     *   índice UNIQUE, e '' repetido violaria o índice a partir do segundo
     *   cliente sem o campo.
     */
    public function sanitize(): array
    {
        $cpf = trim(Request::sanitizeValue($this->input['cpf'] ?? ''));
        $email = strtolower(trim($this->input['email'] ?? ''));
        $dataNascimento = trim(Request::sanitizeValue($this->input['data_nascimento'] ?? ''));

        return [
            // Dados pessoais
            'nome' => ucwords(
                mb_strtolower(
                    Request::sanitizeValue($this->input['nome'] ?? ''),
                    'UTF-8'
                )
            ),

            'celular' => trim(
                Request::sanitizeValue($this->input['celular'] ?? '')
            ),

            'cpf' => $cpf === '' ? null : $cpf,

            'data_nascimento' => $dataNascimento === '' ? null : $dataNascimento,

            // Autenticação
            'email' => $email === '' ? null : $email,

            // Senhas sem sanitizeValue: o byte exato importa para o hash
            'password' => trim(
                $this->input['password'] ?? ''
            ),

            'password_confirmation' => trim(
                $this->input['password_confirmation'] ?? ''
            ),

            // Endereço principal
            'endereco_nome' => trim(
                Request::sanitizeValue($this->input['endereco_nome'] ?? '')
            ),

            'cep' => trim(
                Request::sanitizeValue($this->input['cep'] ?? '')
            ),

            'logradouro' => trim(
                Request::sanitizeValue($this->input['logradouro'] ?? '')
            ),

            'numero' => trim(
                Request::sanitizeValue($this->input['numero'] ?? '')
            ),

            'complemento' => trim(
                Request::sanitizeValue($this->input['complemento'] ?? '')
            ),

            'bairro' => trim(
                Request::sanitizeValue($this->input['bairro'] ?? '')
            ),

            'cidade' => trim(
                Request::sanitizeValue($this->input['cidade'] ?? '')
            ),

            'estado' => strtoupper(
                trim($this->input['estado'] ?? '')
            ),

            'referencia' => trim(
                Request::sanitizeValue($this->input['referencia'] ?? '')
            ),

            // Controle de acesso
            'ativo' => trim(
                $this->input['ativo'] ?? '1'
            ),
        ];
    }
}
