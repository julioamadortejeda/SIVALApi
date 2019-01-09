<?php

namespace App\Http\Controllers\Audio;

use App\Audio;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;

class AudioController extends ApiController
{
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Audio  $audio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Audio $audio)
    {
        $ruta = $audio->id_folio . "/Audios/" . $audio->ruta;
        if(!Storage::delete($ruta))
            return $this->errorResponse('No se pudo encontrar el audio a eliminar.', 404);

        $audio->delete();

        return $this->showOne($audio);
    }
}
