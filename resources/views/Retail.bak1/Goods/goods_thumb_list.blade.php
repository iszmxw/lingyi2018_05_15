<div class="table-responsive">
    <form class="form-horizontal" method="get">
        <input type="hidden" id="goods_thumb_delete_comfirm_url" value="{{ url('retail/ajax/goods_thumb_delete') }}">
        <input type="hidden" id="thumb_edit_displayorder_url" value="{{ url('retail/ajax/thumb_edit_displayorder') }}">
        <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
    </form>
    <table class="table table-bordered table-stripped">
        <thead>
        <tr>
            <th>
                图片预览
            </th>
            <th>
                图片链接
            </th>
            <th>
                图片排序
            </th>
            <th>

            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($goods_thumb as $key=>$val)
        <tr>
            <td>
                <img src="{{asset('/')}}{{$val->thumb}}" style="width: 50px; height: 50px;" onclick="bigthumb(this.src)">
            </td>
            <td>
                {{asset('/'.$val->thumb)}}
            </td>
            <td>
                <input type="text" name="displayorder" size="3" onchange="return editDisplayorder('{{ $val->id }}',this);" value="{{$val->displayorder}}" />
            </td>
            <td>
                <button type="button" id="deleteBtn" class="btn btn-danger btn-xs" onclick="getDeleteForm({{ $val->id }})"><i class="fa fa-times"></i></button>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <div class="line line-dashed b-b line-lg pull-in"></div>
    <div class="form-group">
        <div class="col-sm-12 col-sm-offset-6">
            <button type="button" class="btn btn-success" id="addBtn">保存信息</button>
        </div>
    </div>
</div>


<script>
    function editDisplayorder(id,obj){
        var url = $('#thumb_edit_displayorder_url').val();
        var token = $('#_token').val();
        var displayorder = $(obj).val();

        if(id==''){
            swal({
                title: "提示信息",
                text: '数据传输错误',
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确定",
            },function(){
                window.location.reload();
            });
            return;
        }

        var data = {'id':id,'displayorder':displayorder,'_token':token};
        $.post(url,data,function(response){
            if(response.status!='1'){
                swal({
                    title: "提示信息",
                    text: response.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定",
                },function(){
                    //window.location.reload();
                });
            }else{
                swal({
                    title: "温馨提示",
                    text: response.data,
                    type: "success"
                });
            }

        });
    }
</script>
