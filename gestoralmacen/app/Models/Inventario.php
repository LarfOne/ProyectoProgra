<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;
    protected $table = 'inventario';
    protected $fillable = ['cantidad'];

    public function producto(){
        return $this->belongsTo('App\Models\Producto','idProducto');
    }

}
