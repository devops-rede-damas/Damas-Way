<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\TogglesStatus;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    use TogglesStatus;

    public function index()
    {
        $categorias = Categoria::orderBy('categoria')->get();

        return view('categorias.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        try {
            $validacao = Validator::make($request->all(), [
                'categoria' => 'required|string|max:255|unique:categorias,categoria',
            ], [
                'categoria.required' => 'O nome da categoria é obrigatório.',
                'categoria.unique'   => 'Já existe uma categoria com este nome.',
            ]);

            if ($validacao->fails()) {
                return redirect()->back()->with('error', $validacao->errors()->first());
            }

            DB::beginTransaction();

            Categoria::create([
                'categoria'  => $request->categoria,
                'status'     => 1,
                'criado_por' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Categoria criada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao criar categoria: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Categoria $categoria)
    {
        try {
            $validacao = Validator::make($request->all(), [
                'categoria' => 'required|string|max:255|unique:categorias,categoria,' . $categoria->id,
            ], [
                'categoria.required' => 'O nome da categoria é obrigatório.',
                'categoria.unique'   => 'Já existe uma categoria com este nome.',
            ]);

            if ($validacao->fails()) {
                return redirect()->back()->with('error', $validacao->errors()->first());
            }

            DB::beginTransaction();

            $categoria->update([
                'categoria'      => $request->categoria,
                'modificado_por' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Categoria atualizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao atualizar categoria: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Categoria $categoria)
    {
        return $this->performToggleStatus($categoria);
    }
}
