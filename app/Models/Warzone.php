<?php
/**
 * program表的模型
 *
 */
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Warzone extends Model{
    use SoftDeletes;
    protected $table = 'warzone';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $dateFormat = 'U';//设置保存的created_at updated_at为时间戳格式

    //获取所有列表
    public static function get_all(){
        return self::all();
    }

    //获取列表
    public static function getList($where,$limit=0,$orderby,$sort='DESC'){
        $model = new Warzone();
        if(!empty($limit)){
            $model = $model->limit($limit);
        }
        return $model->where($where)->orderBy($orderby,$sort)->get();
    }

    //添加数据
    public static function addProgram($param){
        $program = new Program();//实例化程序模型
        $program->program_name = $param['program_name'];//程序名称
        $program->program_url = $param['program_url'];//程序名称
        $program->complete_id = $param['complete_id'];//上级程序
        $program->is_asset = $param['is_asset'];//是否资产程序
        $program->save();
        return $program->id;
    }
    //修改数据
    public static function editProgram($where,$param){
        if($model = self::where($where)->first()){
            foreach($param as $key=>$val){
                $model->$key=$val;
            }
            $model->save();
        }
    }
    //获取总数
    public static function getCount($where=[]){
        return self::where($where)->count();
    }

    //查询数据是否存在（仅仅查询ID增加数据查询速度）
    public static function checkRowExists($where){
        $row = self::getPluck($where,'id')->toArray();
        if(empty($row)){
            return false;
        }else{
            return true;
        }
    }

    //获取程序分页列表
    public static function getPaginage($where,$paginate,$orderby,$sort='DESC'){
        return self::where($where)->orderBy($orderby,$sort)->paginate($paginate);
    }

    //获取单行数据的其中一列
    public static function getPluck($where,$pluck){
        return self::where($where)->pluck($pluck);
    }
}
?>