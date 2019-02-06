<?php

namespace App\Transformers;

use App\Folio;
use League\Fractal\TransformerAbstract;

class FolioTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Folio $folio)
    {
        return [
            'folio' => (int)$folio->id_folio,
            'fechaCaptura' => (string)$folio->fecha_captura,
            'telefonoAsignado' => (string)$folio->telefono_asignado,
            'telefonoPortado' => (string)$folio->telefono_portado,
            'fechaCambioEstatus' => (string)$folio->fecha_cambio,
            'claveEmpresa' => (int)$folio->clave_empresa,
            'NombreEmpresa' => (string)$folio->nombre_empresa,
            'facturacionTerceros' => (string)$folio->facturacion_terceros,
            //'traficoVoz' => (string)$folio->trafico_voz,
            'traficoVozEntrante' => (string)$folio->voz_entrante,
            'traficoVozSaliente' => (string)$folio->voz_saliente,
            'fechaTraficoVoz' => (string)$folio->fecha_trafico_voz,
            'traficoDatos' => (string)$folio->trafico_datos,
            'fechaTraficoDatos' => (string)$folio->fecha_trafico_datos,
            'fechaFacturacion' => (string)$folio->fecha_facturacion,
            //'descripcionAdeudo' => (string)$folio->descripcion_adeudo,
            'correo' => (string)$folio->correo,
            'fechaNacimiento' => (string)$folio->fecha_nacimiento,
            'IDAux' => (string)$folio->id_aux,
            'terminal' => (string)$folio->terminal,
            'distrito' => (string)$folio->distrito,
            'telefonoCelular' => (string)$folio->celular,
            'entregoExpediente' => (bool)$folio->entrego_expediente,
            'tipoExpediente' => (string)$folio->tipo_expediente,
            'fechaExpediente' => (string)$folio->fecha_expediente,
            //'estrategia' => (string)$folio->estrategia,
            'observaciones' => (string)$folio->observaciones,
            'respuestaTelmex' => (string)$folio->respuesta_telmex,
            //'motivoRechazo' => (string)$folio->motivo_rechazo,
            'estaValidado' => (bool)$folio->validado,
            // 'folio_orden' => empty($folio->folio_orden) ? null : [
            //     'clave' => (int) $folio->folio_orden->id_folio_orden,
            //     'ordenServicio' => (double) $folio->folio_orden->orden->numero_orden,
            //     //'fecha_orden' => isset($folio->folio_orden->fecha_orden) ? 
            // ],
            'folio_orden' => $folio->folio_orden,
            'empleado' => is_null($folio->empleado) ? null : [ 
                'clave' => (int)$folio->id_empleado,
                'nombre' => (string)$folio->empleado->nombre,
                'RFC' => (string)$folio->empleado->rfc,
                'estatus' => (string)$folio->empleado->estatus,                      
            ],
            'area' => is_null($folio->id_area) ? null : [ 
                'clave' => (int)$folio->id_area,
                'nombre' => (string)$folio->area->nombre
            ],
            'estatusSIAC' => is_null($folio->id_estatus_siac) ? null : [ 
                'clave' => (int)$folio->id_estatus_siac,
                'nombre' => (string)$folio->estatus_siac->nombre
            ],
            'linea' => is_null($folio->id_linea) ? null : [ 
                'clave' => (int)$folio->id_linea,
                'nombre' => (string)$folio->linea->nombre
            ],
            'lineaContratada' => is_null($folio->id_linea_contratada) ? null : [ 
                'clave' => (int)$folio->id_linea_contratada,
                'nombre' => (string)$folio->linea_contratada->nombre
            ],
            'division' => is_null($folio->id_division) ? null : [ 
                'clave' => (int)$folio->id_division,
                'nombre' => (string)$folio->division->nombre
            ],
            'tienda' => is_null($folio->id_tienda) ? null : [ 
                'clave' => (int)$folio->id_tienda,
                'nombre' => (string)$folio->tienda->nombre
            ],
            'paquete' => is_null($folio->id_paquete) ? null : [ 
                'clave' => (int)$folio->id_paquete,
                'nombre' => (string)$folio->paquete->nombre
            ],
            'servicio' => is_null($folio->id_servicio) ? null : [ 
                'clave' => (int)$folio->id_servicio,
                'nombre' => (string)$folio->servicio->nombre
            ],
            'campana' => is_null($folio->id_campana) ? null : [ 
                'clave' => (int)$folio->id_campana,
                'nombre' => (string)$folio->campana->nombre
            ],
            'adeudo' => is_null($folio->id_adeudo) ? null : [ 
                'clave' => (int)$folio->id_adeudo,
                'nombre' => (string)$folio->adeudo->nombre
            ],
            'cliente' => is_null($folio->id_cliente) ? null : [ 
                'clave' => (int)$folio->id_cliente,
                'nombre' => (string)$folio->cliente->nombre
            ],
            'entretenimiento' => is_null($folio->id_entretenimiento) ? null : [ 
                'clave' => (int)$folio->id_entretenimiento,
                'nombre' => (string)$folio->entretenimiento->nombre
            ],
            'estrategia' => is_null($folio->id_estrategia) ? null : [ 
                'clave' => (int)$folio->id_estrategia,
                'nombre' => (string)$folio->estrategia->nombre
            ], 
            'gasto' => is_null($folio->id_gasto) ? null : [ 
                'clave' => (int)$folio->id_gasto,
                'nombre' => (string)$folio->gasto->nombre
            ],
            'giro' => is_null($folio->id_giro) ? null : [ 
                'clave' => (int)$folio->id_giro,
                'nombre' => (string)$folio->giro->nombre
            ],
            'rechazo' => is_null($folio->id_rechazo) ? null : [ 
                'clave' => (int)$folio->id_rechazo,
                'nombre' => (string)$folio->rechazo->nombre
            ],
            'traficoVoz' => is_null($folio->id_trafico_voz) ? null : [ 
                'clave' => (int)$folio->id_trafico_voz,
                'nombre' => (string)$folio->trafico_voz->nombre
            ],
            'validacion' => is_null($folio->id_validacion) ? null : [ 
                'clave' => (int)$folio->id_validacion,
                'nombre' => (string)$folio->validacion->nombre
            ],
            'fechaCreacion' => (string)$folio->fecha_creacion,
            'fechaActualizacion' => (string)$folio->fecha_modificacion,
            'fechaEliminacion' => isset($folio->fecha_eliminacion) ? (string)$folio->fecha_eliminacion : null
        ];
    }

    public static function originalAttribute($index)
    {
        $attribute = [
            'folio' => 'id_folio',
            'fechaCaptura' => 'fecha_captura',
            'telefonoAsignado' => 'telefono_asignado',
            'telefonoPortado' => 'telefono_portado',
            'fechaCambioEstatus' => 'fecha_cambio',
            'claveEmpresa' => 'clave_empresa',
            'NombreEmpresa' => 'nombre_empresa',
            'facturacionTerceros' => 'facturacion_terceros',
            'traficoVoz' => 'trafico_voz',
            'traficoVozEntrante' => 'voz_entrante',
            'traficoVozSaliente' => 'voz_saliente',
            'fechaTraficoVoz' => 'fecha_trafico_voz',
            'traficoDatos' => 'trafico_datos',
            'fechaTraficoDatos' => 'fecha_trafico_datos',
            'fechaFacturacion' => 'fecha_facturacion',
            'descripcionAdeudo' => 'descripcion_adeudo',
            'correo' => 'correo',
            'fechaNacimiento' => 'fecha_nacimiento',
            'IDAux' => 'id_aux',
            'terminal' => 'terminal',
            'distrito' => 'distrito',
            'telefonoCelular' => 'celular',
            'entregoExpediente' => 'entrego_expediente',
            'tipoExpediente' => 'tipo_expediente',
            'fechaExpediente' => 'fecha_expediente',
            'estrategia' => 'estrategia',
            'observaciones' => 'observaciones',
            'respuestaTelmex' => 'respuesta_telmex',
            'motivoRechazo' => 'motivo_rechazo',
            'estaValidado' => 'validado',
            'empleado' => 'id_empleado',
            'area' => 'id_area',
            'estatusSIAC' => 'id_estatus_siac',
            'linea' => 'id_linea',
            'lineaContratada' => 'id_linea_contratada',
            'division' => 'id_division',
            'tienda' => 'id_tienda',
            'paquete' => 'id_paquete',
            'servicio' => 'id_servicio',
            'campana' => 'id_campana',
            'fechaCreacion' => 'fecha_creacion',
            'fechaActualizacion' => 'fecha_modificacion',
            'fechaEliminacion' => 'fecha_eliminacion'
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
    
}
