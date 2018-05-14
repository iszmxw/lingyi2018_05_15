<?php
/**
 * Province表的模型
 *
 */
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Province extends Model{
    use SoftDeletes;
    protected $table = 'province';
    protected $primaryKey = 'id';
    public $timestamps = true;
//    public $dateFormat = 'U';//设置保存的created_at updated_at为时间戳格式

    //和战区节点关联，多对多
    public function warzone()
    {
        return $this->belongsToMany('App\Models\Warzone','warzone_province','province_id','zone_id');
    }

    //获取战区分页列表
    public static function getList($where,$limit=0,$orderby,$sort='DESC'){
        $model = self::with('warzone');
        if(!empty($limit)){
            $model = $model->limit($limit);
        }
        return $model->where($where)->orderBy($orderby,$sort)->get();
    }

    //获取省份列表
    public static function provinceList($where){
        return  $model = self::where($where)->get();
    }

    //获取战区
    public static function getPluck($where,$pluck){
        $model = self::with('warzone');
        return $model->where($where)->pluck($pluck);
    }

    //获取战区
    public static function getOne($where){
        $model = self::with('warzone');
        return $model->where($where)->get();
    }
}
?>