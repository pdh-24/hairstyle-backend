<?php

use App\Http\Controllers\BarbershopController;
use App\Http\Controllers\CompatiblefaceController;
use App\Http\Controllers\HaircutController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\ScanhistoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// function () {return response()->noContent();} //Function that is a placeholder for doing nothing post request
Route::get('/', function () {return "Selamat datang di Hairstyle app api";});
Route::redirect('/tesroute', '/');
Route::get('/tesdb', function () {
    try {
        DB::connection()->getPdo();
        return "Connected successfully to remote database!";
    } catch (\Exception $e) {
        return "Could not connect to the database. Please check your configuration.";
    }
});
// Route::post('/user/daftar', [UserController::class, 'registrasi']);
// Route::post('/user/login', [UserController::class, 'login']);
// Route::get('/users/{username}', [UserController::class, 'getUserByUsername']);
Route::controller(UserController::class)->group(function () {
    // POST
    Route::post('/user/daftar', 'registrasi');
    Route::post('/user/login', 'login');
    Route::post('/updateuser', 'updateUser');
    Route::post('/lupapassword', 'lupaPass');
    Route::post('/verifikasiotp',action: 'verifyOtp');
    Route::post('/logout','login');

    // GET
    Route::get('/user/{username}', 'getUserByUsername');
});
Route::controller(ProductController::class)->group(function () {
    Route::post('/getproduct','getProduct');
});
Route::controller(BarbershopController::class)->group(function () {
    Route::post('/getbarbershop','getBarbershop');
});
Route::controller(CompatiblefaceController::class)->group(function () {
    Route::post('/getcompatibleface','getcompatibleface');
});
Route::controller(HaircutController::class)->group(function () {
    Route::post('/gethaircut','gethaircut');
});
Route::controller(RecommendationController::class)->group(function () {
    Route::post('/getrekomendasi','getrecommendation');
});
Route::controller(ScanhistoryController::class)->group(function () {
    Route::post('/getscanhistory','getscanhistory');
});
Route::post('/product', function () {return response()->noContent();});
Route::post('/haircut', function () {return response()->noContent();});
Route::post('/face', function () {return response()->noContent();});
Route::post('/scan', function () {return response()->noContent();});
Route::post('/recommendation', function () {return response()->noContent();});
Route::post('/barbershop', function () {return response()->noContent();});
