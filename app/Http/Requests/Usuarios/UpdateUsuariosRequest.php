<?php

namespace App\Http\Requests\Usuarios;

use App\Http\Requests\FormRequest;
use App\Services\ValidacaoCPF\CpfService;
use Framework\Auth\Auth;
use Framework\Http\Request;

/**
 * UpdateUsuariosRequest
 * ─────────────────────────────────────────────────────────────────────────────
 * Centraliza validação, sanitização e autorização do formulário.
 *
 * Fluxo automático ao instanciar:
 *   new UpdateUsuariosRequest()
 *     → authorize()   — verifica permissão
 *     → sanitize()    — limpa os dados
 *     → validate()    — aplica rules() com messages()
 *
 * Uso no Controller:
 *   $request = new UpdateUsuariosRequest();
 *   if ($request->fails()) {
 *       Session::flash('error', $request->firstError());
 *       Session::flashInput($request->all());
 *       $this->back();
 *   }
 *   $data = $request->validated();
 */
class UpdateUsuariosRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            // Dados pessoais
            'nome'      => 'required|min:5|max:150',
            'cpf'       => 'nullable|cpf|max:20|unique',
            'celular'   => 'nullable|max:20',
            'perfil_id' => 'nullable|integer|exists:perfis,id',

            // Autenticação
            'email'                 => 'required|email|max:180',
            'password'              => 'nullable|min:8|max:255',
            'password_confirmation' => 'nullable|same:password',

            // Endereço
            'cep'         => 'nullable|max:9',
            'logradouro'  => 'nullable|max:250',
            'numero'      => 'nullable|max:30',
            'complemento' => 'nullable|max:200',
            'bairro'      => 'nullable|max:200',
            'cidade'      => 'nullable|max:200',
            'estado'      => 'nullable|max:2',
            'referencia'  => 'nullable|max:200',

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
            'nome.required'     => 'O nome do usuário é obrigatório.',
            'nome.min'          => 'O nome do usuário deve ter pelo menos 5 caracteres.',
            'nome.max'          => 'O nome do usuário deve ter no máximo 150 caracteres.',

            'cpf.max'           => 'O CPF deve ter no máximo 20 caracteres.',
            'cpf.unique'        => 'Este CPF do usuário já está em uso.',

            'celular.max'       => 'O celular deve ter no máximo 20 caracteres.',

            'perfil_id.integer' => 'O perfil selecionado é inválido.',
            'perfil_id.exists'  => 'O perfil selecionado não existe.',

            // Autenticação
            'email.required' => 'O e-mail é obrigatório.',
            'email.email'    => 'Informe um e-mail válido.',
            'email.max'      => 'O e-mail deve ter no máximo 180 caracteres.',

            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.max' => 'A senha deve ter no máximo 255 caracteres.',

            'password_confirmation.same' =>
            'A confirmação da senha não confere.',

            // Endereço
            'cep.max'         => 'O CEP deve ter no máximo 9 caracteres.',
            'logradouro.max'  => 'O logradouro deve ter no máximo 250 caracteres.',
            'numero.max'      => 'O número deve ter no máximo 30 caracteres.',
            'complemento.max' => 'O complemento deve ter no máximo 200 caracteres.',
            'bairro.max'      => 'O bairro deve ter no máximo 200 caracteres.',
            'cidade.max'      => 'A cidade deve ter no máximo 200 caracteres.',
            'estado.max'      => 'O estado deve ter no máximo 2 caracteres.',
            'referencia.max'  => 'A referência deve ter no máximo 200 caracteres.',

            // Controle de acesso
            'ativo.required' => 'O status do usuário é obrigatório.',
            'ativo.in'       => 'O status do usuário deve ser "Ativo" ou "Inativo".',
        ];
    }

    /**
     * Sanitiza os dados recebidos antes da validação/processamento.
     *
     * - cpf e celular vazios viram NULL (colunas unique).
     * - perfil_id vazio ("Sem perfil") vira NULL.
     */
    public function sanitize(): array
    {
        $cpf = CpfService::formatar(Request::sanitizeValue($this->input['cpf'] ?? ''));
        $celular = trim(Request::sanitizeValue($this->input['celular'] ?? ''));
        $perfilId = trim(Request::sanitizeValue($this->input['perfil_id'] ?? ''));

        return [
            // Dados pessoais
            'nome' => ucwords(
                mb_strtolower(
                    Request::sanitizeValue($this->input['nome'] ?? ''),
                    'UTF-8'
                )
            ),

            'cpf' => $cpf === '' ? null : $cpf,

            'celular' => $celular === '' ? null : $celular,

            'perfil_id' => $perfilId === '' ? null : $perfilId,

            // Autenticação
            'email' => strtolower(
                trim($this->input['email'] ?? '')
            ),

            // Senhas sem sanitizeValue: o byte exato importa para o hash
            'password' => trim(
                $this->input['password'] ?? ''
            ),

            'password_confirmation' => trim(
                $this->input['password_confirmation'] ?? ''
            ),

            // Endereço
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
