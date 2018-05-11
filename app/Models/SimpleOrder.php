<?php
/**
 * simple_order表的模型
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimpleOrder extends Model
{
    use SoftDeletes;
    protected $table = 'simple_order';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $dateFormat = 'U';//设置保存的created_at updated_at为时间戳格式
    public $guarded = [];

    //和User表多对一的关系
    public function User()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    //和SimpleOrderGoods表一对多的关系
    public function SimpleOrderGoods()
    {
        return $this->hasMany('App\Models\SimpleOrderGoods', 'order_id', 'id');
    }

    //和account表一对一的关系
    public function account()
    {
        return $this->hasOne('App\Models\Account', 'id', 'operator_id');
    }

    //和个人信息表一对一的关系
    public function account_info()
    {
        return $this->hasOne('App\Models\AccountInfo', 'account_id', 'operator_id');
    }

    public static function getOne($where)
    {
        return self::with('User')->with('SimpleOrderGoods')->where($where)->first();
    }

    //获取列表
    public static function getList($where, $limit = 0, $orderby, $sort = 'DESC', $select = [])
    {
        $model = new SimpleOrder();
        if (!empty($limit)) {
            $model = $model->limit($limit);
        }
        if (!empty($select)) {
            $model = $model->select($select);
        }
        return $model->with('SimpleOrderGoods')->where($where)->orderBy($orderby, $sort)->get();
    }

    //获取列表
    public static function getListPaginate($where, $paginate, $orderby, $sort = 'DESC', $select = [])
    {
        $model = new SimpleOrder();
        if (!empty($select)) {
            $model = $model->select($select);
        }
        return $model->where($where)->orderBy($orderby, $sort)->paginate($paginate);
    }


    //修改订单信息
    public static function editSimpleOrder($where, $param)
    {
        $model = self::where($where)->first();
        foreach ($param as $key => $val) {
            $model->$key = $val;
        }
        $model->save();
    }


    //添加数据
    public static function addSimpleOrder($param)
    {
        $simpleorder = new SimpleOrder();//实例化程序模型
        $simpleorder->ordersn = $param['ordersn'];//订单编号
        $simpleorder->order_price = $param['order_price'];//订单价格
        $simpleorder->user_id = $param['user_id'];//订单人id
        $simpleorder->status = $param['status'];//订单状态
        $simpleorder->operator_id = $param['operator_id'];//操作人员id
        $simpleorder->fansmanage_id = $param['fansmanage_id'];//管理平台id
        $simpleorder->simple_id = $param['simple_id'];//店铺所属组织ID
        $simpleorder->discount_price = $param['discount_price'];//折扣价
        if (!empty($param['payment_price'])) {
            $simpleorder->payment_price = $param['payment_price'];//实收价
        }
        $simpleorder->discount = $param['discount'];//折扣比率
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
        $model = self::with('User')->with('account')->with('account_info');
        return $model->with('SimpleOrderGoods')->where($where)->orderBy($orderby, $sort)->paginate($paginate);

    }

    //获取单行数据的其中一列
    public static function getPluck($where, $pluck)
    {
        return self::where($where)->value($pluck);
    }
}

?>