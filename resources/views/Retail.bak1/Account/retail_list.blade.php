<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.html">
    <title>选择店铺 | 零壹餐饮系统管理平台</title>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/bootstrap-reset.css">
    <link rel="stylesheet" href="{{asset('public/Retail/library/sweetalert')}}/sweetalert.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail/library/font-awesome')}}/css/font-awesome.css">
    <link rel="stylesheet" href="{{asset('public/Retail/library/jquery-easy-pie-chart')}}/jquery.easy-pie-chart.css">
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/owl.carousel.css">
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/style.css">
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/style-responsive.css">
    <!--[if lt IE 9]>
    <script src="{{asset('public/Retail/library/ie')}}/html5shiv.js"></script>
    <script src="{{asset('public/Retail/library/ie')}}/respond.min.js"></script>
    <script src="{{asset('public/Retail/library/ie')}}/excanvas.js"></script>
    <![endif]-->
</head>

<body>
<section class="">
    <div class="row state-overview" style="margin: 10px;">
        <div class="col-lg-12 col-sm-6">
            <!--breadcrumbs start -->
            <ul class="breadcrumb">
                <li>
                    <h3 style="margin-top: 10px;"><i class="icon-desktop"></i> 选择要进入的店铺组织</h3>
                </li>
            </ul>
            <!--breadcrumbs end -->
        </div>
    </div>
    <div class="row state-overview" style="margin: 10px;">
        <div class="form-group">
            <div class="col-lg-12">
                <form method="get" role="form" id="searchForm" action="" onsubmit="return searchFormCheck();">
                <div class="input-group m-bot15 col-lg-3 "><span class="input-group-addon"><i
                                class="icon-search"></i></span>
                    <input type="text"  name="organization_name" value="{{$organization_name}}" class="form-control " placeholder="店铺名称">
                </div>
                <div class="input-group m-bot15 col-lg-2 ">
                    <button type="submit" class="btn btn-primary"><i class="icon-search"></i> 查询</button>
                </div>
                </form>
            </div>
        </div>
        <div style="clear:both;"></div>
    </div>
    <!--state overview start-->
    <div class="row state-overview" style="margin: 10px;">
        <input type="hidden" id="_token" value="{{csrf_token()}}">
        <input type="hidden" id="url" value="{{url('retail/ajax/retail_select')}}">
        @foreach($organization as $key=>$val)
        <div class="col-lg-3 col-sm-6">
            <a href="javascript:;" onclick="postForm({{$val->account->id}})">
                <section class="panel">
                    <div class="symbol terques"><i class="icon-arrow-right"></i></div>
                    <div class="value"><b>{{ $val->organization_name }}</b>
                        <p>
                            所属总店：【{{$val->cateringname}}】
                        </p>
                    </div>
                </section>
            </a>
        </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-sm-12 text-right text-center-xs">
            {!! $organization->links() !!}
        </div>
    </div>
    <!--state overview end-->
</section>
<script src="{{asset('public/Retail')}}/js/jquery.js"></script>
<script src="{{asset('public/Retail')}}/js/jquery-1.8.3.min.js"></script>
<script src="{{asset('public/Retail')}}/js/bootstrap.min.js"></script>
<script src="{{asset('public/Retail')}}/js/jquery.scrollTo.min.js"></script>
<script src="{{asset('public/Retail')}}/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="{{asset('public/Retail/library/sweetalert')}}/sweetalert.min.js"></script>
<!--common script for all pages-->
<script src="{{asset('public/Retail')}}/js/common-scripts.js"></script>
<script>
    //提交表单
    function postForm(account_id){
        var _token = $("#_token").val();
        var url = $("#url").val();
        var data = {'_token':_token,'account_id':account_id};
        $.post(url,data,function(json){
            console.log(json.status);
            if (json.status == 1){
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
    //搜索商户组织
    function searchFormCheck(){
        var url = $('#searchForm').attr('action');
        var data = $('#searchForm').serialize();
        $.get(url+'?'+data,function(json){
            if(json.status==0){
                swal({
                    title: "提示信息",
                    text: json.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定",
                });
                return false;
            }else{
                location.href=url+'?'+data;
            }
        });
        return false;
    }
</script>
</body>
</html>
