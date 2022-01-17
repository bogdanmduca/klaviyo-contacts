<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\TrackerController;
use App\Http\Controllers\UploadedContactController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware(['auth'])->group(function () {
    Route::resource('contacts', ContactController::class);
    Route::get('uploaded-contacts', [UploadedContactController::class, 'create'])->name('uploaded-contacts.create');
    Route::post('uploaded-contacts', [UploadedContactController::class, 'store'])->name('uploaded-contacts.store');
    Route::post('tracker', [TrackerController::class, 'store'])->name('tracker.store');
});
