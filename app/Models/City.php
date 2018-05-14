<?php
/**
 * program_admin表的模型
 *
 */
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class City extends Model{
    use SoftDeletes;
    protected $table = 'city';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $dateFormat = 'U';//设置保存的created_at updated_at为时间戳格式

    public function area()
    {
        return $this->hasMany('App\Models\Area','city_id','id');
    }

    //查询获取列表
    public static function getList($where){
        return $model = self::with('area')->where($where)->get()->toArray();
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
    //获取分页数据
    public static function getPaginage($where,$paginate,$orderby,$sort='DESC'){
        return self::with('account_roles')->with('account_info')->where($where)->orderBy($orderby,$sort)->paginate($paginate);
    }
}
?>