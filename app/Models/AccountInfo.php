<?php
/**
 * account_info表的模型
 * 存储后台管理系统的账号信息
 *
 */
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class AccountInfo extends Model
{
    use SoftDeletes;
    protected $table = 'account_info';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $dateFormat = 'U';//设置保存的created_at updated_at为时间戳格式

    //和Account表是一对一的关系
    public function account(){
        return $this->belongsTo('App\Models\Account', 'account_id');
    }

    //和RetailOrder表是一对一的关系
    public function RetailOrder(){
        return $this->belongsTo('App\Models\RetailOrder','account_id','operator_id');
    }

    //和RetailPurchaseOrder表是一对一的关系
    public function RetailPurchaseOrder(){
        return $this->belongsTo('App\Models\RetailPurchaseOrder', 'id','operator_id');
    }

    //和RetailLossOrder表是一对一的关系
    public function RetailLossOrder(){
        return $this->belongsTo('App\Models\RetailLossOrder', 'id','operator_id');
    }

    //修改账号
    public static function editAccountInfo($where,$param){
        $model = self::where($where)->first();
        foreach($param as $key=>$val){
            $model->$key=$val;
        }
        $model->save();
    }

    //添加用户个人信息
    public static function addAccountInfo($param){
        $model = new AccountInfo();
        $model->account_id = $param['account_id'];//组织id
        $model->realname = $param['realname'];//用户名
        if(!empty($param['idcard'])) {
            $model->idcard = $param['idcard'];//身份证号
        }
        $model->save();
        return $model->id;
    }

    //查询数据是否存在（仅仅查询ID增加数据查询速度）
    public static function checkRowExists($where){
        $row = self::getPluck($where,'id');
        if(empty($row)){
            return false;
        }else{
            return true;
        }
    }
    //获取单行数据的其中一列
    public static function getPluck($where,$pluck){
        return self::where($where)->value($pluck);
    }
    //获取单条数据
    public static function getOne($where){
        return self::where($where)->first();
    }
}
?>