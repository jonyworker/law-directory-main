<?php

namespace App\Imports;

use App\Imports\Gov\IngredientsImport;
use App\Imports\Gov\ProgovImport;
use App\Imports\Gov\ManuImport;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;


//use Maatwebsite\Excel\Concerns\WithMapping;
//use Maatwebsite\Excel\Concerns\WithMappedCells;

class GovImport implements WithMultipleSheets, SkipsUnknownSheets
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function sheets(): array
    {
        return [
            0 => new ProgovImport(),
            1 => new ManuImport(),
            2 => new IngredientsImport(),
        ];
    }
    
    public function onUnknownSheet($sheetName)
    {
        // E.g. you can log that a sheet was not found.
        info("Sheet {$sheetName} was skipped");
    }
    
}
