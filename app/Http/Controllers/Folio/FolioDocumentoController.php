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
            'documentos.*' => 'max:5000000',
        ];

        $this->validate($request, $reglas);
        return $this->errorResponse($request->documentos, 400);

        try {
            $imageTypes = array('image/jpeg', 'image/jpg', 'image/png');

            foreach ($request->documentos as $image) {
                if (!in_array($image->type, $imageTypes)) {
                    return $this->errorResponse('Tipo de archivo no permitido.', 422);
                }
            }
            $ruta = $documento->store($folio->id_folio . '/Documentos');
            $ruta = str_replace($folio->id_folio . "/Documentos/", '', $ruta);
            $datos['nombre'] = $documento->getClientOriginalName();
            $datos['ruta'] = $ruta;
            $datos['id_folio'] = $folio->id_folio;
            $datos['id_usuario'] = $request->user()->id_usuario;

            $doc = Documento::create($datos);
            $documentos->push($doc);

            $datos['nombre'] = date('d-m-Y H:i:s'); //REVISAR SI EL NOMBRE DEL AUDIO SE QUEDA CON LA FECHA O SE CAMBIA
            $nombreRandom = str_random(40) . '.mp3';
            Storage::put($folio->id_folio . "/documentos/" . $nombreRandom, $data);

            // $ruta =  $request->audio->storeAs('', $folio->id_folio
            //     . "/audios/" . str_random(40) . '.mp3');
            // $ruta = str_replace($folio->id_folio . "/audios/", '', $ruta);

            $datos['ruta'] = $nombreRandom;
            $datos['id_folio'] = $folio->id_folio;
            $datos['id_usuario'] = $request->user()->id_usuario;

            $audio = Audio::create($datos);

            return $this->showOne($audio, 201);
        } catch (\Throwable $th) {
            dd($th);
            return $this->errorResponse($th, 400);
            //throw $th;
        }

        try {
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
        } catch (\Throwable $th) {
            return $this->errorResponse($th, 400);
            //throw $th;
        }

        return $this->showAll($documentos, 201);
    }
}
