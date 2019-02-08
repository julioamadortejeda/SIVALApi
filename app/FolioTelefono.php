<?php

namespace App;

use App\User;
use App\Folio;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id_folio_telefono
 * @property int $id_folio
 * @property string $telefono
 * @property string $fecha_creacion
 * @property string $fecha_modificacion
 * @property string $fecha_eliminacion
 * @property Folio $folio
 */
class FolioTelefono extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'FoliosTelefonos';
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_modificacion';
    const DELETED_AT = 'fecha_eliminacion';
    protected $hidden = ['fecha_creacion','fecha_modificacion', 'fecha_eliminacion'];

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'id_telefono';

    /**
     * @var array
     */
    protected $fillable = ['id_folio', 'id_user', 'telefono'];

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
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
