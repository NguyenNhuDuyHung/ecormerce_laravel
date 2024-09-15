<?php

use App\Http\Controllers\Ajax\LocationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Ajax\DashboardController as AjaxDashboardController;
use App\Http\Controllers\Ajax\AttributeController as AjaxAttributeController;
use App\Http\Controllers\Backend\GenerateController;
use App\Http\Controllers\Backend\LanguageController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\PostCatalogueController;
use App\Http\Controllers\Backend\PostController;
use App\Http\Controllers\Backend\UserCatalogueController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\ProductCatalogueController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\AttributeCatalogueController;
use App\Http\Controllers\Backend\AttributeController;
//@@useController@@






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
Route::group(['middleware' => ['admin', 'locale', 'default_backend_locale']], function () {
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
        Route::get('permission', [UserCatalogueController::class, 'permission'])->name('user.catalogue.permission');
        Route::post('updatePermission', [UserCatalogueController::class, 'updatePermission'])->name('user.catalogue.updatePermission');
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
        Route::get(
            'translate/{id}/{languageId}/{model}',
            [LanguageController::class, 'translate']
        )
            ->where('id', '[0-9]+')
            ->where('languageId', '[0-9]+')->name('language.translate');
        Route::post('storeTranslate', [LanguageController::class, 'storeTranslate'])->name('language.storeTranslate');
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

    Route::group(['prefix' => 'generate'], function () {
        Route::get('index', [GenerateController::class, 'index'])->name('generate.index');
        Route::get('create', [GenerateController::class, 'create'])->name('generate.create');
        Route::post('store', [GenerateController::class, 'store'])->name('generate.store');
        Route::get('edit/{id}', [GenerateController::class, 'edit'])->where('id', '[0-9]+')->name('generate.edit');
        Route::post('update/{id}', [GenerateController::class, 'update'])->where('id', '[0-9]+')->name('generate.update');
        Route::get('delete/{id}', [GenerateController::class, 'delete'])->where('id', '[0-9]+')->name('generate.delete');
        Route::post('destroy/{id}', [GenerateController::class, 'destroy'])->where('id', '[0-9]+')->name('generate.destroy');
    });

    Route::group(['prefix' => 'product/catalogue'], function () {
    Route::get('index', [ProductCatalogueController::class, 'index'])->name('product.catalogue.index');
    Route::get('create', [ProductCatalogueController::class, 'create'])->name('product.catalogue.create');
    Route::post('store', [ProductCatalogueController::class, 'store'])->name('product.catalogue.store');
    Route::get('edit/{id}', [ProductCatalogueController::class, 'edit'])->where(['id' => '[0-9]+'])->name('product.catalogue.edit');
    Route::post('update/{id}', [ProductCatalogueController::class, 'update'])->where(['id' => '[0-9]+'])->name('product.catalogue.update');
    Route::get('delete/{id}', [ProductCatalogueController::class, 'delete'])->where(['id' => '[0-9]+'])->name('product.catalogue.delete');
    Route::post('destroy/{id}', [ProductCatalogueController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('product.catalogue.destroy');
});
Route::group(['prefix' => 'product'], function () {
    Route::get('index', [ProductController::class, 'index'])->name('product.index');
    Route::get('create', [ProductController::class, 'create'])->name('product.create');
    Route::post('store', [ProductController::class, 'store'])->name('product.store');
    Route::get('edit/{id}', [ProductController::class, 'edit'])->where(['id' => '[0-9]+'])->name('product.edit');
    Route::post('update/{id}', [ProductController::class, 'update'])->where(['id' => '[0-9]+'])->name('product.update');
    Route::get('delete/{id}', [ProductController::class, 'delete'])->where(['id' => '[0-9]+'])->name('product.delete');
    Route::post('destroy/{id}', [ProductController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('product.destroy');
});
Route::group(['prefix' => 'attribute/catalogue'], function () {
    Route::get('index', [AttributeCatalogueController::class, 'index'])->name('attribute.catalogue.index');
    Route::get('create', [AttributeCatalogueController::class, 'create'])->name('attribute.catalogue.create');
    Route::post('store', [AttributeCatalogueController::class, 'store'])->name('attribute.catalogue.store');
    Route::get('edit/{id}', [AttributeCatalogueController::class, 'edit'])->where(['id' => '[0-9]+'])->name('attribute.catalogue.edit');
    Route::post('update/{id}', [AttributeCatalogueController::class, 'update'])->where(['id' => '[0-9]+'])->name('attribute.catalogue.update');
    Route::get('delete/{id}', [AttributeCatalogueController::class, 'delete'])->where(['id' => '[0-9]+'])->name('attribute.catalogue.delete');
    Route::post('destroy/{id}', [AttributeCatalogueController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('attribute.catalogue.destroy');
});
Route::group(['prefix' => 'attribute'], function () {
    Route::get('index', [AttributeController::class, 'index'])->name('attribute.index');
    Route::get('create', [AttributeController::class, 'create'])->name('attribute.create');
    Route::post('store', [AttributeController::class, 'store'])->name('attribute.store');
    Route::get('edit/{id}', [AttributeController::class, 'edit'])->where(['id' => '[0-9]+'])->name('attribute.edit');
    Route::post('update/{id}', [AttributeController::class, 'update'])->where(['id' => '[0-9]+'])->name('attribute.update');
    Route::get('delete/{id}', [AttributeController::class, 'delete'])->where(['id' => '[0-9]+'])->name('attribute.delete');
    Route::post('destroy/{id}', [AttributeController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('attribute.destroy');
});
//@@new-module@@




    // Ajax 
    Route::get('ajax/location/getLocation', [LocationController::class, 'getLocation'])->name('ajax.location.getLocation');
    Route::post('ajax/dashboard/changeStatus', [AjaxDashboardController::class, 'changeStatus'])->name('ajax.dashboard.changeStatus');
    Route::post('ajax/dashboard/changeStatusAll', [AjaxDashboardController::class, 'changeStatusAll'])->name('ajax.dashboard.changeStatusAll');
    Route::get('ajax/attribute/getAttribute', [AjaxAttributeController::class, 'getAttribute'])->name('ajax.attribute.getAttribute');
    Route::get('ajax/attribute/loadAttribute', [AjaxAttributeController::class, 'loadAttribute'])->name('ajax.attribute.loadAttribute');

});


Route::get('admin', [AuthController::class, 'index'])->name('auth.admin')->middleware('login');
Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
