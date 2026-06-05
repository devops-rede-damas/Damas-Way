<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\TogglesStatus;
use App\Models\Transportadora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransportadoraController extends Controller
{
    use TogglesStatus;

    public function index()
    {
        $transportadoras = Transportadora::orderBy('nome')->get();

        return view('transportadoras.index', compact('transportadoras'));
    }

    public function store(Request $request)
    {
        try {
            $validacao = Validator::make($request->all(), [
                'nome' => 'required|string|max:255|unique:transportadoras,nome',
                'api'  => 'nullable|string|max:255',
            ], [
                'nome.required' => 'O nome da transportadora é obrigatório.',
                'nome.unique'   => 'Já existe uma transportadora com este nome.',
            ]);

            if ($validacao->fails()) {
                return redirect()->back()->with('error', $validacao->errors()->first());
            }

            DB::beginTransaction();

            Transportadora::create([
                'nome'       => $request->nome,
                'api'        => $request->api,
                'status'     => 1,
                'criado_por' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Transportadora criada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao criar transportadora: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Transportadora $transportadora)
    {
        try {
            $validacao = Validator::make($request->all(), [
                'nome' => 'required|string|max:255|unique:transportadoras,nome,' . $transportadora->id,
                'api'  => 'nullable|string|max:255',
            ], [
                'nome.required' => 'O nome da transportadora é obrigatório.',
                'nome.unique'   => 'Já existe uma transportadora com este nome.',
            ]);

            if ($validacao->fails()) {
                return redirect()->back()->with('error', $validacao->errors()->first());
            }

            DB::beginTransaction();

            $transportadora->update([
                'nome'           => $request->nome,
                'api'            => $request->api,
                'modificado_por' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Transportadora atualizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao atualizar transportadora: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Transportadora $transportadora)
    {
        return $this->performToggleStatus($transportadora);
    }
}
