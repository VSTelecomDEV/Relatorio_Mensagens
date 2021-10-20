<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Get_Msgs;
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

Route::get("/get_xls", [Get_Msgs::class, "export_Xls"])->name("api.relatorio");

Route::get("/get_msg", [Get_Msgs::class, "contagem_Msg"])->name("api.contmsg");
