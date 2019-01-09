<?php

namespace App;

use App\Folio;
use App\Empleado;
use App\Scopes\EmpleadoScope;
use App\ProcesarExcel\DocEmpleados;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

/**
 * @property int $id_empleado
 * @property int $id_gerente
 * @property string $nombre
 * @property string $rfc
 * @property string $estatus
 * @property string $fecha_creacion
 * @property string $fecha_modificacion
 * @property string $fecha_eliminacion
 * @property Empleado $empleado
 */
class Empleado extends Model implements ToArray, WithMultipleSheets 
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'Empleados';
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_modificacion';
    const DELETED_AT = 'fecha_eliminacion';
    protected $hidden = ['fecha_creacion','fecha_modificacion', 'fecha_eliminacion'];

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'id_empleado';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['id_empleado','nombre', 'rfc', 'estatus'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gerente()
    {
        return $this->belongsTo(Empleado::class, 'id_gerente', 'id_empleado');
    }

    public function folios()
    {
        return $this->hasMany(Folio::class, 'id_empleado', 'id_empleado');
    }

    public function empleadosAsignados()
    {
        return $this->hasMany(Empleado::class, 'id_gerente', 'id_empleado');
    }

    public function empleadoValido()
    {
        //si el empleado esta eliminado con softdelete 
        //o el gerente es nulo (tambien eliminado con softdelete el empleado no es valido)
        return !$this->trashed() && !is_null($this->gerente);
    }

    /********************************************************************/
    /*FUNCIONES PARA PROCESAR EL ARCHIVO DE CARGA DE EMPLEADOS **********/
    public function array(Array $rows)
    {
        $errores = collect([]);
        $linea = 1;
        foreach (array_slice($rows, 1) as $row) 
        {         
            list($correcto, $mensaje) = DocEmpleados::procesarEmpleados($row);

            if (!$correcto) {
                $errores->put('Empleado-'. $linea, $mensaje);
            }

            $linea++;
        }

        //Se reprocesar el archivo para poder actualizar los gerentes
        $linea = 1;
        foreach (array_slice($rows, 1) as $row) 
        {
            list($correcto, $mensaje) = DocEmpleados::procesarGerentes($row);

            if (!$correcto) {
                $errores->put('Gerente-'. $linea, $mensaje);
            }

            $linea++;
        }

        return $errores;
    }

    //Metodo para indicar, en caso de que el excel tenga multiples hojas, 
    //solo la primera se procesara con el metodo array del modelo Empleado
    public function sheets(): array
    {
        return [
            // Select by sheet index
            0 => new Empleado(),
        ];
    }

    /**********FIN FUNCIONES DE CARGA****************************************/
}
