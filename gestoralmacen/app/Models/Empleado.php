<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;
    protected $table = 'empleado';
    protected $fillable = ['cedula','nombre','apellido1','apellido2','telefono','email', 
    'cuentabancaria','direccion']; //datos modificables

    public function factura(){
        return $this->hasMany('App\Models\Factura');
    }
}
