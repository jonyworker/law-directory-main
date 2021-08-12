<?php

namespace App\Admin\Controllers;

use App\Models\Products;
use App\Models\Process;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;
use App\Admin\Selectable\Test;

class ProcessController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Process';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
     protected function detail($id)
    {
        $show = new Show(Process::findOrFail($id));

        return $show;
    }

    
    protected function grid()
    {
        $grid = new Grid(new Process());
        $grid->model()->orderby('completed','asc');

     //   $grid->column('id', __('Id'));
    //    $grid->column('product_id', __('Product id'));
        $grid->column('product_code', __('貨號'));
        $grid->column('product_name', __('名稱'));
        
        
        $grid->column('a', '包材/瓶器')->expand(function ($model) {
            $comments = $model->take(10)->get()->map(function ($comment) {
                return $comment->only(['package_date', 'package_confirm_date', 'package_purchase_date','container_date', 'container_confirm_date', 'container_purchase_date']);
            });
            return new Table(['包材提供日期', '包材確認日期', '瓶器採購日期','瓶器提供日期', '瓶器確認日期', '包材採購日期'], $comments->toArray());
        });
        $grid->column('b', '樣品')->expand(function ($model) {
            $comments = $model->take(10)->get()->map(function ($comment) {
                return $comment->only(['sample_date', 'sample_confirm_date', 'product_purchase_date', 'estimate_arrival','product_info_date', 'confirm_design_date']);
            });
            return new Table(['樣品提供日期', '樣品確認日期','產品採購日期','預計到達日','提供產品資訊日期','確認設計稿日期'], $comments->toArray());
        });
        $grid->column('intl_barcode', __('國際條碼'));
        $grid->column('supplier', __('供應商'));
        $grid->column('manufacturer', __('製造商'));
        $grid->column('event_date', __('預計活動期間'));
        $grid->column('estimate_arrival', __('預計到達日'));
        $grid->column('completed', __('完成流程'))->bool();

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Process());
             //use App\Admin\Selectable\Test;
        $form->belongsTo('product_id', Test::class,'品名')->required();
    //    $form->number('product_id', __('Product id'));
        $form->hidden('product_code');
        $form->hidden('product_name');
        $form->text('intl_barcode', __('國際條碼'));
        $form->text('supplier', __('供應商'));
        $form->text('manufacturer', __('製造商'));
        $form->date('event_date', __('預計活動期間'))->format('YYYY-MM');
        $form->date('sample_date', __('樣品提供日期'));
        $form->date('container_date', __('瓶器提供日期'));
        $form->date('package_date', __('包材提供日期'));
        $form->date('sample_confirm_date', __('樣品確認日期'));
        $form->date('container_confirm_date', __('瓶器確認日期'));
        $form->date('package_confirm_date', __('包材確認日期'));
        $form->date('product_info_date', __('提供產品資訊日期'));
        $form->date('confirm_design_date', __('確認設計稿日期'));
        $form->date('container_purchase_date', __('瓶器採購日期'));
        $form->date('package_purchase_date', __('包材採購日期'));
        $form->date('product_purchase_date', __('產品採購日期'));
        $form->date('estimate_arrival', __('預計到達日'));
        $form->radio('completed', __('已完成流程'))->options([0=>'否',1=>'是']);
        
      
         $form->saving(function (Form $form) {
            $product = Products::where('id', $form->product_id)->first();  
         
            $form->input('product_name', $product->Name);
            $form->input('product_code', $product->Code);
        //    $form->input('government.ingredient_list', $form->ingredient_list);
        });
        return $form;
    }
}
