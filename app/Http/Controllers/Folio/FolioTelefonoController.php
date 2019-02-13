<?php

namespace App\Http\Controllers\Folio;

use App\User;
use App\Folio;
use App\Telefono;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Transformers\TelefonoTransformer;

class FolioTelefonoController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:'. TelefonoTransformer::class)->only(['store']);
        $this->middleware('scope:administrador')->only('index');
        $this->middleware('scope:administrador,modificar-folios')->only(['store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Folio $folio)
    {
        $telefonos = $folio->telefonos;

        return $this->showAll($telefonos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Folio $folio)
    {
        $reglas = [
            'telefono' => 'required|regex:/^[0-9]{7,13}$/',
            'id_usuario' => 'required|integer'
        ];

        $this->validate($request, $reglas);

        $user = User::find($request->id_usuario);

        if(is_null($user)) {
            return $this->errorResponse('Usuario no valido.', 409);
        }

        $datos = $request->all();
        $datos['id_folio'] = $folio->id_folio;
        $telfono = Telefono::create($datos);

        return $this->showOne($telfono);

    }
}
