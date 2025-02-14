<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CandidateAuthController;
use App\Http\Controllers\CandidateController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/policy-form', [App\Http\Controllers\CandidateController::class, 'policyForm'])->name('policy.form');
Route::POST('/submit-policy-form', [App\Http\Controllers\CandidateController::class, 'submitPolicyForm'])->name('submit.policy.form');
Route::get('/message-page', [App\Http\Controllers\CandidateController::class, 'thankyouPage'])->name('thankyou.page');

Route::prefix('candidate')->group(function () {
    Route::get('login', [CandidateAuthController::class, 'showLoginForm'])->name('candidate.login');
    Route::post('login', [CandidateAuthController::class, 'login']);
    Route::post('logout', [CandidateAuthController::class, 'logout'])->name('candidate.logout');
    Route::resource('candidates', CandidateController::class);
    Route::get('change-password', [CandidateAuthController::class, 'password'])->name('candidate.password.index');
    Route::put('update-password', [CandidateAuthController::class, 'updatePassword'])->name('candidate.password.update');

});