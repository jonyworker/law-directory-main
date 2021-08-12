@php $get = DB::table('government_product_kind')->get();@endphp
<div class="form-group {!! !$errors->has($label) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>
    <div class="row">
        <div class="col-sm-3">

            @include('admin::form.error')

            <input class="form-control" id="{{$id}}" name="{{$name}}" placeholder=" {{$label}}" {!! $attributes !!} value="{{str_pad($value,  4, '0', STR_PAD_LEFT)}}" />
        </div>
        <div class="col-sm-3">    
            <a href="#" id="sss" class="filter-box btn btn-primary">選擇種類</a>      
        </div>
    </div>
    
    <div class="modal fade" id="hue" tabindex="-1" role="dialog" style="overflow: scroll;">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 5px;">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>

          </div>
          <div class="modal-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td>代碼</td>
                        <td>代碼內容</td>
                        <td>代碼名稱</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($get as $got)
                    <tr>
                        <td><a class="c-selected" href="#" data-value="{{str_pad($got->id,  4, '0', STR_PAD_LEFT)}}">{{str_pad($got->id,  4, '0', STR_PAD_LEFT)}}</a></td>
                        <td>{{$got->name}}</td>
                        <td>{{$got->description}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
    
</div>