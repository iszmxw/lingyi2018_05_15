<!DOCTYPE html>
<html lang="en" class="app">
<head>
    <meta charset="utf-8"/>
    <title>零壹云管理平台 | 零售版店铺管理系统</title>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/library/jPlayer/jplayer.flat.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/bootstrap.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/animate.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/font-awesome.min.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/simple-line-icons.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/font.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/app.css" type="text/css"/>
    <link href="{{asset('public/Retail')}}/library/sweetalert/sweetalert.css" rel="stylesheet"/>
    <!--[if lt IE 9]>
    <script src="{{asset('public/Retail')}}/library/ie/html5shiv.js"></script>
    <script src="{{asset('public/Retail')}}/library/ie/respond.min.js"></script>
    <script src="{{asset('public/Retail')}}/library/ie/excanvas.js"></script>
    <![endif]-->
</head>
<body class="">
<section class="vbox">
    {{--头部--}}
    @include('Retail/Public/Header')
    {{--头部--}}
    <section>
        <section class="hbox stretch">
            <!-- .aside -->
        @include('Retail/Public/Nav')
        <!-- /.aside -->
            <section id="content">
                <section class="vbox">
                    <section class="scrollable padder">
                        <div class="m-b-md">
                            <h3 class="m-b-none">店铺概况</h3>
                        </div>

                        <div class="col-lg-3">
                            <section class="panel panel-default">

                                <header class="panel-heading font-bold">
                                    概况
                                    <button id="editBtn" class="btn btn-default btn-xs pull-right"><i class="fa fa-edit "></i>&nbsp;编辑</button>
                                </header>
                                <div class="panel-body">
                                    <div class="form-group clearfix text-center m-t">
                                        <div class="inline">
                                            <div class="thumb-lg" >
                                                @if(!empty($organization->OrganizationRetailinfo->retail_logo))
                                                    <img src="{{asset('/'.$organization->OrganizationRetailinfo->retail_logo)}}" class="img-circle" alt="店铺logo">
                                                @else
                                                    <img src="{{asset('public/Fansmanage')}}/img/shop_logo.png" class="img-circle" alt="...">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                    <div class="form-group">
                                        <label class="col-sm-4 text-right" for="input-id-1">模式</label>
                                        <div class="col-sm-8">
                                            <div>
                                                <label class="label label-success m-t-xs">
                                                    {{$organization->program_name->program_name}}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                    <div class="form-group">
                                            <label class="col-sm-4 text-right" for="input-id-1">分店名称</label>
                                            <div class="col-sm-8">
                                                <label class="label label-info">{{$organization->organization_name}}</label>
                                            </div>
                                        </div>

                                    <div style="clear:both;"></div>
                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                    <div class="form-group">
                                            <label class="col-sm-4 text-right" for="input-id-1">类型</label>
                                            <div class="col-sm-8">
                                                <label class="label label-success">
                                                    @if($organization->type == 1)
                                                        零壹组织
                                                    @elseif($organization->type == 2)
                                                        分公司组织
                                                    @elseif($organization->type == 3)
                                                        商户组织
                                                    @elseif($organization->type == 4)
                                                        店铺组织
                                                    @endif
                                                </label>
                                            </div>
                                        </div>

                                    <div style="clear:both;"></div>
                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                    <div class="form-group">
                                            <label class="col-sm-4 text-right" for="input-id-1">负责人</label>
                                            <div class="col-sm-8">
                                                <label class="label label-info">{{$organization->OrganizationRetailinfo->retail_owner}}</label>
                                            </div>
                                        </div>

                                    <div style="clear:both;"></div>
                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                    <div class="form-group">
                                            <label class="col-sm-4 text-right" for="input-id-1">手机号码</label>
                                            <div class="col-sm-8">
                                                <label class="label label-info">{{$organization->OrganizationRetailinfo->retail_owner_mobile}}</label>
                                            </div>
                                        </div>

                                    <div style="clear:both;"></div>
                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                    <div class="form-group">
                                            <label class="col-sm-4 text-right" for="input-id-1">状态</label>
                                            <div class="col-sm-8">
                                                <label class="label label-success">
                                                    @if($organization->status == 1)
                                                        正常运营
                                                    @elseif($organization->status == 0)
                                                        冻结中
                                                    @endif
                                                </label>
                                            </div>
                                        </div>

                                    <div style="clear:both;"></div>
                                        <div class="line line-dashed b-b line-lg pull-in"></div>
                                        <div class="form-group">
                                            <label class="col-sm-4 text-right" for="input-id-1">店铺地址</label>
                                            <div class="col-sm-8">
                                                <label class="label label-primary">{{$organization->OrganizationRetailinfo->retail_address}}</label>
                                            </div>
                                        </div>

                                    <div style="clear:both;"></div>
                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                </div>
                            </section>
                        </div>
                        <div class="col-lg-9 ">
                            <div class="col-lg-12">
                                <div class="col-lg-4 state-overview">
                                <section class="panel">
                                    <div class="symbol bg-danger">
                                        <i class="fa fa-money"></i>
                                    </div>
                                    <div class="value">
                                        <h1>{{$statistics['operating_receipt']}}</h1>
                                        <p>元营收</p>
                                    </div>
                                </section>
                            </div>

                            <div class="col-lg-4 state-overview">
                            <section class="panel">
                                <div class="symbol bg-success">
                                    <i class="icon icon-user"></i>
                                </div>
                                <div class="value">
                                    <h1>{{$statistics['fans']}}</h1>
                                    <p>个粉丝用户</p>
                                </div>
                            </section>
                        </div>

                        <div class="col-lg-4 state-overview">
                        <section class="panel">
                            <div class="symbol bg-info">
                                <i class="icon icon-basket-loaded"></i>
                            </div>
                            <div class="value">
                                <h1>{{$statistics['goods']}}</h1>
                                <p>个商品</p>
                            </div>
                        </section>
                        </div>


                        </div>

                        <div class="col-lg-12">
                            <div class="col-lg-4 state-overview">
                            <section class="panel">
                                <div class="symbol bg-warning">
                                    <i class="fa fa-list"></i>
                                </div>
                                <div class="value">
                                    <h1>{{$statistics['order_spot']}}</h1>
                                    <p>现场订单数</p>
                                </div>
                            </section>
                        </div>

                        </div>

                        <div class="col-lg-12">
                            <div class="col-lg-6">
                                <section class="panel panel-default">
                                    <header class="panel-heading">最近登录日志</header>
                                    <table class="table table-striped m-b-none">
                                        <thead>
                                        <tr>
                                            <th>登录账号</th>
                                            <th>登录IP</th>
                                            <th>登录地址</th>
                                            <th>登录时间</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($login_log_list as $key=>$val)
                                            <tr>
                                                <td>{{$val->accounts->account}}</td>
                                                <td>{{  long2ip($val->ip) }}</td>
                                                <td>{{  $val->ip_position }}</td>
                                                <td>{{  $val->created_at }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </section>
                            </div>

                            <div class="col-lg-6">
                                <section class="panel panel-default">
                                    <header class="panel-heading">最近操作日志</header>
                                    <table class="table table-striped m-b-none">
                                        <thead>
                                        <tr>
                                            <th>操作账号</th>
                                            <th>操作内容</th>
                                            <th>操作时间</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($operation_log_list as $key=>$val)
                                            <tr>
                                                <td>{{ $val->accounts->id }}</td>
                                                <td>{{ $val->operation_info }}</td>
                                                <td>{{ $val->created_at }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </section>
                            </div>
                        </div>
                        </div>
                    </section>
                </section>
            </section>
        </section>
    </section>
</section>


{{--编辑店铺信息--}}
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form class="form-horizontal" role="form" id="store_edit" method="post" enctype="multipart/form-data" action="">
        <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
        <input type="hidden" name="organization_id" id="organization_id" value="{{$admin_data['organization_id']}}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">店铺信息编辑</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-2 text-right">店铺名称</label>
                        <div class="col-sm-10">
                            <input type="text" value="{{$organization->organization_name}}" name="organization_name" placeholder="店铺名称" class="form-control">
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    <div class="line line-dashed b-b line-lg pull-in"></div>


                    <div class="form-group">
                        <label class="col-sm-2 text-right">负责人</label>
                        <div class="col-sm-10">
                            <input type="text" value="{{$organization->OrganizationRetailinfo->retail_owner}}" name="retail_owner" placeholder="负责人" class="form-control">
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    <div class="line line-dashed b-b line-lg pull-in"></div>

                    <div class="form-group">
                        <label class="col-sm-2 text-right">手机号码</label>
                        <div class="col-sm-10">
                            <input type="number" value="{{$organization->OrganizationRetailinfo->retail_owner_mobile}}" name="mobile" placeholder="手机号码" class="form-control">
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    <div class="line line-dashed b-b line-lg pull-in"></div>

                    <div class="form-group">
                        <label class="col-sm-2 text-right">店铺LOGO</label>
                        <div class="col-sm-10">
                            <input type="file" name="retail_logo" class="filestyle" style="display: none;" data-icon="true" data-classButton="btn btn-default" data-classInput="form-control inline v-middle input-s">
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    <div class="line line-dashed b-b line-lg pull-in"></div>

                    <div class="form-group">
                        <label class="col-sm-2 text-right">店铺地址</label>
                        <div class="col-sm-8">
                            <input type="text" id='address' value="{{$organization->OrganizationRetailinfo->retail_address}}" name="retail_address" placeholder="店铺地址" class="form-control">
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn" name="submit" value="搜索" onClick="bmap.searchMapByAddress($('#address').val())">定位</button>
                        </div>
                    </div>
                    <div id="baidumap" style="width:550px; height:300px;"></div>
                    <div style="clear:both"></div>
                    <div class="line line-dashed b-b line-lg pull-in"></div>
                    <div class="form-group">
                        <div class="col-sm-9">
                            <div class="col-sm-4">
                                <input type="text" name="lng" id="lng" value="{{$organization->OrganizationRetailinfo->lng}}"  class="form-control" />
                            </div>
                            <div class="col-sm-1">
                                -
                            </div>
                            <div class="col-sm-4">
                                <input type="text" id="lat" name="lat" value="{{$organization->OrganizationRetailinfo->lat}}"  class="form-control" />
                            </div>
                        </div>
                    </div>


                    <div style="clear:both;"></div>
                    <div class="line line-dashed b-b line-lg pull-in"></div>

                    <div class="form-group">
                        <label class="col-sm-2 text-right">安全密码</label>
                        <div class="col-sm-10">
                            <input type="password" value="" name="safe_password" placeholder="安全密码" class="form-control" >
                        </div>
                    </div>
                    <div style="clear:both;"></div>

                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                    <button class="btn btn-success" type="button" onclick="return EditStore()">确定</button>
                </div>
            </div>
        </div>
    </form>
</div>
{{--编辑店铺信息--}}


<script src="{{asset('public/Retail')}}/js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="{{asset('public/Retail')}}/js/bootstrap.js"></script>
<!-- App -->
<script src="{{asset('public/Retail')}}/js/app.js"></script>
<script src="{{asset('public/Retail')}}/library/slimscroll/jquery.slimscroll.min.js"></script>
<script src="{{asset('public/Retail')}}/js/app.plugin.js"></script>
<script src="{{asset('public/Retail')}}/library/file-input/bootstrap-filestyle.min.js"></script>
<script type="text/javascript" src="{{asset('public/Retail')}}/library/jPlayer/jquery.jplayer.min.js"></script>
<script type="text/javascript" src="{{asset('public/Retail')}}/library/jPlayer/add-on/jplayer.playlist.min.js"></script>
<script type="text/javascript" src="{{asset('public/Retail')}}/library/jPlayer/demo.js"></script>
<script type="text/javascript" src="{{asset('public/Retail')}}/library/sweetalert/sweetalert.min.js"></script>

<script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=Xv2dLyXPQEWxRVZ3GVGWE9SkkfhS4WBW"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#retail_logo").val("{{$organization->OrganizationRetailinfo->retail_logo}}");
    });
    $('#editBtn').click(function () {
        $('#myModal').modal();
    });

    //编辑店铺信息
    function EditStore() {
        var formData = new FormData($( "#store_edit" )[0]);
        $.ajax({
            url: '{{ url('retail/ajax/store_edit_check') }}',
            type: 'post',
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            success: function (json) {
                if (json.status == -1) {
                    window.location.reload();
                } else if(json.status == 1) {
                    swal({
                        title: "提示信息",
                        text: json.data,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "确定",
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
            },
            error: function (json) {
                console.log(json);
            }
        });
    }

    $(function(){

        bmap.init({});

        if ($('#address').val()) {
            bmap.searchMapByAddress($('#address').val());

        }
    });



    var bmap = {


        'option' : {



            'lock' : false,



            'container' : 'baidumap',



            'infoWindow' : {'width' : 250, 'height' : 100, 'title' : ''},



            'point' : {'lng' : 116.403851, 'lat' : 39.915177}



        },



        'init' : function(option) {



            var $this = this;



            $this.option = $.extend({},$this.option,option);



            $this.option.defaultPoint = new BMap.Point($this.option.point.lng, $this.option.point.lat);



            $this.bgeo = new BMap.Geocoder();



            $this.bmap = new BMap.Map($this.option.container);



            $this.bmap.centerAndZoom($this.option.defaultPoint, 15);



            $this.bmap.enableScrollWheelZoom();



            $this.bmap.enableDragging();



            $this.bmap.enableContinuousZoom();



            $this.bmap.addControl(new BMap.NavigationControl());



            $this.bmap.addControl(new BMap.OverviewMapControl());



            //添加标注



            $this.marker = new BMap.Marker($this.option.defaultPoint);



            $this.marker.setLabel(new BMap.Label('请您移动此标记，选择您的坐标！', {'offset':new BMap.Size(10,-20)}));



            $this.marker.enableDragging();



            $this.bmap.addOverlay($this.marker);



            //$this.marker.setAnimation(BMAP_ANIMATION_BOUNCE);



            $this.showPointValue($this.marker.getPosition());



            //拖动地图事件



            $this.bmap.addEventListener("dragging", function() {



                $this.setMarkerCenter();



                $this.option.lock = false;



            });



            //缩入地图事件



            $this.bmap.addEventListener("zoomend", function() {



                $this.setMarkerCenter();



                $this.option.lock = false;



            });



            //拖动标记事件



            $this.marker.addEventListener("dragend", function (e) {



                $this.showPointValue();



                $this.showAddress();



                $this.bmap.panTo(new BMap.Point(e.point.lng, e.point.lat));



                $this.option.lock = false;



                $this.marker.setAnimation(null);



            });



        },



        'searchMapByAddress' : function(address) {


            var $this = this;



            $this.bgeo.getPoint(address, function (point) {



                if (point) {



                    $this.showPointValue();



                    //$this.showAddress();



                    $this.bmap.panTo(point);



                    $this.setMarkerCenter();



                }



            });



        },



        'searchMapByPCD' : function(address) {



            var $this = this;



            $this.option.lock = true;



            $this.searchMapByAddress($('#sel-provance').val()+$('#sel-city').val()+$('#sel-area').val());



        },



        'setMarkerCenter' : function() {



            var $this = this;



            var center = $this.bmap.getCenter();



            $this.marker.setPosition(new BMap.Point(center.lng, center.lat));



            $this.showPointValue();



            //$this.showAddress();



        },



        'showPointValue' : function() {



            var $this = this;



            var point = $this.marker.getPosition();



            $('#lng').val(point.lng);



            $('#lat').val(point.lat);



        },



        'showAddress' : function() {



            var $this = this;



            var point = $this.marker.getPosition();



            $this.bgeo.getLocation(point, function (s) {



                if (s) {



                    $('#address').val(s.address);



                }



            });



        }



    };

</script>








</body>
</html>