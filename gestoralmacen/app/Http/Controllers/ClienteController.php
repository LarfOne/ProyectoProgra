<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    public function __construct()
    {
        // Inyectar meddleware
    }
    //index -->devuelve todos los elementos  GET
    public function index(){
        $data=Cliente::all(); //minuto 51
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
            $data=Cliente::find($id)->load('factura'); //->load('posts')//cargar lo que está asociado a este
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
                'message'=>'Cliente no encontrado'
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
            'cedula'=>'required|unique:cliente',
            'nombre'=>'required|alpha',
            'apellido'=>'required|alpha',
            'telefono'=>'numeric|unique:cliente',//no tienen requirido por que aveces los clientes no quieren dejar sus datos
            'email'=>'email|unique:cliente'
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

            $cliente=new Cliente();  // imagen se edita no se agrega de un solo
            $cliente->cedula=$data['cedula'];
            $cliente->nombre=$data['nombre'];
            $cliente->apellido=$data['apellido'];
            $cliente->telefono=$data['telefono'];
            $cliente->email=$data['email'];
            $cliente->save();
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
            'nombre'=>'alpha',
            'apellido'=>'alpha',
            'telefono'=>'numeric|unique:cliente',//no tienen requirido por que aveces los clientes no quieren dejar sus datos
            'email'=>'email'
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
                $updated=Cliente::where('cedula',$id)->update($data);
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
            $deleted=Cliente::where('cedula',$id)->delete();
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
                    'message'=>'Problemas al eleminar el cliente, puede ser que el recurso no exista'
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
