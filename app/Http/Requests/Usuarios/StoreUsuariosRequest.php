<?php

namespace App\Http\Requests\Usuarios;

use App\Http\Requests\FormRequest;
use App\Services\ValidacaoCPF\CpfService;
use Framework\Auth\Auth;
use Framework\Http\Request;

/**
 * StoreUsuariosRequest
 * ─────────────────────────────────────────────────────────────────────────────
 * Centraliza validação, sanitização e autorização do formulário.
 *
 * Fluxo automático ao instanciar:
 *   new StoreUsuariosRequest()
 *     → authorize()   — verifica permissão
 *     → sanitize()    — limpa os dados
 *     → validate()    — aplica rules() com messages()
 *
 * Uso no Controller:
 *   $request = new StoreUsuariosRequest();
 *   if ($request->fails()) {
 *       Session::flash('error', $request->firstError());
 *       Session::flashInput($request->all());
 *       $this->back();
 *   }
 *   $data = $request->validated();
 */
class StoreUsuariosRequest extends FormRequest
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
            // Dados pessoais
            'nome'      => 'required|min:5|max:150',
            'cpf'       => 'nullable|cpf|max:20|unique:usuarios,cpf',
            'celular'   => 'nullable|max:20|unique:usuarios,celular',
            'perfil_id' => 'nullable|integer|exists:perfis,id',

            // Autenticação
            'email'                 => 'required|email|max:180|unique:usuarios,email',
            'password'              => 'required|min:8|max:255',
            'password_confirmation' => 'required|same:password',

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
            'cpf.unique'        => 'Este CPF já está cadastrado.',
            'cpf.cpf'           => 'Informe um CPF válido.',

            'celular.max'       => 'O celular deve ter no máximo 20 caracteres.',
            'celular.unique'    => 'Este celular já está cadastrado.',

            'perfil_id.integer' => 'O perfil selecionado é inválido.',
            'perfil_id.exists'  => 'O perfil selecionado não existe.',

            // Autenticação
            'email.required'    => 'O e-mail é obrigatório.',
            'email.email'       => 'Informe um e-mail válido.',
            'email.max'         => 'O e-mail deve ter no máximo 180 caracteres.',
            'email.unique'      => 'Este e-mail já está cadastrado.',

            'password.required' => 'A senha é obrigatória.',
            'password.min'      => 'A senha deve ter pelo menos 8 caracteres.',
            'password.max'      => 'A senha deve ter no máximo 255 caracteres.',

            'password_confirmation.required' => 'A confirmação da senha é obrigatória.',
            'password_confirmation.same'     => 'A confirmação da senha não confere.',

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
     * - cpf e celular vazios viram NULL (colunas unique: '' repetido
     *   violaria o índice a partir do segundo usuário sem o campo).
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
