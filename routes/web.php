<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\SyncController;
use App\Http\Controllers\ColigadaController;
use App\Http\Controllers\FilialController;
use App\Http\Controllers\NivelController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\TransportadoraController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Recuperar Senha
Route::get('/esqueceu-senha', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
Route::post('/esqueceu-senha', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/redefinir-senha/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/redefinir-senha', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

// Rotas autenticadas
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    // Perfil (todos os níveis)
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');

    // Rotas para Super Administrador e Administrador
    Route::middleware('nivel:Super Administrador,Administrador')->group(function () {
        // Usuários
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{usuario}', [UsuarioController::class, 'show'])->name('usuarios.show');
        Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::patch('/usuarios/{usuario}/toggle-status', [UsuarioController::class, 'toggleStatus'])->name('usuarios.toggleStatus');

        // Transportadoras
        Route::get('/transportadoras', [TransportadoraController::class, 'index'])->name('transportadoras.index');
        Route::post('/transportadoras', [TransportadoraController::class, 'store'])->name('transportadoras.store');
        Route::put('/transportadoras/{transportadora}', [TransportadoraController::class, 'update'])->name('transportadoras.update');
        Route::patch('/transportadoras/{transportadora}/toggle-status', [TransportadoraController::class, 'toggleStatus'])->name('transportadoras.toggleStatus');

        // Categorias
        Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
        Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
        Route::put('/categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update');
        Route::patch('/categorias/{categoria}/toggle-status', [CategoriaController::class, 'toggleStatus'])->name('categorias.toggleStatus');

        // Produtos
        Route::get('/produtos', [ProdutoController::class, 'index'])->name('produtos.index');
        Route::post('/produtos', [ProdutoController::class, 'store'])->name('produtos.store');
        Route::get('/produtos/{produto}', [ProdutoController::class, 'show'])->name('produtos.show');
        Route::put('/produtos/{produto}', [ProdutoController::class, 'update'])->name('produtos.update');
        Route::patch('/produtos/{produto}/toggle-status', [ProdutoController::class, 'toggleStatus'])->name('produtos.toggleStatus');
        Route::delete('/produtos/{produto}/imagem/{imagem}', [ProdutoController::class, 'destroyImagem'])->name('produtos.destroyImagem');
    });

    // Rotas exclusivas do Super Administrador
    Route::middleware('nivel:Super Administrador')->group(function () {
        // Coligadas
        Route::get('/coligadas', [ColigadaController::class, 'index'])->name('coligadas.index');
        Route::patch('/coligadas/{coligada}/toggle-status', [ColigadaController::class, 'toggleStatus'])->name('coligadas.toggleStatus');

        // Filiais
        Route::get('/filiais', [FilialController::class, 'index'])->name('filiais.index');
        Route::patch('/filiais/{filial}/toggle-status', [FilialController::class, 'toggleStatus'])->name('filiais.toggleStatus');

        // Níveis
        Route::get('/niveis', [NivelController::class, 'index'])->name('niveis.index');
        Route::post('/niveis', [NivelController::class, 'store'])->name('niveis.store');
        Route::put('/niveis/{nivel}', [NivelController::class, 'update'])->name('niveis.update');
        Route::patch('/niveis/{nivel}/toggle-status', [NivelController::class, 'toggleStatus'])->name('niveis.toggleStatus');

        // Sincronização TOTVS RM (manual via botão)
        Route::post('/sync/coligadas', [SyncController::class, 'sincronizarColigadas'])->name('sync.coligadas');
        Route::post('/sync/filiais', [SyncController::class, 'sincronizarFiliais'])->name('sync.filiais');
    });
});
