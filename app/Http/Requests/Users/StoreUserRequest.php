<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\FormRequest;
use Framework\Http\Request;

/**
 * StoreUserRequest
 * ─────────────────────────────────────────────────────────────────────────────
 * EXEMPLO DE REFERÊNCIA — não há um UserController neste boilerplate que use
 * esta classe. Ela demonstra o padrão de FormRequest para telas de gestão.
 *
 * Valida a criação de um novo usuário.
 *
 * Campos validados:
 *   - nome       → obrigatório, 5–150 caracteres
 *   - cpf        → opcional, máximo 20 caracteres, único
 *   - celular    → opcional, máximo 20 caracteres, único
 *   - perfil_id  → opcional, inteiro e existente em perfis
 *   - email      → obrigatório, formato válido, único
 *   - password   → obrigatório, 8–255 caracteres
 *   - confirmação da senha → deve ser igual à senha
 *   - endereço   → campos opcionais com limites de tamanho
 *   - ativo      → obrigatório, deve ser 0 ou 1
 */
class StoreUserRequest extends FormRequest
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
            'cpf'       => 'nullable|max:20|unique:usuarios,cpf',
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
     */
    public function sanitize(): array
    {
        return [
            // Dados pessoais
            'nome' => ucwords(
                mb_strtolower(
                    Request::sanitizeValue($this->input['nome'] ?? ''),
                    'UTF-8'
                )
            ),

            'cpf' => trim(
                Request::sanitizeValue($this->input['cpf'] ?? '')
            ),

            'celular' => trim(
                Request::sanitizeValue($this->input['celular'] ?? '')
            ),

            'perfil_id' => trim(
                Request::sanitizeValue($this->input['perfil_id'] ?? '')
            ),

            // Autenticação
            'email' => strtolower(
                trim($this->input['email'] ?? '')
            ),

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
