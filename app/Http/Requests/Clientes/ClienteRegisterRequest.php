<?php
namespace App\Http\Requests\Clientes;
use App\Http\Requests\FormRequest;
class ClienteRegisterRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return ['nome'=>'required|min:2|max:250','email'=>'required|email|max:180|unique:clientes,email','celular'=>'required|max:20|unique:clientes,celular','senha'=>'required|min:8|max:255','senha_confirmacao'=>'required|same:senha']; }
    public function messages(): array { return ['nome.required'=>'O nome é obrigatório.','email.required'=>'O e-mail é obrigatório.','email.unique'=>'Este e-mail já está cadastrado.','celular.required'=>'O celular é obrigatório.','celular.unique'=>'Este celular já está cadastrado.','senha.required'=>'A senha é obrigatória.','senha.min'=>'A senha deve ter pelo menos 8 caracteres.','senha_confirmacao.same'=>'A confirmação não confere.']; }
    public function sanitize(): array { return ['nome'=>ucwords(mb_strtolower(trim(strip_tags($this->input['nome'] ?? '')),'UTF-8')),'email'=>strtolower(trim($this->input['email'] ?? '')),'celular'=>trim(strip_tags($this->input['celular'] ?? '')),'senha'=>trim($this->input['senha'] ?? ''),'senha_confirmacao'=>trim($this->input['senha_confirmacao'] ?? '')]; }
}
