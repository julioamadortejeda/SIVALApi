<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Folio;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class UserFolioController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $folios = null;
        if ($user->esAdministrador()) {
            $folios = Folio::take(500)->get();
        }
        elseif (!$user->esValidacion()) {
            $folios = Folio::whereHas('empleado', function($query) use($user){
                $query->where('id_empleado', $user->id_empleado)->orWhere('id_gerente', $user->id_empleado);   
            })->get();
            //$folios = Folio::whereIn('id_empleado',[$user->id_empleado, $user->empleado->id_gerente])->get();
        }
        else{
            $folios = Folio::where('validado', false)->take(500)->get();
        }

        return $this->showAll($folios);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, Folio $folio)
    {
        if (!$user->esAdministrador()) {
            if (!$user->esValidacion()) {
                if ($folio->id_empleado != $user->id_empleado && $folio->empleado->id_gerente != $user->id_empleado) {
                    return $this->errorResponse("El folio no pertenece al empleado ($user->id_empleado " 
                                                . $user->empleado->nombre . ")", 409);
                }
            }
        }

        return $this->showOne($folio);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, Folio $folio)
    {
        if (!$user->esAdministrador()) {
            if (!$user->esValidacion()) {
                return $this->errorResponse('No se tienen permisos para modificar el folio.', 409);
            }
        }

        $reglas = [
            'validado' => 'required|bool'
        ];

        $this->validate($request, $reglas);

        if ($folio->validado != $request->validado) {
            if ($folio->estaValidado() && $request->validado == false && !$user->esAdministrador()) {
                return $this->errorResponse('No se tienen permisos para quitar la validacion al folio.', 422);
            }
        }
        else{
            return $this->errorResponse('Se debe especificar un valor diferente para actualizar.', 422);
        }

        $folio->validado = $request->validado;
        $folio->save();

        return $this->showOne($folio);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Folio $folio)
    {
        if (!$user->esAdministrador()) {   
            return $this->errorResponse('No se tienen permisos para eliminar el folio.', 409);
        }

        $folio->delete();

        return $this->showOne($folio, 200);
    }
}
