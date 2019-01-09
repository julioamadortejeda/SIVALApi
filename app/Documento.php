<?php

namespace App;

use App\Folio;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id_folio_documento
 * @property int $id_folio
 * @property string $nombre
 * @property string $ruta
 * @property string $fecha_creacion
 * @property string $fecha_modificacion
 * @property string $fecha_eliminacion
 */
class Documento extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'Documentos';
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_modificacion';
    const DELETED_AT = 'fecha_eliminacion';
    protected $hidden = ['fecha_creacion','fecha_modificacion', 'fecha_eliminacion'];

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'id_documento';

    /**
     * @var array
     */
    protected $fillable = ['id_folio', 'nombre', 'ruta'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function folio()
    {
        return $this->belongsTo(Folio::class, 'id_folio', 'id_folio');
    }

}
