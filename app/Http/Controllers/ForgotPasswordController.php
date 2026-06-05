<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Usuario;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        try {

            $validacao = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                ],
                [
                    'email.required' => 'O campo e-mail é obrigatório.',
                    'email.email' => 'Informe um e-mail válido.',
                ]
            );

            if ($validacao->fails()) {
                return back()->withErrors($validacao)->onlyInput('email');
            }

            $usuario = Usuario::where('email', $request->email)->where('status', 1)->first();

            if (!$usuario) {
                return back()->withErrors([
                    'email' => 'Nenhum usuário ativo encontrado com este e-mail.',
                ])->onlyInput('email');
            }

            DB::beginTransaction();

            DB::table('password_resets')->where('email', $request->email)->delete();

            $token = Str::random(64);

            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => now(),
            ]);

            DB::commit();

            $resetUrl = url('/redefinir-senha/' . $token . '?email=' . urlencode($request->email));

            Mail::send('emails.reset-password', ['url' => $resetUrl, 'nome' => $usuario->nome], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Redefinição de Senha - Damas Way');
            });

            return back()->with('success', 'Link de redefinição enviado para o seu e-mail.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['email' => 'Erro ao enviar e-mail. Tente novamente.'])->onlyInput('email');
        }
    }

    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        try {

            $validacao = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'token' => 'required',
                    'senha' => 'required|min:6|confirmed',
                ],
                [
                    'email.required' => 'O campo e-mail é obrigatório.',
                    'email.email' => 'Informe um e-mail válido.',
                    'token.required' => 'Token inválido.',
                    'senha.required' => 'O campo senha é obrigatório.',
                    'senha.min' => 'A senha deve ter no mínimo 6 caracteres.',
                    'senha.confirmed' => 'As senhas não conferem.',
                ]
            );

            if ($validacao->fails()) {
                return back()->withErrors($validacao)->withInput();
            }

            $reset = DB::table('password_resets')
                ->where('email', $request->email)
                ->first();

            if (!$reset) {
                return back()->withErrors(['email' => 'Token inválido ou expirado.']);
            }

            if (!Hash::check($request->token, $reset->token)) {
                return back()->withErrors(['email' => 'Token inválido ou expirado.']);
            }

            if (now()->diffInMinutes($reset->created_at) > 60) {
                DB::table('password_resets')->where('email', $request->email)->delete();
                return back()->withErrors(['email' => 'Token expirado. Solicite um novo link.']);
            }

            DB::beginTransaction();

            Usuario::where('email', $request->email)->update([
                'senha' => Hash::make($request->senha),
                'modificado_em' => now(),
            ]);

            DB::table('password_resets')->where('email', $request->email)->delete();

            DB::commit();

            return redirect()->route('login')->with('success', 'Senha redefinida com sucesso! Faça login.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['email' => 'Erro ao redefinir senha. Tente novamente.']);
        }
    }
}
