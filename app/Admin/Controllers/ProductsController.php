<?php

namespace App\Admin\Controllers;

use App\Models\Products;
use App\Models\Category;
use App\Models\Vendor;
use App\Models\Brand;
use App\Models\Process;
use App\Models\Manufacturer;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Admin\Extensions\ERPExporter;
use App\Admin\Actions\Post\ImportProduct;
use App\Exports\ERPExport;
use Maatwebsite\Excel\Facades\Excel;
//use App\Admin\Selectable\ManufacturerSelect;

use App\Http\Controllers\Controller;



class ProductsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '所有產品';
   
    
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Products());
         $grid->tools(function (Grid\Tools $tools) {
            $tools->append(new ImportProduct());
        });
        $grid->model()->orderBy('Code', 'asc');
        $grid->filter(function($filter){
            // Remove the default id filter
            $filter->disableIdFilter();
            // Add a column filter
            $filter->column(1/3, function ($filter) {
                $filter->like('Code', __('Code'));
                $filter->like('Name', __('Name'));
            });
            $filter->column(1/3, function ($filter) {
                $filter->equal('LargeCategorySerNo', '大分類')->select(Category::Large()->pluck('name', 'id'));
                $filter->equal('BrandSerNo', '品牌')->select(Brand::All()->pluck('name', 'id'));
            });
        });
   
        
         $grid->column('id','貨號')->display(function () {
            return "<a href='/admin/products/$this->id/edit'>$this->Code</a>";
        });
     //   $grid->column('Code', __('Code'));
        $grid->column('Name', __('Name'));
        $grid->column('Ename', __('Ename'));
        $grid->column('BrandSerNo','品牌')->display(function ($id) {
            return(Brand::find($id)->name);
        });
        $grid->column('LargeCategorySerNo','大分類')->display(function ($id) {
            return(Category::find($id)->name);
        });
        $grid->column('MiddleCategorySerNo','中分類')->display(function ($id) {
            return(Category::find($id)->name);
        });
   
      //  $grid->exporter(new GovExporter());
        $grid->exporter(new ERPExporter());
        return $grid;
    }
    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Products());
        
        Admin::script('
            jQuery("#has-many-controls .add").click(function (){
                setTimeout(function() {
                    jQuery(".has-many-controls-form .product_code").val(jQuery("input#Code").val());
                    jQuery(".has-many-controls-form .product_name").val(jQuery("input#Name").val());
                 }, 1500);
            });
            jQuery("#has-many-purchase .add").click(function (){
                setTimeout(function() {
                    jQuery(".has-many-purchase-form .product_code").val(jQuery("input#Code").val());
                    jQuery(".has-many-purchase-form .product_name").val(jQuery("input#Name").val());
                 }, 1500);
            });
             jQuery("#has-many-ingredients .add").click(function (){
                setTimeout(function() {
                    jQuery(".has-many-ingredients-form .product_code").val(jQuery("input#Code").val());
                    jQuery(".has-many-ingredients-form .product_name").val(jQuery("input#Name").val());
                    jQuery(".has-many-ingredients-form .product_en_name").val(jQuery("input#Ename").val());
                 }, 1500);
            });
            $("#company_num, #sale_amount").change(function(e)  {
                var company = parseFloat($(".company_num").val());
                var sale = parseFloat($(".sale_amount").val());
                parseFloat($(".estimate_amount").val(Math.abs(sale - company)));
                var es = $(".estimate_amount").val();
                $("#estimate_add").val((es * 1.1).toFixed());   
            });
        ');
        
        $form->tab('產品基本資料', function ($form) {
            
           
            
            $form->setWidth(9, 2);
            $form->text('Code', __('貨號'))->required();
            $form->text('Name', __('商品名稱'))->required();
            $form->text('Ename', __('商品英文名稱'))->required();
       //     $form->text('SpecName', __('SpecName'));
        //    $form->number('ESpecName', __('ESpecName'));
            $form->date('SellYear', __('年度'))->format('YYYY')->default(date('Y'))->required();
            $form->date('KeyInDate', __('建檔日期'))->format('YYYYMMDD')->required();
            $form->select('LargeCategorySerNo', __('大分類'))->options(
                Category::Large()->pluck('name', 'id')
            )->load('MiddleCategorySerNo', '/admin/api/catMid')->required();

            $form->select('MiddleCategorySerNo', __('中分類'))->options(function ($id) {
                return Category::options($id);
            })->load('SmallCategorySerNo', '/admin/api/catSmall');

            $form->select('SmallCategorySerNo', __('小分類'))->options(function ($id) {
                return Category::options($id);
            });
             $form->select('BrandSerNo', __('品牌代號'))->options(
                Brand::all()->pluck('name', 'id')
            )->required();
            
            
            $inventory = [0=>'日盤點',1 => '週盤點', 2 => '雙週盤點', 3 => '月盤點',4=>'雙月盤點',5=>'季盤點',6=>'半年盤點',7=>'年盤點'];
            $form->select('InventoryCycle', __('盤點區分'))->options($inventory)->required();

            $form->number('GoodsSource', __('GoodsSource'))->required();
            $form->number('GoodsType', __('GoodsType'))->required();
            $form->number('IsCalculate', __('IsCalculate'))->required();
            $form->radio('IsStop', __('暫停使用標記'))->options([0 => '使用', 1=>'停用'])->default(0)->required();
            $form->select('MainSupplierSerNo', __('主供應商代號'))->options(
                 Vendor::all()->pluck('name', 'vendor_id')
            )->required();
          //  $form->number('MiddleBrandSerNo', __('MiddleBrandSerNo'));
            $form->text('Barcode', __('條碼'));
            $form->text('InternationalBarcode', __('國際條碼'));
           
           
        })->tab('其他資料', function ($form) {
            if($form->isEditing()){ 
                $folder = Products::where('id',\Request::segment(3))->first()->Code;  
                $form->file('art_file', __('設計部門AI檔案'))->move('ai/'.$folder)->removable();
            }
            $form->number('ModelCode', __('ModelCode'));
            $form->number('GoodsPropertiesSerNo', __('GoodsPropertiesSerNo'));
            $form->number('GoodsProperties2SerNo', __('GoodsProperties2SerNo'));
            $form->number('UnitSerNo', __('UnitSerNo'));
            $form->number('BigUnitSerNo', __('BigUnitSerNo'));
            $form->number('BigExchangeRate', __('BigExchangeRate'));
            $form->number('MiddleUnitSerNo', __('MiddleUnitSerNo'));
            $form->number('MiddleExchangeRate', __('MiddleExchangeRate'));
            
            $form->number('StopDate', __('StopDate'));
            $form->number('LeadDay', __('LeadDay'));
            $form->number('PurchaseBatchAmount', __('PurchaseBatchAmount'));
            $form->number('IsSupplierConsignment', __('IsSupplierConsignment'));
            $form->number('DullDay', __('DullDay'));
            $form->number('OrderBatchAmount', __('OrderBatchAmount'));
         
           
            $form->number('ProductPlaceSerNo', __('ProductPlaceSerNo'));
            $form->text('Remark', __('Remark'));
            $form->text('Remark2', __('Remark2'));
            $form->text('Remark3', __('Remark3'));
            $form->text('Remark4', __('Remark4'));
            $form->text('Remark5', __('Remark5'));
            $form->number('PurchaseCurrencySerNo', __('PurchaseCurrencySerNo'));
            $form->number('PurchaseRate', __('PurchaseRate'));

            $form->number('ColorPicture', __('ColorPicture'));
            $form->number('Picture1', __('Picture1'));
            $form->number('levyIncomeTax', __('LevyIncomeTax'));
            $form->number('IsEditableName', __('IsEditableName'));
            $form->text('GoodsDescribe', __('GoodsDescribe'));
        
        })->tab('政府上傳資料', function ($form) {  
            
            $dom = [ 'A' =>'國產', 'B' =>'輸入'];
            $usage = ['01'=> '防曬', '02'=>'染髮', '03'=>'燙髮', '04'=>'止汗制臭', '05'=>'美白', '06'=>'抗菌', '07'=>'收斂', '08'=>'髮用造型', '09'=>'護髮', '10'=>'清潔頭髮', '11'=>'清潔臉部', '12'=>'清潔身體', '13'=>'彩妝', '14'=>'潤膚', '15'=>'保濕', '16'=>'軟化角質', '17'=>'去除角質', '18'=>'預防面皰、青春痘', '20'=>'脫色脫染', '21'=>'牙齒美白', '22'=>'刺激嗅覺（香水、香膏類）', '23'=>'清潔牙齒', '24'=>'清潔口腔', '99'=>'其他'];
            $dosage = ['F01'=>'粉劑','F02'=>'液劑','F03'=>'乳劑','F04'=>'油劑','F05'=>'油膏','F06'=>'固形','F07'=>'眉筆 ','F08'=>'噴霧劑','F09'=>'非手工香皂','F10'=>'手工香皂',];
            $form->hidden('government.brand');
            $form->radio('government.domestic','國產/輸入')->options($dom);
            $form->select('government.work_manu','作業場所(製造)')->options(
                 Manufacturer::all()->pluck('manufacturer_name', 'id')
            );
            $form->select('government.work_pack','作業場所(製造)')->options(
                 Manufacturer::all()->pluck('manufacturer_name', 'id')
            );
            $form->selector('government.product_kind','產品種類');
            $form->select('government.product_usage','產品用途')->options($usage);
            $form->select('government.product_dosage','產品劑型')->options($dosage);
            $form->text('government.comment','使用注意事項');
            $form->text('government.contact','聯絡人');
        
             $form->hasMany('ingredients','成分', function (Form\NestedForm $form) {

               $restrict =[''=>'無','1'=>'防曬劑','2'=>'染髮劑','5'=>'燙髮劑','7'=>'防腐劑','8'=>'抗菌','9'=>'色素','11'=>'抑制黑色素形成','16'=>'潤膚','18'=>'收斂','20'=>'止汗制臭劑','21'=>'抗菌、制臭','22'=>'美白牙齒','23'=>'軟化角質、面皰預防','24'=>'面皰預防使用於面霜乳液等','26'=>'美白牙齒(使用於牙齒美白牙膏)','28'=>'染髮劑(使用於氧化性染髮劑)','29'=>'染髮劑(使用於非氧化性染髮劑)','30'=>'其他(使用於非立即沖洗產品)','31'=>'其他(使用於立即沖洗產品)','34'=>'抗菌(使用於立即沖洗產品)','35'=>'抗菌(使用於其他產品)','36'=>'抗菌(使用於非立即沖洗產品)','38'=>'防腐劑(使用於立即沖洗產品)','39'=>'防腐劑(使用於其他產品)','40'=>'防腐劑(使用於非立即沖洗產品)','44'=>'防腐劑(使用於止汗劑及制臭劑)','45'=>'防腐劑(以acid計，單獨使用)','46'=>'防腐劑(以acid計，混合使用)','47'=>'防腐劑(使用於立即沖洗髮用產品)','48'=>'燙髮劑(冷燙用)','49'=>'燙髮劑(熱燙用)','53'=>'染髮劑(使用於氧化性染髮劑，限量標準以free base計)','54'=>'染髮劑(使用於非氧化性染髮劑，限量標準以free base計)','55'=>'染髮劑(使用於氧化性染髮劑，限量標準以tetrahydrochloride salt計)','56'=>'染髮劑(使用於非氧化性染髮劑，限量標準以tetrahydrochloride salt計)','57'=>'染髮劑(使用於氧化性染髮劑，限量標準以dihydrochloride計)','58'=>'染髮劑(使用於非氧化性染髮劑，限量標準以dihydrochloride計)','59'=>'燙髮劑(限美髮專業技術人士使用)','60'=>'防腐劑(使用於立即沖洗產品(口腔製劑除外))','61'=>'防腐劑(使用於漱口水產品)','62'=>'防腐劑(使用於口腔製劑)','67'=>'保護劑','82'=>'其他(使用於髮用乳(液)及洗髮產品)','83'=>'其他(使用於指甲用產品)','84'=>'其他(使用於立即沖洗產品(pH調整液))','85'=>'其他(使用於氧化性染髮產品)','86'=>'其他(使用於立即沖洗產品(口腔製劑除外))','87'=>'其他(使用於非藥用牙膏及漱口水)','88'=>'其他(使用於非藥用牙膏)','89'=>'其他(使用於染髮產品)','90'=>'其他(使用於其他產品)','91'=>'其他(使用於香皂)','92'=>'其他(使用於pH調整液)','93'=>'其他(使用於非立即沖洗臉部產品)','95'=>'其他(使用於髮用產品)','96'=>'其他(使用於非立即沖洗髮用產品)','97'=>'其他(使用於立即沖洗髮用產品)','98'=>'其他(使用於指甲角質劑)','99'=>'其他(化粧品成分使用限制)','102'=>'其他(使用於抗頭皮屑洗髮產品)','103'=>'其他(使用於制臭劑(噴霧劑型除外))','ZZ'=>'其他'];
                 
                $form->hidden('product_code', __('Product code'));
                $form->hidden('product_name', __('Product name'));
                $form->hidden('product_en_name', __('Product name'));
                $form->radio('unit', __('Unit'))->options(['A'=>'A','B'=>'B']);
                $form->text('ingredient_name', __('Ingredient name'));
                $form->select('restrict_usage', __('Restrict usage'))->options($restrict);
                $form->radio('content', __('Content'))->options(['A'=>'A','B'=>'B']);
                $form->text('content_amount', __('Content amount'));
             });
/*            
            $form->table('ingredient_list','成分', function ($table) {
                $table->text('name','成分名稱');
                $table->radio('unit','單位')->options(['A'=>'A','B'=>'B']);
            //    $table->text('restrict_usage','限量成分用途');
                $table->radio('contentkind','含量類別')->options(['A'=>'A','B'=>'B']);
                $table->text('content','含量');
                
            });*/
            
            
        })->tab('政府資料', function ($form) {  

      //     echo $headerName = Products::where('id',\Request::segment(3))->get('Code');
            if($form->isEditing()){   
            $folder = Products::where('id',\Request::segment(3))->first()->Code;  
            $form->hidden('government.product_name');
            $form->hidden('government.product_code');
            $form->textarea('government.bad_reaction','產品使用不良反應');
            $form->multipleFile('government.packaging','外包裝瓶器(多)')->move('files/'.$folder)->removable();
            $form->file('government.label','標籤仿單(1)')->move('files/'.$folder)->removable();
            $form->file('government.made_method','化妝品優良製造準則證明(1)')->move('files/'.$folder)->removable();
            $form->file('government.how_to_use','使用方法部位用量頻率及族群(1)')->move('files/'.$folder)->removable();
            $form->multipleFile('government.ingredients_chemical','產品個別成分之物理化學特性(多)')->move('files/'.$folder)->removable();
            $form->multipleFile('government.poison_info','成分之毒理資料(多)')->move('files/'.$folder)->removable();
            $form->file('government.product_stable_report','產品安定性試驗報告(1)')->move('files/'.$folder)->removable();
            $form->file('government.microbe_report','微生物檢測報告(1)')->move('files/'.$folder)->removable();
            $form->file('government.perservative_test_report','防腐效能測試報告(1)')->move('files/'.$folder)->removable();
            $form->multipleFile('government.function_report','功能評估佐證報告(多)')->move('files/'.$folder)->removable();
            $form->multipleFile('government.container_report','與產品接觸之包裝材質資料(多)')->move('files/'.$folder)->removable();
            $form->multipleFile('government.safety_report','產品安全資料(多)')->move('files/'.$folder)->removable();
            
            }
            if ($form->isCreating()){
                $form->html('<h5 class="alert alert-warning">請先儲存商品</h5>');
            }
            
        })->tab('流程', function ($form) {        
            if($form->isEditing()){ 
                /*
                $tt = Process::where('product_id',\Request::segment(3))->first();       
                $a = json_decode($tt);
                foreach ($a as $t => $val){
                    echo $t;
                    echo $val;
                }*/              
                $pp = Process::where('product_id',\Request::segment(3))->first();  
                if($pp){
                    if($pp->completed == '1'){
                        $com = '已完成'; $label = 'success';
                    }else{
                        $com = '進行中'; $label = 'warning';
                    }
                    $form->html('<a href="/admin/process/' . $pp->id . '/edit" class="btn btn-primary">修改流程</a>&nbsp;&nbsp; 新商品流程：<span class="label label-' . $label . '"> 
                         ' . $com . '</span>                            
                    ');   
                }
                else{
                    $form->html('<a href="/admin/process/create?id=' . \Request::segment(3) . '" class="btn btn-primary">建立新流程</a>');
                }
            }
            
        })->tab('採購', function ($form) {    
           if($form->isEditing()){                  
              $form->hasMany('purchase','採購', function (Form\NestedForm $form) {

                $form->hidden('product_code', __('Product code'));
                $form->hidden('product_name', __('Product name'));
                  
                $form->date('date', __('Date'));
                $form->number('warehouse_num', __('Warehouse num'));
                $form->number('company_num', __('Company num'));
               
                $form->number('sale_amount', __('Sale amount'));
                $form->number('estimate_amount', __('Estimate amount'));
                $form->number('estimate_add', __('Estimate add'));
                $form->number('actual_amount', __('Actual amount'));
                $form->date('estimate_arrival', __('Estimate arrival'));
             });
            }
            else{
                 $form->html('<h5 class="alert alert-warning">請先儲存商品</h5>');
            }

        })->tab('品管', function ($form) {    
            if($form->isEditing()){                  
                  $form->hasMany('controls','品管', function (Form\NestedForm $form) {
                    $states = [ 1 =>'YES', 0 =>'NO'];
                    $status = [ 1 =>'OK', 0 =>'NO'];
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
                    $form->text('pass', __('判定通過/退回'));
                    $form->radio('history', __('是否留樣'))->options($states);
                 });
            }
            else{
                 $form->html('<h5 class="alert alert-warning">請先儲存商品</h5>');
            }
        });

        $form->saved(function (Form $form) {

           // $form->model()->Name;
      
        });
        
        $form->saving(function (Form $form) {
            $form->input('government.product_name', $form->Name);
            $form->input('government.product_code', $form->Code);
            $form->input('government.brand', $form->BrandSerNo);
        //    $form->input('government.ingredient_list', $form->ingredient_list);
        });
        return $form;
    }
    
     /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Products::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('Code', __('Code'));
        $show->field('Name', __('Name'));
        $show->field('SpecName', __('SpecName'));
        $show->field('ColorSerNo', __('ColorSerNo'));
        $show->field('SizeSerNo', __('SizeSerNo'));
        $show->field('SellYear', __('SellYear'));
        $show->field('SellSeason', __('SellSeason'));
        $show->field('LargeCategorySerNo', __('LargeCategorySerNo'));
        $show->field('MiddleCategorySerNo', __('MiddleCategorySerNo'));
        $show->field('SmallCategorySerNo', __('SmallCategorySerNo'));
        $show->field('BrandSerNo', __('BrandSerNo'));
        $show->field('MiddleBrandSerNo', __('MiddleBrandSerNo'));
        $show->field('InventoryCycle', __('盤點區分'));
        $show->field('Barcode', __('Barcode'));
        $show->field('InternationalBarcode', __('InternationalBarcode'));
        $show->field('ModelCode', __('ModelCode'));
        $show->field('GoodsPropertiesSerNo', __('GoodsPropertiesSerNo'));
        $show->field('GoodsProperties2SerNo', __('GoodsProperties2SerNo'));
        $show->field('UnitSerNo', __('UnitSerNo'));
        $show->field('BigUnitSerNo', __('BigUnitSerNo'));
        $show->field('BigExchangeRate', __('BigExchangeRate'));
        $show->field('MiddleUnitSerNo', __('MiddleUnitSerNo'));
        $show->field('MiddleExchangeRate', __('MiddleExchangeRate'));
        $show->field('GoodsSource', __('GoodsSource'));
        $show->field('GoodsType', __('GoodsType'));
        $show->field('IsCalculate', __('IsCalculate'));
        $show->field('IsStop', __('IsStop'));
        $show->field('StopDate', __('StopDate'));
        $show->field('LeadDay', __('LeadDay'));
        $show->field('PurchaseBatchAmount', __('PurchaseBatchAmount'));
        $show->field('IsSupplierConsignment', __('IsSupplierConsignment'));
        $show->field('DullDay', __('DullDay'));
        $show->field('OrderBatchAmount', __('OrderBatchAmount'));
        $show->field('KeyInDate', __('KeyInDate'));
        $show->field('MainSupplierSerNo', __('MainSupplierSerNo'));
        $show->field('Ename', __('Ename'));
        $show->field('ESpecName', __('ESpecName'));
        $show->field('ProductPlaceSerNo', __('ProductPlaceSerNo'));
        $show->field('Remark', __('Remark'));
        $show->field('Remark2', __('Remark2'));
        $show->field('Remark3', __('Remark3'));
        $show->field('Remark4', __('Remark4'));
        $show->field('Remark5', __('Remark5'));
        $show->field('PurchaseCurrencySerNo', __('PurchaseCurrencySerNo'));
        $show->field('PurchaseRate', __('PurchaseRate'));
        $show->field('PurchaseTaxType', __('PurchaseTaxType'));
        $show->field('CostPrice', __('CostPrice'));
        $show->field('TaxedCostPrice', __('TaxedCostPrice'));
        $show->field('SellCurrencySerNo', __('SellCurrencySerNo'));
        $show->field('SellRate', __('SellRate'));
        $show->field('SellTaxType', __('SellTaxType'));
        $show->field('ListPrice', __('ListPrice'));
        $show->field('TaxedListPrice', __('TaxedListPrice'));
        $show->field('UpsetPrice', __('UpsetPrice'));
        $show->field('TaxedUpsetPrice', __('TaxedUpsetPrice'));
        $show->field('TaxedEqualPrice', __('TaxedEqualPrice'));
        $show->field('POSEnabledDiscount', __('POSEnabledDiscount'));
        $show->field('Picture', __('Picture'));
        $show->field('ColorPicture', __('ColorPicture'));
        $show->field('Picture1', __('Picture1'));
        $show->field('levyIncomeTax', __('LevyIncomeTax'));
        $show->field('IsEditableName', __('IsEditableName'));
        $show->field('GoodsDescribe', __('GoodsDescribe'));
        $show->field('comment', __('Comment'));

        return $show;
    }
    public function export() 
    {
        return Excel::download(new ERPExport, 'users.xlsx');
    }
    public function categoryMiddle(Request $request)
    {
        $catMid = $request->get('q');
        return Category::mid()->where('parent_id', $catMid)->get(['id',DB::raw('name as text')]);
    }

    public function categorySmall(Request $request)
    {
        $catSid = $request->get('q');
        return Category::small()->where('parent_id', $catSid)->get(['id', DB::raw('name as text')]);
    }
 
}