<?php

namespace App\Admin\Controllers;

use App\Models\Quality;
use App\Models\Products;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Admin;
use App\Admin\Selectable\Test;
//use App\Imports\VendorImport;
//use Maatwebsite\Excel\Facades\Excel;
//use Illuminate\Support\MessageBag;

class QualityController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '品管';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Quality());
     //   $grid->column('id', __('Id'));
        $grid->filter(function($filter){
            // Add a column filter
            $filter->column(1/2, function ($filter) {
                $filter->equal('history','是否留樣')->radio(['' => '無',0 => '否',1 => '是',]);
            });
        

        });
        $grid->column('id')->display(function ($id) {
            return "<a href='/admin/quality-control/$id/edit'>編輯</a>";
        })->width(50);
        $grid->column('product_code', __('貨號'));
        $grid->column('product_name', __('品名'))->width(100);
        $grid->column('purchase_date', __('進貨日期'));
        $grid->column('purchase_quantity', __('進貨數量'))->hide();
        $grid->column('manufacture_date', __('製造日期'))->hide();
        $grid->column('manufacture_number', __('批號'));
        $grid->column('package', __('外盒包裝'))->bool();
        $grid->column('product_color', __('產品色澤'))->bool();
        $grid->column('product_smell', __('產品氣味'))->bool();
        $grid->column('product_test', __('產品試用'))->bool();
        $grid->column('test_number', __('抽檢數'));
        $grid->column('problem_number', __('不良數'));
        $grid->column('problem_reason', __('不良原因'))->hide();
        $grid->column('problem_percentage', __('不良率'));
        $grid->column('pass', __('判定通過/退回'))->replace([0 => '退回' , 1 => '通過']);;
        $grid->column('history', __('是否留樣'))->replace([0 => '否' , 1 => '是']);;
    /*    $grid->column('history')->using([
            1 => '是',
            0 => '否',
        ], 'Unknown')->dot([
            1 => 'success',
            0 => 'danger',
        ], 'warning');*/

         $grid->actions(function ($actions) {
            $actions->disableView();
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
        Admin::script("
            $('#test_number, #problem_number').change(function(e)  {
                var p = parseFloat($('#problem_number').val());
                var t = parseFloat($('#test_number').val());
                $('#problem_percentage').val((p / t) * 100 +'%');
            });
        ");
        $form = new Form(new Quality());
        $form->belongsTo('product_id', Test::class,'商品');
        $states = [ 1 =>'YES', 0 =>'NO'];
        $status = [ 1 =>'OK', 0 =>'NO'];
        $pass = [ 1 =>'通過', 0 =>'退回'];
        $form->hidden('product_code', __('貨號'));
        $form->hidden('product_name', __('品名'));
        $form->date('purchase_date', __('進貨日期'))->required();
        $form->date('manufacture_date', __('製造日期'))->required();
        $form->text('purchase_quantity', __('進貨數量'));
        $form->text('manufacture_number', __('批號'))->required();
        $form->radio('package','外盒包裝')->options($status);
        $form->radio('product_color', __('產品色澤'))->options($status);
        $form->radio('product_smell', __('產品氣味'))->options($status);
        $form->radio('product_test', __('產品試用'))->options($status);
        $form->text('test_number', __('抽檢數'));
        $form->text('problem_number', __('不良數'));
        $form->text('problem_reason', __('不良原因：備註'));
        $form->text('problem_percentage', __('抽檢不良率'));
        $form->radio('pass', __('判定通過/退回'))->options($pass);
        $form->radio('history', __('是否留樣'))->options($states);


        $form->saving(function (Form $form) {
            $product = Products::where('id', $form->product_id)->first();  
            $form->input('product_name', $product->Name);
            $form->input('product_code', $product->Code);
        //    $form->input('government.ingredient_list', $form->ingredient_list);
        });
        return $form;
    }
    /*
    public function import() 
    {
        Excel::import(new VendorImport, 'ERP_vendor.xlsx','public');
        
         $success = new MessageBag([
            'title'   => 'title...',
            'message' => 'message....',
        ]);
        
        return redirect('/admin')->with(compact('success'));
    }*/
}
