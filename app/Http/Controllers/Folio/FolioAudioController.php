<?php

namespace App\Http\Controllers\Folio;

use App\Audio;
use App\Folio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;

class FolioAudioController extends ApiController
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
        $audios = $folio->audios;

        return $this->showAll($audios);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Folio $folio)
    {
        /******************************************************************************************
         * PENDIENTE VER SI ES NECESARIO LIGAR EL AUDIO A UN REGISTRO DE LA TABLA TELEFONOS
        /*****************************************************************************************/
        //dd($request->audio);
        $reglas  = [
            'audio' => 'required|max:100000000'
        ];

        $this->validate($request, $reglas);

        try {
            $data = explode(';base64,', $request->audio);
            $data = base64_decode($data[1]);
            //return $this->errorResponse($data, 422);
            $datos['nombre'] = date('d-m-Y H:i:s'); //REVISAR SI EL NOMBRE DEL AUDIO SE QUEDA CON LA FECHA O SE CAMBIA
            $nombreRandom =  str_random(40) . '.mp3';
            Storage::put($folio->id_folio . "/audios/" . $nombreRandom, $data);

            $datos['ruta'] = $nombreRandom;
            $datos['id_folio'] = $folio->id_folio;
            $datos['id_usuario'] = $request->user()->id_usuario;

            $audio = Audio::create($datos);

            return $this->showOne($audio, 201);
            
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
            //throw $th;
        }
    }
}
