

      <h4>新商品流程</h4>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <td>貨號</td>
                        <td>商品名稱</td>
                        <td>待辦事項</td>
                        <td>完成事項</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                @foreach($envs as $env)
                <tr> 
                    <td>{{ $env->product_code }}</td>
                    <td width="120px">{{ $env->product_name }}</td>
                    <td>{{ $env->answered_question_count - 1 - $env->unanswered_question_count}}</td>
                    <td>{{$env->unanswered_question_count - 4}}</td>
                    <td><a href="/admin/process/{{$env->id}}/edit"><i class="fa fa-edit"></i></a></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.table-responsive -->