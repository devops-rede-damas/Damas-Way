<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\TogglesStatus;
use App\Models\Nivel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NivelController extends Controller
{
    use TogglesStatus;
    public function index()
    {
        $niveis = Nivel::orderBy('nivel')->get();

        return view('niveis.index', compact('niveis'));
    }

    public function store(Request $request)
    {
        try {
            $validacao = Validator::make($request->all(), [
                'nivel' => 'required|string|max:255|unique:niveis,nivel',
            ], [
                'nivel.required' => 'O nome do nível é obrigatório.',
                'nivel.unique'   => 'Já existe um nível com este nome.',
            ]);

            if ($validacao->fails()) {
                return redirect()->back()->with('error', $validacao->errors()->first());
            }

            DB::beginTransaction();

            Nivel::create([
                'nivel'      => $request->nivel,
                'status'     => 1,
                'criado_por' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Nível criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao criar nível: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Nivel $nivel)
    {
        try {
            $validacao = Validator::make($request->all(), [
                'nivel' => 'required|string|max:255|unique:niveis,nivel,' . $nivel->id,
            ], [
                'nivel.required' => 'O nome do nível é obrigatório.',
                'nivel.unique'   => 'Já existe um nível com este nome.',
            ]);

            if ($validacao->fails()) {
                return redirect()->back()->with('error', $validacao->errors()->first());
            }

            DB::beginTransaction();

            $nivel->update([
                'nivel'          => $request->nivel,
                'modificado_por' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Nível atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao atualizar nível: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Nivel $nivel)
    {
        return $this->performToggleStatus($nivel);
    }
}
