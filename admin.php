<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\DocumentTypeController;
use App\Http\Controllers\Admin\QuestionController;


Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');

    // profile
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile-update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('change-password', [ProfileController::class, 'password'])->name('password.index');
    Route::put('update-password', [ProfileController::class, 'updatePassword'])->name('password.update');

    Route::resource('questions', QuestionController::class);
    Route::resource('documenttypes', DocumentTypeController::class);
    Route::resource('candidates', CandidateController::class);
    Route::get('candidates/{candidate}/convert', [CandidateController::class, 'showConvertToEmployeeForm'])->name('candidates.convert');
    Route::post('candidates/{candidate}/convert', [CandidateController::class, 'convertToEmployeeSubmit'])->name('candidates.convert.submit');
    Route::post('candidate/documents/download', [CandidateController::class, 'downloadAll'])->name('candidates.download.documents');
    Route::resource('users', UserController::class);
    Route::get('user-ban-unban/{id}/{status}', [UserController::class, 'banUnban'])->name('user.banUnban');
    Route::get('user-review-status/{id}/{status}', [UserController::class, 'reviewForm'])->name('user.review.status');
    Route::get('users/{user}/questions-response', [UserController::class, 'viewQuestionsResponse'])->name('user.questions.response');

    Route::get('user-send-questions/{id}', [UserController::class, 'sendQuestions'])->name('user.send.questions');
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::post('/candidates/{candidateId}/verify', [CandidateController::class, 'verifyDocument']);
});
