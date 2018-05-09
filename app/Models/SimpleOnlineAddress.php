<?php
/**
 * simple_order表的模型
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimpleOnlineAddress extends Model
{
    use SoftDeletes;
    protected $table = 'simple_online_address';
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
    public static function editSimpleOnlineAddress($where, $param)
    {
        $model = self::where($where)->first();
        foreach ($param as $key => $val) {
            $model->$key = $val;
        }
        $model->save();
    }


    //添加数据
    public static function addSimpleOnlineAddress($param)
    {
        $model = new SimpleOnlineAddress();//实例化程序模型
        $model->order_id = $param['order_id'];//订单id
        $model->province_name = $param['province_name'];//省份名称
        $model->city_name = $param['city_name'];//城市名称
        $model->district_name = $param['district_name'];//地区名称
        $model->address = $param['address'];//详细地址
        $model->realname = $param['realname'];//收货人真实姓名
        $model->mobile = $param['mobile'];//手机号码
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