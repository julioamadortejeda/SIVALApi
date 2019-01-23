<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class FolioScope implements Scope
{
	public function apply(Builder $builder, Model $model)
	{
		$builder->with(['empleado', 'area', 'estatus_siac', 'linea',
					'linea_contratada', 'division', 'tienda', 'paquete', 'servicio', 'campana'])
				->orderBy('fecha_captura', 'desc')
                ->orderBy('id_folio','desc');
	}
}