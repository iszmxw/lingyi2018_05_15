<?php
/**
 * retail_order_goods表的模型
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimpleSleftakeGoods extends Model
{
    use SoftDeletes;
    protected $table = 'simple_sleftake_goods';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $dateFormat = 'U';//设置保存的created_at updated_at为时间戳格式

    //获取列表
    public static function getList($where, $limit = 0, $orderby, $sort = 'DESC')
    {
        $model = new SimpleSleftakeGoods();
        if (!empty($limit)) {
            $model = $model->limit($limit);
        }
        return $model->where($where)->orderBy($orderby, $sort)->get();
    }

    //添加数据
    public static function addSimpleSleftakeGoods($param)
    {
        $model = new SimpleSleftakeGoods();//实例化程序模型
        $model->order_id = $param['order_id'];//订单id
        $model->goods_id = $param['goods_id'];//商品id
        $model->title = $param['title'];//商品标题快照
        $model->thumb = $param['thumb'];//商品图片快照
        $model->total = $param['total'];//商品数量
        if (!empty($param['details'])) {
            $model->details = $param['details'];//商品详情
        }
        $model->price = $param['price'];//商品价格
        $model->save();
        return $model->id;
    }

    //修改数据
    public static function editOrder($where, $param)
    {
        if ($model = self::where($where)->first()) {
            foreach ($param as $key => $val) {
                $model->$key = $val;
            }
            $model->save();
        }
    }

    //获取分页列表
    public static function getPaginage($where, $paginate, $orderby, $sort = 'DESC')
    {
        return self::where($where)->orderBy($orderby, $sort)->paginate($paginate);
    }
}

?>