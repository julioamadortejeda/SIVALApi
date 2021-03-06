<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Documento;
use Illuminate\Support\Facades\Storage;

class DocumentoTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Documento $documento)
    {
        return [
            'clave' => (int)$documento->id_documento,
            'nombre' => (string)$documento->nombre,
            'link' => Storage::disk('custom')->url($documento->id_folio . '/documentos/' . $documento->ruta),
            'fechaCreacion' => (string)$documento->fecha_creacion,
            'fechaActualizacion' => (string)$documento->fecha_modificacion,
            'fechaEliminacion' => isset($documento->fecha_eliminacion) ? (string)$documento->fecha_eliminacion : null
        ];
    }

    public static function originalAttribute($index)
    {
        $attribute = [
            'clave' => 'id_documento',
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
            'id_documento' => 'clave',
            'nombre' => 'nombre',
            'ruta' => 'link',
            'fecha_creacion' => 'fechaCreacion',
            'fecha_modificacion' => 'fechaActualizacion',
            'fecha_eliminacion' => 'fechaEliminacion'
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
