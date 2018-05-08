<?php
/**
 * simple_goods表的模型
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimpleGoods extends Model
{
    use SoftDeletes;
    protected $table = 'simple_goods';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $dateFormat = 'U';//设置保存的created_at updated_at为时间戳格式

    //和创建者account表多对一的关系
    public function create_account()
    {
        return $this->belongsto('App\Models\Account', 'created_by', 'id');
    }

    //和SimpleCategory多对一的关系
    public function category()
    {
        return $this->belongsTo('App\Models\SimpleCategory', 'category_id', 'id');
    }

    //和organization表一对一的关系
    public function organization()
    {
        return $this->belongsto('App\Models\Organization', 'simple_id', 'id');
    }

    //和SimpleGoodsThumb表一对多的关系
    public function SimpleGoodsThumb()
    {
        return $this->hasMany('App\Models\SimpleGoodsThumb', 'goods_id', 'id');
    }

    //和SimpleGoodsThumb表一对一的关系
    public function GoodsThumb()
    {//后台商品列表调用第一张缩略图使用
        return $this->hasOne('App\Models\SimpleGoodsThumb', 'goods_id', 'id');
    }


    //获取单条餐饮商品信息
    public static function getOne($where)
    {
        return self::with('category')->with('SimpleGoodsThumb')->where($where)->first();
    }

    //获取单行数据的其中一列
    public static function getPluck($where, $pluck)
    {
        return self::where($where)->value($pluck);
    }

    //查询数据是否存在（仅仅查询ID增加数据查询速度）
    public static function checkRowExists($where, $pluck)
    {
        $row = self::getPluck($where, $pluck);
        if (empty($row)) {
            return false;
        } else {
            return true;
        }
    }

    //获取餐饮商品列表
    public static function getList($where, $limit = 0, $orderby, $sort = 'DESC', $select = [])
    {
        $model = new SimpleGoods();
        if (!empty($limit)) {
            $model = $model->limit($limit);
        }
        if (!empty($select)) {
            $model = $model->select($select);
        }
        return $model->where($where)->orderBy($orderby, $sort)->get();
    }

    //获取餐饮商品列表
    public static function getListApi($where, $limit = 0, $orderby, $sort = 'DESC', $select = [])
    {
        $model = new SimpleGoods();
        if (!empty($limit)) {
            $limit1 = $limit * 2 - 2;
            $limit2 = $limit * 2;
            $model = $model->offset(6)->limit(6);
        }
        if (!empty($select)) {
            $model = $model->select($select);
        }
        return $model->where($where)->orderBy($orderby, $sort)->get();
    }

    //添加餐饮商品
    public static function addSimpleGoods($param)
    {
        $model = new SimpleGoods();
        $model->name = $param['name'];
        $model->details = $param['details'];
        $model->price = $param['price'];
        $model->barcode = $param['barcode'];
        $model->stock = $param['stock'];
        $model->created_by = $param['created_by'];
        $model->category_id = $param['category_id'];
        $model->displayorder = $param['displayorder'];
        $model->fansmanage_id = $param['fansmanage_id'];
        $model->simple_id = $param['simple_id'];
        $model->save();
        return $model->id;
    }

    //修改餐饮商品数据
    public static function editSimpleGoods($where, $param)
    {
        if ($model = self::where($where)->first()) {
            foreach ($param as $key => $val) {
                $model->$key = $val;
            }
            $model->save();
        }
    }

    //获取分页列表
    public static function getPaginage($where, $search_data, $paginate, $orderby, $sort = 'DESC')
    {
        $model = self::with('GoodsThumb');
        if (!empty($search_data['category_id'])) {
            $model = $model->where([['category_id', $search_data['category_id']]]);
        }
        if (!empty($search_data['goods_name'])) {
            $model = $model->where('name', 'like', '%' . $search_data['goods_name'] . '%');
        }
        return $model->with('Organization')->with('create_account')->with('organization')->with('category')->where($where)->orderBy($orderby, $sort)->paginate($paginate);
    }


    //查询出模型，再删除模型 一定要查询到才能删除
    public static function select_delete($id)
    {
        $model = Self::find($id);
        return $model->forceDelete();
    }
}

?>