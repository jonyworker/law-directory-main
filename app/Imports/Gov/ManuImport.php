<?php

namespace App\Imports\Gov;

use App\Models\Government;
use App\Models\Manufacturer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

//use Maatwebsite\Excel\Concerns\WithMultipleSheets;
//use Maatwebsite\Excel\Concerns\WithMapping;
//use Maatwebsite\Excel\Concerns\WithMappedCells;

class ManuImport implements ToCollection, WithStartRow, SkipsEmptyRows
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function startRow(): int
    {
        return 3;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $code = ltrim($row[0], '0');
            $at = Manufacturer::where('manufacturer_name',$row[3])->first();
            if($at !== null){
                $ida = $at->id;
            }
            else{
                $ida = 0;
            }
            if($row[1] == 'A'){
                Government::updateOrCreate(
                    ['product_code' => $code],
                    ['work_manu' => $ida]
                ); 
            }
            if($row[1] == 'B'){
                Government::updateOrCreate(
                    ['product_code' => $code],
                    ['work_pack' => $ida]
                ); 
            }
          
        }
    }
    
}
