<?php

namespace App;

use App\Folio;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id_area
 * @property string $nombre
 * @property string $fecha_creacion
 * @property string $fecha_modificacion
 * @property string $fecha_eliminacion
 */
class Area extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'Areas';
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_modificacion';
    const DELETED_AT = 'fecha_eliminacion';
    protected $hidden = ['fecha_creacion','fecha_modificacion', 'fecha_eliminacion'];
    //public $timestamps = false;

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'id_area';

    /**
     * @var array
     */
    protected $fillable = ['nombre'];

    /*
        *****************************Funciones***************************************
    */
    public function folios()
    {
        return $this->hasMany(Folio::class, 'id_area', 'id_area');
    }

}
