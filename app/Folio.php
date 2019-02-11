<?php

namespace App;

use App\Area;
use App\Giro;
use App\Audio;
use App\Gasto;
use App\Linea;
use App\Orden;
use App\Adeudo;
use App\Tienda;
use App\Campana;
use App\Cliente;
use App\Paquete;
use App\Rechazo;
use App\Empleado;
use App\Servicio;
use App\Telefono;
use App\Direccion;
use App\Documento;
use App\Estrategia;
use App\FolioOrden;
use App\TraficoVoz;
use App\Validacion;
use App\Division;   
use App\EstatusSIAC;
use App\Entretenimiento;
use App\LineaContratada;
use App\Scopes\FolioScope;
use App\ProcesarExcel\PIPES;
use App\ProcesarExcel\PIPES1;
use App\ProcesarExcel\PIPES2;
use App\Transformers\FolioTransformer;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id_folio
 * @property int $id_empleado
 * @property int $id_estatus_siac
 * @property int $id_linea
 * @property int $id_linea_contratada
 * @property int $id_area
 * @property int $id_division
 * @property int $id_tienda
 * @property int $id_paquete
 * @property int $id_campana
 * @property int $id_servicio
 * @property string $fecha_captura
 * @property string $telefono_asignado
 * @property string $telefono_portado
 * @property string $fecha_cambio
 * @property int $clave_empresa
 * @property string $nombre_empresa
 * @property string $facturacion_terceros
 * @property string $trafico_voz
 * @property string $voz_entrante
 * @property string $voz_saliente
 * @property string $fecha_trafico_voz
 * @property string $trafico_datos
 * @property string $fecha_trafico_datos
 * @property string $fecha_facturacion
 * @property string $descripcion_adeudo
 * @property string $correo
 * @property string $fecha_nacimiento
 * @property string $id_aux
 * @property string $terminal
 * @property string $distrito
 * @property string $celular
 * @property boolean $entrego_expediente
 * @property string $tipo_expediente
 * @property string $fecha_expediente
 * @property string $estrategia
 * @property string $observaciones
 * @property string $respuesta_telmex
 * @property string $motivo_rechazo
 * @property boolean $validado
 * @property string $fecha_creacion
 * @property string $fecha_modificacion
 * @property string $fecha_eliminacion
 * @property Area $area
 * @property Campana $campana
 * @property Divisione $divisione
 * @property Empleado $empleado
 * @property Estatussiac $estatussiac
 * @property Linea $linea
 * @property Lineascontratada $lineascontratada
 * @property Paquete $paquete
 * @property Servicio $servicio
 * @property Tienda $tienda
 */
class Folio extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'Folios';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'id_folio';
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_modificacion';
    const DELETED_AT = 'fecha_eliminacion';
    protected $hidden = ['fecha_creacion','fecha_modificacion', 'fecha_eliminacion'];
    public $transformer = FolioTransformer::class;

    /**
     * @var array
     */
    protected $fillable = ['fecha_captura', 'id_empleado', 'id_estatus_siac', 'id_linea', 'id_linea_contratada', 'id_area', 'id_division', 'IdTienda', 'IdPaquete', 'id_campana', 'IdServicio',
    'id_adeudo', 'id_cliente', 'id_entretenimiento', 'id_estrategia', 'id_gasto', 'id_giro', 'id_rechazo', 'id_trafico_voz', 'id_validacion', 'telefono_asignado', 'telefono_portado', 'fecha_cambio', 'clave_empresa', 'nombre_empresa', 'facturacion_terceros', 'trafico_voz', 'voz_entrante', 'voz_saliente', 'fecha_trafico_voz', 'trafico_datos', 'fecha_trafico_datos', 'fecha_facturacion', 'descripcion_adeudo', 'correo', 'fecha_nacimiento', 'id_aux', 'terminal', 'distrito', 'celular', 'entrego_expediente', 'tipo_expediente', 'fecha_expediente', 'estrategia', 'observaciones', 'respuesta_telmex', 'motivo_rechazo'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new FolioScope);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area()
    {
        return $this->belongsTo(Area::class, 'id_area', 'id_area');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campana()
    {
        return $this->belongsTo(Campana::class, 'id_campana', 'id_campana');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function division()
    {
        return $this->belongsTo(Division::class, 'id_division', 'id_division');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function estatus_siac()
    {
        return $this->belongsTo(EstatusSIAC::class, 'id_estatus_siac', 'id_estatus_siac');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function linea()
    {
        return $this->belongsTo(Linea::class, 'id_linea', 'id_linea');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function linea_contratada()
    {
        return $this->belongsTo(LineaContratada::class, 'id_linea_contratada', 'id_linea_contratada');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paquete()
    {
        return $this->belongsTo(Paquete::class, 'id_paquete', 'id_paquete');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio', 'id_servicio');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tienda()
    {
        return $this->belongsTo(Tienda::class, 'id_tienda', 'id_tienda');
    }

    public function folio_orden()
    {
        return $this->hasMany(FolioOrden::class, 'id_folio', 'id_folio');
    }

    public function telefonos()
    {
        return $this->hasMany(Telefono::class, 'id_folio', 'id_folio');
    }

    public function direcciones()
    {
        return $this->hasMany(Direccion::class, 'id_folio', 'id_folio');
    }

    public function audios()
    {
        return $this->hasMany(Audio::class, 'id_folio', 'id_folio');
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class, 'id_folio', 'id_folio');
    }

    public function adeudo()
    {
        return $this->belongsTo(Adeudo::class, 'id_adeudo', 'id_adeudo');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function entretenimiento()
    {
        return $this->belongsTo(Entretenimiento::class, 'id_entretenimiento', 'id_entretenimiento');
    }

    public function estrategia()
    {
        return $this->belongsTo(Estrategia::class, 'id_estrategia', 'id_estrategia');
    }

    public function gasto()
    {
        return $this->belongsTo(Gasto::class, 'id_gasto', 'id_gasto');
    }

    public function giro()
    {
        return $this->belongsTo(Giro::class, 'id_giro', 'id_giro');
    }

    public function rechazo()
    {
        return $this->belongsTo(Rechazo::class, 'id_rechazo', 'id_rechazo');
    }

    public function trafico_voz()
    {
        return $this->belongsTo(TraficoVoz::class, 'id_trafico_voz', 'id_trafico_voz');
    }

    public function validacion()
    {
        return $this->belongsTo(Validacion::class, 'id_validacion', 'id_validacion');
    }

    /**
    Funciones 
    */

    public function estaValidado()
    {
        return $this->validado == 1;
    }
}
