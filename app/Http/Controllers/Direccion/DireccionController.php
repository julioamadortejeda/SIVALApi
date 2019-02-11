<?php

namespace App\Http\Controllers\Direccion;

use App\Direccion;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class DireccionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $direcciones =  Direccion::all();

        return $this->showAll($direcciones);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $direccion = Direccion::findOrFail($id);
        $direccion->delete();

        return $this->showOne($direccion);
    }
}
