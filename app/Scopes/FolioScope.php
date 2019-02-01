<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class FolioScope implements Scope
{
	public function apply(Builder $builder, Model $model)
	{
		$builder->with(['folio_orden', 'empleado', 'area', 'estatus_siac', 'linea',
					'linea_contratada', 'division', 'tienda', 'paquete', 'servicio', 'campana', 'adeudo', 'cliente', 'entretenimiento', 
					'estrategia', 'gasto', 'giro', 'rechazo', 'trafico_voz', 'validacion'])
				->orderBy('fecha_captura', 'desc')
                ->orderBy('id_folio','desc');
	}
}