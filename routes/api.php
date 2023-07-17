<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ListaController;

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


Route::middleware(['cors'])->group(function () {
    Route::get('/categories/find_by_name', [CategoryController::class, 'find_by_name']);
    Route::get('/items/find_by_name', [ItemController::class, 'find_by_name']);
    Route::get('/lists/find_by_name', [ListaController::class, 'find_by_name']);

    Route::get('/items/get_top_items_categories', [ItemController::class, 'get_top_items_categories']);

    Route::get('/lists/find_by_user_id', [ListaController::class, 'find_by_user_id']);

    Route::get('/lists/get_number_items_by_month', [ListaController::class, 'get_number_items_by_month']);

    Route::get('/lists/find_items_grouped_by_category', [ListaController::class, 'find_items_grouped_by_category']);

    Route::get('/lists/find_items/{id}', [ListaController::class, 'find_items']);

    Route::get('/lists/find_lists_by_month_year', [ListaController::class, 'find_lists_by_month_year']);

    Route::get('/categories/get_items', [CategoryController::class, 'get_items']);

    Route::post('/lists/set_active_list/{list_id}',[ListaController::class, 'set_active_list']);

    Route::post('/lists/cancel_complete_list',[ListaController::class, 'cancel_complete_list']);

    Route::post('/lists/add_item_to_list/{item_id}/{list_id}',[ListaController::class, 'add_item_to_list']);

    Route::put('/lists/update_list_items', [ListaController::class, 'update_list_items']);

    Route::put('/lists/update_item_quantity', [ListaController::class, 'update_item_quantity']);

    Route::put('/lists', [ListaController::class, 'update']);

    Route::delete('/lists/remove_item_from_list', [ListaController::class, 'remove_item_from_list']);

    Route::resource('items',ItemController::class);

    Route::resource('categories',CategoryController::class);

    Route::resource('lists',ListaController::class);

});