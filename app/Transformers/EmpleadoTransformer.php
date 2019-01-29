<?php

namespace App\Transformers;

use App\Empleado;
use League\Fractal\TransformerAbstract;

class EmpleadoTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Empleado $empleado)
    {
        return [
            'clave' => (int)$empleado->id_empleado,
            'nombre' => (string)$empleado->nombre,
            'RFC' => (string)$empleado->rfc,
            'estatus' => (string)$empleado->estatus,
            'gerente' => is_null($empleado->gerente) ? null : [ 
                'clave' => (int)$empleado->gerente->id_empleado,
                'nombre' => (string)$empleado->gerente->nombre,
                'RFC' => (string)$empleado->gerente->rfc,
                'estatus' => (string)$empleado->gerente->estatus,                      
            ],
            'fechaCreacion' => (string)$empleado->fecha_creacion,
            'fechaActualizacion' => (string)$empleado->fecha_modificacion,
            'fechaEliminacion' => isset($empleado->fecha_eliminacion) ? (string)$empleado->fecha_eliminacion : null
        ];
    }

    public static function originalAttribute($index)
    {
        $attribute = [
            'clave' => 'id_empleado',
            'nombre' => 'nombre',
            'RFC' => 'rfc',
            'estatus' => 'estatus',
            'fechaCreacion' => 'fecha_creacion',
            'fechaActualizacion' => 'fecha_modificacion',
            'fechaEliminacion' => 'fecha_eliminacion'
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
