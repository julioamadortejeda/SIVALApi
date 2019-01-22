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
            'clave' => (int) $user->id_usuario,
            'nombre' => (string) $user->nombre,
            'empleado' => [ 'clave' => $user->id_empleado,
                            'nombre' => $user->empleado->nombre                        
            ]
        ];
    }
}
