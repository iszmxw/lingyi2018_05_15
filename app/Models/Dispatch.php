<?php
/**
 * dispatch表的模型
 *
 */
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Dispatch extends Model{
    use SoftDeletes;
    protected $table = 'dispatch';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $dateFormat = 'U';//设置保存的created_at updated_at为时间戳格式

    //添加运费模板名称
    public static function addDispatch($param){
        $model = new Dispatch();
        $model->fansmanage_id = $param['fansmanage_id'];
        $model->store_id = $param['store_id'];
        $model->name = $param['name'];
        $model->number = $param['number'];
        $model->displayorder = $param['displayorder'];
        $model->save();
        return $model->id;
    }

    //获取单条信息
    public static function getOne($where){
        return self::where($where)->first();
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

    //获取列表
    public static function getList($where,$limit=0,$orderby,$sort='DESC',$select=[]){
        $model = new Dispatch();
        if(!empty($limit)){
            $model = $model->limit($limit);
        }
        if(!empty($select)){
            $model = $model->select($select);
        }
        return $model->where($where)->orderBy($orderby,$sort)->get();
    }

    //修改数据
    public static function editDispatch($where,$param){
        if($model = self::where($where)->first()){
            foreach($param as $key=>$val){
                $model->$key=$val;
            }
            $model->save();
        }
    }

    //获取单行数据的其中一列
    public static function getPluck($where,$pluck){
        return self::where($where)->pluck($pluck);
    }

    //获取分页列表
    public static function getPaginage($where,$paginate,$orderby,$sort='DESC'){
        return self::where($where)->orderBy($orderby,$sort)->paginate($paginate);
    }

    //查询出模型，再删除模型 一定要查询到才能删除
    public static function select_delete($id){
        $model = self::find($id);
        return $model->forceDelete();
    }
}
?>