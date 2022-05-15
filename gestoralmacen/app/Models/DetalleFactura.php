<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleFactura extends Model
{
    use HasFactory;
    protected $table='detalleFactura';
    protected $fillable=['cantidad','precioUnitario','descuento','subTotal','idProducto','idFactura']; //pendiente
    
    public function producto(){
        return $this->belongsTo('App\Models\Producto','idProducto');
    }
    public function factura(){
        return $this->belongsTo('App\Models\Factura','idFactura');
    }
}
