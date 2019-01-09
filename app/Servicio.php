<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id_servicio
 * @property string $nombre
 * @property string $fecha_creacion
 * @property string $fecha_modificacion
 * @property string $fecha_eliminacion
 */
class Servicio extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'Servicios';
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_modificacion';
    const DELETED_AT = 'fecha_eliminacion';
    protected $hidden = ['fecha_creacion','fecha_modificacion', 'fecha_eliminacion'];

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'id_servicio';

    /**
     * @var array
     */
    protected $fillable = ['nombre'];
}
