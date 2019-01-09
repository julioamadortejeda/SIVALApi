<?php

namespace App;

use App\Folio;
use App\Orden;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id_folio_orden
 * @property int $id_folio
 * @property int $id_orden
 * @property string $fecha_creacion
 * @property string $fecha_modificacion
 * @property string $fecha_eliminacion
 * @property Folio $folio
 * @property Ordene $ordene
 */
class FolioOrden extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'FoliosOrdenes';
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_modificacion';
    const DELETED_AT = 'fecha_eliminacion';
    protected $hidden = ['fecha_creacion','fecha_modificacion', 'fecha_eliminacion'];
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'id_folio_orden';

    /**
     * @var array
     */
    protected $fillable = ['id_folio', 'id_orden'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function folio()
    {
        return $this->belongsTo(Folio::class, 'id_folio', 'id_folio');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ordene()
    {
        return $this->belongsTo(Orden::class, 'id_orden', 'id_orden');
    }
}
