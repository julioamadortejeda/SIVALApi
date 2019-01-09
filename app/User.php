<?php

namespace App;

use App\Empleado;
use App\TipoUsuario;
use App\Scopes\UserScope;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id_usuario
 * @property int $id_empleado
 * @property int $id_tipo_usuario
 * @property string $nombre
 * @property string $password
 * @property string $fecha_creacion
 * @property string $fecha_modificacion
 * @property string $fecha_eliminacion
 * @property Empleado $empleado
 * @property Tiposusuario $tiposusuario
 */
class User extends Authenticatable
{
    private const USER_ADMINISTRADOR = 'administrador';
    use Notifiable, SoftDeletes;
    
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_modificacion';
    const DELETED_AT = 'fecha_eliminacion';
    protected $hidden = ['Password', 'fecha_creacion', 'fecha_modificacion', 'fecha_eliminacion'];
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'Users';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'id_usuario';

    /**
     * @var array
     */
    protected $fillable = ['id_empleado', 'id_tipo_usuario', 'nombre', 'password'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new UserScope);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipoUsuario()
    {
        return $this->belongsTo(TipoUsuario::class, 'id_tipo_usuario', 'id_tipo_usuario');
    }

    public function esAdministrador()
    {
        return strtolower($this->tipoUsuario->nombre) == User::USER_ADMINISTRADOR;
    }

    public function esValidacion()
    {
        return is_null($this->id_empleado);
    }
}
