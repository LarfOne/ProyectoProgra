<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    public function _costruct(){
        //inyectar mildeware (control de acceso a los metodos del rest)
    }
    //index->devuelve todos los elementos GET
    public function index(){
        $data=Factura::all();
        $response=array(
            'status'=>'success',
            'code'=>200,
            'data'=>$data
        );
        return response()->json($response,200);
    }
    //show ->devuelve uno por su id GET
    public function show($codigo){
        $data=Factura::find($codigo)->load('Factura');
        if(is_object($data)){
            $response=array(
                'status'=>'success',
                'code'=>200,
                'data'=>$data
            );
        }else{
            $response=array(
                'status'=>'error',
                'code'=>404,
                'data'=>'Recursos no encontrados'
            );
        }
        return response()->json($response['code']);
    }
   
    //store -> agrega o guarda un elemento POST
    public function store(Request $request) { //*****************Pendiente**************/
        $json=$request->input('json',null);
        $data=json_decode($json,true);
        $data=array_map('trim',$data);
        $rules=[
            'impuesto'=> 'numeric',
            'subtotal'=>'required|numeric',
            'descuento'=>'numeric',
            'total'=>'numeric',
            'idCliente'=>'required',
            'idEmpleado'=>'required'    
        ];
        
        $valid=\validator($data,$rules);
        if($valid->fails()){
            $response=array(
                'status'=>'error',
                'code'=>406,
                'message'=>'Datos enviados no cumplen con las reglas establecidas',
                'errors'=>$valid->errors()
            );
        }else{
            $factura=new Factura();
            $factura->subtotal=$data['subtotal'];
            $factura->impuesto=$data['impuesto'];
            $factura->descuento=$data['descuento'];
            $factura->total=$data['total'];
            $factura->idCliente=$data['idCliente'];
            $factura->idEmplado=$data['idEmpleado'];
            $factura->save();
            $response=array(
                'status'=>'success',
                'code'=>200,
                'message'=>'Datos almacenados satisfactoriamente'
            );
        }
        return response()->json($response,$response['code']);
    }


    //update -> modifica un elemento PUT
    public function update(Request $request){
        $json=$request->input('json',null,true);
        $data=json_decode($json,true);
        //Error a solucionar tarea profe
        $data=array_map('trim',$data);
        $rules=[
            'impuesto'=> 'numeric',
            'subtotal'=>'required|numeric',
            'fechaFactura'=>'required',
            'descuento'=>'numeric',
            'total'=>'numeric',
            'idCliente'=>'required',
            'idEmpleado'=>'required'    
        ];
        $valid=\validator($data,$rules);
        if($valid->fails()){
            $response=array(
                'status'=>'error',
                'code'=>406,
                'message'=>'Datos enviados no cumplen con las reglas establecidas',
                'errors'=>$valid->errors()
            );
        }else{                    //ignorar
            $codigo=$data['codigo'];   // valor a validar con base de datos
            unset($data['codigo']);
            unset($data['idCliente']);
            unset($data['idEmpleado']);
            unset($data['created_at']);
            unset($data['updated_at']);
            $updated=User::where('codigo',$codigo)->update($data);
            if($updated>0){
                $response=array(
                    'status'=>'success',
                    'code'=>200,
                    'message'=>'Datos actualizados satisfactoriamente'
                );
            }else{
                $response=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'No se pudo actualizar el usuario, puede ser que no exista'
                );
            }
        }
        return response()->json($response,$response['code']);
    }





    //destroy -> elimina un elemento DELETE
    public function destroy($codigo){
        if(isset($codigo)){ //isset esta la varible creada?
            $deleted = Factura::where('id',$codigo)->delete();
            if($deleted){
                $response=array(
                    'status' => 'succes',
                    'code' => 200,
                    'message'=>'Eliminado corretamente'
                );
            }else{
                $response=array(
                    'status' => 'error',
                    'code' => 406,
                    'message'=>'Problemas al eliminar'
                );
            }
        }else{
            $response=array(
                'status' => 'error',
                'code' => 406,
                'message'=>'Falta el identificador del recurso'
            );
        }
        return response()->json($response,$response['code']);
    }
}
