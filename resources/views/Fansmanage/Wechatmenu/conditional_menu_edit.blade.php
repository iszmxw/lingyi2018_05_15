<header class="panel-heading font-bold" style="color:#FF0000">
    个性化菜单修改
</header>

<div class="panel-body">
    <form class="form-horizontal" role="form" id="conditional_menu_edit_check"
          action="{{ url('fansmanage/ajax/conditional_menu_edit_check') }}">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="menu_id" id="menu_id" value="{{$conditionalmenu->id}}">
        <input type="hidden" name="response_type" id="response_type" value="{{$conditionalmenu->response_type}}">

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-id-1">会员标签组</label>
            <div class="col-sm-10">
                <select class="form-control m-b" disabled="true" id="member_label">
                    <option value="{{$label_info["id"]}}">{{$label_info['label_name']}}</option>
                </select>
            </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-id-1">上级菜单</label>
            <div class="col-sm-10">
                <select name="parent_id" class="form-control m-b" id="parent_id"
                        @if($conditionalmenu->parent_id == 0) disabled="true" @endif>
                    {{--<select name="parent_id" class="form-control m-b" id="parent_id" disabled="true">--}}
                    <option value="0">无</option>
                    @foreach($list as $key=>$val)
                        <option value="{{$val->id}}"
                                @if($conditionalmenu->parent_id == $val->id) selected @endif>{{$val->menu_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="line line-dashed b-b line-lg pull-in"></div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-id-1">菜单名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" placeholder="限制四个字" name="menu_name" maxlength="4"
                       value="{{$conditionalmenu->menu_name}}">
            </div>
        </div>

        <div class="line line-dashed b-b line-lg pull-in"></div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-id-1">事件类型</label>
            <div class="col-sm-10">
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-sm btn-info @if($conditionalmenu->event_type == 1) active @endif"
                           style="margin-right: 5px;margin-top: 10px;" onclick="$('#response_type').val(1)" id="type_1">
                        <input type="radio" name="event_type" value="1"
                               @if($conditionalmenu->event_type == 1) checked @endif ><i
                                class="fa fa-check text-active"></i> 链接
                    </label>

                    <label class="btn btn-sm btn-info onclick type_2 @if($conditionalmenu->event_type == 2) active @endif"
                           style="margin-right: 5px;margin-top: 10px;">
                        <input type="radio" name="event_type" value="2"
                               @if($conditionalmenu->event_type == 2) checked @endif><i
                                class="fa fa-check text-active"></i> 模拟关键字
                    </label>

                    <label class="btn btn-sm btn-info onclick type_2 @if($conditionalmenu->event_type == 3) active @endif"
                           style="margin-right: 5px;margin-top: 10px;">
                        <input type="radio" name="event_type" value="3"
                               @if($conditionalmenu->event_type == 3) checked @endif><i
                                class="fa fa-check text-active"></i> 扫码
                    </label>

                    <label class="btn btn-sm btn-info onclick type_2 @if($conditionalmenu->event_type == 4) active @endif"
                           style="margin-right: 5px;margin-top: 10px;">
                        <input type="radio" name="event_type" value="4"
                               @if($conditionalmenu->event_type == 4) checked @endif><i
                                class="fa fa-check text-active"></i> 扫码(带等待信息)
                    </label>

                    <label class="btn btn-sm btn-info onclick type_2 @if($conditionalmenu->event_type == 5) active @endif"
                           style="margin-right: 5px;margin-top: 10px;">
                        <input type="radio" name="event_type" value="5"
                               @if($conditionalmenu->event_type == 5) checked @endif><i
                                class="fa fa-check text-active"></i> 拍照发图
                    </label>

                    <label class="btn btn-sm btn-info onclick type_2 @if($conditionalmenu->event_type == 6) active @endif"
                           style="margin-right: 5px;margin-top: 10px;">
                        <input type="radio" name="event_type" value="6"
                               @if($conditionalmenu->event_type == 6) checked @endif><i
                                class="fa fa-check text-active"></i> 拍照或者相册发图
                    </label>

                    <label class="btn btn-sm btn-info onclick type_2 @if($conditionalmenu->event_type == 7) active @endif"
                           style="margin-right: 5px;margin-top: 10px;">
                        <input type="radio" name="event_type" value="7"
                               @if($conditionalmenu->event_type == 7) checked @endif><i
                                class="fa fa-check text-active"></i> 微信相册发图
                    </label>

                    <label class="btn btn-sm btn-info onclick type_2 @if($conditionalmenu->event_type == 8) active @endif"
                           style="margin-right: 5px;margin-top: 10px;">
                        <input type="radio" name="event_type" value="8"
                               @if($conditionalmenu->event_type == 8) checked @endif><i
                                class="fa fa-check text-active"></i> 地理位置
                    </label>
                </div>
                <span class="help-block m-b-none"><p class="text-danger">事件类型为"链接"时，响应类型必须为跳转链接</p></span>
            </div>
        </div>

        <div class="line line-dashed b-b line-lg pull-in"></div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-id-1">响应类型</label>
            <div class="col-sm-10">
                <section class="panel panel-default">
                    <header class="panel-heading text-right bg-light">
                        <ul class="nav nav-tabs pull-left">
                            <li @if($conditionalmenu->response_type == 1) class="active" @endif  id="link_type"><a
                                        href="#link_response" onclick="$('#response_type').val(1)" data-toggle="tab"><i
                                            class="fa fa-file-text-o text-muted"></i>&nbsp;&nbsp;跳转链接</a></li>
                            <li @if($conditionalmenu->response_type == 2) class="active" @endif  id="text_type"><a
                                        href="#text_response" onclick="$('#response_type').val(2)" data-toggle="tab"><i
                                            class="icon icon-picture text-muted"></i>&nbsp;&nbsp;关键字回复</a></li>
                        </ul>
                        <span class="hidden-sm">&nbsp;</span>
                    </header>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in @if($conditionalmenu->response_type == 1) active @endif"
                                 id="link_response">
                                <input type="text" class="form-control" name="response_url"
                                       value="{{$conditionalmenu->response_url}}" placeholder="跳转链接">
                                <span class="help-block m-b-none">
                                    <p>指定点击此菜单时要跳转的链接（注：链接需加<span style="color: red">http://</span>）</p>
                                </span>
                            </div>
                            <div class="tab-pane fade in @if($conditionalmenu->response_type == 2) active @endif"
                                 id="text_response">
                                <select style="width:260px" name="response_keyword" class="chosen-select2">
                                    <option value="">请选择关键字</option>
                                    @foreach($wechatreply as $key=>$val)
                                        <option value="{{$val->keyword}}"
                                                @if($conditionalmenu->response_keyword == $val->keyword)selected @endif>{{$val->keyword}}</option>
                                    @endforeach
                                </select>
                                <span class="help-block m-b-none">
                                    <p>指定点击此菜单时要执行的操作, 你可以在这里输入关键字, 那么点击这个菜单时就就相当于发送这个内容至公众号</p>
                                    <p>这个过程是程序模拟的, 比如这里添加关键字: 优惠券, 那么点击这个菜单是, 相当于接受了粉丝用户的消息, 内容为"优惠券"</p>
                                </span>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
            <div class="col-sm-12 col-sm-offset-3">
                <button type="button" class="btn btn-success" onclick="EditPostForm()">修改菜单</button>
                {{--<button type="button" class="btn btn-primary" id="addBtn">一键创建默认自定义菜单</button>--}}
                <button type="button" class="btn btn-dark" id="wechat_conditional_menu_btn">一键同步到微信公众号</button>
            </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>
        <input type="hidden" id="parent_id_item" name="parent_id_item" value="{{ $conditionalmenu["parent_id"] }}">
        <input type="hidden" id="conditional_menu_get" value="{{ url('fansmanage/ajax/conditional_menu_get') }}">
        <input type="hidden" id="conditional_menu_edit" value="{{ url('fansmanage/ajax/conditional_menu_edit') }}">
        <input type="hidden" id="wechat_conditional_menu_add"
               value="{{ url('fansmanage/ajax/wechat_conditional_menu_add') }}">
        <input type="hidden" id="tag_id" value="{{$label_info["id"]}}">
        <input type="hidden" id="edit_id" value="{{$edit_id}}">
    </form>


</div>


<script>
    $("#wechat_conditional_menu_btn").click(function () {
        var url = $('#wechat_conditional_menu_add').val();
        var token = $('#_token').val();
        var tag_id = $('#tag_id').val();
        var data = {'_token': token, 'tag_id': tag_id};
        $.post(url, data, function (response) {
            if (response.status == '-1') {
                swal({
                    title: "提示信息",
                    text: response.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定",
                }, function () {
                    window.location.reload();
                });
                return;
            } else {
                $('#myModal').html(response);
                $('#myModal').modal();
            }
        });
    })

    // 获取列表主体
    function changeConditionalMennuEdit() {
        var url = $('#conditional_menu_get').val();
        var token = $('#_token').val();
        var $parent_id = $("#parent_id_item").val();
        var $label_id = $("#member_label").val();
        var data = {'_token': token, 'label_id': $label_id, 'parent_id': $parent_id};
        $.post(url, data, function (response) {
            if (response.status == '-1') {
                swal({
                    title: "提示信息",
                    text: response.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定",
                }, function () {
                    window.location.reload();
                });
                return;
            } else {
                $('#menu_box').html(response);
            }
        });
    }

    $(function () {
        $('.onclick').click(function () {
            $('#response_type').val(2);
        });
        $('#type_1').click(function () {
            $('#text_type').removeClass('active');
            $('#text_response').removeClass('active');
            $('#link_type').addClass('active');
            $('#link_response').addClass('active');

        });
        $('.type_2').click(function () {
            $('#link_type').removeClass('active');
            $('#link_response').removeClass('active');
            $('#text_type').addClass('active');
            $('#text_response').addClass('active');
        });
    });

    function EditPostForm() {
        var target = $("#conditional_menu_edit_check");
        var url = target.attr("action");
        var data = target.serialize();
        var $member_label = $("#member_label").val();
        if ($("#parent_id").val() == 0) {
            data += '&parent_id=0'
        }

        $.post(url, data, function (json) {
            if (json.status == 1) {
                swal({
                    title: "提示信息",
                    text: json.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定"
                }, function () {
                    changeConditionalMennuEdit($member_label);
                });
            } else {
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