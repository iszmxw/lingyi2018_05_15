<?php
/**
 * retail_order_goods表的模型
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimpleSelftakeUser extends Model
{
    use SoftDeletes;
    protected $table = 'simple_selftake_user';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $dateFormat = 'U';//设置保存的created_at updated_at为时间戳格式

    //获取列表
    public static function getList($where, $limit = 0, $orderby, $sort = 'DESC')
    {
        $model = new SimpleSelftakeGoods();
        if (!empty($limit)) {
            $model = $model->limit($limit);
        }
        return $model->where($where)->orderBy($orderby, $sort)->get();
    }

    //添加数据
    public static function addSimpleSelftakeUser($param)
    {
        $model = new SimpleSelftakeUser();//实例化程序模型
        $model->order_id = $param['order_id'];//订单id
        $model->realname = $param['realname'];//取货人姓名
        $model->sex = $param['sex'];//性别
        $model->mobile = $param['mobile'];//手机号码
        $model->code = $param['code'];//取货码
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