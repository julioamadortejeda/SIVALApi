<?php

namespace App\Http\Controllers\Folio;

use App\Folio;
use App\Documento;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Support\Facades\Storage;

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
            'documentos.*' => 'max:5000000',
        ];

        $this->validate($request, $reglas);
        //return $this->errorResponse($request->documentos, 400);

        try {

            $imageTypes = array('image/jpeg', 'image/jpg', 'image/png');
            $documentos = collect();

            foreach ($request->documentos as $image) {
                if (!in_array($image['type'], $imageTypes)) {
                    return $this->errorResponse('Tipo de archivo no permitido.', 422);
                }

                $data = base64_decode($image['value']);
                $imgType = str_replace('image/', '', $image['type']);
                $nombreRandom = str_random(40) . '.jpg';
                Storage::put($folio->id_folio . "/documentos/" . $nombreRandom, $data);

                $datos['nombre'] = $image['name'];
                $datos['ruta'] = $nombreRandom;
                $datos['id_folio'] = $folio->id_folio;
                $datos['id_usuario'] = $request->user()->id_usuario;

                $doc = Documento::create($datos);
                $documentos->push($doc);
            }
            return $this->showAll($documentos, 201);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 412);
        }
    }
}
