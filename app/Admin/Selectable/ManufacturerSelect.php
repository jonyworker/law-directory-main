<?php

namespace App\Admin\Selectable;

use App\Models\Manufacturer;
use Encore\Admin\Grid\Filter;
use Encore\Admin\Grid\Selectable;

class ManufacturerSelect extends Selectable
{
    public $model = Manufacturer::class;

    public function make()
    {
        $this->column('manufacturer_name','廠商名稱');
        $this->column('address','地址');
        $this->column('country','國別');

        $this->filter(function (Filter $filter) {
            $filter->like('manufacturer_name','廠商名稱');
            $filter->like('country','國別');
            $filter->disableIdFilter();
        });
    }
}