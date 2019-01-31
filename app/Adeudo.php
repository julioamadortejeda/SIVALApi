<?php

namespace App;

use App\Folio;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id_adeudo
 * @property string $nombre
 * @property string $fecha_creacion
 * @property string $fecha_modificacion
 * @property string $fecha_eliminacion
 * @property Folio[] $folios
 */
class Adeudo extends Model
{
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_modificacion';
    const DELETED_AT = 'fecha_eliminacion';
    protected $hidden = ['fecha_creacion','fecha_modificacion', 'fecha_eliminacion'];
    public $llave = 'id_adeudo';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'id_adeudo';

    /**
     * @var array
     */
    protected $fillable = ['nombre'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function folios()
    {
        return $this->hasMany(Folio::class, 'id_adeudo', 'id_adeudo');
    }
}
