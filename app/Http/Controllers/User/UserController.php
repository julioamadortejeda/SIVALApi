<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Empleado;
use App\TipoUsuario;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::all();

        return $this->showAll($usuarios);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $datos = $request->all();
        $reglas = [
            'nombre' => 'required|unique:users,nombre|min:6',
            'password' => 'required|min:6|confirmed',
            'id_tipo_usuario' => 'required',
            'id_empleado' => 'integer|min:1'
        ];

        $this->validate($request, $reglas);

        $empleado  = null;
        if ($request->has('id_empleado'))
            $empleado = Empleado::find($request->id_empleado);
        
        $tipoUsuario = TipoUsuario::find($request->id_tipo_usuario);

        if (!is_null($tipoUsuario)) {
            if($tipoUsuario->nombre == User::USER_ADMINISTRADOR || $tipoUsuario->nombre == User::USER_VALIDACION) {
                if(!is_null($empleado) || !is_null($request->id_empleado)) {
                    return $this->errorResponse(sprintf('El tipo de usuario (%s) no debe tener un empleado asignado.', $tipoUsuario->nombre), 409);
                }
                
                $datos['id_empleado'] = null;
            }
            else {
                if (is_null($empleado)) {
                    if (is_null($request->id_empleado)) {
                        return $this->errorResponse(sprintf('Se requiere un Empleado para el tipo de usuario (%s)', $tipoUsuario->nombre), 409);
                    }
                    return $this->errorResponse(sprintf('El Empleado (%s), no es valido.', $request->id_empleado), 409);
                }
                else
                    $datos['id_empleado'] = $empleado->id_empleado;
            }

            $datos['id_tipo_usuario'] = $tipoUsuario->id_tipo_usuario;
        }
        else
            return $this->errorResponse('El tipo de usuario no existe.', 409);
            
        $datos['password'] = bcrypt($request->password);

        $usuario = User::create($datos);

        return $this->showOne($usuario, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //$user = User::with(['empleado', 'tipousuario'])->findOrFail($id);

        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $reglas = [
            'nombre' => 'unique:users,nombre,' . $user->id_usuario . ',id_usuario',
            'password' => 'min:6|confirmed',
            'id_empleado' => 'integer|min:1',
        ];

        $this->validate($request, $reglas);
        $empleado = null;

        if ($request->has('id_empleado')) {
            $empleado = Empleado::find($request->id_empleado);

            if($user->esAdministrador() || $user->esValidacion()) {
                if(!is_null($empleado) || !is_null($request->id_empleado))
                    return $this->errorResponse(sprintf('El tipo de usuario (%s) no debe tener un empleado asignado.', $user->tipo_usuario->nombre), 409);
            }
            else {
                if (is_null($empleado))
                    return $this->errorResponse(sprintf('El Empleado (%s), no es valido.', $request->id_empleado), 409);
                else
                    $user->id_empleado  = $empleado->id_empleado;
            }
        }
            
        //*/********************************************************************* */
        // POR EL MOMENTO NO SE PUEDE EDITAR EL TIPO DE USUARIO
        //*********************************************************************** */

        // if ($request->has('id_tipo_usuario')){
        //     if (!is_null(TipoUsuario::find($request->id_tipo_usuario))) {
        //         //if ($user->id_tipo_usuario != $request->id_tipo_usuario) {
        //             $user->id_tipo_usuario = $request->id_tipo_usuario;
        //         //}
        //     }
        //     else
        //         return $this->errorResponse('El tipo de usuario no existe.', 409);
        // }
            
        if ($request->has('nombre') && $request->nombre != $user->nombre)
            $user->nombre = $request->nombre;

        if ($request->has('password'))
            $user->password = bcrypt($request->password);

        if (!$user->isDirty()) 
            return $this->errorResponse('Se debe espeificar un valor diferente para actualizar.', 422);

        $user->save();
        $user->refresh();
        return $this->showOne($user, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //$user = User::findOrFail($id);
        $user->delete();

        return $this->showOne($user, 200);
    }
}
