<?php 

namespace App\ProcesarExcel;

use App\Area;
use App\Giro;
use App\Folio;
use App\Gasto;
use App\Linea;
use App\Orden;
use App\Adeudo;
use App\Tienda;
use App\Campana;
use App\Cliente;
use App\Paquete;
use App\Rechazo;
use App\Division;
use App\Servicio;
use App\Empleado; 
use App\Estrategia;
use App\FolioOrden;
use App\TraficoVoz;
use App\Validacion;
use App\EstatusSIAC;
use App\Entretenimiento;
use App\LineaContratada;
use Illuminate\Database\Eloquent\Collection;

class PIPES1
{
	public static function procesarPIPES(Array $rows)
	{
		// $listaEstatusSIAC = Empleado::take(10)->select('id_empleado', 'nombre')->get()->toArray();
		// dd($listaEstatusSIAC);
		$listaEstatusSIAC = EstatusSIAC::take(10)->select('id_estatus_siac', 'nombre')->get();
		$listaLineas = Linea::take(10)->select('id_linea', 'nombre')->get();
		$listaLineasContratadas = LineaContratada::take(10)->select('id_linea_contratada', 'nombre')->get();
		$listaAreas = Area::take(10)->select('id_area', 'nombre')->get();
		$listaDivisiones = Division::take(10)->select('id_division', 'nombre')->get();
		$listaCampanas = Campana::take(10)->select('id_campana', 'nombre')->get();
		$listaServicios = Servicio::take(10)->select('id_servicio', 'nombre')->get();
		$listaPaquetes = Paquete::take(10)->select('id_paquete', 'nombre')->get();
		$listaTiendas = Tienda::take(10)->select('id_tienda', 'nombre')->get();
		$listaClientes = Cliente::take(10)->select('id_cliente', 'nombre')->get();
		$listaEntretenimientos = Entretenimiento::take(10)->select('id_entretenimiento', 'nombre')->get();
		$listaGastos = Gasto::take(10)->select('id_gasto', 'nombre')->get();
		$listaGiros = Giro::take(10)->select('id_giro', 'nombre')->get();
		$listaAdeudos = Adeudo::take(10)->select('id_adeudo', 'nombre')->get();
		$listaValidaciones = Validacion::take(10)->select('id_validacion', 'nombre')->get();
		$listaEstrategias = Estrategia::take(10)->select('id_estrategia', 'nombre')->get();
		$listaRechazos = Rechazo::take(10)->select('id_rechazo', 'nombre')->get();
		$listatraficos = TraficoVoz::take(10)->select('id_trafico_voz', 'nombre')->get();
		//dd($listaAdeudos);

		try 
		{
			$errores = collect([]);
			$linea = 0;
			foreach (array_slice($rows, 1) as $row) 
			{
				$linea++;
				try 
				{
					$fecha_captura = PIPES::transformDate($row[0]);

					if (is_null($fecha_captura)) {
						$errores->put($linea, sprintf(trans('mensajes.errorFechaCaptura'), $row[0]));
						continue;
					}

					$id_folio = trim($row[3]);

					if (is_null($id_folio) ||!is_numeric($id_folio) || $id_folio <= 0) {
						$errores->put($linea, sprintf(trans('mensajes.errorFolioInvalido'), $id_folio));
						continue;
					}

					$folio = Folio::find($id_folio) ?? new Folio();

					$folio->id_folio = $id_folio;
					$folio->fecha_captura = $fecha_captura;
					$folio->telefono_asignado = $row[14];
					$folio->telefono_portado = $row[15];
					$folio->fecha_cambio = PIPES::transformDate($row[26]);
					$folio->clave_empresa = $row[27];
					$folio->nombre_empresa = $row[28];
					$folio->facturacion_terceros = $row[29];
					//$folio->trafico_voz = $row[32];
					$folio->voz_entrante = $row[33];
					$folio->voz_saliente = $row[34];
					$folio->fecha_trafico_voz = PIPES::transformDate($row[35]);
					$folio->trafico_datos = $row[36];
					$folio->fecha_trafico_datos = PIPES::transformDate($row[37]);
					$folio->fecha_facturacion = PIPES::transformDate($row[38]);
					//$folio->descripcion_adeudo = $row[39];
					$folio->correo = $row[40];
					$folio->fecha_nacimiento = PIPES::transformDate($row[41]);
					$folio->id_aux = $row[42];
					$folio->Terminal = $row[43];
					$folio->Distrito = $row[44];
					$folio->Celular = $row[45];
					$folio->entrego_expediente = is_numeric($row[47]) && $row[47] == 1 ? 1 : 0 ;
					$folio->tipo_expediente = $row[48];
					$folio->fecha_expediente = PIPES::transformDate($row[49]);
					//$folio->Estrategia = $row[1];
					$folio->Observaciones = str_replace("'", "", $row[11]);
					$folio->respuesta_telmex = str_replace("'", "", $row[12]);
					//$folio->motivo_rechazo = str_replace("'", "", $row[13]);

					$id_empleado = trim($row[2]);
					if(!is_numeric($id_empleado) && $id_empleado <= 0) {
						$errores->put($linea, sprintf(trans('mensajes.errorNumeroEmpleado'), $id_empleado));
						continue;
					}

					$empleado = Empleado::find($id_empleado);

					if (is_null($empleado)) {
						$errores->put($linea, sprintf(trans('mensajes.errorEmpleadoNoExiste'), $id_empleado));
						continue;
					}
					
					if (!$empleado->empleadoValido()) {
						$errores->put($linea, sprintf(trans('mensajes.errorEmpleadoNoValido'), $id_empleado));
						continue;
					}

					$folio->id_empleado = $empleado->id_empleado;
					$folio->id_estatus_siac = PIPES1::asignarCatalogo(EstatusSIAC::class, $row[4], $listaEstatusSIAC);
					$folio->id_linea = PIPES1::asignarCatalogo(Linea::class, $row[5], $listaLineas);
					$folio->id_linea_contratada = PIPES1::asignarCatalogo(LineaContratada::class, $row[6], $listaLineasContratadas);
					$folio->id_area = PIPES1::asignarCatalogo(Area::class, $row[7], $listaAreas);
					$folio->id_division = PIPES1::asignarCatalogo(Division::class, $row[8], $listaDivisiones);
					$folio->id_tienda = PIPES1::asignarCatalogo(Tienda::class, $row[9], $listaTiendas);
					$folio->id_paquete = PIPES1::asignarCatalogo(Paquete::class, $row[10], $listaPaquetes);
					$folio->id_campana = PIPES1::asignarCatalogo(Campana::class, $row[20], $listaCampanas);

					$folio->id_adeudo = PIPES1::asignarCatalogo(Adeudo::class, $row[39], $listaAdeudos);
					$folio->id_cliente = PIPES1::asignarCatalogo(Cliente::class, $row[51], $listaClientes);
					$folio->id_entretenimiento = PIPES1::asignarCatalogo(Entretenimiento::class, $row[53], $listaEntretenimientos);
					$folio->id_estrategia = PIPES1::asignarCatalogo(Estrategia::class, $row[1], $listaEstrategias);
					$folio->id_gasto = PIPES1::asignarCatalogo(Gasto::class, $row[54], $listaGastos);
					$folio->id_giro = PIPES1::asignarCatalogo(Giro::class, $row[55], $listaGiros);
					$folio->id_rechazo = PIPES1::asignarCatalogo(Rechazo::class, $row[13], $listaRechazos);
					$folio->id_trafico_voz = PIPES1::asignarCatalogo(TraficoVoz::class, $row[32], $listatraficos);
					$folio->id_validacion = PIPES1::asignarCatalogo(Validacion::class, $row[50], $listaValidaciones);

					$servicio = $row[52];
					//si el servicio viene vacio, se pone I- 2PLAY como defalut para indicar que es DP
					if (is_null($servicio) || strlen($servicio) == 0) {
						$servicio = trans('mensajes.paqueteDPDefault');
					}

					$folio->id_servicio = PIPES1::asignarCatalogo(Servicio::class, $servicio, $listaServicios);
					$numero_orden = trim($row[16]);
					
					//folio sin orden de servicio
					if (is_null($numero_orden) || strlen(trim($numero_orden)) == 0 || $numero_orden == 0) {
						$folio->save();
						continue;
						//return array(true, trans('mensajes.folioSinOrden'));
					}

					if (!is_null($numero_orden) && !is_numeric($numero_orden)) {
						$errores->put($linea, sprintf(trans('mensajes.errorOrdenServicio'), $numero_orden));
						continue;
					}

					$folio->save();

					/*se comprueba si la orden existe, en caso de ser asi, se verifica si ya hay un registro en FoliosOrdenes
					para esa orden con el folio, si es asi se procede a guardar la orden, si no, se crea*/
					$folioOrden = null;
					$orden = new Orden();
					$idsOrdenes = Orden::where('numero_orden', $numero_orden)->pluck('id_orden')->toArray();

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

					$orden->numero_orden = $numero_orden;
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
					if ($folio->estatus_siac->nombre != trans('mensajes.solicitudDuplicada') || !$orden->exists) {
						$orden->save();
					}
					
					if (is_null($folioOrden)) {
						FolioOrden::create(['id_orden' => $orden->id_orden, 'id_folio' => $folio->id_folio]);
					}
					
					continue;
					//return array(true, trans('mensajes.folioGuardado'));

				} catch (\Throwable $th) {
					$errores->put($linea, $th->getMessage());
					continue;
				}
			}

			return $errores;
		} 
		catch(\Exception $e) 
		{		
			return array(false, $e->getMessage(). ' *Error*');
		}
	}

	/******************************************************************
	//Asignacion o Creacion de catalogos
	*******************************************************************/
	private static function asignarCatalogo($modelo, $nombre = '', Collection $catalogo = null)
	{
		try 
		{
			if($nombre == '')
				return null;
			if (!is_null($catalogo)) {
				$objetoModelo = $catalogo->where('nombre', $nombre) ?? $modelo::create(['nombre' => $nombre]);
				dd($objetoModelo->nombre);
				if(!$objetoModelo->exists)
					$objetoModelo->save();
			}
			
			return $objetoModelo;
			
		} catch (\Exception $e) {
			 throw $e;			
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
		catch (\Exception $e) {
			return null;
            //return \Carbon\Carbon::createFromFormat($format, $value);
		}
	}
}

?>
