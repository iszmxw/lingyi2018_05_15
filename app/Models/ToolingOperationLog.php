<?php
/**
 * program_operation_log表的模型
 *
 */
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ToolingOperationLog extends Model{
    protected $table = 'tooling_operation_log';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $dateFormat = 'U';//设置保存的created_at updated_at为时间戳格式

    //关联程序管理工具账户表
    public function accounts(){
        return $this->belongsTo('App\Models\ToolingAccount', 'account_id');
    }

    //查询获取列表
    public static function getList($where,$limit,$orderby,$sort='DESC'){
        return self::with('accounts')->where($where)->where('is_delete','0')->limit($limit)->orderBy($orderby,$sort)->get();
    }
}
?>