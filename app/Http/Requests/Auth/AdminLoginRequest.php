<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\FormRequest;
use Framework\Http\Request;

class AdminLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return ['email' => 'required|email|max:180', 'senha' => 'required|min:1|max:255'];
    }
    public function messages(): array
    {
        return ['email.required' => 'O e-mail é obrigatório.', 'email.email' => 'Informe um e-mail válido.', 'senha.required' => 'A senha é obrigatória.'];
    }
    public function sanitize(): array
    {
        return ['email' => strtolower(trim($this->input['email'] ?? '')), 'senha' => trim($this->input['senha'] ?? '')];
    }
}
