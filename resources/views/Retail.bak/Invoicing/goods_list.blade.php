@foreach($goods as $key=>$val)
    <tr id="{{$val->id}}">
        <td class="id">{{$val->id}}</td>
        <td class="name">{{$val->name}}</td>
        <td class="price">{{$val->price}}</td>
        <td>
            <button onclick="goodsSelect({{$val->id}});" class="btn btn-info btn-xs" type="button">
                <i class="fa fa-plus"></i>&nbsp;&nbsp;选择
            </button>
        </td>
    </tr>
@endforeach