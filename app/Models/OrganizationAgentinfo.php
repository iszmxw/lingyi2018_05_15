<?php
/**
 * module_node表的模型
 *
 */
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationAgentinfo extends Model{
    use SoftDeletes;
    protected $table = 'organization_agentinfo';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $dateFormat = 'U';//设置保存的created_at updated_at为时间戳格式

    //和organization表一对一的关系
    public function Organization(){
        return $this->belongsto('App\Models\Organization', 'agent_id');//by tang,hasone-->belongsto
    }

    //添加数据
    public static function addOrganizationAgentinfo($param){
        $program = new OrganizationAgentinfo();//实例化程序模型
        $program->agent_id = $param['agent_id'];//组织id
        $program->agent_owner = $param['agent_owner'];//分公司负责人姓名
        $program->agent_owner_idcard = $param['agent_owner_idcard'];//分公司负责人身份证
        $program->agent_owner_mobile = $param['agent_owner_mobile'];//分公司负责人手机号
        $program->save();
        return $program->id;
    }

    //修改数据
    public static function editOrganizationAgentinfo($where,$param){
        $model = self::where($where)->first();
        foreach($param as $key=>$val){
            $model->$key=$val;
        }
        $model->save();
    }
}
?>