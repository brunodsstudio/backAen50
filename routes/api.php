<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\WritersController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\GaleriaController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\AtracaoController;
use App\Http\Controllers\ConcursoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Auth routes
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api');

Route::get('/areas', [AreaController::class, 'index']);
Route::get('/areas/{id}', [AreaController::class, 'show']);
Route::post('/areas', [AreaController::class, 'store']);
Route::put('/areas/{id}', [AreaController::class, 'update']);
Route::delete('/areas/{id}', [AreaController::class, 'destroy']);

Route::group(['prefix' => 'materias'], function () {
    Route::get('/', [MateriaController::class, 'index']);
    Route::get('/{id}', [MateriaController::class, 'show']);
    Route::post('/', [MateriaController::class, 'store']);
    Route::put('/{id}', [MateriaController::class, 'update']);
    Route::delete('/{id}', [MateriaController::class, 'destroy']);
});

// Matérias Home route
Route::get('/MateriasHome', [MateriaController::class, 'materiasHome']);

// Matérias por Categoria route
Route::get('/MateriasCategoria', [MateriaController::class, 'materiasCategoria']);

// Matéria por LinkTitulo
Route::get('/MateriaByLink/{linkTitulo}', [MateriaController::class, 'getByLinkTitulo']);

// Matérias por Tag
Route::get('/MateriasByTag/{tag}', [MateriaController::class, 'getByTag']);

// Resumo das Tags mais usadas
Route::get('/materias/tags/summary', [MateriaController::class, 'getTagsSummary']);

// Images routes
Route::get('/materias/{materiaId}/images', [ImagesController::class, 'index']);
Route::get('/images/{id}', [ImagesController::class, 'show']);
Route::post('/materias/{materiaId}/images', [ImagesController::class, 'store']);
Route::post('/materias/{materiaId}/images/featured-editor', [ImagesController::class, 'storeFeaturedEditor']);
Route::post('/materias/{materiaId}/images/batch', [ImagesController::class, 'storeBatch']);
Route::put('/images/{id}', [ImagesController::class, 'update']);
Route::delete('/images/{id}', [ImagesController::class, 'destroy']);
Route::delete('/materias/{materiaId}/images/batch', [ImagesController::class, 'deleteBatch']);

// Writers routes
Route::get('/writers', [WritersController::class, 'index']);
Route::get('/writers/{id}', [WritersController::class, 'show']);
Route::post('/writers', [WritersController::class, 'store']);
Route::put('/writers/{id}', [WritersController::class, 'update']);
Route::delete('/writers/{id}', [WritersController::class, 'destroy']);

// Videos routes
Route::group(['prefix' => 'videos'], function () {
    Route::get('/', [VideoController::class, 'index']);
    Route::get('/home', [VideoController::class, 'getVideosHome']);
    Route::get('/youtube/{videoId}', [VideoController::class, 'getByVideoId']);
    Route::get('/materia/{materiaId}', [VideoController::class, 'getByMateria']);
    Route::get('/area/{areaId}', [VideoController::class, 'getByArea']);
    Route::get('/{id}', [VideoController::class, 'show']);
    Route::post('/', [VideoController::class, 'store']);
    Route::put('/{id}', [VideoController::class, 'update']);
    Route::delete('/{id}', [VideoController::class, 'destroy']);
});

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Galeria routes - Acesso público
Route::get('/Galerias/{pastaS3}/{pagina}', [GaleriaController::class, 'fetchGaleria'])
    ->where(['pagina' => '[0-9]+']);

// Events routes - Acesso público
Route::prefix('events')->group(function () {
    // Eventos principais
    Route::get('/', [EventoController::class, 'index']);
    Route::post('/', [EventoController::class, 'store']);
    Route::get('/{id}', [EventoController::class, 'show']);
    Route::put('/{id}', [EventoController::class, 'update']);
    Route::delete('/{id}', [EventoController::class, 'destroy']);

    // Atrações
    Route::get('/atracoes', [AtracaoController::class, 'index']);
    Route::post('/atracoes', [AtracaoController::class, 'store']);
    Route::get('/atracoes/{id}', [AtracaoController::class, 'show']);
    Route::put('/atracoes/{id}', [AtracaoController::class, 'update']);
    Route::delete('/atracoes/{id}', [AtracaoController::class, 'destroy']);

    // Concursos
    Route::get('/concursos', [ConcursoController::class, 'index']);
    Route::post('/concursos', [ConcursoController::class, 'store']);
    Route::get('/concursos/{id}', [ConcursoController::class, 'show']);
    Route::put('/concursos/{id}', [ConcursoController::class, 'update']);
    Route::delete('/concursos/{id}', [ConcursoController::class, 'destroy']);
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);

});