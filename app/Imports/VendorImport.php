<?php

namespace App\Imports;

use App\Models\GovKind;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMappedCells;

class VendorImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function startRow(): int
    {
        return 2;
    }
    /*
    public function mapping(): array
    {
        return [
            'vendor_id'  => 'A18',
            'name'  => 'B18',
            'short_name'  => 'B19',
            'telephone'  => 'C18',
            'tax_number'  => 'D18',
            'contact'  => 'E18',
            'incharge'  => 'E19',
            'address'  => 'F18',
            'payment_type' => 'G18',
            'payment_day' => 'H18',
           
        ];
    }
    */
    public function model(array $row)
    {
        return new GovKind([
            //
            'id' => $row[0],
            'name' => $row[1],
            'description' => $row[2],
            
            /*
            'vendor_id' => $row['vendor_id'],
            'name' => $row['name'],
            'short_name' => $row['short_name'],
            'telephone' => $row['telephone'],
            'tax_number' => $row['tax_number'],
            'contact' => $row['contact'],
            'in_charge' => $row['incharge'],
            'address' => $row['address'],
            'payment_type' => $row['payment_type'],
            'payment_day' => $row['payment_day'],
            */
            /*'name' => $row[1],
            'short_name' => $row[0],
            'telephone' => $row[3],
            'tax_number' => $row[4],
            'contact' => $row[5],
            'in_charge' => $row[5],
            'address' => $row[6],
            'payment_type' => $row[7],
            'payment_day' => $row[8],*/
        ]);
    }
}
