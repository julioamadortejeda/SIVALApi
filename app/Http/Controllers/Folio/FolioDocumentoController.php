<?php

namespace App\Http\Controllers\Folio;

use App\Folio;
use App\Documento;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\ApiController;

class FolioDocumentoController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        //$this->middleware('scope:administrador')->only('index');
        $this->middleware('scope:administrador,modificar-folios')->only(['store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Folio $folio)
    {
        $documentos = $folio->documentos;

        return $this->showAll($documentos);
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
            'documentos' => 'required',
            'documentos.*' => 'max:3000|mimes:pdf,jpeg,jpg,png',
        ];

        $this->validate($request, $reglas);

        $documentos = collect();
        foreach ($request->documentos as $documento) {
            $ruta = $documento->store($folio->id_folio . '/Documentos');
            $ruta = str_replace($folio->id_folio . "/Documentos/", '', $ruta);
            $datos['nombre'] = $documento->getClientOriginalName();
            $datos['ruta'] = $ruta;
            $datos['id_folio'] = $folio->id_folio;
            $datos['id_usuario'] = $request->user()->id_usuario;

            $doc = Documento::create($datos);
            $documentos->push($doc);
        }

        return $this->showAll($documentos, 201);
    }
}
