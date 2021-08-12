<?php

namespace App\Imports;

use App\Models\Brand;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
//use Maatwebsite\Excel\Concerns\WithMultipleSheets;
//use Maatwebsite\Excel\Concerns\WithMapping;
//use Maatwebsite\Excel\Concerns\WithMappedCells;

class CatImport implements ToModel, WithStartRow
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
    /*
    Code	Name	GoodsStyleCode	SpecName	LargeCategorySerNo	MiddleCategorySerNo	BrandSerNo	InventoryCycle	InternationalBarcode	UnitSerNo	GoodsSource	GoodsType	IsCalculate	IsStop	IsSupplierConsignment	KeyInDate	MainSupplierSerNo	Ename	ESpecName	ProductPlaceSerNo	Remark	PurchaseCurrencySerNo	PurchaseRate	PurchaseTaxType	CostPrice	TaxedCostPrice	SellCurrencySerNo	SellRate	SellTaxType	ListPrice	TaxedListPrice	POSEnabledDiscount	IsEditableName	GoodsDescribe*/
    
    public function model(array $row)
    {
        return new Brand([
            'id' => $row[0],
            'name' => $row[1],
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
