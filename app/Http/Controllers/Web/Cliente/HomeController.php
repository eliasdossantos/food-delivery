<?php
namespace App\Http\Controllers\Web\Cliente;
use App\Http\Controllers\Web\BaseController;
use Framework\Auth\Auth;
class HomeController extends BaseController
{
    public function index(): void { $this->view('cliente.home.index',['titulo'=>'Área do cliente','cliente'=>Auth::guard('cliente')->identidade()],'main'); }
}
