<?php
namespace App\Services\Interfaces;
use Illuminate\Http\Request;

interface IAuthService
{
    public function register(array $arr);
    public function confirmRegister(Request $request);
    public function login(Request $request);
    public function logout(Request $request);
}
