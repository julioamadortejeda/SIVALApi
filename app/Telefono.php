<?php

namespace App;

use App\User;
use App\Folio;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\TelefonoTransformer;

/**
 * @property int $id_telefono
 * @property int $id_folio
 * @property int $id_usuario
 * @property string $telefono
 * @property string $fecha_creacion
 * @property string $fecha_modificacion
 * @property string $fecha_eliminacion
 * @property Folio $folio
 * @property User $usuario
 */
class Telefono extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'Telefonos';
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_modificacion';
    const DELETED_AT = 'fecha_eliminacion';
    protected $hidden = ['fecha_creacion','fecha_modificacion', 'fecha_eliminacion'];
    public $transformer = TelefonoTransformer::class;


    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'id_telefono';

    /**
     * @var array
     */
    protected $fillable = ['id_folio', 'id_usuario', 'telefono'];

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
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }
}
