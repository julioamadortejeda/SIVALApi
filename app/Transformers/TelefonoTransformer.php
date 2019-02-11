<?php

namespace App\Transformers;

use App\Telefono;
use League\Fractal\TransformerAbstract;

class TelefonoTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Telefono $telefono)
    {
        return [
            'clave' => (int)$telefono->id_telefono,
            'numeroTelefono' => (string) $telefono->telefono,
            'folio' => (int)$telefono->id_folio,
            'usuario' => [
                'clave' => (int)$telefono->user->id_usuario,
                'nombre' => (string)$telefono->user->nombre,
                'categoria' => (string)$telefono->user->tipo_usuario->nombre
            ],
            'fechaCreacion' => (string)$telefono->fecha_creacion,
            'fechaActualizacion' => (string)$telefono->fecha_modificacion,
            'fechaEliminacion' => isset($telefono->fecha_eliminacion) ? (string)$telefono->fecha_eliminacion : null
        ];
    }

    public static function originalAttribute($index)
    {
        $attribute = [
            'clave' => 'id_telefono',
            'numeroTelefono' => 'telefono',
            'folio' => 'id_folio',
            'usuario' => 'id_usuario',
            'fechaCreacion' => 'fecha_creacion',
            'fechaActualizacion' => 'fecha_modificacion',
            'fechaEliminacion' => 'fecha_eliminacion'
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $attribute = [
            'id_telefono' => 'clave',
            'telefono' => 'numeroTelefono',
            'id_folio' => 'folio',
            'id_usuario' => 'usuario',
            'fecha_creacion' => 'fechaCreacion',
            'fecha_modificacion' => 'fechaActualizacion',
            'fecha_eliminacion' => 'fechaEliminacion'
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
