<?php

use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\DetalleFacturaController;
use App\Http\Controllers\ClienteController;
//use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    //RUTAS ESPECIFICAS imagenes
    //Route::post('/user/upload',[UserController::class,'uploadImage']);
    //Route::get('/user/getimage/{filename}',[UserController::class,'getImage']);
    //RUTAS AUTOMATICAS RESTful
    Route::resource('/empleado', EmpleadoController::class,['except'=>['create','edit']]);
    Route::resource('/factura', FacturaController::class,['except'=>['create','edit']]);
    Route::resource('/detallefactura', DetalleFacturaController::class,['except'=>['create','edit']]);
    Route::resource('/cliente', ClienteController::class,['except'=>['create','edit']]);
});
