<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\JwtAuth;

class EmpleadoController extends Controller
{
    public function __construct()
    {
     //   $this->middleware('api.auth',['except'=>['show','login','store','getImage']]);
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
            $data=Empleado::find($id)->load('factura');
           //$data=Empleado::where('cedula','=', $cedula)->get(); //->load('posts')//cargar lo que está asociado a este
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


    public function mostrarFacturasEmpleado($id)
    {
        $empleado = Empleado::find($id);
        if (is_object($empleado)) {
            $empleado = Empleado::find($id);
            $empleado = $empleado->load('factura');

            $response = array(
                'status' => 'success',
                'code' => 200,
                'data' => $empleado
            );
        } else {
            $response = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'Empleado no encontrado'
            );
        }
        return response()->json($response, $response['code']);
    }




    //store --> agrega o guarda un elemnto  POST
    public function store(Request $request){   //PENDIENTE CAPTURA DE ERROR AL EENVIAR EL MISMO USUARIO O ENVIAR NADA
        $json=$request->input('json',null,true);
        $data=json_decode($json,true);
        var_dump($json);//perimte ver internamente en postman el arreglo que estoy enviando
        $data=array_map('trim',$data);
        $rules=[  //EN PROYECTO AGREGAR ID NO ES AUTOINCREMENTBLE
            'id'=>'required|numeric|unique:empleado',
            'nombre'=>'required|alpha',
            'apellido1'=>'required|alpha',
            'apellido2'=>'required|alpha',
            'role'=>'required|alpha',
            'contrasena'=>'required',
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
            $empleado=new Empleado();
            $empleado->id=$data['id'];
            $empleado->nombre=$data['nombre'];
            $empleado->apellido1=$data['apellido1'];
            $empleado->apellido2=$data['apellido2'];
            $empleado->role=$data['role'];
            $empleado->contrasena=hash('sha256',$data['contrasena']);
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
            //$data->contrasena= hash('sha256',$data['contrasena']);
            $rules=[
                'nombre'=>'alpha',
                'apellido1'=>'alpha',
                'apellido2'=>'alpha',
                'telefono'=>'numeric',
                'email'=>'email',
                'role'=>'alpha'
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

                $id=$data['id'];
                unset($data['id']);        //Unset: atributos que no se modifican
                unset($data['created_at']);
                $updated=Empleado::where('id',$id)->update($data);
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
            $deleted=Empleado::where('id',$id)->delete();
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
        /** Funciona a traves de metodo POST retornando un token */
        public function login(Request $request){
            $jwtAuth = new JwtAuth();
            $json = $request->input('json', null);
            $data = json_decode($json, true);
            $data = array_map('trim', $data);
            $rules = [
                'id'=>'required',
                'contrasena'=>'required'
            ];
            $valid=\validator($data, $rules);
            if($valid->fails()){
                $response = array(
                    'status' => 'error',
                    'code' => 406,
                    'message' => 'Los datos enviados son incorrectos',
                    'Validator errors' => $valid->errors()
                );
                return response()->json($data, 406);
            } else{
                $response = $jwtAuth->getToken($data['id'], $data['contrasena']);
                return response()->json($response);
            }
        }
        /** Funciona a traves de metodo POST retorna un json de datos del usuario autenticado */
        public function getIdentity(Request $request){
            $jwtAuth=new JwtAuth();
            $token=$request->header('token');
            if(isset($token)){
                $response=$jwtAuth->checkToken($token,true);
            }else{
                $response=array(
                    'status'=>'error',
                    'code'=>406,
                    'message'=>'token no encontrado'
                );
            }
            return response()->json($response);
        }

        public function uploadImage(Request $request){
            $image=$request->file('file0');
            $valid=\Validator::make($request->all(),[
                'file0'=>'required|image|mimes:jpg,png'
            ]);
            if(!$image||$valid->fails()){
                $response=array(
                    'status'=>'error',
                    'code'=>406,
                    'message'=>'Error al subir el archivo',
                    'errors'=>$valid->errors()
                );
            }else{
                $filename=time().$image->getClientOriginalName();
                \Storage::disk('empleados')->put($filename,\File::get($image));
                $response=array(
                    'status'=>'success',
                    'code'=>200,
                    'message'=>'Imagen guardada correctamente',
                    'image_name'=>$filename
                );
            }
            return response()->json($response,$response['code']);
        }

        public function getImage($filename){
            $exist=\Storage::disk('empleados')->exists($filename);
            if($exist){
                $file=\Storage::disk('empleados')->get($filename);
                return new Response($file,200);
            }else{
                $response=array(
                    'status'=>'error',
                    'code'=>404,
                    'message'=>'Imagen no existe'
                );
                return response()->json($response,404);
            }
}

}
