<?php
namespace App\Http\Requests\Auth;
use App\Http\Requests\FormRequest;
class AdminResetPasswordRequest extends FormRequest
{ public function authorize(): bool{return true;} public function rules():array{return ['token'=>'required','senha'=>'required|min:8|max:255','senha_confirmacao'=>'required|same:senha'];} public function messages():array{return ['token.required'=>'O token é obrigatório.','senha.required'=>'A nova senha é obrigatória.','senha.min'=>'A nova senha deve ter pelo menos 8 caracteres.','senha_confirmacao.same'=>'A confirmação não confere.'];} public function sanitize():array{return ['token'=>trim($this->input['token']??''),'senha'=>trim($this->input['senha']??''),'senha_confirmacao'=>trim($this->input['senha_confirmacao']??'')];} }
