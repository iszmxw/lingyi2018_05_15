<form class="form-horizontal tasi-form" method="post" enctype="multipart/form-data" action="{{ url('api/ajax/meterial_image_upload_check') }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">上传本地图片</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="get">
                    <div class="form-group">
                        <label class="col-sm-2 text-right">本地图片</label>
                        <div class="col-sm-10">
                            <input type="file" name="image" class="filestyle" style="display: none;" data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline v-middle input-s">
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                <button class="ladda-button btn btn-success" type="button" data-style="expand-right"><span class="ladda-label">提交</span><span class="ladda-spinner"></span></button>
            </div>
        </div>
    </div>
</form>
<script src="{{asset('public/Catering')}}/js/file-input/bootstrap-filestyle.min.js"></script>
<!-- Ladda -->
<script src="{{asset('public/Catering')}}/ladda/spin.min.js"></script>
<script src="{{asset('public/Catering')}}/ladda/ladda.min.js"></script>
<script src="{{asset('public/Catering')}}/ladda/ladda.jquery.min.js"></script>
<script>
    $(function(){
        var l = $( '.ladda-button' ).ladda();
        l.click(function(){

            // Start loading
            l.ladda( 'start' );
            setTimeout(function(){
                l.ladda('stop');
            },12000);
        });
    });

    //提交表单
    function postForm() {
        var target = $("#currentForm");
        var url = target.attr("action");
        var data = target.serialize();

        var l = $( '.ladda-button' ).ladda();
        l.ladda( 'start' );

        $.post(url, data, function (json) {
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
                l.ladda('stop');
            }else{
                swal({
                    title: "提示信息",
                    text: json.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定",
                    //type: "warning"
                });
                l.ladda('stop');
            }
        });
    }
</script>