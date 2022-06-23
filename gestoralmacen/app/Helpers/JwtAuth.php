<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\Empleado;

class JwtAuth{
    private $key;

    function __construct()
    {
        $this->key='12Sawsfe2saWaaasawSQ';
    }

    public function getToken($id, $contrasena){
        $empleado = Empleado::where(['id'=>$id, 'contrasena'=>hash('sha256', $contrasena)])->first();
        if(is_object($empleado )){
            $token = array(
                'sub'=>$empleado->id,
                'email'=>$empleado->email,
                'nombre'=>$empleado->nombre,
                'apellido1'=>$empleado->apellido1,
                'apellido2'=>$empleado->apellido2,
                'telefono'=>$empleado->telefono,
                'direccion'=>$empleado->direccion,
                'role'=>$empleado->role,
                'cuentabancaria'=>$empleado->cuentabancaria,
                'image'=>$empleado->image,
                'iat'=>time(),
                'exp'=>time()+(120)
            );
            $data = JWT::encode($token, $this->key, 'HS256');
        } else{
            $data = array(
                'status' => 'error',
                'code' => 401,
                'message' => 'Datos de autenticacion incorrectos'
            );
        }

        return $data;
    }


    public function checkToken($jwt,$getIdentity=false){
        $auth=false;
        if(isset($jwt)){
            try{
                $decoded=JWT::decode($jwt,new Key($this->key,'HS256'));
            }catch(\DomainException $ex){
                $auth=false;
             }catch(\UnexpectedValueException $ex){
                $auth=false;
            }catch(\ExpiredException $ex){
                $auth=false;
            }
            if(!empty($decoded)&&is_object($decoded)&&isset($decoded->sub)){
                $auth=true;
            }
            if($getIdentity&&$auth){
                return $decoded;
            }
        }
        return $auth;
    }
}
