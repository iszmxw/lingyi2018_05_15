<form class="form-horizontal tasi-form" method="get">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">订单详情</h4>
            </div>
            <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-2 text-right">订单编号</label>
                        <div class="col-sm-10">
                            <input type="text" value="{{$order->ordersn}}" class="form-control" disabled="" name="ordersn">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 text-right">类型</label>
                        <div class="col-sm-10">
                            <input type="text" value="@if($order->type == 1)进货@elseif($order->type == 2)退货@elseif($order->type == 3)报损@elseif($order->type == 4)盘点@else未知@endif" class="form-control" disabled="" name="type">
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
                <div class="table-responsive">
                    <table class="table table-striped b-t b-light">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>商品名称</th>
                            <th>商品ID</th>
                            <th>数量</th>
                            <th>价格</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($order->type == 1 || $order->type == 2)
                        @foreach($order->RetailPurchaseOrderGoods as $key=>$val)
                        <tr>
                            <td>{{$val->id}}</td>
                            <td><a href="{{url('retail/goods/goods_edit?goods_id='.$val->goods_id)}}">{{$val->title}}</a></td>
                            <td>{{$val->goods_id}}</td>
                            <td>{{$val->total}}</td>
                            <td>{{$val->price}}</td>
                        </tr>
                        @endforeach
                        @elseif($order->type == 3)
                            @foreach($order->RetailLossOrderGoods as $key=>$val)
                                <tr>
                                    <td>{{$val->id}}</td>
                                    <td><a href="{{url('retail/goods/goods_edit?goods_id='.$val->goods_id)}}">{{$val->title}}</a></td>
                                    <td>{{$val->goods_id}}</td>
                                    <td>{{$val->total}}</td>
                                    <td>{{$val->price}}</td>
                                </tr>
                            @endforeach
                        @elseif($order->type == 4)
                            @foreach($order->RetailCheckOrderGoods as $key=>$val)
                                <tr>
                                    <td>{{$val->id}}</td>
                                    <td><a href="{{url('retail/goods/goods_edit?goods_id='.$val->goods_id)}}">{{$val->title}}</a></td>
                                    <td>{{$val->goods_id}}</td>
                                    <td>{{$val->total}}</td>
                                    <td>{{$val->price}}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                <button data-dismiss="modal" class="btn btn-success" type="button">确定</button>
            </div>
        </div>
    </div>
</form>