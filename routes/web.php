<?php

use App\Http\Controllers\Ajax\LocationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Ajax\DashboardController as AjaxDashboardController;
use App\Http\Controllers\Backend\LanguageController;
use App\Http\Controllers\Backend\PostCatalogueController;
use App\Http\Controllers\Backend\UserCatalogueController;
use App\Http\Controllers\Backend\UserController;

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

// Backend Routes
Route::get('dashboard/index', [DashboardController::class, 'index'])->name('dashboard.index')->middleware('admin');

// User
Route::group(['prefix' => 'user'], function () {
    Route::get('index', [UserController::class, 'index'])->name('user.index')->middleware('admin');
    Route::get('create', [UserController::class, 'create'])->name('user.create')->middleware('admin');
    Route::post('store', [UserController::class, 'store'])->name('user.store')->middleware('admin');
    Route::get('edit/{id}', [UserController::class, 'edit'])->where('id', '[0-9]+')->name('user.edit')->middleware('admin');
    Route::post('update/{id}', [UserController::class, 'update'])->where('id', '[0-9]+')->name('user.update')->middleware('admin');
    Route::get('delete/{id}', [UserController::class, 'delete'])->where('id', '[0-9]+')->name('user.delete')->middleware('admin');
    Route::post('destroy/{id}', [UserController::class, 'destroy'])->where('id', '[0-9]+')->name('user.destroy')->middleware('admin');
});

Route::group(['prefix' => 'user/catalogue'], function () {
    Route::get('index', [UserCatalogueController::class, 'index'])->name('user.catalogue.index')->middleware('admin');
    Route::get('create', [UserCatalogueController::class, 'create'])->name('user.catalogue.create')->middleware('admin');
    Route::post('store', [UserCatalogueController::class, 'store'])->name('user.catalogue.store')->middleware('admin');
    Route::get('edit/{id}', [UserCatalogueController::class, 'edit'])->where('id', '[0-9]+')->name('user.catalogue.edit')->middleware('admin');
    Route::post('update/{id}', [UserCatalogueController::class, 'update'])->where('id', '[0-9]+')->name('user.catalogue.update')->middleware('admin');
    Route::get('delete/{id}', [UserCatalogueController::class, 'delete'])->where('id', '[0-9]+')->name('user.catalogue.delete')->middleware('admin');
    Route::post('destroy/{id}', [UserCatalogueController::class, 'destroy'])->where('id', '[0-9]+')->name('user.catalogue.destroy')->middleware('admin');
});

Route::group(['prefix' => 'language'], function () {
    Route::get('index', [LanguageController::class, 'index'])->name('language.index')->middleware('admin');
    Route::get('create', [LanguageController::class, 'create'])->name('language.create')->middleware('admin');
    Route::post('store', [LanguageController::class, 'store'])->name('language.store')->middleware('admin');
    Route::get('edit/{id}', [LanguageController::class, 'edit'])->where('id', '[0-9]+')->name('language.edit')->middleware('admin');
    Route::post('update/{id}', [LanguageController::class, 'update'])->where('id', '[0-9]+')->name('language.update')->middleware('admin');
    Route::get('delete/{id}', [LanguageController::class, 'delete'])->where('id', '[0-9]+')->name('language.delete')->middleware('admin');
    Route::post('destroy/{id}', [LanguageController::class, 'destroy'])->where('id', '[0-9]+')->name('language.destroy')->middleware('admin');
});


Route::group(['prefix' => 'post/catalogue'], function () {
    Route::get('index', [PostCatalogueController::class, 'index'])->name('post.catalogue.index')->middleware('admin');
    Route::get('create', [PostCatalogueController::class, 'create'])->name('post.catalogue.create')->middleware('admin');
    Route::post('store', [PostCatalogueController::class, 'store'])->name('post.catalogue.store')->middleware('admin');
    Route::get('edit/{id}', [PostCatalogueController::class, 'edit'])->where('id', '[0-9]+')->name('post.catalogue.edit')->middleware('admin');
    Route::post('update/{id}', [PostCatalogueController::class, 'update'])->where('id', '[0-9]+')->name('post.catalogue.update')->middleware('admin');
    Route::get('delete/{id}', [PostCatalogueController::class, 'delete'])->where('id', '[0-9]+')->name('post.catalogue.delete')->middleware('admin');
    Route::post('destroy/{id}', [PostCatalogueController::class, 'destroy'])->where('id', '[0-9]+')->name('post.catalogue.destroy')->middleware('admin');
});

// Ajax 
Route::get('ajax/location/getLocation', [LocationController::class, 'getLocation'])->name('ajax.location.getLocation')->middleware('admin');
Route::post('ajax/dashboard/changeStatus', [AjaxDashboardController::class, 'changeStatus'])->name('ajax.dashboard.changeStatus')->middleware('admin');
Route::post('ajax/dashboard/changeStatusAll', [AjaxDashboardController::class, 'changeStatusAll'])->name('ajax.dashboard.changeStatusAll')->middleware('admin');

Route::get('admin', [AuthController::class, 'index'])->name('auth.admin')->middleware('login');
Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
