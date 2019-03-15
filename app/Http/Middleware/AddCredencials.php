<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Support\Facades\Auth;

class AddCredencials
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $credenciales = [
            'user_name' => $request->username,
            'password' => $request->password
        ];

        $scope = '';
        if (Auth::validate($credenciales)) {
           $user = User::where('user_name', $credenciales['user_name'])->first();
           if(!is_null($user)) {
                if($user->esAdministrador())
                    $scope = 'administrador';
                elseif ($user->esValidacion()) {
                    $scope = 'modificar-folios';
                }
           }
        }
        
        //se agregan los parametros a la peticion POST, para poder obtener un token, y asi evitar que esos parametros se envien
        //desde la aplicacion fontend
        $request->request->add([
            'grant_type' => 'password',
            'client_id' => 7,
            'client_secret' => 'TKmC9CG5aJcWgMTyUvD3BtJVstqmFT6byNps4Qri',
            'scope' => $scope,
        ]);

        return $next($request);
    }
}
