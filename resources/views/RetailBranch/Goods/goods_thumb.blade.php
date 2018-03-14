<div class="table-responsive">
    <table class="table table-bordered table-stripped">
        <thead>
        <tr>
            <th>
                图片预览
            </th>
            <th>
                图片链接
            </th>
            <th>
                图片排序
            </th>
            <th>

            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($goods_thumb as $key=>$val)
        <tr>
            <td>
                <img src="{{asset('/')}}{{$val->thumb}}" style="width: 50px; height: 50px;">
            </td>
            <td>
                {{asset('/'.$val->thumb)}}
            </td>
            <td>
                <input type="text" name="displayorder" size="3" value="{{$val->displayorder}}" />
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <div class="line line-dashed b-b line-lg pull-in"></div>
    <div class="form-group">
        <div class="col-sm-12 col-sm-offset-6">
            <button type="button" class="btn btn-success" id="addBtn">保存信息</button>
        </div>
    </div>
</div>