<?php

namespace App;

use App\Empleado;
use App\TipoUsuario;
use App\Scopes\UserScope;
use Laravel\Passport\HasApiTokens;
use App\Transformers\UserTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int $id_usuario
 * @property int $id_empleado
 * @property int $id_tipo_usuario
 * @property string $user_name
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
    use Notifiable, HasApiTokens, SoftDeletes;
    
    public const USER_ADMINISTRADOR = 'administrador';
    public const USER_VALIDACION = 'validacion';
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_modificacion';
    const DELETED_AT = 'fecha_eliminacion';
    protected $hidden = ['password', 'fecha_creacion', 'fecha_modificacion', 'fecha_eliminacion'];
    public $transformer = UserTransformer::class;
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
    protected $fillable = ['id_empleado', 'id_tipo_usuario', 'user_name', 'nombre', 'password'];

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
    public function tipo_usuario()
    {
        return $this->belongsTo(TipoUsuario::class, 'id_tipo_usuario', 'id_tipo_usuario');
    }

    public function esAdministrador()
    {
        return strtolower($this->tipo_usuario->nombre) == User::USER_ADMINISTRADOR;
    }

    public function esValidacion()
    {
        return strtolower($this->tipo_usuario->nombre) == User::USER_VALIDACION;
        //return is_null($this->id_empleado) && !$this->esAdministrador();
    }

    public function findForPassport($username) {
        return $this->where('user_name', $username)->first();
    }
}
