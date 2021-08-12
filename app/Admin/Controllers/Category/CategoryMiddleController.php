<?php

namespace App\Admin\Controllers\Category;

use App\Models\Category;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;

use App\Imports\VendorImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\MessageBag;

class CategoryMiddleController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '中分類';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Category());
        $grid->model()->mid();
        
        $grid->quickCreate(function (Grid\Tools\QuickCreate $create) {
            $create->text('erp_id', 'ERP ID');
            $create->text('name', '中分類名稱');
            $create->select('parent_id', __('大分類'))->options(
                Category::Large()->pluck('name', 'id')
            );
            $create->text('type','分級（不需改）')->default(2);
        });

        $grid->column('erp_id', __('ERP Id'));
        $grid->column('name', __('大分類名稱'));
        $grid->column('parent_id', __('大分類'))->display(function($userId) {
            return Category::find($userId)->name;
        });
    //    $grid->column('parent_id', __('中分類名稱'));
    //    $grid->column('parent_id', __('parent ID'))->editable();
        $grid->children('小分類')->pluck('name')->label()->width(500);
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
        $form->select('parent_id', __('大分類'))->options(

            Category::Large()->pluck('name', 'id')

            )->required();
   
        $form->text('name', __('中分類名稱'));
        $form->hidden('type', __('Type'))->default(2);

        return $form;
    }
    
    public function import() 
    {
        Excel::import(new VendorImport, 'kind.xlsx','public');
        
         $success = new MessageBag([
            'title'   => 'Import',
            'message' => 'Success!',
        ]);
        
        return redirect('/admin/categorymiddle')->with(compact('success'));
    }
}
