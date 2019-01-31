<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id_validacion
 * @property string $nombre
 * @property string $fecha_creacion
 * @property string $fecha_modificacion
 * @property string $fecha_eliminacion
 */
class Validacion extends Model
{
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_modificacion';
    const DELETED_AT = 'fecha_eliminacion';
    protected $hidden = ['fecha_creacion','fecha_modificacion', 'fecha_eliminacion'];
    public $llave = 'id_validacion';

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'Validaciones';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'id_validacion';

    /**
     * @var array
     */
    protected $fillable = ['nombre'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function folios()
    {
        return $this->hasMany(Folio::class, 'id_validacion', 'id_validacion');
    }

}
