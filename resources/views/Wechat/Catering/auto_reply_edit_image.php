<form class="form-horizontal tasi-form" method="post" id="currentForm" action="{{ url('api/ajax/auto_reply_edit_image_check') }}">
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
    <input type="hidden" name="id" id="id" value="{{$id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">选择图片素材</h4>
            </div>
            <div class="modal-body">
                <div class="row row-sm">
                    <div class="col-lg-2">
                        <div class="item">
                            <div class="pos-rlt">
                                <a href="javascript:;"><img src="{{asset('public/Catering')}}/img/m1.jpg" alt="" class="r r-2x img-full"></a>
                            </div>
                            <div class="padder-v">
                                <span>414631616.JPG</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                <button class="btn btn-success" type="button" id="save_btn">确定</button>
            </div>
        </div>
    </div>
</form>