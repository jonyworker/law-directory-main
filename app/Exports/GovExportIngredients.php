<?php

namespace App\Exports;

use App\Models\Government;
use App\Models\Ingredients;
use App\Models\Products;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
//abstract class ExcelGovExporter extends AbstractExporter implements FromQuery, WithMultipleSheets
class GovExportIngredients implements FromQuery, WithHeadings, withTitle, withMapping
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
  //  protected $columns = [];
    
   
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
           ['*登錄編號','*產品中文品名','*產品英文品名','*單位(代碼)','*成分名稱' ,'*限量成分用途','*含量類別(A or B)','*含量 '],
           ['說明', '說明','說明','說明','說明','說明','說明','說明'],
        ];
        
    }

    public function title(): string
    {
        return '全成分 ';
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
             //   ->leftjoin([Ingredients])
            //    ->where()
                ->setEagerLoads([])
                ->forPage($this->page, $perPage)->get();
            
            
            // If $querybuilder is a Model, it must be reassigned, unless it is a eloquent/query builder.
            $queryBuilder = $queryBuilder->whereIn($keyName, $scope->pluck($keyName));
            
        }

        return $queryBuilder;
    }
    
    public function map($at): array{
        
      
        
           $fields = [
            sprintf("%014d", $at->product_code),  
            $at->product_name, 
            $at->product_en_name,
            $at->unit,
            $at->ingredient_name,
            $at->restrict_usage,
            $at->content,
            $at->content_amount
         ];
        return $fields;
    }
    public function query()
    {
       
        $columns = [
            'product_code' => '',
        ];
        if (!empty($columns)) {
            $columns = array_keys($columns);

            $eagerLoads = array_keys($this->getQuery()->getEagerLoads());

            $columns = collect($columns)->reject(function ($column) use ($eagerLoads) {
                return Str::contains($column, '.') || in_array($column, $eagerLoads);
            }) ;
            $dd = $this->getQuery()->select($columns->toArray())->get();
            return Ingredients::query()->wherein('product_code', $dd);
        }
        return $this->getQuery();
    }

}
