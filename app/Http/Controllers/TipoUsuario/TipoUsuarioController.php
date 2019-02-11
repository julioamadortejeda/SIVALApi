<?php

namespace App\Http\Controllers\TipoUsuario;

use App\TipoUsuario;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Transformers\TipoUsuarioTransformer;

class TipoUsuarioController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:'. TipoUsuarioTransformer::class)->only(['store', 'update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tiposUsuarios = TipoUsuario::all();

        return $this->showAll($tiposUsuarios);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reglas = ['nombre' => 'required|unique:TiposUsuarios,nombre'];
        //$reglas = ['nombre' => 'required|unique:TiposUsuarios'];
        $this->validate($request, $reglas);

        $campos = $request->all();
        $tipoUsuario = TipoUsuario::create($campos);

        return $this->showOne($tipoUsuario, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TipoUsuario  $tipoUsuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tipousuario = TipoUsuario::findOrFail($id);

        $reglas = ['nombre' => 'unique:TiposUsuarios,nombre,' . $tipousuario->id_tipo_usuario. ',id_tipo_usuario'];
        $this->validate($request, $reglas);

        if ($request->has('nombre') && $request->nombre != $tipousuario->nombre) {
                $tipousuario->nombre = $request->nombre;
        }

        if (!$tipousuario->isDirty())    
            return $this->errorResponse('Se debe espeificar un valor diferente para actualizar.', 422);

        $tipousuario->save();
        return $this->showOne($tipousuario);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TipoUsuario  $tipoUsuario
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tipoUsuario = TipoUsuario::findOrFail($id);
        $tipoUsuario->delete();

        return $this->showOne($tipoUsuario);
    }
}
