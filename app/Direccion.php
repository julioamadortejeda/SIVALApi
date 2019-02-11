<?php

namespace App;

use App\User;
use App\Folio;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\DireccionTransformer;

/**
 * @property int $id_direccion
 * @property int $id_usuario
 * @property int $id_folio
 * @property string $calle
 * @property string $numero
 * @property string $colonia
 * @property string $ciudad
 * @property string $estado
 * @property int $codigo_postal
 * @property string $datos_adicionales
 * @property string $fecha_creacion
 * @property string $fecha_modificacion
 * @property string $fecha_eliminacion
 * @property Folio $folio
 * @property User $user
 */
class Direccion extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'direcciones';
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_modificacion';
    const DELETED_AT = 'fecha_eliminacion';
    protected $hidden = ['fecha_creacion','fecha_modificacion', 'fecha_eliminacion'];
    public $transformer = DireccionTransformer::class;

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'id_direccion';

    /**
     * @var array
     */
    protected $fillable = ['id_usuario', 'id_folio', 'calle', 'numero', 'colonia', 'ciudad', 'estado', 'codigo_postal', 'datos_adicionales'];

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
    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }
}
