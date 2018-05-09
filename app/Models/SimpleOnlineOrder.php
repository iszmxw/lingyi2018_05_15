<?php
/**
 * simple_order表的模型
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimpleOnlineOrder extends Model
{
    use SoftDeletes;
    protected $table = 'simple_online_order';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $dateFormat = 'U';//设置保存的created_at updated_at为时间戳格式
    public $guarded = [];


    //获取列表
    public static function getListPaginate($where, $paginate, $orderby, $sort = 'DESC', $select = [])
    {
        $model = new SimpleOnlineOrder();
        if (!empty($select)) {
            $model = $model->select($select);
        }
        return $model->where($where)->orderBy($orderby, $sort)->paginate($paginate);
    }

    //获取单条数据
    public static function getOne($where)
    {
        return  self::where($where)->first();
    }

    //修改订单信息
    public static function editSimpleOnlineOrder($where, $param)
    {
        $model = self::where($where)->first();
        foreach ($param as $key => $val) {
            $model->$key = $val;
        }
        $model->save();
    }


    //添加数据
    public static function addSimpleOnlineOrder($param)
    {
        $simpleorder = new SimpleOnlineOrder();//实例化程序模型
        $simpleorder->ordersn = $param['ordersn'];//订单编号
        $simpleorder->order_price = $param['order_price'];//订单价格
        $simpleorder->user_id = $param['user_id'];//订单人id
        $simpleorder->status = $param['status'];//订单状态
        $simpleorder->operator_id = $param['operator_id'];//操作人员id
        $simpleorder->fansmanage_id = $param['fansmanage_id'];//管理平台id
        $simpleorder->simple_id = $param['simple_id'];//店铺所属组织ID
        if (!empty($param['paytype'])) {
            $simpleorder->paytype = $param['paytype'];//付款方式
        }
        if (!empty($param['remarks'])) {
            $simpleorder->remarks = $param['remarks'];//备注信息
        }
        $simpleorder->save();
        return $simpleorder->id;
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