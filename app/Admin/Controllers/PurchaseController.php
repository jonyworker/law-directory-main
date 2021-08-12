<?php

namespace App\Admin\Controllers;

use App\Models\Purchase;
use App\Models\Products;
use App\Models\Ingredients;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Admin;

use App\Admin\Selectable\Test;

class PurchaseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Purchase';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Purchase());
       
        $grid->model()->orderBy('product_code', 'asc');
   
         Admin::script("
        ");
    //    $grid->column('id', __('Id'));
    //    $grid->column('product_id', __('Product id'));
        $grid->column('product_code', __('Product code'));
        $grid->column('product_name', __('Product name'));
        $grid->column('date', __('Date'))->editable('date');
        $grid->column('warehouse_num', __('Warehouse num'));
        $grid->column('company_num', __('Company num'));
        $grid->column('sale_amount', __('Sale amount'));
        $grid->column('estimate_amount', __('Estimate amount'));
        $grid->column('estimate_add', __('Estimate add'));
        $grid->column('actual_amount', __('Actual amount'))->editable();
        $grid->column('estimate_arrival', __('Estimate arrival'))->editable('date');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Purchase::findOrFail($id));

     //   $show->field('id', __('Id'));
    //    $show->field('product_id', __('Product id'));
        $show->field('product_code', __('Product code'));
        $show->field('product_name', __('Product name'));
        $show->field('date', __('Date'));
        $show->field('warehouse_num', __('Warehouse num'));
        $show->field('company_num', __('Company num'));
        $show->field('sale_amount', __('Sale amount'));
        $show->field('estimate_amount', __('Estimate amount'));
        $show->field('estimate_add', __('Estimate add'));
        $show->field('actual_amount', __('Actual amount'));
        $show->field('estimate_arrival', __('Estimate arrival'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        Admin::script("
            $('#company_num, #sale_amount').change(function(e)  {
                var company = parseFloat($('#company_num').val());
                var sale = parseFloat($('#sale_amount').val());
                parseFloat($('#estimate_amount').val(Math.abs(sale - company)));
                var es = $('#estimate_amount').val();
                $('#estimate_add').val((es * 1.1).toFixed());   
            });
        ");
        $form = new Form(new Purchase());
        $form->belongsTo('product_id', Test::class);
       // $form->number('product_id', __('Product id'));
        $form->hidden('product_code', __('Product code'));
        $form->hidden('product_name', __('Product name'));
        $form->date('date', __('Date'));
        $form->text('warehouse_num', __('Warehouse num'));
        $form->text('company_num', __('Company num'));
        $form->text('sale_amount', __('Sale amount'));
        $form->text('estimate_amount', __('Estimate amount'));
        $form->text('estimate_add', __('Estimate add'));
        $form->text('actual_amount', __('Actual amount'));
        $form->date('estimate_arrival', __('Estimate arrival'));

        $form->saving(function (Form $form) {
            $product = Products::where('id', $form->product_id)->first();  
    
            $form->input('product_name', $product->Name);
            $form->input('product_code', $product->Code);
        //    $form->input('government.ingredient_list', $form->ingredient_list);
        });
        
        return $form;
    }
}
