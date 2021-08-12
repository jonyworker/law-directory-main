<?php

namespace App\Exports;

use App\Models\Government;
use App\Models\Manufacturer;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
//abstract class ExcelGovExporter extends AbstractExporter implements FromQuery, WithMultipleSheets
class GovExportManufacturer implements FromQuery, WithHeadings, withTitle, withMapping
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
           ['*登錄編號','*製造/包裝(代碼)','*工廠登記編號','*工廠名稱','*工廠地址' ,'*國別(代碼)'],
           ['說明', '說明','說明','說明','說明','說明'],
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
   /* public function prepareRows($rows): array
    {
        return array_map(function ($user) {
            $user->name .= ' (prepared)';

            return $user;
        }, $rows);
    }
    */
    public function title(): string
    {
        return '製造包裝作業場所';
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
        
        $b =   Manufacturer::where('id', $at->work_pack)->first();
        $a =   Manufacturer::where('id', $at->work_manu)->first();     
        $fields = [
            [
                sprintf("%014d", $at->product_code),  
                'A',
                $a->register_id,
                $a->manufacturer_name, 
                $a->address,
                $a->country
            ],
            [
                sprintf("%014d", $at->product_code),  
                'B',
                $b->register_id,
                $b->manufacturer_name, 
                $b->address,
                $b->country
            ],
         ];
        
        return $fields;
    }
    public function query()
    {
       
        $columns = [
            'product_code' => '',
            'work_manu' => '',
            'work_pack' => '',
        ];
        if (!empty($columns)) {
            $columns = array_keys($columns);

            $eagerLoads = array_keys($this->getQuery()->getEagerLoads());

            $columns = collect($columns)->reject(function ($column) use ($eagerLoads) {
                return Str::contains($column, '.') || in_array($column, $eagerLoads);
            }) ;
            return $this->getQuery()->select($columns->toArray());/*
        $dd = $this->getQuery()->select($columns->toArray())->get();
            
            dd( Manufacturer::query()->wherein('id', $dd));
            return Manufacturer::query()->wherein('id', $dd);*/
        }
        return $this->getQuery();
    }

}
