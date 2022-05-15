<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function __construct()
    {
        // Inyectar meddleware
    }
    //index -->devuelve todos los elementos  GET
   public function index(){
        $data=Empleado::all(); //minuto 51
        $response=array(
            'status'=>'success',
            'code'=>200,
            'data'=>$data
        );
        return response()->json($response,200);
    }
    //show--> devuelve un elemento por su id GET
    public function show($id){
        if(isset($id)){
            $data=Empleado::find($id); //->load('posts')//cargar lo que está asociado a este
            if(is_object($data)){
                $response=array(
                    'status'=>'success',
                    'code'=>200,
                    'data'=>$data
                );
            }
        }else{
            $response=array(
                'status'=>'error',
                'code'=>404,
                'message'=>'Empleado no encontrado'
            );
        }
        return response()->json($response,$response['code']);
    }

    //store --> agrega o guarda un elemnto  POST
    public function store(Request $request){   //PENDIENTE CAPTURA DE ERROR AL EENVIAR EL MISMO USUARIO O ENVIAR NADA
        $json=$request->input('json',null,true);
        $data=json_decode($json,true);
        var_dump($json);//perimte ver internamente en postman el arreglo que estoy enviando
        $data=array_map('trim',$data);
        $rules=[  //EN PROYECTO AGREGAR ID NO ES AUTOINCREMENTBLE
            'cedula'=>'required|numeric|unique:empleado',
            'nombre'=>'required|alpha',
            'apellido1'=>'required|alpha',
            'apellido2'=>'required|alpha',
            'telefono'=>'required|numeric',
            'email'=>'required|email|unique:empleado',
            'cuentabancaria'=>'required|unique:empleado',
            'direccion'=>'required'
        ];
        $valid=\validator($data,$rules);
        if($valid->fails()){
            $response=array(
                'status'=>'error',
                'code'=>406,
                'message'=>'Datos enviados no cumplen con las reglas establecidas',
                ''=>$valid->errors()
                );
        }else{
            $empleado=new Empleado();  // imagen se edita no se agrega de un solo
            $empleado->cedula=$data['cedula'];
            $empleado->nombre=$data['nombre'];
            $empleado->apellido1=$data['apellido1'];
            $empleado->apellido2=$data['apellido2'];
            $empleado->telefono=$data['telefono'];
            $empleado->email=$data['email'];
            $empleado->cuentabancaria=$data['cuentabancaria'];
            $empleado->direccion=$data['direccion'];
            $empleado->save();
            $response=array(
                'status'=>'success',
                'code'=>200,
                'message'=>'Datos almacenados satisfactoriamente',
                ''=>$valid->errors()
                );
        }
        return response()->json($response,$response['code']);
    }
    //*********************Hasta aqui **************************/
    //update --> modifica un elemento    PUT
    public function update(Request $request){
        $json=$request->input('json',null);
        $data=json_decode($json,true);
        if(!empty($data)){
            $data=array_map('trim',$data);
            $rules=[
                'nombre'=>'required|alpha',
                'apellido1'=>'required|alpha',
                'apellido2'=>'required|alpha',
                'telefono'=>'required',
                'email'=>'required|email',
                'cuentabancaria'=>'required',
                'direccion'=>'required'
            ];
            $validate=\validator($data,$rules);
            if($validate->fails()){
                $response=array(
                    'status'=>'error',
                    'code'=>406,
                    'message'=>'Los datos enviados son incorrectos',
                    'errors'=>$validate->errors()
                );
            }else{
                $id=$data['cedula'];
                unset($data['cedula']);        //Unset: atributos que no se modifican
                unset($data['created_at']);
                $updated=Empleado::where('cedula',$id)->update($data);
                if($updated>0){
                    $response=array(
                        'status'=>'success',
                        'code'=>200,
                        'message'=>'Datos actualizados exitosamente'
                    );
                }else{
                    $response=array(
                        'status'=>'error',
                        'code'=>400,
                        'message'=>'No se pudo actualizar los datos'
                    );
                }
            }
            //se puede hacer una funcion de mensajes y solo llamar a la funcion
        }else{
            $response=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Faltan parametros'
            );
        }
        return response()->json($response,$response['code']);
    }
    //destroy --> Elimina un elemento   DELETE
    public function destroy($id){
        if(isset($id)){
            $deleted=Empleado::where('cedula',$id)->delete();
            if($deleted){
                $response=array(
                    'status'=>'success',
                    'code'=>200,
                    'message'=>'Eliminado correctamente'
                );
            }else{
                $response=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Problemas al eleminar el recurso, puede ser que el recurso no exista'
                );
            }
        }else{
            $response=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Falta el identificador del recurso'
            );
        }
        return response()->json($response,$response['code']);
    }
}
