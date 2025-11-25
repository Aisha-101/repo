<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategorijaController;
use App\Http\Controllers\KnygaController;
use App\Http\Controllers\RekomendacijaController;
use App\Http\Controllers\AuthController;

// Viešas turinys – visi naudotojai
Route::get('kategorijos', [KategorijaController::class, 'index']); // Viešos kategorijos
Route::get('knygos', [KnygaController::class, 'index']); // Populiariausios knygos
Route::get('/kategorijos/{kategorijaId}/knygos/{knygaId}/rekomendacijos', 
    [KnygaController::class, 'rekomendacijosPagalKategorija']); // Viešos rekomendacijos

// Auth susiję route'ai – prisijungimas / registracija / atnaujinimas
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('refresh', [AuthController::class, 'refresh']);

// Registruoti naudotojai – auth middleware
Route::middleware(['auth.jwt'])->group(function () {

    // Naudotojo informacija ir atsijungimas
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    // Naudotojas gali tvarkyti savo knygas
    Route::apiResource('knygos', KnygaController::class)
        ->except(['index', 'show']); // Index ir Show vieši, likę protected

    // Naudotojas gali kurti ir tvarkyti savo rekomendacijas
    Route::apiResource('rekomendacijos', RekomendacijaController::class)
        ->except(['index', 'show']); // Vieši per viešą route

    // Vertinimas kitų rekomendacijų (jei reikia atskirai)
    // Route::post('rekomendacijos/{id}/ivertinti', ...);
});

// Administratorius – role middleware
Route::middleware(['auth.jwt', 'role:admin'])->group(function () {

    // Tvarkyti kategorijas (CRUD)
    Route::apiResource('kategorijos', KategorijaController::class)
        ->except(['index', 'show']); // Vieši GET metodai, admin CRUD

    // Tvarkyti naudotojus ir patvirtinti registracijas
    Route::post('users/{id}/approve', [AuthController::class, 'approveUser']);

    // Patvirtinti rekomendacijas
    Route::post('rekomendacijos/{id}/approve', [RekomendacijaController::class, 'approve']);

    // Administratorius gali šalinti bet kokias knygas ar rekomendacijas
    Route::delete('knygos/{id}/admin-delete', [KnygaController::class, 'adminDelete']);
    Route::delete('rekomendacijos/{id}/admin-delete', [RekomendacijaController::class, 'adminDelete']);
});
