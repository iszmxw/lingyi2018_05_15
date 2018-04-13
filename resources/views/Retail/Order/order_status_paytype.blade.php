<form class="form-horizontal" id="order_status_check" method="post" action="{{url('retail/ajax/order_status_check')}}">
     <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
     <input type="hidden" name="order_id" id="order_id" value="{{$order_id}}">
     <input type="hidden" name="status" id="status" value="{{$status}}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">确认修改订单状态</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="get">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-id-1">安全密码</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="safe_password" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-id-1">安全密码</label>
                            <div class="col-sm-2">
                                <select name="company_name" class="form-control m-b">
                                    <option value="0">请选择公司名称</option>
                                    <option value="{{$val->company_name}}">{{$val->company_name}}</option>
                                        @if($order->paytype == '-1' )
                                        <option value="-1">现金支付，其他支付</option>
                                        @elseif($order->paytype == '1' )
                                        <option value="1">支付宝扫码</option>
                                        @elseif($order->paytype == '2' )
                                        <option value="2">支付宝二维码</option>
                                        @elseif($order->paytype == '3' )
                                        <option value="3">微信扫码</option>
                                        @elseif($order->paytype == '4' )
                                        <option value="4">微信二维码</option>
                                        @elseif($order->paytype == '0' )
                                        <option value="0">银行卡支付</option>
                                        @endif
                                </select>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                    <button class="btn btn-success" type="button" onclick="EditOrderStatus()">确定</button>
                </div>
            </div>
        </div>
</form>
 <script>
     //删除商品信息提交
     function EditOrderStatus(){
         var target = $("#order_status_check");
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