<?php
namespace App\Http\Requests\Auth;
use App\Http\Requests\FormRequest;
class ClienteForgotPasswordRequest extends FormRequest
{ public function authorize(): bool{return true;} public function rules():array{return ['email'=>'required|email|max:180'];} public function messages():array{return ['email.required'=>'O e-mail é obrigatório.','email.email'=>'Informe um e-mail válido.'];} public function sanitize():array{return ['email'=>strtolower(trim($this->input['email']??''))];} }
