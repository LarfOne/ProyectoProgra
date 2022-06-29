<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\Empleado as Authenticatable;

class Empleado extends Model
{



    use HasFactory;
    protected $table = 'empleado';
    protected $fillable = ['id','nombre','apellido1','apellido2','role','contrasena',
    'telefono','email','cuentabancaria','direccion','image']; //datos modificables

    public function factura(){
        return $this->hasMany('App\Models\Factura');
    }
}
