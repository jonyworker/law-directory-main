<?php

namespace App\Admin\Controllers\Category;

use App\Models\Category;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;

class CategoryLargeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '大分類';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Category());
        $grid->model()->large();
        
        $grid->quickCreate(function (Grid\Tools\QuickCreate $create) {
            $create->text('name', '大分類名稱');
            $create->text('id', 'ID');
            $create->text('parent_id')->default(0);
            $create->text('type')->default(1);
        });

    //    $grid->column('id', __('Id'));
        $grid->column('id', __('ERP Id'));
        $grid->column('name', __('大分類名稱'));
        $grid->children('中分類')->pluck('name')->label()->width(500);
        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Category());

        $form->hidden('parent_id', __('Parent id'))->default(0);
        $form->text('erp_id', __('ID'));
        $form->text('name', __('Name'));
        $form->hidden('type', __('Type'))->default(1);

        return $form;
    }
}
