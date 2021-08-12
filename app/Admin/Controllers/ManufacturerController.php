<?php

namespace App\Admin\Controllers;

use App\Models\Manufacturer;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ManufacturerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Manufacturer';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Manufacturer());

        $grid->column('id', __('Id'));
        $grid->column('manufacturer_name', __('Manufacturer name'));
        $grid->column('register_id', __('Register id'));
        $grid->column('address', __('Address'));
        $grid->column('country', __('Country ID'));
        $grid->column('comment', __('Comment'));

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
        $show = new Show(Manufacturer::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('manufacturer_name', __('Manufacturer name'));
        $show->field('register_id', __('Register id'));
        $show->field('address', __('Address'));
        $show->field('country', __('Country'));
        $show->field('comment', __('Comment'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Manufacturer());

        $form->text('manufacturer_name', __('Manufacturer name'));
        $form->number('register_id', __('Register id'));
        $form->text('address', __('Address'));
        $form->text('country', __('Country ID'));
        $form->text('comment', __('Comment'));

        return $form;
    }
}
