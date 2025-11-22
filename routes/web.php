<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\LiveHormann;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\PackingUnit1Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
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


Route::group(['middleware' => 'auth'], function () {

	Route::get('/', [HomeController::class, 'home']);
	Route::get('dashboard', function () {return view('dashboard');})->name('dashboard');
	Route::get('dashboard/finishGoodP1', [HomeController::class, 'finishGoodP1'])->name('dashboard.finishGoodP1');
	Route::get('dashboard/finishGoodP2', [HomeController::class, 'finishGoodP2'])->name('dashboard.finishGoodP2');
	
	Route::get('packing-unit1', function () {return view('packing-unit1');})->name('packing-unit1');
	Route::get('packing-unit1/getLiveScada', [PackingUnit1Controller::class, 'getLiveScada'])->name('packing-unit1.getLiveScada');
	Route::get('packing-unit1/getRecapMachine', [PackingUnit1Controller::class, 'getRecapMachine'])->name('packing-unit1.getRecapMachine');
	
	Route::get('live-hormann', function () {return view('live-hormann');})->name('live-hormann');
	Route::get('live-hormann/getLiveWhUnit1', [LiveHormann::class, 'getLiveWhUnit1'])->name('live-hormann.getLiveWhUnit1');
	Route::get('live-hormann/getLiveWhUnit2', [LiveHormann::class, 'getLiveWhUnit2'])->name('live-hormann.getLiveWhUnit2');
	Route::get('live-hormann/getLiveDC', [LiveHormann::class, 'getLiveDC'])->name('live-hormann.getLiveDC');
	
	Route::get('billing', function () {
		return view('billing');
	})->name('billing');

	Route::get('profile', function () {
		return view('profile');
	})->name('profile');

	Route::get('rtl', function () {
		return view('rtl');
	})->name('rtl');

	Route::get('user-management', function () {
		return view('laravel-examples/user-management');
	})->name('user-management');

	Route::get('tables', function () {
		return view('tables');
	})->name('tables');

	Route::get('virtual-reality', function () {
		return view('virtual-reality');
	})->name('virtual-reality');

	Route::get('static-sign-in', function () {
		return view('static-sign-in');
	})->name('sign-in');

	Route::get('static-sign-up', function () {
		return view('static-sign-up');
	})->name('sign-up');

	Route::get('/logout', [SessionsController::class, 'destroy']);
	Route::get('/user-profile', [InfoUserController::class, 'create']);
	Route::post('/user-profile', [InfoUserController::class, 'store']);
	Route::get('/login', function () {
		return view('dashboard');
	})->name('sign-up');
});

Route::group(['middleware' => 'guest'], function () {
	Route::get('/register', [RegisterController::class, 'create']);
	Route::post('/register', [RegisterController::class, 'store']);
	Route::get('/login', [SessionsController::class, 'create']);
	Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});

Route::get('/login', function () {
	return view('session/login-session');
})->name('login');
