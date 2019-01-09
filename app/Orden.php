<?php

namespace App;

use App\Folio;
use App\FolioOrden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id_orden
 * @property int $numero_orden
 * @property string $fecha_orden
 * @property string $estatus_orden_sigla
 * @property string $estatus_orden
 * @property string $fecha_posteo_orden
 * @property string $etapa_orden
 * @property int $orden_tv
 * @property string $fecha_orden_tv
 * @property string $estatus_orden_tv
 * @property string $fecha_posteo_orden_tv
 * @property string $etapa_orden_tv
 * @property string $fecha_creacion
 * @property string $fecha_modificacion
 * @property string $fecha_eliminacion
 */
class Orden extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'Ordenes';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'id_orden';
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_modificacion';
    const DELETED_AT = 'fecha_eliminacion';
    protected $hidden = ['fecha_creacion','fecha_modificacion', 'fecha_eliminacion'];
    
    /**
     * @var array
     */
    protected $fillable = ['NumeroOrden','FechaOrden', 'EstatusOrdenS', 'EstatusOrden', 'FechaPosteoOrden', 'EtapaOrden', 'OrdenTV', 'FechaOrdenTV', 'EstatusOrdenTV', 'FechaPosteoOrdenTV', 'EtapaOrdenTV'];
    
    public function folioOrden()
    {
        return $this->hasMany(FolioOrden::class, 'id_orden', 'id_orden');
    }
}
