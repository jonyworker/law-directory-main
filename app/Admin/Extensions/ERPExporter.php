<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\ExcelERPExporter;
use App\Models\Products;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;

class ERPExporter extends ExcelERPExporter
{
    protected $fileName = 'ERP產品匯出.xlsx';

    protected $columns = [
        'Code' => 'Code',
    ];
}