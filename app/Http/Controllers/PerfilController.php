<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Filial;

class PerfilController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $usuario->load('nivel', 'filiais');

        $filiaisVinculadas = Filial::with('coligada')
            ->whereIn('id', $usuario->filiais->pluck('id_filial'))
            ->orderBy('id_rm')
            ->get();

        return view('perfil.index', compact('usuario', 'filiaisVinculadas'));
    }

    public function update(Request $request)
    {
        try {
            $usuario = Auth::user();

            $validacao = Validator::make($request->all(), [
                'senha' => 'required|string|min:6|confirmed',
            ], [
                'senha.required'  => 'Informe a nova senha.',
                'senha.min'       => 'A senha deve ter no mínimo 6 caracteres.',
                'senha.confirmed' => 'A confirmação de senha não confere.',
            ]);

            if ($validacao->fails()) {
                return redirect()->back()->with('error', $validacao->errors()->first());
            }

            DB::beginTransaction();

            $usuario->update([
                'senha'          => Hash::make($request->senha),
                'modificado_por' => $usuario->id,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Senha atualizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao atualizar senha: ' . $e->getMessage());
        }
    }
}
