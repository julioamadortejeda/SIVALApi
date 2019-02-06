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
use Illuminate\Support\Collection;

class PIPES2
{
	public static function procesarPIPES(Array $rows)
	{  
        $errores = collect([]);
        $listaEstatusSIAC = collect();
		$listaLineas = collect();
		$listaLineasContratadas = collect();
		$listaAreas = collect();
		$listaDivisiones = collect();
		$listaCampanas = collect();
		$listaServicios = collect();
		$listaPaquetes = collect();
		$listaTiendas = collect();
		$listaClientes = collect();
		$listaEntretenimientos = collect();
		$listaGastos = collect();
		$listaGiros = collect();
		$listaAdeudos = collect();
		$listaValidaciones = collect();
		$listaEstrategias = collect();
		$listaRechazos = collect();
        $listatraficos = collect();

        $estatus = array_unique(array_column(array_slice($rows, 1), 4));
        $lineas = array_unique(array_column(array_slice($rows, 1), 5));
        $lineasContratadas = array_unique(array_column(array_slice($rows, 1), 6));
        $areas = array_unique(array_column(array_slice($rows, 1), 7));
        $divisiones = array_unique(array_column(array_slice($rows, 1), 8));
        $tiendas = array_unique(array_column(array_slice($rows, 1), 9));
        $paquetes = array_unique(array_column(array_slice($rows, 1), 10));
        $campanas = array_unique(array_column(array_slice($rows, 1), 20));
        $adeudos = array_unique(array_column(array_slice($rows, 1), 39));
        $clientes = array_unique(array_column(array_slice($rows, 1), 51));
        $entretenimientos = array_unique(array_column(array_slice($rows, 1), 53));
        $estrategias = array_unique(array_column(array_slice($rows, 1), 1));
        $gastos = array_unique(array_column(array_slice($rows, 1), 54));
        $giros = array_unique(array_column(array_slice($rows, 1), 55));
        $rechazos = array_unique(array_column(array_slice($rows, 1), 13));
        $traficos = array_unique(array_column(array_slice($rows, 1), 32));
        $validaciones = array_unique(array_column(array_slice($rows, 1), 50));
        $servicios = array_unique(array_column(array_slice($rows, 1), 52));

        PIPES2::llenarCatalogo(EstatusSIAC::class, $estatus, $listaEstatusSIAC);
        PIPES2::llenarCatalogo(Linea::class, $lineas, $listaLineas);
        PIPES2::llenarCatalogo(LineaContratada::class, $lineasContratadas, $listaLineasContratadas);
        PIPES2::llenarCatalogo(Area::class, $areas, $listaAreas);
        PIPES2::llenarCatalogo(Division::class, $divisiones, $listaDivisiones);
        PIPES2::llenarCatalogo(Tienda::class, $tiendas, $listaTiendas);
        PIPES2::llenarCatalogo(Paquete::class, $paquetes, $listaPaquetes);
        PIPES2::llenarCatalogo(Campana::class, $campanas, $listaCampanas);
        PIPES2::llenarCatalogo(Servicio::class, $servicios, $listaServicios);
        PIPES2::llenarCatalogo(Adeudo::class, $adeudos, $listaAdeudos);
        PIPES2::llenarCatalogo(Cliente::class, $clientes, $listaClientes);
        PIPES2::llenarCatalogo(Entretenimiento::class, $entretenimientos, $listaEntretenimientos);
        PIPES2::llenarCatalogo(Estrategia::class, $estrategias, $listaEstrategias);
        PIPES2::llenarCatalogo(Gasto::class, $gastos, $listaGastos);
        PIPES2::llenarCatalogo(Giro::class, $giros, $listaGiros);
        PIPES2::llenarCatalogo(Rechazo::class, $rechazos, $listaRechazos);
        PIPES2::llenarCatalogo(TraficoVoz::class, $traficos, $listatraficos);
        PIPES2::llenarCatalogo(Validacion::class, $validaciones, $listaValidaciones);

        //dd($listaAdeudos);
		try 
		{
			$linea = 1; //se inicia en la linea 1, ya que se supone que el archivo tiene los encabezados de las columnas
			foreach (array_slice($rows, 1) as $row) 
			{
				$linea++;
				try 
				{
					$fecha_captura = PIPES::transformDate($row[0]);

					if (is_null($fecha_captura)) {
						$errores->put("Linea " . $linea, sprintf(trans('mensajes.errorFechaCaptura'), $row[0]));
						continue;
					}

					$id_folio = trim($row[3]);

					if (is_null($id_folio) ||!is_numeric($id_folio) || $id_folio <= 0) {
						$errores->put("Linea " . $linea, sprintf(trans('mensajes.errorFolioInvalido'), $id_folio));
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
						$errores->put("Linea " . $linea, sprintf(trans('mensajes.errorNumeroEmpleado'), $id_empleado));
						continue;
					}

					$empleado = Empleado::find($id_empleado);

					if (is_null($empleado)) {
						$errores->put("Linea " . $linea, sprintf(trans('mensajes.errorEmpleadoNoExiste'), $id_empleado));
						continue;
					}
					
					if (!$empleado->empleadoValido()) {
						$errores->put("Linea " . $linea, sprintf(trans('mensajes.errorEmpleadoNoValido'), $id_empleado));
						continue;
					}

					$folio->id_empleado = $empleado->id_empleado;
					$folio->id_estatus_siac = PIPES2::asignarCatalogo($row[4], $listaEstatusSIAC)->id_estatus_siac ?? null;
					$folio->id_linea = PIPES2::asignarCatalogo($row[5], $listaLineas)->id_linea ?? null;
					$folio->id_linea_contratada = PIPES2::asignarCatalogo($row[6], $listaLineasContratadas)->id_linea_contratada ?? null;
					$folio->id_area = PIPES2::asignarCatalogo($row[7], $listaAreas)->id_area ?? null;
					$folio->id_division = PIPES2::asignarCatalogo($row[8], $listaDivisiones)->id_division ?? null;
					$folio->id_tienda = PIPES2::asignarCatalogo($row[9], $listaTiendas)->id_tienda ?? null;
					$folio->id_paquete = PIPES2::asignarCatalogo($row[10], $listaPaquetes)->id_paquete ?? null;
					$folio->id_campana = PIPES2::asignarCatalogo($row[20], $listaCampanas)->id_campana ?? null;

					$folio->id_adeudo = PIPES2::asignarCatalogo($row[39], $listaAdeudos)->id_adeudo ?? null;
					$folio->id_cliente = PIPES2::asignarCatalogo($row[51], $listaClientes)->id_cliente ?? null;
					$folio->id_entretenimiento = PIPES2::asignarCatalogo($row[53], $listaEntretenimientos)->id_entretenimiento ?? null;
					$folio->id_estrategia = PIPES2::asignarCatalogo($row[1], $listaEstrategias)->id_estrategia ?? null;
					$folio->id_gasto = PIPES2::asignarCatalogo($row[54], $listaGastos)->id_gasto ?? null;
					$folio->id_giro = PIPES2::asignarCatalogo($row[55], $listaGiros)->id_giro ?? null;
					$folio->id_rechazo = PIPES2::asignarCatalogo($row[13], $listaRechazos)->id_rechazo ?? null;
					$folio->id_trafico_voz = PIPES2::asignarCatalogo($row[32], $listatraficos)->id_trafico_voz ?? null;
                    $folio->id_validacion = PIPES2::asignarCatalogo($row[50], $listaValidaciones)->id_validacion ?? null;

					$servicio = $row[52];
					//si el servicio viene vacio, se pone I- 2PLAY como defalut para indicar que es DP
					if (is_null($servicio) || strlen($servicio) == 0) {
                        $errores->put("Linea " . $linea, trans('mensajes.servicioNoValido'));
                        continue;
                        //$servicio = trans('mensajes.paqueteDPDefault');
					}

					$folio->id_servicio = PIPES2::asignarCatalogo($servicio, $listaServicios)->id_servicio;
					$numero_orden = trim($row[16]);
					
					//folio sin orden de servicio
					if (is_null($numero_orden) || strlen(trim($numero_orden)) == 0 || $numero_orden == 0) {
						$folio->save();
						continue;
						//return array(true, trans('mensajes.folioSinOrden'));
					}

					if (!is_null($numero_orden) && !is_numeric($numero_orden)) {
						$errores->put("Linea " . $linea, sprintf(trans('mensajes.errorOrdenServicio'), $numero_orden));
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

					dump($folio->folio_orden->count());
					
					if (is_null($folioOrden)) {
						FolioOrden::create(['id_orden' => $orden->id_orden, 'id_folio' => $folio->id_folio]);
					}

					
					$folio = null;
					continue;
					//return array(true, trans('mensajes.folioGuardado'));

				} catch (\Throwable $th) {
					//throw $th;
					$errores->put("Linea " . $linea, $th->getMessage());
					continue;
				}
			}

			return $errores;
		} 
		catch(\Exception $e) 
		{	
			//throw $e;
			$errores->put("Linea " . $linea, $e->getMessage(). ' *Error*');
		}

		return $errores;
	}

	/******************************************************************
	//Asignacion o Creacion de catalogos
	*******************************************************************/    
    private static function llenarCatalogo($modelo, Array $lista, Collection &$catalogo)
	{
		try 
		{           
            foreach ($lista as $item => $value) {
                if(empty($value))
                    continue;

                $objetoModelo = $modelo::where('nombre', $value)->first() ?? $modelo::create(['nombre' => $value]);
                $catalogo->push($objetoModelo);
            }
			
			return;
			
		} catch (\Exception $e) {
			 throw $e;			
			 return null;
		}
    }
    
    private static function asignarCatalogo($nombre = '', Collection $catalogo)
	{
		try 
		{
			if(is_null(trim($nombre)))
				return null;

            return $catalogo->firstWhere('nombre', $nombre);
			
		} catch (\Exception $e) {		
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
		catch (\Exception $e) {
			return null;
            //return \Carbon\Carbon::createFromFormat($format, $value);
		}
	}
}

?>
