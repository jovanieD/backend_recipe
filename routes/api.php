<?php

use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\PurchasedRecipeController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [UserController::class, 'login']);

Route::post('/register', [UserController::class, 'store']);

Route::get('/sale', [RecipeController::class, 'index']);

Route::get('/free', [RecipeController::class, 'free']);

Route::post('/recipes', [RecipeController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/users/{id}', [UserController::class, 'authuser']);

    Route::get('/home', [RecipeController::class, 'index']); //this is for all recipes available

    Route::get('/search/{id}', [RecipeController::class, "searchById"]);

    Route::get('/search/tag/{tag}', [RecipeController::class, "searchbyTag"]);

    Route::get('/search/category/{cat}', [RecipeController::class, "searchbyCategory"]);

    // Route::get('/search/{tag}', [RecipeController::class, "searchbyTag"]);


    Route::put('/recipes/{id}', [RecipeController::class, 'update']);
    Route::delete('/recipes/{id}', [RecipeController::class, 'destroy']);

    //this is for bookmarks

    Route::get('/bookmarks', [BookmarkController::class, 'index']);  //this is to show the users Bookmarks
    Route::post('/bookmarks', [BookmarkController::class, 'store']);
    Route::delete('/bookmarks/{id}', [BookmarkController::class, 'destroy']);

    //this is for display the purchased recipe

    Route::get('/purchases', [PurchasedRecipeController::class, 'index']); //this is only for log in user

    //this is for the payment of the recipe

    Route::get('createpaypal', [PaypalController::class, 'createpaypal'])->name('createpaypal');
    Route::get('processpaypal', [PaypalController::class, 'processpaypal'])->name('processpaypal');
    Route::get('processSuccess', [PaypalController::class, 'processSuccess'])->name('processSuccess');
    Route::get('processCancel', [PaypalController::class, 'processCancel'])->name('processCancel');

    Route::get('/logout', [UserController::class, 'logout']);
});
