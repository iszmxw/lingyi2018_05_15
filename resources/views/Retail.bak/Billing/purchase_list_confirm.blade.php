<form class="form-horizontal tasi-form" method="post" id="currentForm" action="{{ url('retail/ajax/purchase_list_confirm_check') }}">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="order_id" value="{{$order->id}}">
    <input type="hidden" name="status" value="{{$status}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">
                   审核订单确认
                </h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="get">
                    <div class="form-group">
                        <label class="col-sm-2 text-right">订单编号</label>
                        <div class="col-sm-10">
                            <input type="text" value="{{$order->ordersn}}" class="form-control" disabled="" name="ordersn">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 text-right">类型</label>
                        <div class="col-sm-10">
                            <input type="text" value="@if($order->type == 1)进货@elseif($order->type == 2)退货@else未知@endif" class="form-control" disabled="" name="type">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 text-right">供应商</label>
                        <div class="col-sm-10">
                            <input type="text" value="{{$order->RetailSupplier->company_name}}" class="form-control" disabled="" name="company_name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 text-right">供应商联系方式</label>
                        <div class="col-sm-10">
                            <input type="text" value="{{$order->RetailSupplier->contactmobile}}" class="form-control" disabled="" name="contactmobile">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 text-right">经手操作人员</label>
                        <div class="col-sm-10">
                            <input type="text" value="{{$order->account_info->realname}}" class="form-control" disabled="" name="operator_id">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 text-right">订单金额</label>
                        <div class="col-sm-10">
                            <input type="text" value="{{$order->order_price}}" class="form-control" disabled="" name="order_price">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 text-right">开单时间</label>
                        <div class="col-sm-10">
                            <input type="text" value="{{$order->created_at}}" class="form-control" disabled="" name="created_at">
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    <div class="line line-dashed b-b line-lg pull-in"></div>
                    <div class="form-group">
                        <label class="col-sm-2 text-right">安全密码</label>
                        <div class="col-sm-10">
                            <input type="text" value="" placeholder="输入安全密码确认审核" class="form-control" name="safe_password">
                            <span class="help-block m-b-none">
                               @if($status == '0')
                                    <p class="text-danger">确定要审核该订单吗？审核后将修改库存！</p>
                                @endif
                          </span>
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                <button class="btn btn-success" type="button" id="save_btn" onclick="postForm()">确定</button>
            </div>
        </div>
    </div>
</form>
<script>
    //提交表单
    function postForm() {
        var target = $("#currentForm");
        var url = target.attr("action");
        var data = target.serialize();
        $.post(url, data, function (json) {
            if (json.status == -1) {
                window.location.reload();
            } else if(json.status == 1) {
                swal({
                    title: "提示信息",
                    text: json.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定"
                },function(){
                    window.location.reload();
                });
            }else{
                swal({
                    title: "提示信息",
                    text: json.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定"
                });
            }
        });
    }
</script>
