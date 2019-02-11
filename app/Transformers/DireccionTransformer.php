<?php

namespace App\Transformers;

use App\Direccion;
use League\Fractal\TransformerAbstract;

class DireccionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Direccion $direccion)
    {
        return [
            'clave' => (int)$direccion->id_direccion,
            'folio' => (int)$direccion->id_folio,
            'usuario' => [
                'clave' => (int)$direccion->user->id_usuario,
                'nombre' => (string)$direccion->user->nombre,
                'categoria' => (string)$direccion->user->tipo_usuario->nombre
            ],
            'calle' => (string)$direccion->calle,
            'numero' => (string)$direccion->numero,
            'colonia' => (string)$direccion->colonia,
            'ciudad' => (string)$direccion->ciudad, 
            'estado' => (string)$direccion->estado,
            'codigoPostal' => (string)$direccion->codigo_postal,
            'adicional' => empty($direccion->datos_adicionales) ? null : (string)$direccion->datos_adicionales,
            'fechaCreacion' => (string)$direccion->fecha_creacion,
            'fechaActualizacion' => (string)$direccion->fecha_modificacion,
            'fechaEliminacion' => isset($direccion->fecha_eliminacion) ? (string)$direccion->fecha_eliminacion : null
        ];
    }

    public static function originalAttribute($index)
    {
        $attribute = [
            'clave' => 'id_direccion',
            'folio' => 'id_folio',
            'usuario' => 'id_usuario',
            'calle' => 'calle',
            'numero' => 'numero',
            'colonia' => 'colonia',
            'ciudad' => 'ciudad',
            'estado' => 'estado',
            'codigoPostal' => 'codigo_postal',
            'adicional' => 'datos_adicionales',
            'fechaCreacion' => 'fecha_creacion',
            'fechaActualizacion' => 'fecha_modificacion',
            'fechaEliminacion' => 'fecha_eliminacion'
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $attribute = [
            'id_direccion' => 'clave',
            'id_folio' => 'folio',
            'id_usuario' => 'usuario',
            'calle' => 'calle',
            'numero' => 'numero',
            'colonia' => 'colonia',
            'ciudad' => 'ciudad',
            'estado' => 'estado',
            'codigo_postal' => 'codigoPostal',
            'datos_adicionales' => 'adicional',
            'fecha_creacion' => 'fechaCreacion',
            'fecha_modificacion' => 'fechaActualizacion',
            'fecha_eliminacion' => 'fechaEliminacion'
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
