<?php

namespace App\Imports\Gov;

use App\Models\Government;
use App\Models\Products;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

//use Maatwebsite\Excel\Concerns\WithMultipleSheets;
//use Maatwebsite\Excel\Concerns\WithMapping;
//use Maatwebsite\Excel\Concerns\WithMappedCells;

class ProgovImport implements ToCollection, WithStartRow, SkipsEmptyRows
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
            $at = Products::where('Code',$code)->first();
            if($at !== null){
                $ida = $at->id;
                $name = $at->Name;
            }
            else{
                $ida = 0;
                $name = 'Name';
            }
           
            Government::updateOrCreate(
                ['product_code' => $code],
                ['product_id' => $ida,
                 'product_name' => $name,
                 'domestic' =>  $row[1],
                 'brand_name' =>  $row[2],
                 'product_usage' =>  $row[4],
                 'product_kind' =>  $row[3],
                 'product_dosage' =>  $row[5],
                 'contact' =>  $row[7],
                 'comment' =>  $row[6]],
            );
            /*
            $ing =  Government::where('product_code', $code)->first();
            if ($ing !== null) {
                $ing->update([
                    'domestic' =>  $row[1],
                    'brand_name' =>  $row[2],
                    'product_usage' =>  $row[4],
                    'product_kind' =>  $row[3],
                    'product_dosage' => $row[5],
                    'contact' =>  $row[7],
                    'comment' =>  $row[6],
                ]);
            } else {
                 Government::create([
                    'product_id' => $ida,
                    'product_code' => $code,
                    'product_name' => $name,
                    'domestic' =>  $row[1],
                    'brand_name' =>  $row[2],
                    'product_usage' =>  $row[4],
                    'product_kind' =>  $row[3],
                    'product_dosage' =>  $row[5],
                    'contact' =>  $row[7],
                    'comment' =>  $row[6],
                ]);
               
            }*/
        }
    }
    
}
