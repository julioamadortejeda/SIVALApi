<?php

namespace App\Http\Controllers\empleado;

use App\Empleado;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\ApiController;

class EmpleadoController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('scope:administrador');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empleados = Empleado::with(['gerente'])->get();

        return $this->showAll($empleados);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $empleado = Empleado::with('gerente')->findOrFail($id);

        return $this->showOne($empleado);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->delete();

        return $this->showOne($empleado, 200);
    }

    public function import() 
    {
        $errores = Excel::import(new Empleado, request()->file('file'));
    }
}
