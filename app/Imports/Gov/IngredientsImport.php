<?php

namespace App\Imports\Gov;

use App\Models\Ingredients;
use App\Models\Products;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

//use Maatwebsite\Excel\Concerns\WithMultipleSheets;
//use Maatwebsite\Excel\Concerns\WithMapping;
//use Maatwebsite\Excel\Concerns\WithMappedCells;



class IngredientsImport implements ToCollection, WithStartRow, SkipsEmptyRows
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
                $id = $at->id;
            }
            else{
                $id = 0;
            }
            $ing = Ingredients::where('product_code', $code)
                ->where('ingredient_name', $row[4])
                ->first();
            if ($ing !== null) {
                $ing->update([
                    'product_en_name' => $row[2],
                    'unit' => $row[3],
                    'restrict_usage' => $row[5],
                    'content' => $row[6],
                    'content_amount' => $row[7],
                ]);
            } else {
                Ingredients::create([
                    'product_id' => $id,
                    'product_code' => $code,
                    'product_name' => $row[1],
                    'product_en_name' => $row[2],
                    'unit' => $row[3],
                    'ingredient_name' => $row[4],
                    'restrict_usage' => $row[5],
                    'content' => $row[6],
                    'content_amount' => $row[7],
                ]);
            }
        }
    }
    
}
