<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\ExcelGovExporter;
use App\Models\Products;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;

class GovExporter extends ExcelGovExporter
{
    protected $fileName = '政府上傳.xlsx';

    protected $columns = [
        //'id' => 'ID',
        'product_code' => '登陸編號',
        'domestic' => '國產/輸入(代碼)',
        'brand_name' => '產品品牌',
        'product_kind' => '產品品牌',
        'product_usage' => '產品品牌',
        'product_dosage' => '產品品牌',
        'comment' => '產品品牌',
        'contact' => '產品品牌',
    ];
}