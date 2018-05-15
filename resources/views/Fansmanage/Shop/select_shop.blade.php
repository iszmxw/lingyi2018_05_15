<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>选择分公司 | 零壹分公司管理平台</title>

    <!-- Bootstrap core CSS -->
    <link href="{{asset('public/Agent')}}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('public/Agent')}}/css/bootstrap-reset.css" rel="stylesheet">
    {{--<!--external css-->--}}
    <link href="{{asset('public/Agent')}}/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="{{asset('public/Agent')}}/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="{{asset('public/Agent')}}/css/owl.carousel.css" type="text/css">
    <!-- Custom styles for this template -->
    <link href="{{asset('public/Agent')}}/css/style.css" rel="stylesheet">
    <link href="{{asset('public/Agent')}}/css/style-responsive.css" rel="stylesheet" />
    <link href="{{asset('public/Agent/library/sweetalert')}}/css/sweetalert.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="{{asset('public/Agent')}}/js/html5shiv.js"></script>
    <script src="{{asset('public/Agent')}}/js/respond.min.js"></script>
    <![endif]-->
</head>

<body>


<section class="">
    <div class="row state-overview" style="margin: 10px;">
        <div class="col-lg-12 col-sm-6">
            <!--breadcrumbs start -->
            <ul class="breadcrumb">
                <li><h3 style="margin-top: 10px;"><i class="icon-desktop"></i> 选择要进入的店铺</h3></li>
            </ul>
            <!--breadcrumbs end -->
        </div>
    </div>

    <div class="row state-overview" style="margin: 10px;">
        <div class="form-group">
            <div class="col-lg-12">
                <div class="input-group m-bot15 col-lg-3 ">
                    <span class="input-group-addon"><i class="icon-search"></i></span>
                    <input type="text" class="form-control " placeholder="店铺名称">

                </div>
                <div class="input-group m-bot15 col-lg-2 ">
                    <button type="button" class="btn btn-primary"><i class="icon-search"></i> 查询</button>
                </div>
            </div>
        </div>
        <div style="clear:both;"></div>
    </div>
    <!--state overview start-->
    <div class="row state-overview" style="margin: 10px;">
        <input type="hidden" id="_token" value="{{csrf_token()}}">
        <input type="hidden" id="url" value="{{url('fansmanage/select_shop')}}">
    @foreach($listOrg as $key=>$value)
        <div class="col-lg-3 col-sm-6">
            <a href="javascript:;" onclick="postForm('{{$value->id}}','{{$value->account->id}}')">
                <section class="panel">
                    <div class="symbol terques">
                        <i class="icon-arrow-right"></i>
                    </div>
                    <div class="value">
                        <b>{{$value->organization_name}}</b>
                        {{--<p>{{$value->warzone['0']['zone_name']}}</p>--}}
                    </div>
                </section>
            </a>
        </div>
        @endforeach
    </div>
</section>
<!-- js placed at the end of the document so the pages load faster -->
<script src="{{asset('public/Agent')}}/js/jquery.js"></script>
<script src="{{asset('public/Agent')}}/js/jquery-1.8.3.min.js"></script>
<script src="{{asset('public/Agent')}}/js/bootstrap.min.js"></script>
<script src="{{asset('public/Agent')}}/js/jquery.scrollTo.min.js"></script>
<script src="{{asset('public/Agent')}}/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="{{asset('public/Agent')}}/js/jquery.sparkline.js" type="text/javascript"></script>
<script src="{{asset('public/Agent')}}/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js"></script>
<script src="{{asset('public/Agent')}}/js/owl.carousel.js" ></script>
<script src="{{asset('public/Agent')}}/js/jquery.customSelect.min.js" ></script>
<!--common script for all pages-->
<script src="{{asset('public/Agent')}}/js/common-scripts.js"></script>
<!--script for this page-->
<script src="{{asset('public/Agent')}}/js/sparkline-chart.js"></script>
<script src="{{asset('public/Agent')}}/js/easy-pie-chart.js"></script>
<script src="{{asset('public/Agent/library/sweetalert')}}/js/sweetalert.min.js"></script>
<script>
    //提交表单
    function postForm(organization_id,account_id){
        var _token = $("#_token").val();
        var url = $("#url").val();
        var data = {'_token':_token,'organization_id':organization_id,'account_id':account_id};
        $.post(url,data,function(json){
            if(json.status==1){
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
                    confirmButtonColor:"#DD6B55",
                    confirmButtonText: "确定"
                },function () {
                    window.location.reload();
                });
            }
        });
    }
</script>
</body>
</html>
