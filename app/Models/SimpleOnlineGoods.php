<?php
/**
 * simple_order表的模型
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimpleOnlineGoods extends Model
{
    use SoftDeletes;
    protected $table = 'simple_online_goods';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $dateFormat = 'U';//设置保存的created_at updated_at为时间戳格式
    public $guarded = [];


    //获取列表
    public static function getListPaginate($where, $paginate, $orderby, $sort = 'DESC', $select = [])
    {
        $model = new SimpleOnlineGoods();
        if (!empty($select)) {
            $model = $model->select($select);
        }
        return $model->where($where)->orderBy($orderby, $sort)->paginate($paginate);
    }


    //修改订单信息
    public static function editSimpleOnlineGoods($where, $param)
    {
        $model = self::where($where)->first();
        foreach ($param as $key => $val) {
            $model->$key = $val;
        }
        $model->save();
    }


    //添加数据
    public static function addSimpleOnlineGoods($param)
    {
        $model = new SimpleOnlineGoods();//实例化程序模型
        $model->order_id = $param['order_id'];//订单id
        $model->goods_id = $param['goods_id'];//商品id
        $model->title = $param['title'];//商品标题快照
        $model->thumb = $param['thumb'];//商品图片快照
        if (!empty($param['details'])) {
            $model->details = $param['details'];//商品详情
        }
        $model->price = $param['price'];//商品价格
        $model->save();
        return $model->id;
    }

    //获取分页列表
    public static function getPaginage($where, $paginate, $orderby, $sort = 'DESC')
    {
        return self::where($where)->orderBy($orderby, $sort)->paginate($paginate);
    }

    //获取单行数据的其中一列
    public static function getPluck($where, $pluck)
    {
        return self::where($where)->value($pluck);
    }
}

?>