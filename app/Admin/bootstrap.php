<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */
//Admin::disablePjax();
use Encore\Admin\Facades\Admin;
use App\Admin\Extensions\Selector;
use Encore\Admin\Form;
use Encore\Admin\Grid;

Encore\Admin\Form::forget(['map', 'editor']);
Admin::css('/css/style.css');
Form::extend('selector', Selector::class);

/*
Form::init(function (Form $form) {

    $form->disableEditingCheck();

    $form->disableCreatingCheck();

    $form->disableViewCheck();

    $form->tools(function (Form\Tools $tools) {
        $tools->disableDelete();
        $tools->disableView();
        $tools->disableList();
    });
});
*/
Grid::init(function (Grid $grid) {
     
     $grid->filter(function($filter){
        $filter->disableIdFilter();
        if(\Request::segment(2) != 'products'){
             $filter->column(1/2, function ($filter) {
                $filter->like('product_code', __('Code'));
                $filter->like('product_name', __('Name'));
             });     
        }
       
     });
     $grid->expandFilter();
     $grid->actions(function ($actions) {
        $actions->disableView();
    });
});
      