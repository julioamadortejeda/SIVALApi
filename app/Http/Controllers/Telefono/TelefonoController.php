<?php

namespace App\Http\Controllers\Telefono;

use App\Telefono;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class TelefonoController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        //$this->middleware('transform.input:'. TipoUsuarioTransformer::class)->only(['store', 'update']);
        $this->middleware('scope:administrador');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $telefonos = Telefono::all();

        return $this->showAll($telefonos);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Telefono $telefono)
    {
        $telefono->delete();

        return $this->showOne($telefono);
    }
}
