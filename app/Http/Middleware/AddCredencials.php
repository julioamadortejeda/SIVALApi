<?php

namespace App\Http\Middleware;

use Closure;

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
        //se agregan los parametros a la peticion POST, para poder obtener un token, y asi evitar que esos parametros se envien
        //desde la aplicacion fontend
        $request->request->add([
            'grant_type' => 'password',
            'client_id' => 3,
            'client_secret' => 'hsOMGm8xlRbMv67D7gcFkI68cwHHLDkQ1f9m0YZH',
        ]);

        return $next($request);
    }
}
