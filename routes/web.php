<?php

use App\Http\Controllers\TodoListController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

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
    $table  = "todolist";
    $checkTable = Schema::hasTable($table);
    if(!$checkTable)
    {
        Artisan::call("migrate");
        return redirect("todolist");
    }
    else
    {
        return redirect("todolist");
    }
})->name("home");

Route::resource('todolist', TodoListController::class); 
