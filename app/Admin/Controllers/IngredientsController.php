<?php

namespace App\Admin\Controllers;

use App\Models\Ingredients;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use App\Admin\Actions\Post\ImportIngredients;
use App\Imports\GovImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\MessageBag;

class IngredientsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Ingredients';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Ingredients());
        $grid->tools(function (Grid\Tools $tools) {
            $tools->append(new ImportIngredients());
        });
         $grid->filter(function($filter){
         //   $filter->disableIdFilter(false);
         });
        $grid->model()->orderBy('product_code', 'asc');
        $grid->column('id', __('Id'));
        
        
        $hue = ['1274' => true, 0 => false];
        $grid->column('product_code', __('Product code'));
        $grid->column('product_name', __('Product name'));
        $grid->column('unit', __('Unit'));
        $grid->column('ingredient_name', __('Ingredient name'));
        $grid->column('restrict_usage', __('Restrict usage'));
        $grid->column('content', __('Content'));
        $grid->column('content_amount', __('Content amount'));
        $grid->column('product_id', __('是否對應商品'))->display(function ($id) {
            if($id == 0){
                $icon = '<i class="fa fa-close text-red"></i>';
            }
            else{
                $icon = '<i class="fa fa-check text-green"></i>';
            }
            return $icon;
        });
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
        $show = new Show(Ingredients::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('product_id', __('Product id'));
        $show->field('product_code', __('Product code'));
        $show->field('product_name', __('Product name'));
        $show->field('unit', __('Unit'));
        $show->field('ingredient_name', __('Ingredient name'));
        $show->field('restrict_usage', __('Restrict usage'));
        $show->field('content', __('Content'));
        $show->field('content_amount', __('Content amount'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Ingredients());

        $form->number('product_id', __('Product id'));
        $form->text('product_code', __('Product code'));
        $form->text('product_name', __('Product name'));
        $form->text('product_en_name', __('Product name'));
        $form->text('unit', __('Unit'));
        $form->number('ingredient_name', __('Ingredient name'));
        $form->number('restrict_usage', __('Restrict usage'));
        $form->text('content', __('Content'));
        $form->text('content_amount', __('Content amount'));

        return $form;
    }
   
}
