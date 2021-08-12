<?php

namespace App\Admin\Controllers;

use App\Models\Government;
use App\Models\Manufacturer;
use App\Models\Brand;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use App\Admin\Extensions\GovExporter;
use App\Admin\Selectable\Test;

class GovController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Government';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Government());
        $grid->exporter(new GovExporter());    
        $grid->model()->orderBy('product_code', 'asc');
    
        $grid->column('id', __('Id'));
      //  $grid->column('product_id', __('Product id'));
        $grid->column('product_code', __('Product code'));
        $grid->column('product_name', __('Product name'));
        $grid->column('brand', __('Brand'));
        $grid->column('work_manu', __('Work manu'));
        $grid->column('work_pack', __('Work pack'));

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
        $show = new Show(Government::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('product_id', __('Product id'));
        $show->field('product_code', __('Product code'));
        $show->field('product_name', __('Product name'));
        $show->field('brand', __('Brand'));
        $show->field('brand_name', __('Brand name'));
        $show->field('label', __('Label'));
        $show->field('packaging', __('Packaging'));
        $show->field('made_method', __('Made method'));
        $show->field('how_to_use', __('How to use'));
        $show->field('bad_reaction', __('Bad reaction'));
        $show->field('ingredients_chemical', __('Ingredients chemical'));
        $show->field('poison_info', __('Poison info'));
        $show->field('product_stable_report', __('Product stable report'));
        $show->field('microbe_report', __('Microbe report'));
        $show->field('perservative_test_report', __('Perservative test report'));
        $show->field('function_report', __('Function report'));
        $show->field('container_report', __('Container report'));
        $show->field('safety_report', __('Safety report'));
        $show->field('comment', __('Comment'));
        $show->field('domestic', __('Domestic'));
        $show->field('ingredient_list', __('Ingredient list'));
        $show->field('product_kind', __('Product kind'));
        $show->field('product_usage', __('Product usage'));
        $show->field('product_dosage', __('Product dosage'));
        $show->field('contact', __('Contact'));
        $show->field('work_manu', __('Work manu'));
        $show->field('work_pack', __('Work pack'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Government());

        $dom = [ 'A' =>'國產', 'B' =>'輸入'];
        $usage = ['01'=> '防曬', '02'=>'染髮', '03'=>'燙髮', '04'=>'止汗制臭', '05'=>'美白', '06'=>'抗菌', '07'=>'收斂', '08'=>'髮用造型', '09'=>'護髮', '10'=>'清潔頭髮', '11'=>'清潔臉部', '12'=>'清潔身體', '13'=>'彩妝', '14'=>'潤膚', '15'=>'保濕', '16'=>'軟化角質', '17'=>'去除角質', '18'=>'預防面皰、青春痘', '20'=>'脫色脫染', '21'=>'牙齒美白', '22'=>'刺激嗅覺（香水、香膏類）', '23'=>'清潔牙齒', '24'=>'清潔口腔', '99'=>'其他'];
        $dosage = ['F01'=>'粉劑','F02'=>'液劑','F03'=>'乳劑','F04'=>'油劑','F05'=>'油膏','F06'=>'固形','F07'=>'眉筆 ','F08'=>'噴霧劑','F09'=>'非手工香皂','F10'=>'手工香皂'];
        
        $form->belongsTo('product_id', Test::class,'商品')->required();
        $form->hidden('product_name');
        $form->hidden('product_code');
        
        $form->radio('domestic','國產/輸入')->options($dom)->required();
        $form->select('brand')->options(
              Brand::all()->pluck('name', 'id')
        )->required();
        $form->select('work_manu','作業場所(製造)')->options(
             Manufacturer::all()->pluck('manufacturer_name', 'id')
        )->required();
        $form->select('work_pack','作業場所(製造)')->options(
             Manufacturer::all()->pluck('manufacturer_name', 'id')
        )->required();
        $form->selector('product_kind','產品種類')->required();
        $form->select('product_usage','產品用途')->options($usage)->required();
        $form->select('product_dosage','產品劑型')->options($dosage)->required();
        $form->text('comment','使用注意事項')->required();
        $form->text('contact','聯絡人')->required();
        
        $form->divider();
        if($form->isEditing()){   
            $folder = Government::where('id',\Request::segment(3))->first()->product_code;
            $form->textarea('bad_reaction','產品使用不良反應');
            $form->multipleFile('packaging','外包裝瓶器(多)')->move('files/'.$folder)->removable();
            $form->file('label','標籤仿單(1)')->move('files/'.$folder)->removable();
            $form->file('made_method','化妝品優良製造準則證明(1)')->move('files/'.$folder)->removable();
            $form->file('how_to_use','使用方法部位用量頻率及族群(1)')->move('files/'.$folder)->removable();
            $form->multipleFile('ingredients_chemical','產品個別成分之物理化學特性(多)')->move('files/'.$folder)->removable();
            $form->multipleFile('poison_info','成分之毒理資料(多)')->move('files/'.$folder)->removable();
            $form->file('product_stable_report','產品安定性試驗報告(1)')->move('files/'.$folder)->removable();
            $form->file('microbe_report','微生物檢測報告(1)')->move('files/'.$folder)->removable();
            $form->file('perservative_test_report','防腐效能測試報告(1)')->move('files/'.$folder)->removable();
            $form->multipleFile('function_report','功能評估佐證報告(多)')->move('files/'.$folder)->removable();
            $form->multipleFile('container_report','與產品接觸之包裝材質資料(多)')->move('files/'.$folder)->removable();
            $form->multipleFile('safety_report','產品安全資料(多)')->move('files/'.$folder)->removable();
        }
        return $form;
    }
}
