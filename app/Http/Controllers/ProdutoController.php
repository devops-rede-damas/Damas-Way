<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\TogglesStatus;
use App\Models\Categoria;
use App\Models\Filial;
use App\Models\Produto;
use App\Models\ProdutoImg;
use App\Models\UsuarioFilial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProdutoController extends Controller
{
    use TogglesStatus;

    public function index()
    {
        $user = Auth::user();

        $query = Produto::with(['categoria', 'filial', 'imagemPrincipal', 'imagens' => function ($q) {
                $q->where('status', 1)->orderByDesc('principal')->orderBy('id');
            }]);

        // Administrador vê apenas produtos das filiais vinculadas
        if (!$user->isSuperAdmin()) {
            $filialIds = $this->getFiliaisDoUsuario()->pluck('id');
            $query->whereIn('id_filial', $filialIds);
        }

        $produtos = $query->orderBy('produto')->get();

        $categorias = Categoria::where('status', 1)->orderBy('categoria')->get();

        // Filiais vinculadas ao usuário logado (formato ID_RM - Nome)
        $filiaisUsuario = $this->getFiliaisDoUsuario();

        return view('produtos.index', compact('produtos', 'categorias', 'filiaisUsuario'));
    }

    public function store(Request $request)
    {
        try {
            $validacao = Validator::make($request->all(), [
                'produto'       => 'required|string|max:255',
                'id_categoria'  => 'required|exists:categorias,id',
                'id_filial'     => 'required|exists:filiais,id',
                'valor'         => 'required|string',
                'qtd_estoque'   => 'required|integer|min:0',
                'imagens'       => 'nullable|array|max:4',
                'imagens.*'     => 'image|mimes:jpg,jpeg,png,webp|max:3072',
            ], [
                'produto.required'      => 'O nome do produto é obrigatório.',
                'id_categoria.required' => 'Selecione uma categoria.',
                'id_categoria.exists'   => 'Categoria inválida.',
                'id_filial.required'    => 'Selecione uma filial.',
                'id_filial.exists'      => 'Filial inválida.',
                'valor.required'        => 'O valor é obrigatório.',
                'qtd_estoque.required'  => 'A quantidade em estoque é obrigatória.',
                'qtd_estoque.integer'   => 'A quantidade deve ser um número inteiro.',
                'qtd_estoque.min'       => 'A quantidade não pode ser negativa.',
                'imagens.max'           => 'São permitidas no máximo 4 imagens.',
                'imagens.*.image'       => 'O arquivo deve ser uma imagem.',
                'imagens.*.mimes'       => 'Formatos aceitos: JPG, PNG ou WebP.',
                'imagens.*.max'         => 'Cada imagem deve ter no máximo 3MB.',
            ]);

            if ($validacao->fails()) {
                return redirect()->back()->with('error', $validacao->errors()->first());
            }

            // Verificar se o usuário tem vínculo com a filial selecionada
            if (!$this->usuarioTemVinculoFilial($request->id_filial)) {
                return redirect()->back()->with('error', 'Você não tem permissão para cadastrar produtos nesta filial.');
            }

            DB::beginTransaction();

            $produto = Produto::create([
                'produto'       => $request->produto,
                'id_categoria'  => $request->id_categoria,
                'id_filial'     => $request->id_filial,
                'valor'         => $this->converterValor($request->valor),
                'qtd_estoque'   => $request->qtd_estoque,
                'status'        => 1,
                'criado_por'    => Auth::id(),
            ]);

            // Upload de imagens (primeira é principal)
            if ($request->hasFile('imagens')) {
                $primeiro = true;
                foreach ($request->file('imagens') as $imagem) {
                    $path = $imagem->store("produtos/{$produto->id}", 'public');

                    ProdutoImg::create([
                        'id_produto' => $produto->id,
                        'path'       => $path,
                        'principal'  => $primeiro ? 1 : 0,
                        'status'     => 1,
                        'criado_por' => Auth::id(),
                    ]);
                    $primeiro = false;
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Produto criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao criar produto: ' . $e->getMessage());
        }
    }

    public function show(Produto $produto)
    {
        if (!$this->usuarioTemVinculoFilial($produto->id_filial)) {
            return response()->json(['error' => 'Acesso negado.'], 403);
        }

        $produto->load(['categoria', 'filial', 'imagens' => function ($q) {
            $q->where('status', 1);
        }]);

        return response()->json([
            'produto' => $produto,
            'imagens' => $produto->imagens->map(function ($img) {
                return [
                    'id'        => $img->id,
                    'url'       => asset('storage/' . $img->path),
                    'principal' => $img->principal,
                ];
            }),
            'filial_label' => $produto->filial ? $produto->filial->id_rm . ' - ' . $produto->filial->filial : '—',
        ]);
    }

    public function update(Request $request, Produto $produto)
    {
        try {
            // Verificar se o usuário tem acesso ao produto (filial atual)
            if (!$this->usuarioTemVinculoFilial($produto->id_filial)) {
                return redirect()->back()->with('error', 'Você não tem permissão para editar este produto.');
            }

            $imagensAtuais = $produto->imagens()->where('status', 1)->count();
            $imagensRemover = $request->input('imagens_remover', []);
            $novasImagens = $request->hasFile('imagens') ? count($request->file('imagens')) : 0;
            $totalFinal = $imagensAtuais - count($imagensRemover) + $novasImagens;

            $validacao = Validator::make($request->all(), [
                'produto'       => 'required|string|max:255',
                'id_categoria'  => 'required|exists:categorias,id',
                'id_filial'     => 'required|exists:filiais,id',
                'valor'         => 'required|string',
                'qtd_estoque'   => 'required|integer|min:0',
                'imagens'       => 'nullable|array',
                'imagens.*'     => 'image|mimes:jpg,jpeg,png,webp|max:3072',
            ], [
                'produto.required'      => 'O nome do produto é obrigatório.',
                'id_categoria.required' => 'Selecione uma categoria.',
                'id_categoria.exists'   => 'Categoria inválida.',
                'id_filial.required'    => 'Selecione uma filial.',
                'id_filial.exists'      => 'Filial inválida.',
                'valor.required'        => 'O valor é obrigatório.',
                'qtd_estoque.required'  => 'A quantidade em estoque é obrigatória.',
                'qtd_estoque.integer'   => 'A quantidade deve ser um número inteiro.',
                'qtd_estoque.min'       => 'A quantidade não pode ser negativa.',
                'imagens.*.image'       => 'O arquivo deve ser uma imagem.',
                'imagens.*.mimes'       => 'Formatos aceitos: JPG, PNG ou WebP.',
                'imagens.*.max'         => 'Cada imagem deve ter no máximo 3MB.',
            ]);

            if ($validacao->fails()) {
                return redirect()->back()->with('error', $validacao->errors()->first());
            }

            if ($totalFinal > 4) {
                return redirect()->back()->with('error', 'O produto pode ter no máximo 4 imagens.');
            }

            // Verificar se o usuário tem vínculo com a filial selecionada
            if (!$this->usuarioTemVinculoFilial($request->id_filial)) {
                return redirect()->back()->with('error', 'Você não tem permissão para vincular produtos nesta filial.');
            }

            DB::beginTransaction();

            $produto->update([
                'produto'        => $request->produto,
                'id_categoria'   => $request->id_categoria,
                'id_filial'      => $request->id_filial,
                'valor'          => $this->converterValor($request->valor),
                'qtd_estoque'    => $request->qtd_estoque,
                'modificado_por' => Auth::id(),
            ]);

            // Remover imagens marcadas
            if (!empty($imagensRemover)) {
                foreach ($imagensRemover as $imgId) {
                    $img = ProdutoImg::where('id', $imgId)
                        ->where('id_produto', $produto->id)
                        ->first();

                    if ($img) {
                        Storage::disk('public')->delete($img->path);
                        $img->delete();
                    }
                }
            }

            // Upload de novas imagens
            if ($request->hasFile('imagens')) {
                foreach ($request->file('imagens') as $imagem) {
                    $path = $imagem->store("produtos/{$produto->id}", 'public');

                    ProdutoImg::create([
                        'id_produto' => $produto->id,
                        'path'       => $path,
                        'principal'  => 0,
                        'status'     => 1,
                        'criado_por' => Auth::id(),
                    ]);
                }
            }

            // Atualizar imagem principal
            if ($request->filled('imagem_principal')) {
                ProdutoImg::where('id_produto', $produto->id)->update(['principal' => 0]);
                ProdutoImg::where('id', $request->imagem_principal)
                    ->where('id_produto', $produto->id)
                    ->update(['principal' => 1]);
            }

            // Se não há principal definida, definir a primeira ativa
            $temPrincipal = ProdutoImg::where('id_produto', $produto->id)
                ->where('principal', 1)
                ->where('status', 1)
                ->exists();

            if (!$temPrincipal) {
                $primeira = ProdutoImg::where('id_produto', $produto->id)
                    ->where('status', 1)
                    ->first();
                if ($primeira) {
                    $primeira->update(['principal' => 1]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Produto atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao atualizar produto: ' . $e->getMessage());
        }
    }

    public function destroyImagem(Produto $produto, ProdutoImg $imagem)
    {
        if (!$this->usuarioTemVinculoFilial($produto->id_filial)) {
            return response()->json(['error' => 'Acesso negado.'], 403);
        }

        if ($imagem->id_produto !== $produto->id) {
            return response()->json(['error' => 'Imagem não pertence a este produto.'], 403);
        }

        Storage::disk('public')->delete($imagem->path);
        $imagem->delete();

        return response()->json(['success' => true]);
    }

    public function toggleStatus(Produto $produto)
    {
        if (!$this->usuarioTemVinculoFilial($produto->id_filial)) {
            return response()->json(['error' => 'Acesso negado.'], 403);
        }

        return $this->performToggleStatus($produto);
    }

    /**
     * Retorna as filiais vinculadas ao usuário logado (ativas).
     * Super Administrador vê todas as filiais.
     */
    private function getFiliaisDoUsuario()
    {
        if (Auth::user()->isSuperAdmin()) {
            return Filial::where('status', 1)->orderBy('id_rm')->get();
        }

        $filialIds = UsuarioFilial::where('id_usuario', Auth::id())
            ->where('status', 1)
            ->pluck('id_filial');

        return Filial::whereIn('id', $filialIds)
            ->where('status', 1)
            ->orderBy('id_rm')
            ->get();
    }

    /**
     * Verifica se o usuário logado tem vínculo com a filial.
     * Super Administrador tem acesso a todas.
     */
    private function usuarioTemVinculoFilial(int $filialId): bool
    {
        if (Auth::user()->isSuperAdmin()) {
            return true;
        }

        return UsuarioFilial::where('id_usuario', Auth::id())
            ->where('id_filial', $filialId)
            ->where('status', 1)
            ->exists();
    }

    /**
     * Converte valor formatado (1.234,56) para decimal (1234.56).
     */
    private function converterValor(string $valor): float
    {
        $valor = preg_replace('/[^\d,]/', '', $valor);
        $valor = str_replace(',', '.', $valor);

        return (float) $valor;
    }
}
