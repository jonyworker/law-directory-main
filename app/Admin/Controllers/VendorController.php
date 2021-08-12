<?php

namespace App\Admin\Controllers;

use App\Models\Vendor;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Admin\Actions\Post\ImportPost;

class VendorController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Vendor';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Vendor());
        $grid->tools(function (Grid\Tools $tools) {
            $tools->append(new ImportPost());
        });

        $grid->column('id', __('Id'));
        $grid->column('vendor_id', __('Vendor id'));
        $grid->column('name', __('Name'));
        $grid->column('short_name', __('Short name'));
        $grid->column('telephone', __('Telephone'));
        $grid->column('tax_number', __('Tax number'));
        $grid->column('contact', __('Contact'));
        $grid->column('in_charge', __('In charge'));
        $grid->column('address', __('Address'));
        $grid->column('payment_type', __('Payment type'));
        $grid->column('payment_day', __('Payment day'));

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
        $show = new Show(Vendor::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('vendor_id', __('Vendor id'));
        $show->field('name', __('Name'));
        $show->field('short_name', __('Short name'));
        $show->field('telephone', __('Telephone'));
        $show->field('tax_number', __('Tax number'));
        $show->field('contact', __('Contact'));
        $show->field('in_charge', __('In charge'));
        $show->field('address', __('Address'));
        $show->field('payment_type', __('Payment type'));
        $show->field('payment_day', __('Payment day'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Vendor());

        $form->text('vendor_id', __('Vendor id'));
        $form->text('name', __('Name'));
        $form->text('short_name', __('Short name'));
        $form->text('telephone', __('Telephone'));
        $form->text('tax_number', __('Tax number'));
        $form->text('contact', __('Contact'));
        $form->text('in_charge', __('In charge'));
        $form->text('address', __('Address'));
        $form->text('payment_type', __('Payment type'));
        $form->text('payment_day', __('Payment day'));

        return $form;
    }
}
