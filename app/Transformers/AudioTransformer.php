<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Audio;
use Illuminate\Support\Facades\Storage;

class AudioTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Audio $audio)
    {
        //dd(Storage::disk('public')->url('t.txt'));
        return [
            'clave' => (int)$audio->id_audio,
            'nombre' => (string)$audio->nombre,
            'link' => Storage::disk('custom')->url($audio->id_folio . '/audios/' . $audio->ruta),
            'fechaCreacion' => (string)$audio->fecha_creacion,
            'fechaActualizacion' => (string)$audio->fecha_modificacion,
            'fechaEliminacion' => isset($audio->fecha_eliminacion) ? (string)$audio->fecha_eliminacion : null
        ];
    }

    public static function originalAttribute($index)
    {
        $attribute = [
            'clave' => 'id_audio',
            'nombre' => 'nombre',
            'link' => 'ruta',
            'fechaCreacion' => 'fecha_creacion',
            'fechaActualizacion' => 'fecha_modificacion',
            'fechaEliminacion' => 'fecha_eliminacion'
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $attribute = [
            'id_audio' => 'clave',
            'nombre' => 'nombre',
            'ruta' => 'link',
            'fecha_creacion' => 'fechaCreacion',
            'fecha_modificacion' => 'fechaActualizacion',
            'fecha_eliminacion' => 'fechaEliminacion'
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
