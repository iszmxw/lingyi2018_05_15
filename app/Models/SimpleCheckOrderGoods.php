<?php
/**
 * simple_check_order_goods表的模型
 *
 */
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class SimpleCheckOrderGoods extends Model{
    use SoftDeletes;
    protected $table = 'simple_check_order_goods';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $dateFormat = 'U';//设置保存的created_at updated_at为时间戳格式


    //和SimpleCheckOrder表多对一的关系
    public function SimpleCheckOrder(){
        return $this->belongsTo('App\Models\SimpleCheckOrder', 'order_id','id');
    }

    public static function getOne($where)
    {
        $model = self::with('SimpleCheckOrder');
        return $model->where($where)->get();
    }

    //获取列表
    public static function getList($where,$limit=0,$orderby,$sort='DESC'){
        $model = new SimpleCheckOrderGoods();
        if(!empty($limit)){
            $model = $model->limit($limit);
        }
        return $model->with('SimpleCheckOrder')->where($where)->orderBy($orderby,$sort)->get();
    }

    //保存创建订单的商品快照数据
    public static function addOrderGoods($param){
        $model = new SimpleCheckOrderGoods();
        $model->order_id = $param['order_id'];
        $model->goods_id = $param['goods_id'];
        $model->title = $param['title'];
        $model->thumb = $param['thumb'];
        $model->details = $param['details'];
        $model->total = $param['total'];
        $model->price = $param['price'];
        $model->save();
        return $model->id;
    }

    //修改数据
    public static function editOrder($where,$param){
        if($model = self::where($where)->first()){
            foreach($param as $key=>$val){
                $model->$key=$val;
            }
            $model->save();
        }
    }

    //获取分页列表
    public static function getPaginage($where,$paginate,$orderby,$sort='DESC'){
        return self::with('SimpleCheckOrder')->where($where)->orderBy($orderby,$sort)->paginate($paginate);
    }
}
?>