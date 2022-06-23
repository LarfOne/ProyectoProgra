<?php

use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\DetalleFacturaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;
//use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    //RUTAS ESPECIFICAS imagenes
    Route::post('/empleado/login',[EmpleadoController::class,'login']);
    Route::get('/empleado/getidentity',[EmpleadoController::class,'getIdentity']);
    Route::get('/factura/getultimafactura', [FacturaController::class, 'getUltimaFactura']);
    Route::post('/empleado/upload',[EmpleadoController::class,'uploadImage']);
    Route::get('/empleado/getimage/{filename}',[EmpleadoController::class,'getImage']);
    //RUTAS AUTOMATICAS RESTful
    Route::resource('/empleado', EmpleadoController::class,['except'=>['create','edit']]);
    Route::resource('/factura', FacturaController::class,['except'=>['create','edit']]);
    Route::resource('/detallefactura', DetalleFacturaController::class,['except'=>['create','edit']]);
    Route::resource('/cliente', ClienteController::class,['except'=>['create','edit']]);
    Route::resource('/producto', ProductoController::class,['except'=>['create','edit']]);
    Route::get('/empleado/mostrarFacturasEmpleado/{idEmpleado}', [EmpleadoController::class, 'mostrarFacturasEmpleado']);
    //muestra facturas de un empleado
});
