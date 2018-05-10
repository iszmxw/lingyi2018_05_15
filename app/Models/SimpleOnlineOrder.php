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

    //和SimpleOnlineOrderGoods表一对多的关系
    public function Goods()
    {
        return $this->hasMany('App\Models\SimpleOnlineGoods', 'order_id', 'id');
    }

    //和SimpleOnlineAddress表一对一的关系
    public function Address()
    {
        return $this->hasOne('App\Models\SimpleOnlineAddress', 'order_id', 'id');
    }

    //获取餐饮商品列表
    public static function getListApi($where, $page = 0, $orderby, $sort = 'DESC', $select = [])
    {
        $model = new SimpleOnlineOrder();
        if (!empty($page)) {
            $page1 = $page * 2 - 2;
            $page2 = $page * 2;
            $model = $model->offset($page1,$page2)->limit(2);
        }
        if (!empty($select)) {
            $model = $model->select($select);
        }
        return $model->where($where)->orderBy($orderby, $sort)->get();
    }

    //获取单条数据
    public static function getOne($where)
    {
        return  self::where($where)->first();
    }

    //获取单条数据
    public static function getOneJoin($where)
    {
        return  self::with('Address')->with('Goods')->where($where)->first();
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
        $model = new SimpleOnlineOrder();//实例化程序模型
        $model->fansmanage_id = $param['fansmanage_id'];//联盟id
        $model->simple_id = $param['simple_id'];//店铺id
        $model->ordersn = $param['ordersn'];//订单编号
        $model->order_price = $param['order_price'];//订单价格
        if (!empty($param['remarks'])) {
            $model->remarks = $param['remarks'];//备注信息
        }
        if (!empty($param['payment_company'])) {
            $model->payment_company = $param['payment_company'];//支付公司
        }
        $model->user_id = $param['user_id'];//订单人id
        $model->status = $param['status'];//订单状态
        if (!empty($param['operator_id'])) {
            $model->operator_id = $param['operator_id'];//操作人员id
        }
        if (!empty($param['paytype'])) {
            $model->paytype = $param['paytype'];//付款方式
        }
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