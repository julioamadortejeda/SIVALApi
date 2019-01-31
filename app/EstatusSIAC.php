<?php

namespace App;

use App\Folio;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id_estatus_siac
 * @property string $nombre
 * @property string $fecha_creacion
 * @property string $fecha_modificacion
 * @property string $fecha_eliminacion
 */
class EstatusSIAC extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'EstatusSIAC';
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_modificacion';
    const DELETED_AT = 'fecha_eliminacion';
    protected $hidden = ['fecha_creacion','fecha_modificacion', 'fecha_eliminacion'];
    public $llave = 'id_estatus_siac';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'id_estatus_siac';

    /**
     * @var array
     */
    protected $fillable = ['nombre'];

    public function folio()
    {
        return $this->hasMany(Folio::class, 'id_estatus_siac', 'id_estatus_siac');
    }

}
