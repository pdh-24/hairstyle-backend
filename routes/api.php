<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UserController;

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
// Route::post('/', 'function (Request $request) {}');
Route::get('/tesroute', function () {
    return "Selamat datang di Hairstyle app api";
});
Route::get('/tesdb', function () {
    try {
        DB::connection()->getPdo();
        return "Connected successfully to remote database!";
    } catch (\Exception $e) {
        return "Could not connect to the database. Please check your configuration.";
    }
});
// Route::get('/getuser/{id}', function ($id) {
//     $user = DB::table('users')
//                 ->where('id', $id)
//                 ->select('username')
//                 ->first();
//     return "Selamat datang di Hairstyle app api\nUsername dengan id {$id} adalah {$user->username}";
// });
Route::post('/user', function () {return response()->noContent();});
Route::get('/users/{username}', [UserController::class, 'getUserByUsername']);
Route::post('/product', function () {return response()->noContent();});
Route::post('/hairstyle', function () {return response()->noContent();});
Route::post('/face', function () {return response()->noContent();});
Route::post('/scan', function () {return response()->noContent();});
Route::post('/recommendation', function () {return response()->noContent();});
Route::post('/barbershop', function () {return response()->noContent();});
