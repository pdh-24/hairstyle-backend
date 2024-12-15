<?php

use App\Http\Controllers\BarbershopController;
use App\Http\Controllers\CompatiblefaceController;
use App\Http\Controllers\HaircutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\ScanhistoryController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

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

Route::controller(UserController::class)->group(function () {
    // POST
    Route::post('/user/daftar', 'registrasi');
    Route::post('/user/login', 'login');
    Route::post('/updateuser', 'updateUser');
    Route::post('/setuserlike','setUserLike');
    Route::post('/getuserlike','getUserLike');
    Route::post('/deleteuserlike','deleteUserLike');
    Route::post('/lupapassword', 'lupaPass');
    Route::post('/verifikasiotp',action: 'verifyOtp');
    Route::post('/logout','login');
    
    // GET
    Route::get('/user/{username}', 'getUserByUsername');

    // Middleware
    Route::middleware('auth:api')->get('user', 'getUser');
    Route::middleware('auth:api')->post('logout', 'logout');
});

Route::controller(BarbershopController::class)->group(function () {
    Route::get('/getbarbershop','getBarbershop');
});

Route::controller(CompatiblefaceController::class)->group(function () {
    Route::get('/getcompatibleface','getcompatibleface');
});

Route::controller(HaircutController::class)->group(function () {
    Route::get('/gethaircut','gethaircut');
});

Route::controller(ProductController::class)->group(function () {
    Route::get('/getproduct','getProduct');
});

Route::controller(RecommendationController::class)->group(function () {
    Route::get('/getrekomendasi','getrecommendation');
});

Route::controller(ScanhistoryController::class)->group(function () {
    Route::post('/getscanhistory','getScanhistoryByUserId');
});