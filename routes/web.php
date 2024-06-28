<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Home;
use App\Http\Controllers\PetaController;
use Illuminate\Support\Facades\Route;

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

Route::post('/doLogin', [AuthController::class, 'doLogin'])->name('doLogin');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', [AuthController::class, 'index'])->middleware('guest')->name('login');
Route::get('/register', [AuthController::class, 'register'])->middleware('guest')->name('register');
Route::post('/doRegist', [AuthController::class, 'doRegist'])->middleware('guest')->name('doRegist');

Route::get('/home', [Home::class, 'index'])->middleware('auth')->name('home');
Route::get('/informasi', [Home::class, 'informasi'])->middleware('auth')->name('informasi');
Route::get('/visualisasiPeta', [PetaController::class, 'index'])->middleware('auth')->name('peta');
