<?php

namespace App\Http\Controllers\Folio;

use App\Audio;
use App\Folio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\ApiController;

class FolioAudioController extends ApiController
{
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
        $reglas  =[
            'audio' => 'required|mimetypes:mpga,application/octet-stream|max:10000'
        ];

        $this->validate($request, $reglas);

        $datos['nombre'] = date('d-m-Y H:i:s');
        $ruta =  $request->audio->storeAs('', $folio->id_folio
                . "/audios/" . str_random(40) . '.' . $request->audio->getClientOriginalExtension());
        $ruta = str_replace($folio->id_folio . "/audios/", '', $ruta);
        $datos['ruta'] = $ruta;
        $datos['id_folio'] = $folio->id_folio;

        $audio = Audio::create($datos);

        return $this->showOne($audio);
    }
}
