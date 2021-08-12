<?php

namespace App\Exports;

use App\Models\Government;
use App\Models\Brand;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
//abstract class ExcelGovExporter extends AbstractExporter implements FromQuery, WithMultipleSheets
class GovExportProducts implements FromQuery, WithHeadings, withTitle, withMapping
{
    use Exportable;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var array
     */
    protected $headings = [];

    

    private $grid;
    private $page;

    public function __construct($grid,$page)
    {
        $this->grid  = $grid;
        $this->page  = $page;
    }
    /**
     * @var array
     */
    protected $columns = [
        //'id' => 'ID',
        'product_code' => '登陸編號',
        'domestic' => '國產/輸入(代碼)',
        'brand' => '產品品牌',
        'product_kind' => '產品品牌',
        'product_usage' => '產品品牌',
        'product_dosage' => '產品品牌',
        'comment' => '產品品牌',
        'contact' => '產品品牌',
    ];
    
     public function map($at): array{
        
           $fields = [
            sprintf("%014d", $at->product_code),  
            $at->domestic, 
            Brand::where('id', $at->brand)->first()->name,
            sprintf("%04d", $at->product_kind), 
            sprintf("%02d", $at->product_kind), 
            $at->product_dosage,
            $at->comment,
            $at->contact
         ];
        return $fields;
    }
    
    
    public function getQuery()
    {
        $model = $this->grid->getFilter()->getModel();

        $queryBuilder = $model->getQueryBuilder();

        // Export data of giving page number.
        if ($this->page) {
            $keyName = $this->grid->getKeyName();
            $perPage = request($model->getPerPageName(), $model->getPerPage());

            $scope = (clone $queryBuilder)
                ->select([$keyName])
                ->setEagerLoads([])
                ->forPage($this->page, $perPage)->get();
            // If $querybuilder is a Model, it must be reassigned, unless it is a eloquent/query builder.
            $queryBuilder = $queryBuilder->whereIn($keyName, $scope->pluck($keyName));
        }
        return $queryBuilder;
    }
    /**
     * @return array
     */
    public function headings(): array
    {
     /*   if (!empty($this->columns)) {
            return array_values($this->columns);
        }

        return $this->headings;*/
        
        return [
           ['*登錄編號', '*國產/輸入(代碼)','產品品牌','*產品種類(代碼)','*產品用途(代碼)','*產品劑型(代碼)','*使用注意事項','*聯絡人'],
           ['欄位說明：', '欄位說明：','欄位說明：','欄位說明：','欄位說明：', '欄位說明：','欄位說明：','欄位說明：'],
        ];
        
    }

    /*
      $sheet->setColumnFormat([
                'C' => "aaaaa",
                'O' => "#",
                'Q' => "dd mmmm yyyy HH:mm:ss"
            ]);
    */
    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function query()
    {
       /* return Government
            ::query()
            ->whereYear('created_at', $this->year)
            ->whereMonth('created_at', $this->month);*/

        
        if (!empty($this->columns)) {
         $columns = array_keys($this->columns);

            $eagerLoads = array_keys($this->getQuery()->getEagerLoads());

            $columns = collect($columns)->reject(function ($column) use ($eagerLoads) {
                return Str::contains($column, '.') || in_array($column, $eagerLoads);
            });
            
            return $this->getQuery()->select($columns->toArray());
        }

        return $this->getQuery();
    }
      public function title(): string
        {
            return '產品基本資訊';
        }

}
