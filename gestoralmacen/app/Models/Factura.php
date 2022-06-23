<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empleado;
use App\Models\Cliente;

class Factura extends Model
{
    use HasFactory;
    protected $table ='factura';
    protected $fillable=['fechaFactura','subTotal','impuesto','descuento','total','cliente_id','empleado_id'];//los que se pueden modificar

    public function detalleFactura(){
        return $this->hasMany('App\Models\DetalleFactura');
    }
    
    public function cliente(){
        return $this->belongsTo('App\Models\Cliente','cliente_id');
    }

    public function empleado(){
        return $this->belongsTo('App\Models\Empleado','empleado_id');
    }



}
