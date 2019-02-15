<?php

namespace App\Policies;

use App\User;
use App\Folio;
use Illuminate\Auth\Access\HandlesAuthorization;

class FolioPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the folio.
     *
     * @param  \App\User  $user
     * @param  \App\Folio  $folio
     * @return mixed
     */
    public function view(User $user, Folio $folio)
    {
        return $folio->empleado->id_empleado === $user->id_empleado || $folio->empleado->id_gerente === $user->id_empleado
                || $user->esAdministrador() || $user->esValidacion();
    }
}
