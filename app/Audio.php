<?php

namespace App;

use App\User;
use App\Folio;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\AudioTransformer;

/**
 * @property int $id_folio_audio
 * @property int $id_folio
 * @property string $nombre
 * @property string $ruta
 * @property string $fecha_creacion
 * @property string $fecha_modificacion
 * @property string $fecha_eliminacion
 * @property Folio $folio
 * @property User $usuario
 */
class Audio extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'Audios';
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_modificacion';
    const DELETED_AT = 'fecha_eliminacion';
    protected $hidden = ['fecha_creacion','fecha_modificacion', 'fecha_eliminacion'];
    public $transformer = AudioTransformer::class;

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'id_audio';

    /**
     * @var array
     */
    protected $fillable = ['id_folio', 'id_usuario', 'nombre', 'ruta'];

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
