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
            'gernete' => is_null($empleado->gerente) ? null : [ 
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
}
