<?php

use App\Http\Controllers\Ajax\LocationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Ajax\DashboardController as AjaxDashboardController;
use App\Http\Controllers\Backend\LanguageController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\PostCatalogueController;
use App\Http\Controllers\Backend\PostController;
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
Route::group(['middleware' => ['admin', 'locale']], function () {
    Route::get('dashboard/index', [DashboardController::class, 'index'])->name('dashboard.index');

    // User
    Route::group(['prefix' => 'user'], function () {
        Route::get('index', [UserController::class, 'index'])->name('user.index');
        Route::get('create', [UserController::class, 'create'])->name('user.create');
        Route::post('store', [UserController::class, 'store'])->name('user.store');
        Route::get('edit/{id}', [UserController::class, 'edit'])->where('id', '[0-9]+')->name('user.edit');
        Route::post('update/{id}', [UserController::class, 'update'])->where('id', '[0-9]+')->name('user.update');
        Route::get('delete/{id}', [UserController::class, 'delete'])->where('id', '[0-9]+')->name('user.delete');
        Route::post('destroy/{id}', [UserController::class, 'destroy'])->where('id', '[0-9]+')->name('user.destroy');
    });

    Route::group(['prefix' => 'user/catalogue'], function () {
        Route::get('index', [UserCatalogueController::class, 'index'])->name('user.catalogue.index');
        Route::get('create', [UserCatalogueController::class, 'create'])->name('user.catalogue.create');
        Route::post('store', [UserCatalogueController::class, 'store'])->name('user.catalogue.store');
        Route::get('edit/{id}', [UserCatalogueController::class, 'edit'])->where('id', '[0-9]+')->name('user.catalogue.edit');
        Route::post('update/{id}', [UserCatalogueController::class, 'update'])->where('id', '[0-9]+')->name('user.catalogue.update');
        Route::get('delete/{id}', [UserCatalogueController::class, 'delete'])->where('id', '[0-9]+')->name('user.catalogue.delete');
        Route::post('destroy/{id}', [UserCatalogueController::class, 'destroy'])->where('id', '[0-9]+')->name('user.catalogue.destroy');
    });

    Route::group(['prefix' => 'language'], function () {
        Route::get('index', [LanguageController::class, 'index'])->name('language.index');
        Route::get('create', [LanguageController::class, 'create'])->name('language.create');
        Route::post('store', [LanguageController::class, 'store'])->name('language.store');
        Route::get('edit/{id}', [LanguageController::class, 'edit'])->where('id', '[0-9]+')->name('language.edit');
        Route::post('update/{id}', [LanguageController::class, 'update'])->where('id', '[0-9]+')->name('language.update');
        Route::get('delete/{id}', [LanguageController::class, 'delete'])->where('id', '[0-9]+')->name('language.delete');
        Route::post('destroy/{id}', [LanguageController::class, 'destroy'])->where('id', '[0-9]+')->name('language.destroy');
        Route::get('switch/{id}', [LanguageController::class, 'switchBackendLanguage'])->where('id', '[0-9]+')->name('language.switch');
    });


    Route::group(['prefix' => 'post/catalogue'], function () {
        Route::get('index', [PostCatalogueController::class, 'index'])->name('post.catalogue.index');
        Route::get('create', [PostCatalogueController::class, 'create'])->name('post.catalogue.create');
        Route::post('store', [PostCatalogueController::class, 'store'])->name('post.catalogue.store');
        Route::get('edit/{id}', [PostCatalogueController::class, 'edit'])->where('id', '[0-9]+')->name('post.catalogue.edit');
        Route::post('update/{id}', [PostCatalogueController::class, 'update'])->where('id', '[0-9]+')->name('post.catalogue.update');
        Route::get('delete/{id}', [PostCatalogueController::class, 'delete'])->where('id', '[0-9]+')->name('post.catalogue.delete');
        Route::post('destroy/{id}', [PostCatalogueController::class, 'destroy'])->where('id', '[0-9]+')->name('post.catalogue.destroy');
    });

    Route::group(['prefix' => 'post'], function () {
        Route::get('index', [PostController::class, 'index'])->name('post.index');
        Route::get('create', [PostController::class, 'create'])->name('post.create');
        Route::post('store', [PostController::class, 'store'])->name('post.store');
        Route::get('edit/{id}', [PostController::class, 'edit'])->where('id', '[0-9]+')->name('post.edit');
        Route::post('update/{id}', [PostController::class, 'update'])->where('id', '[0-9]+')->name('post.update');
        Route::get('delete/{id}', [PostController::class, 'delete'])->where('id', '[0-9]+')->name('post.delete');
        Route::post('destroy/{id}', [PostController::class, 'destroy'])->where('id', '[0-9]+')->name('post.destroy');
    });

    Route::group(['prefix' => 'permission'], function () {
        Route::get('index', [PermissionController::class, 'index'])->name('permission.index');
        Route::get('create', [PermissionController::class, 'create'])->name('permission.create');
        Route::post('store', [PermissionController::class, 'store'])->name('permission.store');
        Route::get('edit/{id}', [PermissionController::class, 'edit'])->where('id', '[0-9]+')->name('permission.edit');
        Route::post('update/{id}', [PermissionController::class, 'update'])->where('id', '[0-9]+')->name('permission.update');
        Route::get('delete/{id}', [PermissionController::class, 'delete'])->where('id', '[0-9]+')->name('permission.delete');
        Route::post('destroy/{id}', [PermissionController::class, 'destroy'])->where('id', '[0-9]+')->name('permission.destroy');
    });

    // Ajax 
    Route::get('ajax/location/getLocation', [LocationController::class, 'getLocation'])->name('ajax.location.getLocation');
    Route::post('ajax/dashboard/changeStatus', [AjaxDashboardController::class, 'changeStatus'])->name('ajax.dashboard.changeStatus');
    Route::post('ajax/dashboard/changeStatusAll', [AjaxDashboardController::class, 'changeStatusAll'])->name('ajax.dashboard.changeStatusAll');
});


Route::get('admin', [AuthController::class, 'index'])->name('auth.admin')->middleware('login');
Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
