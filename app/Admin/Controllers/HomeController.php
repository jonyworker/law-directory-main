<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Admin\Dashboard\Dash;
//use Encore\Admin\Controllers\Dashboard;

use App\Admin\Actions\Post\ImportGov;
use App\Admin\Actions\Post\ImportIngredients;
use App\Admin\Actions\Post\ImportProduct;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;

class HomeController extends Controller
{
    public function setting(Content $content)
    {
        return $content
            ->title('Website setting')
            ->body(new Setting());
    }
    public function index(Content $content)
    {
        return $content
            ->title('伊日商品管理資料庫')
          //  ->description('Description...')
            ->row(function (Row $row) {
                  $row->column(6, function (Column $column) {
                        $column->append(Dash::title());
                        $column->append('<div class="import-class">');
                        $column->append(new ImportGov());
                        $column->append('<span>匯入政府資料(.xsls)</span><br> ');
                        $column->append(new ImportIngredients());
                        $column->append(' <span>只匯入成分(.xsls)</span><br> ');
                        $column->append(new ImportProduct());
                        $column->append('<span> 匯入商品(ERP)</span><br> ');
                        $column->append(' </div> ');
                  });
                $row->column(2, function (Column $column) {
                  
            //        $column->append(Dashboard::environment());
                });
                $row->column(2, function (Column $column) {
                 
                });
                $row->column(2, function (Column $column) {
                 
                });


                $row->column(6, function (Column $column) {
                    $column->append(Dash::newproducts());
                });
            });
    }
}
