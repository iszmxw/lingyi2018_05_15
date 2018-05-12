<?php
/**
 * module_node表的模型
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimpleSelftake extends Model
{
    use SoftDeletes;
    protected $table = 'simple_selftake';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $dateFormat = 'U';//设置保存的created_at updated_at为时间戳格式
    protected $guarded = [];


    //获取多条信息
    public static function getList($where)
    {
        return self::where($where)->get();
    }

    //获取多条信息
    public static function getOne($where)
    {
        return self::where($where)->first();
    }


    //添加数据
    public static function addSelftake($param)
    {
        $selftake = new SimpleSelftake();//实例化程序模型
        $selftake->zerone_user_id = $param['zerone_user_id'];//零壹id
        $selftake->realname = $param['realname'];//真实姓名
        $selftake->mobile = $param['mobile'];//手机号
        $selftake->sex = $param['sex'];//性别
        $selftake->status = $param['status'];//状态（1为默认取款信息）
        $selftake->save();
        return $selftake->id;
    }

    //修改数据
    public static function editSelftake($where, $param)
    {
        $model = self::where($where)->first();
        foreach ($param as $key => $val) {
            $model->$key = $val;
        }
        $model->save();
    }

    //修改数据
    public static function editaa($where, $param)
    {
        // 更新信息
        self::where($where)->update(['status' => $param["status"]]);
    }


    //查询数据是否存在（仅仅查询ID增加数据查询速度）
    public static function checkRowExists($where)
    {
        $row = self::getPluck($where, 'id');
        if (empty($row)) {
            return false;
        } else {
            return true;
        }
    }

    //获取单行数据的其中一列
    public static function getPluck($where, $pluck)
    {
        return self::where($where)->orderBy('id', 'DESC')->value($pluck);
    }

}

