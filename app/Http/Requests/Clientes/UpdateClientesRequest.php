<?php

namespace App\Http\Requests\Clientes;

use App\Http\Requests\FormRequest;
use Framework\Auth\Auth;
use Framework\Http\Request;

/**
 * UpdateClientesRequest
 * ─────────────────────────────────────────────────────────────────────────────
 * Centraliza validação, sanitização e autorização do formulário de edição.
 *
 * A edição só altera: nome, e-mail, celular, endereço principal e status.
 * CPF, data de nascimento e senha NÃO fazem parte do formulário e, por isso,
 * não são validados nem devolvidos aqui (o controller não os altera).
 *
 * Fluxo automático ao instanciar:
 *   new UpdateClientesRequest()
 *     → authorize()   — verifica permissão
 *     → sanitize()    — limpa os dados
 *     → validate()    — aplica rules() com messages()
 *
 * Uso no Controller:
 *   $request = new UpdateClientesRequest();
 *   if ($request->fails()) {
 *       Session::flash('error', $request->firstError());
 *       Session::flashInput($request->all());
 *       $this->back();
 *   }
 *   $data = $request->validated();
 */
class UpdateClientesRequest extends FormRequest
{
    /**
     * Define se a requisição está autorizada.
     */
    public function authorize(): bool
    {
        // Exemplo:
        // return Auth::check() && Auth::is('administrador');

        return true;
    }

    /**
     * Regras de validação.
     *
     * Atenção: celular e e-mail NÃO têm `unique` aqui (a regra não ignora o
     * próprio registro). A duplicidade é checada no controller, em
     * camposDuplicados().
     */
    public function rules(): array
    {
        return [
            // Dados pessoais
            'nome'    => 'required|min:2|max:250',
            'email'   => 'nullable|email|max:180',
            'celular' => 'required|max:20',

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

            'email.email' => 'Informe um e-mail válido.',
            'email.max'   => 'O e-mail deve ter no máximo 180 caracteres.',

            'celular.required' => 'O celular é obrigatório.',
            'celular.max'      => 'O celular deve ter no máximo 20 caracteres.',

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
     * - e-mail vazio vira NULL (coluna unique: '' repetido violaria o índice).
     */
    public function sanitize(): array
    {
        $email = strtolower(trim($this->input['email'] ?? ''));

        return [
            // Dados pessoais
            'nome' => ucwords(
                mb_strtolower(
                    Request::sanitizeValue($this->input['nome'] ?? ''),
                    'UTF-8'
                )
            ),

            'email' => $email === '' ? null : $email,

            'celular' => trim(
                Request::sanitizeValue($this->input['celular'] ?? '')
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
