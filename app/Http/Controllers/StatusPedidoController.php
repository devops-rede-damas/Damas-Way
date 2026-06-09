<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\TogglesStatus;
use App\Models\StatusPedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StatusPedidoController extends Controller
{
    use TogglesStatus;

    public function index()
    {
        $statusPedidos = StatusPedido::orderBy('nome_status')->get();

        return view('status-pedido.index', compact('statusPedidos'));
    }

    public function store(Request $request)
    {
        try {
            $validacao = Validator::make($request->all(), [
                'nome_status' => 'required|string|max:255|unique:status_pedido,nome_status',
                'descricao'   => 'nullable|string|max:255',
            ], [
                'nome_status.required' => 'O nome do status é obrigatório.',
                'nome_status.unique'   => 'Já existe um status com este nome.',
            ]);

            if ($validacao->fails()) {
                return redirect()->back()->with('error', $validacao->errors()->first());
            }

            DB::beginTransaction();

            StatusPedido::create([
                'nome_status' => $request->nome_status,
                'descricao'   => $request->descricao,
                'status'      => 1,
                'criado_por'  => Auth::id(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Status de pedido criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao criar status de pedido: ' . $e->getMessage());
        }
    }

    public function update(Request $request, StatusPedido $statusPedido)
    {
        try {
            $validacao = Validator::make($request->all(), [
                'nome_status' => 'required|string|max:255|unique:status_pedido,nome_status,' . $statusPedido->id,
                'descricao'   => 'nullable|string|max:255',
            ], [
                'nome_status.required' => 'O nome do status é obrigatório.',
                'nome_status.unique'   => 'Já existe um status com este nome.',
            ]);

            if ($validacao->fails()) {
                return redirect()->back()->with('error', $validacao->errors()->first());
            }

            DB::beginTransaction();

            $statusPedido->update([
                'nome_status'    => $request->nome_status,
                'descricao'      => $request->descricao,
                'modificado_por' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Status de pedido atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao atualizar status de pedido: ' . $e->getMessage());
        }
    }

    public function toggleStatus(StatusPedido $statusPedido)
    {
        return $this->performToggleStatus($statusPedido);
    }
}
