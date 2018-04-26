<?php
/**
 * node表的模型
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FansmanageUser extends Model
{
    use SoftDeletes;
    protected $table = 'fansmanage_user';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $dateFormat = 'U';//设置保存的created_at updated_at为时间戳格式
    protected $guarded = [];

    //用户零壹账号源头表一对一的关系
    public function userOrigin()
    {
        return $this->hasOne('App\Models\UserOrigin', 'user_id', 'user_id');
    }


    //零壹粉丝端账号表一对一的关系
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    /**
     * 跟user_info 表关联,获取用户数据
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userInfo()
    {
        return $this->belongsTo("App\Models\UserInfo", "user_id", "user_id");
    }

    /**
     * 跟 粉丝标签关联, 获取标签数据
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userLabel()
    {
        return $this->belongsTo("App\Models\UserLabel", "user_id", "user_id");
    }


    //用户消费推荐表（导流）一对一的关系
    public function userRecommender()
    {
        return $this->hasOne('App\Models\UserRecommender', 'user_id', 'user_id');
    }

    //简易型查询单条数据关联查询
    public static function getOneFansmanageUser($where)
    {
        return self::where($where)->first();
    }


    //查询获取列表
    public static function getListStoreUser($where, $limit = 0, $orderby, $sort = 'DESC')
    {
        $model = self::where($where)->with('UserLabel')->with('userOrigin')->with('user')->with('userRecommender')->orderBy($orderby, $sort);
        if (!empty($limit)) {
            $model = $model->limit($limit);
        }
        return $model->get();
    }

    //修改数据
    public static function editStoreUser($where, $param)
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

    //查询粉丝数量
    public static function getCount($where)
    {
        return self::where($where)->get()->count();
    }


    // 获取分页数据

    /**
     * 用户列表 数据
     * @param $where
     * @param $user_id
     * @param $paginate
     * @param $orderby
     * @param string $sort
     * @param string $search
     * @return mixed
     */
    public static function getPaginage($where, $user_id, $paginate, $orderby, $sort = 'DESC', $search = '')
    {
        $model = self::select("fansmanage_user.id", "fansmanage_user.fansmanage_id", "fansmanage_user.user_id", "fansmanage_user.open_id", "fansmanage_user.mobile","fansmanage_user.status", "fansmanage_user.created_at", "user.account")->with(["userOrigin", "user" => function ($query) {
            $query->select("id", "account");
        }, "userRecommender" => function ($query) {
            $query->select("recommender_id", "user_id");
        }, "userInfo" => function ($query) {
            $query->select("id", "nickname", "head_imgurl", "user_id");
        }, "userLabel" => function ($query) {
            $query->select("label_id", "user_id");
        }])->leftJoin('user', 'fansmanage_user.user_id', '=', 'user.id');

        // 判断是否有搜索关键字
        if (!empty($search)) {
            $model->where("user.account", "like", "%$search%");
        }

        if (!empty($user_id)) {
            $model->where(['user_id' => $user_id]);
        }
        return $model->where($where)->orderBy($orderby, $sort)->paginate($paginate);
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

        var_dump($res);
        exit;
        if (!empty($res)) {
            return $res->toArray();
        } else {
            return false;
        }
    }
}
