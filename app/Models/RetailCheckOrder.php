<?php
/**
 * retail_check_order表的模型
 *
 */
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class RetailCheckOrder extends Model{
    use SoftDeletes;
    protected $table = 'retail_check_order';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $dateFormat = 'U';//设置保存的created_at updated_at为时间戳格式

    //和User表多对一的关系
    public function User(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    //和RetailOrderGoods表一对多的关系
    public function RetailCheckOrderGoods(){
        return $this->hasMany('App\Models\RetailCheckOrderGoods','order_id','id');
    }

    //和个人信息表一对一的关系
    public function account_info(){
        return $this->hasOne('App\Models\AccountInfo', 'id','operator_id');
    }

    //创建订单
    public static function addOrder($param){
        $model = new RetailCheckOrder();
        $model->ordersn = $param['ordersn'];
        $model->order_price = $param['order_price'];
        $model->remarks = $param['remarks'];
        $model->operator_id = $param['operator_id'];
        $model->type = $param['type'];
        $model->status = '0';
        $model->fansmanage_id = $param['fansmanage_id'];
        $model->retail_id = $param['retail_id'];
        $model->save();
        return $model->id;
    }

    //修改数据
    public static function editOrder($where,$param)
    {
        if ($model = self::where($where)->first()) {
            foreach ($param as $key => $val) {
                $model->$key = $val;
            }
            $model->save();
        }
    }


    public static function getOne($where)
    {
        $model = self::with('RetailCheckOrderGoods');
        return $model->where($where)->get();
    }

    //获取列表
    public static function getList($where,$limit=0,$orderby,$sort='DESC'){
        $model = new RetailCheckOrder();
        if(!empty($limit)){
            $model = $model->limit($limit);
        }
        return $model->where($where)->orderBy($orderby,$sort)->get();
    }

    //获取分页列表
    public static function getPaginage($where,$search_data,$paginate,$orderby,$sort='DESC'){
        $model = self::with('account_info');
        if(!empty($search_data['user_id'])){
            $model = $model->where([['user_id',$search_data['user_id']]]);
        }
        if(!empty($search_data['ordersn'])){
            $model = $model->where([['ordersn',$search_data['ordersn']]]);
        }
        if(!empty($search_data['paytype'])){
            $model = $model->where([['paytype',$search_data['paytype']]]);
        }
        if(!empty($search_data['status'])){
            $model = $model->where([['status',$search_data['status']]]);
        }
        return $model->with('RetailCheckOrderGoods')->where($where)->orderBy($orderby,$sort)->paginate($paginate);
    }
}
?>