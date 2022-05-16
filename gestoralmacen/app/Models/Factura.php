<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;
    protected $table ='factura';
    protected $fillable=['fechaFactura','subTotal','impuesto','descuento','total','idCliente','idEmpleado'];//los que se pueden modificar

    public function detalleFactura(){
        return $this->hasMany('App\Models\DetalleFactura');
    }
}
