<?php

namespace App\Transformers;

use App\TipoUsuario;
use League\Fractal\TransformerAbstract;

class TipoUsuarioTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(TipoUsuario $tipoUsuario)
    {
        return [
            'clave' => (int)$tipoUsuario->id_tipo_usuario,
            'nombre' => (string)$tipoUsuario->nombre,
            'fechaCreacion' => (string)$tipoUsuario->fecha_creacion,
            'fechaActualizacion' => (string)$tipoUsuario->fecha_modificacion,
            'fechaEliminacion' => isset($tipoUsuario->fecha_eliminacion) ? (string)$tipoUsuario->fecha_eliminacion : null
        ];
    }

    public static function originalAttribute($index)
    {
        $attribute = [
            'clave' => 'id_tipo_usuario',
            'nombre' => 'nombre',
            'fechaCreacion' => 'fecha_creacion',
            'fechaActualizacion' => 'fecha_modificacion',
            'fechaEliminacion' => 'fecha_eliminacion'
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
