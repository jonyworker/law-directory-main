<?php

namespace App\Admin\Extensions;

use App\Models\GovKind;
use Encore\Admin\Form\Field;
use Encore\Admin\Admin;

class Selector extends Field
{
    protected $view = 'admin.selector';

      protected function addScript()    
      {
          
        $script = <<<EOT
            $('#sss').click(function (e){
                console.log('hehehe');
                $('#hue').addClass('show');
                $('#hue').removeClass('fade');
                e.preventDefault();
            });
            $('.modal-header .close').click(function (e){
                $('#hue').addClass('fade');
                $('#hue').removeClass('show');
            });

            $( ".c-selected" ).each(function(index) {
                $(this).on("click", function(e){
                    var value = $(this).data('value');
                    $('#government_product_kind').val(value);
                    $('#hue').addClass('fade');
                    $('#hue').removeClass('show');
                    e.preventDefault();
                });
            });
       EOT;
          
        Admin::script($script);
        return $this;
      }

    public function render()
    {
        $this->addScript();
        return parent::render();
    }

}