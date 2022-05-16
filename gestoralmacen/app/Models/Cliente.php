<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cliente';
    protected $fillable = ['nombre','apellido','telefono'];


public function factura(){
    return $this->hasMany('App\Models\Factura');
}
}
