<?php

namespace App\Http\Controllers\Folio;

use App\User;
use App\Folio;
use App\Direccion;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Transformers\DireccionTransformer;

class FolioDireccionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:'. DireccionTransformer::class)->only(['store']);
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
        $direcciones = $folio->direcciones;

        return $this->showAll($direcciones);
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
            'id_usuario' => 'required|integer',
            'calle' => 'required',
            'numero' => 'required|regex:/^#?[ ]?[0-9]{1,8}$/',
            'colonia' => 'required',
            'ciudad' => 'required',
            'estado' => 'required',
            'codigo_postal' => 'required|integer|min:1|regex:/^[0-9]{4,5}$/'
        ];

        $this->validate($request, $reglas);

        $user = User::find($request->id_usuario);

        if(is_null($user)) {
            return $this->errorResponse('Usuario no valido.', 409);
        }

        $datos = $request->all();
        $datos['id_folio'] = $folio->id_folio;

        $direccion = Direccion::create($datos);

        return $this->showOne($direccion);
    }
}
