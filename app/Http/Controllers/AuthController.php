<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {

            $validacao = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'senha' => 'required',
                ],
                [
                    'email.required' => 'O campo e-mail é obrigatório.',
                    'email.email' => 'Informe um e-mail válido.',
                    'senha.required' => 'O campo senha é obrigatório.',
                ]
            );

            if ($validacao->fails()) {
                return back()->withErrors($validacao)->onlyInput('email');
            }

            $attempt = Auth::attempt([
                'email' => $request->email,
                'password' => $request->senha,
                'status' => 1,
            ]);

            if (!$attempt) {
                return back()->withErrors([
                    'email' => 'Credenciais inválidas.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));

        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Ocorreu um erro ao tentar fazer login.',
            ])->onlyInput('email');
        }
    }

    public function logout(Request $request)
    {
        try {

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login');

        } catch (\Exception $e) {
            return redirect()->route('login');
        }
    }
}
