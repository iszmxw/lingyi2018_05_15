<form class="form-horizontal" id="goods_delete_check" method="post" action="{{url('retail/ajax/goods_delete_check')}}">
     <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
     <input type="hidden" name="goods_id" id="goods_id" value="{{$goods_id}}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">确认删除商品</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="get">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-id-1">安全密码</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="safe_password" value="">
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                    <button class="btn btn-success" type="button" onclick="DeleteGoods()">确定</button>
                </div>
            </div>
        </div>
</form>
 <script>
     //删除商品信息提交
     function DeleteGoods(){
         var target = $("#goods_delete_check");
         var url = target.attr("action");
         var data = target.serialize();
         $.post(url,data,function(response){
             if(response.status=='-1'){
                 swal({
                     title: "提示信息",
                     text: response.data,
                     confirmButtonColor: "#DD6B55",
                     confirmButtonText: "确定",
                 },function(){
                     window.location.reload();
                 });
                 return;
             }else{
                 swal({
                     title: "提示信息",
                     text: response.data,
                     confirmButtonColor: "#DD6B55",
                     confirmButtonText: "确定",
                 },function(){
                     window.location.reload();
                 });
             }
         });
     }
 </script>