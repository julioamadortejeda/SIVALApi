<?php

namespace App\Http\Controllers\Documento;

use App\Documento;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('scope:administrador');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Documento  $documento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Documento $documento)
    {   
        $ruta = $documento->id_folio . "/documentos/" . $documento->ruta;
        if(!Storage::delete($ruta))
            return $this->errorResponse('No se pudo encontrar el archivo a eliminar.', 404);

        $documento->delete();

        return $this->showOne($documento);
    }
}
