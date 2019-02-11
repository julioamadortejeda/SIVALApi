<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'clave' => (int)$user->id_usuario,
            'nombre' => (string)$user->nombre,
            'empleado' => is_null($user->empleado) ? null : [ 
                'clave' => (int)$user->id_empleado,
                'nombre' => (string)$user->empleado->nombre,
                'RFC' => (string)$user->empleado->rfc,
                'estatus' => (string)$user->empleado->estatus,                      
            ],
            'categoria' => is_null($user->tipo_usuario) ? null : [ 
                'clave' => (int)$user->tipo_usuario->id_tipo_usuario,
                'nombre' => (string)$user->tipo_usuario->nombre
            ],
            'fechaCreacion' => (string)$user->fecha_creacion,
            'fechaActualizacion' => (string)$user->fecha_modificacion,
            'fechaEliminacion' => isset($user->fecha_eliminacion) ? (string)$user->fecha_eliminacion : null
        ];
    }

    public static function originalAttribute($index)
    {
        $attribute = [
            'clave' => 'id_usuario',
            'nombre' => 'nombre',
            'empleado' => 'id_empleado',
            'contraseña' => 'password',
            'confirmacionContraseña' => 'password_confirmation',
            'categoria' => 'id_tipo_usuario',
            'fechaCreacion' => 'fecha_creacion',
            'fechaActualizacion' => 'fecha_modificacion',
            'fechaEliminacion' => 'fecha_eliminacion'
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $attribute = [
            'id_usuario' => 'clave',
            'nombre' => 'nombre',
            'id_empleado' => 'empleado',
            'password' => 'contraseña',
            'password_confirmation' => 'confirmacionContraseña',
            'id_tipo_usuario' => 'categoria',
            'fecha_creacion' => 'fechaCreacion',
            'fecha_modificacion' => 'fechaActualizacion',
            'fecha_eliminacion' => 'fechaEliminacion'
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
