<?php

namespace App\Admin\Selectable;

use App\Models\Products;
use Encore\Admin\Grid\Filter;
use Encore\Admin\Grid\Selectable;

class Test extends Selectable
{
    public $model = Products::class;

    public function make()
    {
        $this->column('Code','貨號');
        $this->column('Name','商品名稱');

        
        $this->filter(function (Filter $filter) {
            $filter->like('Code','貨號');
            $filter->like('Name','名稱');
            $filter->disableIdFilter();
        });
    }
}