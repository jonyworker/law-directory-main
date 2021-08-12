<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Imports\Gov\IngredientsImport;

    

class ImportIngredients extends Action
{
    public $name = '匯入資料';
    
    protected $selector = '.import-post';

    public function handle(Request $request)
    {
        // $request ...        
        Excel::import(new IngredientsImport,  $request->file('file')); 
        
        return $this->response()->success('Success message...')->refresh();
    }

    public function form()
    {
        $this->file('file', '選擇檔案');
    }
    
    public function html()
    {
        return <<<HTML
          <a class="btn btn-sm btn-default import-post"><i class="fa fa-upload"></i> 匯入成分</a>
HTML;
    }
}