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
            'nombre' => 'required|unique:users,nombre',
            'password' => 'required|min:6|confirmed',
            'id_tipo_usuario' => 'required'
        ];

        $this->validate($request, $reglas);

        if ($request->has('id_empleado') && !is_null(Empleado::find($request->id_empleado)))
            $datos['id_empleado'] = $request->id_empleado;
        elseif (!$request->has('id_empleado')) 
            $datos['id_empleado'] = null;
        else
            return $this->errorResponse(sprintf('El Empleado (%s), no es valido.', $request->id_empleado), 409);

        if ($request->has('id_tipo_usuario') && !is_null(TipoUsuario::find($request->id_tipo_usuario)))
            $datos['id_tipo_usuario'] = $request->id_tipo_usuario;
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
            'password' => 'min:6|confirmed'
        ];

        $this->validate($request, $reglas);

        if ($request->has('id_empleado')) {
            if (is_null($request->id_empleado)) {
                   $user->id_empleado = null;
            }
            else {
                if (!is_null(Empleado::find($request->id_empleado)) ) {
                        $user->id_empleado = $request->id_empleado;
                }
                else
                    return $this->errorResponse(sprintf('El Empleado (%s), no es valido.', $request->id_empleado), 409);
            }
        }
            
        if ($request->has('id_tipo_usuario')){
            if (!is_null(TipoUsuario::find($request->id_tipo_usuario))) {
                //if ($user->id_tipo_usuario != $request->id_tipo_usuario) {
                    $user->id_tipo_usuario = $request->id_tipo_usuario;
                //}
            }
            else
                return $this->errorResponse('El tipo de usuario no existe.', 409);
        }
            
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
