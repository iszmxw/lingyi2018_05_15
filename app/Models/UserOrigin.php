<?php
/**
 * node表的模型
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserOrigin extends Model
{
    use SoftDeletes;
    protected $table = 'user_origin';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $dateFormat = 'U';//设置保存的created_at updated_at为时间戳格式
    protected $guarded = [];

    //和账号多对多的关系
    public function storeUser()
    {
        return $this->belongsTo('App\Models\FansmanageUser', 'user_id', 'user_id');
    }


    //简易型查询单条数据关联查询
    public static function getOne($where)
    {
        return self::where($where)->first();
    }


    //查询获取列表
    public static function getList($where, $limit = 0, $orderby, $sort = 'DESC')
    {
        $model = self::where($where)->orderBy($orderby, $sort);
        if (!empty($limit)) {
            $model = $model->limit($limit);
        }
        return $model->get();
    }

    //修改数据
    public static function editNode($where, $param)
    {
        $model = self::where($where)->first();
        foreach ($param as $key => $val) {
            $model->$key = $val;
        }
        $model->save();
    }

    //查询数据是否存在（仅仅查询ID增加数据查询速度）
    public static function checkRowExists($where)
    {
        $row = self::getPluck($where, 'id')->toArray();
        if (empty($row)) {
            return false;
        } else {
            return true;
        }
    }

    //获取单行数据的其中一列
    public static function getPluck($where, $pluck)
    {
        return self::where($where)->pluck($pluck);
    }

    //获取分页数据
    public static function getPaginage($where, $paginate, $orderby, $sort = 'DESC')
    {
        return self::where($where)->orderBy($orderby, $sort)->paginate($paginate);
    }


    /**
     * 获取源头的信息
     * @param array $where
     * @param array $field
     * @return bool
     */
    public static function getInfo($where = [], $field = [])
    {
        $res = self::select($field)->where($where)->first();
        if (!empty($res)) {
            return $res->toArray();
        } else {
            return false;
        }
    }

    /**
     * 添加数据
     * @param $param
     * @param $type
     * @param $where
     * @return bool
     */
    public static function insertData($param, $type = "update_create", $where = [])
    {
        switch ($type) {
            case "update_create":
                $res = self::updateOrCreate($where, $param);
                break;
            case "first_create":
                $res = self::firstOrCreate($param);
                break;
        }

        if (!empty($res)) {
            return $res->toArray();
        } else {
            return false;
        }
    }
}
