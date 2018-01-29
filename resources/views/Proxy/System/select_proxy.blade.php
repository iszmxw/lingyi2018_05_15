<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>选择服务商 | 零壹服务商管理平台</title>

    <!-- Bootstrap core CSS -->
    <link href="{{asset('public/Proxy')}}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('public/Proxy')}}/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="{{asset('public/Proxy')}}/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="{{asset('public/Proxy')}}/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="{{asset('public/Proxy')}}/css/owl.carousel.css" type="text/css">
    <!-- Custom styles for this template -->
    <link href="{{asset('public/Proxy')}}/css/style.css" rel="stylesheet">
    <link href="{{asset('public/Proxy')}}/css/style-responsive.css" rel="stylesheet" />
    <link href="{{asset('public/Proxy/library/sweetalert')}}/css/sweetalert.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="{{asset('public/Proxy')}}/js/html5shiv.js"></script>
    <script src="{{asset('public/Proxy')}}/js/respond.min.js"></script>
    <![endif]-->
</head>

<body>


<section class="">
    <div class="row state-overview" style="margin: 10px;">
        <div class="col-lg-12 col-sm-6">
            <!--breadcrumbs start -->
            <ul class="breadcrumb">
                <li><h3 style="margin-top: 10px;"><i class="icon-desktop"></i> 选择要进入的服务商组织</h3></li>
            </ul>
            <!--breadcrumbs end -->
        </div>
    </div>

    <div class="row state-overview" style="margin: 10px;">
        <div class="form-group">
            <div class="col-lg-12">
                <div class="input-group m-bot15 col-lg-3 ">
                    <span class="input-group-addon"><i class="icon-search"></i></span>
                    <input type="text" class="form-control " placeholder="服务商名称">

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
        <input type="hidden" id="url" value="{{url('proxy/system/select_proxy')}}">
    @foreach($listOrg as $key=>$value)
        <div class="col-lg-3 col-sm-6">
            <a href="javascript:;" onclick="postForm('{{$value->id}}')">
                <section class="panel">
                    <div class="symbol terques">
                        <i class="icon-arrow-right"></i>
                    </div>
                    <div class="value">
                        <b>{{$value->organization_name}}</b>
                        <p>{{$value->zone_name}}</p>
                    </div>
                </section>
            </a>
        </div>
        @endforeach
    </div>
    <!--state overview end-->
</section>
<script src="{{asset('public/Proxy/library/jquery')}}/js/jquery-2.1.1.js"></script>
<script src="{{asset('public/Proxy')}}/js/bootstrap.min.js"></script>
<script src="{{asset('public/Proxy/library/sweetalert')}}/js/sweetalert.min.js"></script>
<script>
    //提交表单
    function postForm(organization_id){
        var _token = $("#_token").val();
        var url = $("#url").val();
        var data = {'_token':_token,'organization_id':organization_id};
        $.post(url,data,function(json){
            if(json.status==1){
                window.location.reload();
            }else{

                console.log(json);
//                swal({
//                    title: "提示信息",
//                    text: json.data,
//                    confirmButtonColor:"#DD6B55",
//                    confirmButtonText: "确定",
//                    //type: "warning"
//                });
            }
        });
    }
</script>
</body>
</html>
