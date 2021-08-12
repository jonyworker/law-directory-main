<?php

namespace App\Admin\Controllers\Category;

use App\Models\Category;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;

use App\Imports\CatImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\MessageBag;

class CategorySmallController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '小分類';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Category());
        $grid->model()->small();
        
        $grid->quickCreate(function (Grid\Tools\QuickCreate $create) {
            $create->text('erp_id', 'ERP ID');
            $create->text('name', '小分類名稱');
            $create->select('parent_id', __('中分類'))->options(
                Category::Mid()->pluck('name', 'id')
            );
            $create->text('type','分級（不需改）')->default(3);
        });

        $grid->column('erp_id', __('ERP Id'));
        $grid->column('name', __('小分類名稱'));
        $grid->column('parent_id', __('中分類'))->display(function($userId) {
            return Category::find($userId)->name;
        });
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
        
        $form->text('erp_id', __('ID(ERP用)'));
        $form->select('parent_id', __('中分類'))->options(

            Category::Mid()->pluck('name', 'id')

            )->required();
   
        $form->text('name', __('小分類名稱'));
        $form->hidden('type', __('Type'))->default(3);

        return $form;
    }
    
    public function import() 
    {
        Excel::import(new CatImport, 'ERP-分類表a.xlsx','public');
        
         $success = new MessageBag([
            'title'   => 'Import',
            'message' => 'Success!',
        ]);
        
        return redirect('/admin/categorymiddle')->with(compact('success'));
    }
}
