<?php

namespace App\Http\Controllers;

use App\Models\DetalleFactura;
use Illuminate\Http\Request;

class DetalleFacturaController extends Controller
{
    public function _costruct(){
        //inyectar mildeware (control de acceso a los metodos del rest)
    }
    //index->devuelve todos los elementos GET
    public function index(){
        $data=detalleFactura::all();
        $response=array(
            'status'=>'success',
            'code'=>200,
            'data'=>$data
        );
        return response()->json($response,200);
    }
    //show ->devuelve uno por su id GET
    public function show($codigoDetalle){
        $data=detalleFactura::find($codigoDetalle)->load('detalleFactura');
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
        $json = $request->input('json',null,true);
        $data=json_decode($json,true);
        if(!empty($data)){
            $data = array_map('trim',$data);//trim quita los espacios vacios que vengan en el arreglo
            $rules =[
                'name'=>'required|alpha' //regla que sean letras el nombre ¿De que la pregunta hah?
            ];
            $validate=\validator($data,$rules);
            if($validate->fails()){
                $response=array(
                    'status' => 'error',
                    'code' => 406,
                    'message'=>'Los datos son incorrectos',
                    'errors' =>$validate->errors() //devuelve todos los fallos  que surgieron
                );
            }else{
                $detalleFactura = new detalleFactura();
                $detalleFactura->cantidad=$data['cantidad'];
                $detalleFactura->precioUnitario=$data['precioUnitario'];
                $detalleFactura->descuento=$data['descuento'];
                $detalleFactura->subTotal=$data['subTotal'];
                $detalleFactura->idProduto=$data['idProducto'];
                $detalleFactura->idFactura=$data['idFatura'];
                $detalleFactura->save();
                $response=array(
                    'status' => 'success',
                    'code' => 201,
                    'message'=>'Datos almacenados sastifacoriamente'
                );
            }
        }else{
            $response=array(
                'status' => 'error',
                'code' => 406,
                'message'=>'Faltan parametros'
            );
        }
        return response()->json($response,$response['code']);
    }







    //update -> modifica un elemento PUT
    public function update(Request $request){
        $json=$request->input('json',null,true);
        $data=json_decode($json,true);
        if(!empty($data)){
            $data=array_map('trim',$data);
            $rules=[      //***************************PENDIENTES MAS REGLAS PARA CAMBIOS */
                'cantidad'=> 'numeric|required',       
                'precioUnitario'=>'required|numeric',
                'descuento'=>'numeric',
                'subtotal'=>'required|numeric',
                'idProducto'=>'required',
                'idFactura'=>'required'  
            ];
            $validate=\validator($data,$rules);
            if($validate->fails()){
                $response=array(
                    'status' => 'error',
                    'code' => 406,
                    'message'=>'Los datos son incorrectos',
                    'errors' =>$validate->errors() //devuelve todos los fallos  que surgieron
                );
            }else{
                $codigoDetalle=$data['codigoDetalle'];
                unset($data['codigoDetalle']);  
                unset($data['created_at']);
                unset($data['update_at']);
                $update=Factura::where('codigoDetalle',$codigoDetalle)->update($data);
                if($update>0){
                    $response=array(
                        'status' => 'success',
                        'code' => 200,
                        'message'=>'Datos actualizados correctamente'
                    );
                }else{
                    $response=array(
                        'status' => 'error',
                        'code' => 406,
                        'message'=>'No se pudo actualizar los datos'
                    );
                }
            }
        }else{
            $response=array(
                'status' => 'error',
                'code' => 406,
                'message'=>'Faltan parametros'
            );
        }
        return response()->json($response,$response['code']);
    }
    //destroy -> elimina un elemento DELETE
    public function destroy($codigoDetalle){
        if(isset($id)){ //isset esta la varible creada?
            $deleted = detalleFactura::where('codigoDetalle',$codigoDetalle)->delete();
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
