<?php 

namespace App\ProcesarExcel;

use App\Area;
use App\Folio;
use App\Linea;
use App\Orden;
use App\Tienda;
use App\Campana;
use App\Paquete;
use App\Division;
use App\Empleado;
use App\Servicio;
use App\FolioOrden;
use App\EstatusSIAC;
use App\LineaContratada;

class PIPES
{
	public static function procesarPIPES(Array $row)
	{
		try{
			$fecha_captura = PIPES::transformDate($row[0]);

			if (is_null($fecha_captura)) 
				return array(false,  $row[0]);

			if (!is_numeric($row[3]) || $row[3] <= 0 || is_null($row[3])) 
				return array(false, sprintf(trans('mensajes.errorFolioInvalido'), $row[3]));

			$folio = Folio::find($row[3]) ?? new Folio();

			$folio->id_folio = $row[3];
			$folio->fecha_captura = $fecha_captura;
			$folio->telefono_asignado = $row[14];
			$folio->telefono_portado = $row[15];
			$folio->fecha_cambio = PIPES::transformDate($row[26]);
			$folio->clave_empresa = $row[27];
			$folio->nombre_empresa = $row[28];
			$folio->facturacion_terceros = $row[29];

			$folio->trafico_voz = $row[32];
			$folio->voz_entrante = $row[33];
			$folio->voz_saliente = $row[34];
			$folio->fecha_trafico_voz = PIPES::transformDate($row[35]);
			$folio->trafico_datos = $row[36];
			$folio->fecha_trafico_datos = PIPES::transformDate($row[37]);
			$folio->fecha_facturacion = PIPES::transformDate($row[38]);
			$folio->descripcion_adeudo = $row[39];
			$folio->correo = $row[40];
			$folio->fecha_nacimiento = PIPES::transformDate($row[41]);
			$folio->id_aux = $row[42];
			$folio->Terminal = $row[43];
			$folio->Distrito = $row[44];
			$folio->Celular = $row[45];
			$folio->entrego_expediente = is_numeric($row[47]) && $row[47] == 1 ? 1 : 0 ;
			$folio->tipo_expediente = $row[48];
			$folio->fecha_expediente = PIPES::transformDate($row[49]);
			$folio->Estrategia = $row[1];
			$folio->Observaciones = str_replace("'", "", $row[11]);
			$folio->respuesta_telmex = str_replace("'", "", $row[12]);
			$folio->motivo_rechazo = str_replace("'", "", $row[13]);

			if(!is_numeric($row[2]) && $row[2] <= 0){
				return array(false, sprintf(trans('mensajes.errorNumeroEmpleado'), $row[2]));
			}

			$empleado = Empleado::find($row[2]);

			if ($empleado == null)
				return array(false, sprintf(trans('mensajes.errorEmpleadoNoExiste'), $row[2]));

			$folio->id_empleado = $empleado->id_empleado;
			$folio->id_estatus_siac = PIPES::asignarCatalogo(EstatusSIAC::class, $row[4])->id_estatus_siac ?? null;;
			$folio->id_linea = PIPES::asignarCatalogo(Linea::class, $row[5])->id_linea ?? null;;
			$folio->id_linea_contratada = PIPES::asignarCatalogo(LineaContratada::class, $row[6])->id_linea_contratada ?? null;;
			$folio->id_area = PIPES::asignarCatalogo(Area::class, $row[7])->id_area;
			$folio->id_division = PIPES::asignarCatalogo(Division::class, $row[8])->id_division ?? null;;
			$folio->id_tienda = PIPES::asignarCatalogo(Tienda::class, $row[9])->id_tienda ?? null;;
			$folio->id_paquete = PIPES::asignarCatalogo(Paquete::class, $row[10])->id_paquete ?? null;
			$folio->id_campana = PIPES::asignarCatalogo(Campana::class, $row[20])->id_campana ?? null;

			$servicio = $row[46];
			//si el servicio viene vacio, se pone I- 2PLAY como defalut para indicar que es DP
			if (is_null($servicio) || strlen($servicio) == 0) {
				$servicio = trans('mensajes.paqueteDPDefault');
			}

			$folio->id_servicio = PIPES::asignarCatalogo(Servicio::class, $servicio)->id_servicio;

			//folio sin orden de servicio
			if (is_null($row[16]) || strlen(trim($row[16])) == 0 || $row[16] == 0) {
				$folio->save();
				return array(true, trans('mensajes.folioSinOrden'));
			}

			if (!is_null($row[16]) && !is_numeric($row[16]))
				return array(false, sprintf(trans('mensajes.errorOrdenServicio'), $row[16]));

			$folio->save();

			/*se comprueba si la orden existe, en caso de ser asi, se verifica si ya hay un registro en FoliosOrdenes
			para esa orden con el folio, si es asi se procede a guardar la orden, si no, se crea*/
			$folioOrden = null;
			$orden = new Orden();
			$idsOrdenes = Orden::where('numero_orden', $row[16])->pluck('id_orden')->toArray();

			if (!empty($idsOrdenes)) {
				$idsFoliosOrdenes = FolioOrden::where('id_folio', $folio->id_folio)->pluck('id_orden')->toArray();

				if (!empty($idsFoliosOrdenes)) {
					$idOrdenEncontrada = array_intersect($idsOrdenes, $idsFoliosOrdenes);
					if (!empty($idOrdenEncontrada)) {
						$orden = Orden::find(reset($idOrdenEncontrada));
						$folioOrden = FolioOrden::where('id_folio', $folio->id_folio)->where('id_orden', $orden->id_orden)->first();
					}
				}
			}

			$orden->numero_orden = $row[16];
			$orden->fecha_orden = PIPES::transformDate($row[17]);
			$orden->estatus_orden_sigla = $row[21];
			$orden->estatus_orden = $row[22];
			$orden->fecha_posteo_orden = PIPES::transformDate($row[23]);
			$orden->etapa_orden = $row[30];
			$orden->orden_tv = $row[18];
			$orden->fecha_orden_tv = PIPES::transformDate($row[19]);
			$orden->estatus_orden_tv = $row[24];
			$orden->fecha_posteo_orden_tv = PIPES::transformDate($row[25]);
			$orden->etapa_orden_tv = $row[31];

			//Solo si el estatussiac no es de duplicado o si la orden es nueva, entonces se guardan los cambios de la orden
			if ($folio->estatussiac->nombre != trans('mensajes.solicitudDuplicada') || !$orden->exists) {
				$orden->save();
			}
			
			if (is_null($folioOrden)) {
				FolioOrden::create(['id_orden' => $orden->id_orden, 'id_folio' => $folio->id_folio]);
			}

			return array(true, trans('mensajes.folioGuardado'));

		} catch(\ErrorException $e) {
			//throw $e;
			
			return array(false, $e->getMessage(). '------------Error. ');
		}
	}

	/******************************************************************
	//Asignacion o Creacion de catalogos
	*******************************************************************/

	private static function asignarCatalogo($modelo, $nombre = '')
	{
		try 
		{
			if($nombre == '')
				return null;

			$objetoModelo = $modelo::where('nombre', $nombre)->first();
			if ($objetoModelo == null) 
			{
				$objetoModelo = $modelo::create(['nombre' => $nombre]);
			}

			$objetoModelo->save();
			return $objetoModelo;
			
		} catch (Exception $e) {
			return null;			
		}
	}

	/**************Fin de Asignacion o Creacion de catalogos***********/

	public static function transformDate($value, $format = 'd-m-Y')
	{
		try 
		{
			$fecha = \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));

			if ($fecha > date('Y-m-d', strtotime('2000-01-01')))
				return $fecha;
			else
				return null;
			//return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
		} 
		catch (\ErrorException $e) {
			return null;
            //return \Carbon\Carbon::createFromFormat($format, $value);
		}
	}
}

?>
