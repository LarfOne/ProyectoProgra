<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $table='producto';
    protected $fillable=['descripcion', 'precio'];

    public function detallefactura(){ 
        return $this->hasMany('App\Models\DetalleFactura');
    }
}
