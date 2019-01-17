<?php 

namespace App\ProcesarExcel;

use App\Empleado;

class DocEmpleados
{
	public static function procesarEmpleados(Array $row)
	{
		try 
		{
			$id_empleado = $row[0];
			$nombre = trim($row[1]);
			$rfc = trim($row[2]);
			$estatus = trim($row[5]);

			if(!is_numeric($id_empleado) || $id_empleado <= 0) /* || empty(trim($row[1])) || empty(trim($row[2])))*/
				return array(false, sprintf(trans('mensajes.errorNumeroEmpleado'), $id_empleado));

			$empleado = null;
			$empleado = Empleado::withTrashed()->find($id_empleado); //obtiene el empleado aunque haya sido eliminado

			if (!is_null($empleado)) {

				if ($empleado->nombre !=  $nombre) {
					$empleado->nombre = $nombre;
				}

				if ($empleado->rfc !=  $rfc) {
					$empleado->rfc = $rfc;
				}

				if ($empleado->estatus !=  $estatus) {
					$empleado->estatus = $estatus;
				}
				
				if ($empleado->isDirty()) {
					$empleado->save();
				}
			}
			else {
				$campos = [
					'id_empleado' => $id_empleado,
					'nombre' => $nombre,
					'rfc' => $rfc,
					'estatus' => $estatus
				];
				$empleado = Empleado::create($campos);
			}

			return array(true, trans('mensajes.empleadoGuardado'));
			
		} 
		catch (\Exception $e) 
		{
			return array(false, trans('mensajes.errorProcesarEmpleado'));
		}
	}

	public static function procesarGerentes(Array $row)
	{
		$id_empleado = $row[0];
		$id_gerente = trim($row[7]);

		if(!is_numeric($id_empleado) && $id_empleado <= 0)
			return array(false, sprintf(trans('mensajes.errorNumeroEmpleado'), $id_empleado));

		$empleado = Empleado::withTrashed()->find($id_empleado);//obtiene el empleado aunque haya sido eliminado
		$gerente = null;

		if (is_null($empleado))
			return array(false, sprintf(trans('mensajes.errorEmpleadoNoExiste'), $id_empleado));

		if (is_null($id_gerente) || $id_gerente == 0) {
			if ($empleado->id_gerente != null)
				$empleado->id_gerente = null;
		}
		else{
			$gerente = Empleado::withTrashed()->find($id_gerente);

			if (is_null($gerente)) 
				return array(false, sprintf(trans('mensajes.errorGerenteNoExiste'), $id_gerente)); //no existe un empleado con el id_gerente, ya que se proporciono un numero de gerente y la celda no era nula

			//$gerente = $gerente ?? Empleado::withTrashed()->find(200182);

			if ($empleado->id_gerente != $gerente->id_empleado) {
				$empleado->id_gerente = $gerente->id_empleado;
			}
		}

		$empleado->save();

		return array(true, trans('mensajes.empleadoGuardado'));
	}
}