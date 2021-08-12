<?php

namespace App\Imports;

use App\Models\Products;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithUpserts;

class ProductImport implements ToModel, WithStartRow, SkipsEmptyRows, WithBatchInserts, WithChunkReading, WithUpserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function startRow(): int
    {
        return 4;
    }
      public function batchSize(): int
    {
        return 1000;
    }
    
    public function chunkSize(): int
    {
        return 1000;
    }
    
    public function uniqueBy()
    {
        return 'Code';
    }
    
    public function model(array $row)
    {
        return new Products([
            
            'Code' => $row[0],
            'Name' => $row[1],
            'LargeCategorySerNo' => $row[4],
            'MiddleCategorySerNo' => $row[5],
            'BrandSerNo' => $row[6],
            'InventoryCycle' => $row[7],
            'InternationalBarcode' => $row[8],
            'UnitSerNo' => $row[9],
            'GoodsSource' => $row[10],
            'GoodsType' => $row[11],
            'IsCalculate' => $row[12],
            'IsStop' => $row[13],
            'IsSupplierConsignment' => $row[14],
            'KeyInDate' => $row[15],
            'MainSupplierSerNo' => $row[16],
            'Ename' => $row[17],
            'Remark' => $row[20],
            'PurchaseCurrencySerNo' => $row[21],
            'PurchaseRate' => $row[22],
            'PurchaseTaxType' => $row[23],
            'CostPrice' => $row[24],
            'SellCurrencySerNo' => $row[26],
            'SellRate' => $row[27],
            'SellTaxType' => $row[28],
            'TaxedListPrice' => $row[30],
            'POSEnabledDiscount' => $row[31],
            'IsEditableName' => $row[32],
            
        ]);
    }
}
