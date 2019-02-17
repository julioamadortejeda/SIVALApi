<?php

namespace App\Http\Controllers\Folio;

use App\Folio;
use App\Imports\FolioImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Transformers\FolioTransformer;
use App\Http\Controllers\ApiController;

class FolioController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:'. FolioTransformer::class)->only(['update']);
        $this->middleware('scope:administrador')->only(['destroy', 'importarExcel']);
        $this->middleware('scope:administrador,modificar-folios')->only(['update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $folios = null;
        $user = request()->user();

        if ($user->esAdministrador()) {
            $folios = Folio::take(500)->get();
        }
        elseif ($user->esValidacion()) {
            $folios = Folio::where('validado', false)->take(500)->get();
        }
        else {
            $folios = Folio::whereHas('empleado', function($query) use($user){
                $query
                ->where('id_empleado', $user->id_empleado)
                ->orWhere('id_gerente', $user->id_empleado);   
            })->get();
        }

        return $this->showAll($folios);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Folio $folio)
    {
        $user = request()->user();

        if (!($user->esAdministrador() || $user->esValidacion())) {
            if ($folio->id_empleado != $user->id_empleado && $folio->empleado->id_gerente != $user->id_empleado) {
                return $this->errorResponse("El folio no pertenece al empleado ($user->id_empleado " 
                                            . $user->empleado->nombre . ")", 409);
            }
        }

        return $this->showOne($folio);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Folio $folio)
    {
        $user = $request->user();

        if (!($user->esAdministrador() || $user->esValidacion()))
            return $this->errorResponse('No se tienen permisos para modificar el folio.', 409);

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Folio $folio)
    {
        $user = request()->user();
        
        if (!$user->esAdministrador()) {   
            return $this->errorResponse('No se tienen permisos para eliminar el folio.', 409);
        }

        $folio->delete();

        return $this->showOne($folio);
    }

    public function importarExcel() 
    {
        Excel::import(new FolioImport, request()->file('file'));
    }
}
