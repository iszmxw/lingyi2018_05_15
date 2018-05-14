<?php
/**
 * module_node表的模型
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimpleAddress extends Model
{
    use SoftDeletes;
    protected $table = 'simple_address';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $dateFormat = 'U';//设置保存的created_at updated_at为时间戳格式
    protected $guarded = [];


    // 获取多条信息
    public static function getList($where)
    {
        return self::where($where)->get();
    }

    // 获取多条信息
    public static function getOne($where)
    {
        return self::where($where)->first();
    }


    // 添加数据
    public static function addAddress($param)
    {
        $address = new SimpleAddress();//实例化程序模型

        $address->zerone_user_id = $param['zerone_user_id'];//用户零壹id
        $address->province_id = $param['province_id'];//省份id
        $address->province_name = $param['province_name'];//省份名称
        $address->city_id = $param['city_id'];//城市ID
        $address->city_name = $param['city_name'];//城市名称
        if($param['area_id']){
            $address->area_id = $param['area_id'];//地区ID
        }
        if($param['area_name']){
            $address->area_name = $param['area_name'];//地区名称
        }
        $address->address = $param['address'];//详细地址
        $address->realname = $param['realname'];//收货人真实姓名
        $address->mobile = $param['mobile'];//手机号码
        $address->status = $param['status'];//1为默认收货地址
        $address->save();
        return $address->id;
    }

    // 修改数据
    public static function editAddress($where, $param)
    {
        // 更新信息
        self::where($where)->update(['status' => $param["status"]]);
    }

    // 查询数据是否存在（仅仅查询ID增加数据查询速度）
    public static function checkRowExists($where)
    {
        $row = self::getPluck($where, 'id');
        if (empty($row)) {
            return false;
        } else {
            return true;
        }
    }

    // 获取单行数据的其中一列
    public static function getPluck($where, $pluck)
    {
        return self::where($where)->value($pluck);
    }

    // 删除数据
    public static function deleteAddress($where)
    {
        return self::where($where)->forcedelete();
    }

    // 获取分页数据-分店
    public static function getstore($where, $paginate, $orderby, $sort = 'DESC')
    {
        return self::with('OrganizationRetailinfo')->with('account')->where($where)->orderBy($orderby, $sort)->paginate($paginate);
    }
}

