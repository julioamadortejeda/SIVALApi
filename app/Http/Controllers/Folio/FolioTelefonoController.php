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
        ];

        $this->validate($request, $reglas);

        $datos = $request->all();
        $datos['id_folio'] = $folio->id_folio;
        $datos['id_usuario'] = $request->user()->id_usuario;
        $telfono = Telefono::create($datos);

        return $this->showOne($telfono, 201);

    }
}
