<?php

namespace App\Imports;

use App\User;
use App\Folio;
use App\ProcesarExcel\PIPES2;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FolioImport implements ToArray, WithMultipleSheets
{       
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function array(Array $rows)
    {
        $errores = PIPES2::procesarPIPES($rows);

        if(!$errores->isEmpty())
           dump($errores);     
        //dd($errores);
    }
    
    // public function chunkSize(): int
    // {
    //     return 1500;
    // }

    //Metodo para indicar, en caso de que el excel tenga multiples hojas, 
    //solo la primera se procesara con el metodo array del modelo Folio
    public function sheets(): array
    {
        return [
            // Select by sheet index
            0 => new FolioImport(),
        ];
    }
}