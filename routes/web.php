<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\DashboardController;

// Página inicial redireciona pro resumo, mas só para usuários autenticados
Route::get('/', function () {
    return redirect()->route('resumo.index');
})->middleware('auth');

// Grupo protegido por autenticação
Route::middleware('auth')->group(function () {

    Route::get('/resumo', [DashboardController::class, 'index'])->name('resumo.index');

    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /// ROTAS DE RECEITAS (ENTRADAS)
    Route::get('/receitas', [IncomeController::class, 'index'])->name('receitas.index');
    Route::get('/receitas/criar', [IncomeController::class, 'create'])->name('receitas.create');
    Route::post('/receitas', [IncomeController::class, 'store'])->name('receitas.store');

    /// ROTAS DE DESPESAS
    Route::get('/despesas', [ExpenseController::class, 'index'])->name('despesas.index');
    Route::get('/despesas/criar', [ExpenseController::class, 'create'])->name('despesas.create');
    Route::post('/despesas', [ExpenseController::class, 'store'])->name('despesas.store');
    Route::delete('/despesas/{expense}', [ExpenseController::class, 'destroy'])->name('despesas.destroy');

    /// ROTAS DE PARCELAS DE UMA DESPESA ESPECÍFICA
    Route::get('/despesas/{expense}/parcelas', [InstallmentController::class, 'index'])
        ->name('parcelas.index');

    Route::post('/parcelas/{installment}/pagar', [InstallmentController::class, 'pay'])
        ->name('parcelas.pay');

    // BANCOS
    Route::get('/bancos', [BankController::class, 'index'])->name('bancos.index');
    Route::get('/bancos/criar', [BankController::class, 'create'])->name('bancos.create');
    Route::post('/bancos', [BankController::class, 'store'])->name('bancos.store');
});

// Rotas de autenticação geradas pelo Breeze
require __DIR__ . '/auth.php';
