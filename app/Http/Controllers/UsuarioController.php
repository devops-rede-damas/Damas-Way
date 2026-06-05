<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\TogglesStatus;
use App\Models\Usuario;
use App\Models\Nivel;
use App\Models\Filial;
use App\Models\Coligada;
use App\Models\UsuarioFilial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    use TogglesStatus;
    public function index()
    {
        $user = Auth::user();

        $query = Usuario::with('nivel');

        // Administrador vê apenas usuários das filiais vinculadas a ele
        if (!$user->isSuperAdmin()) {
            $filialIds = UsuarioFilial::where('id_usuario', $user->id)
                ->where('status', 1)
                ->pluck('id_filial');

            $usuarioIds = UsuarioFilial::whereIn('id_filial', $filialIds)
                ->where('status', 1)
                ->pluck('id_usuario')
                ->unique();

            $query->whereIn('id', $usuarioIds);
        }

        $usuarios = $query->orderBy('status')->orderBy('nome')->get();

        // Administrador não pode ver/atribuir o nível Super Administrador
        $niveisQuery = Nivel::where('status', 1);
        if (!$user->isSuperAdmin()) {
            $niveisQuery->where('nivel', '!=', 'Super Administrador');
        }
        $niveis = $niveisQuery->orderBy('nivel')->get();

        $coligadas = Coligada::where('status', 1)->orderBy('coligada')->get();

        // Administrador vê apenas filiais vinculadas
        if ($user->isSuperAdmin()) {
            $filiais = Filial::where('status', 1)->orderBy('id_rm')->get();
        } else {
            $filialIds = $filialIds ?? UsuarioFilial::where('id_usuario', $user->id)
                ->where('status', 1)
                ->pluck('id_filial');
            $filiais = Filial::whereIn('id', $filialIds)->where('status', 1)->orderBy('id_rm')->get();
        }

        return view('usuarios.index', compact('usuarios', 'niveis', 'coligadas', 'filiais'));
    }

    public function store(Request $request)
    {
        try {
            $validacao = Validator::make($request->all(), [
                'chapa'    => 'required|string|max:50',
                'nome'     => 'required|string|max:255',
                'email'    => 'required|email|unique:usuarios,email',
                'senha'    => 'required|string|min:6',
                'nivel_id' => 'required|exists:niveis,id',
                'filiais'  => 'nullable|array',
            ], [
                'chapa.required'    => 'A chapa é obrigatória.',
                'nome.required'     => 'O nome é obrigatório.',
                'email.required'    => 'O e-mail é obrigatório.',
                'email.email'       => 'Informe um e-mail válido.',
                'email.unique'      => 'Este e-mail já está cadastrado.',
                'senha.required'    => 'A senha é obrigatória.',
                'senha.min'         => 'A senha deve ter no mínimo 6 caracteres.',
                'nivel_id.required' => 'Selecione um nível de acesso.',
            ]);

            if ($validacao->fails()) {
                return redirect()->back()->with('error', $validacao->errors()->first());
            }

            DB::beginTransaction();

            $usuario = Usuario::create([
                'chapa'       => $request->chapa,
                'nome'        => $request->nome,
                'email'       => $request->email,
                'senha'       => Hash::make($request->senha),
                'nivel_id'    => $request->nivel_id,
                'status'      => 1,
                'criado_por'  => Auth::id(),
            ]);

            // Vincular filiais
            if ($request->filled('filiais')) {
                foreach ($request->filiais as $filialId) {
                    $filial = Filial::find($filialId);
                    if ($filial) {
                        UsuarioFilial::create([
                            'id_usuario'  => $usuario->id,
                            'id_coligada' => $filial->id_coligada,
                            'id_filial'   => $filial->id,
                            'status'      => 1,
                            'criado_por'  => Auth::id(),
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Usuário criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao criar usuário: ' . $e->getMessage());
        }
    }

    public function show(Usuario $usuario)
    {
        $usuario->load('nivel', 'filiais');

        $filiaisVinculadas = [];
        foreach ($usuario->filiais as $uf) {
            $filial = Filial::with('coligada')->find($uf->id_filial);
            if ($filial) {
                $filiaisVinculadas[] = $filial;
            }
        }

        return response()->json([
            'usuario' => $usuario,
            'filiais' => $filiaisVinculadas,
        ]);
    }

    public function update(Request $request, Usuario $usuario)
    {
        try {
            $validacao = Validator::make($request->all(), [
                'chapa'    => 'required|string|max:50',
                'nome'     => 'required|string|max:255',
                'email'    => 'required|email|unique:usuarios,email,' . $usuario->id,
                'senha'    => 'nullable|string|min:6',
                'nivel_id' => 'required|exists:niveis,id',
                'filiais'  => 'nullable|array',
            ], [
                'chapa.required'    => 'A chapa é obrigatória.',
                'nome.required'     => 'O nome é obrigatório.',
                'email.required'    => 'O e-mail é obrigatório.',
                'email.email'       => 'Informe um e-mail válido.',
                'email.unique'      => 'Este e-mail já está cadastrado.',
                'senha.min'         => 'A senha deve ter no mínimo 6 caracteres.',
                'nivel_id.required' => 'Selecione um nível de acesso.',
            ]);

            if ($validacao->fails()) {
                return redirect()->back()->with('error', $validacao->errors()->first());
            }

            DB::beginTransaction();

            $dados = [
                'chapa'          => $request->chapa,
                'nome'           => $request->nome,
                'email'          => $request->email,
                'nivel_id'       => $request->nivel_id,
                'modificado_por' => Auth::id(),
            ];

            if ($request->filled('senha')) {
                $dados['senha'] = Hash::make($request->senha);
            }

            $usuario->update($dados);

            // Recriar vínculos de filiais
            UsuarioFilial::where('id_usuario', $usuario->id)->delete();

            if ($request->filled('filiais')) {
                foreach ($request->filiais as $filialId) {
                    $filial = Filial::find($filialId);
                    if ($filial) {
                        UsuarioFilial::create([
                            'id_usuario'  => $usuario->id,
                            'id_coligada' => $filial->id_coligada,
                            'id_filial'   => $filial->id,
                            'status'      => 1,
                            'criado_por'  => Auth::id(),
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Usuário atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao atualizar usuário: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Usuario $usuario)
    {
        return $this->performToggleStatus($usuario);
    }
}
