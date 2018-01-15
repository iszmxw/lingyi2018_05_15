<?php
/**
 * program_admin表的模型
 *
 */
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
class Account extends Model{
    use SoftDeletes;
    protected $table = 'account';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $dateFormat = 'U';//设置保存的created_at updated_at为时间戳格式

    //和账号多对多的关系
    public function account_roles()
    {
        return $this->belongsToMany('App\Models\OrganizationRole','role_account','account_id','role_id');
    }


    //修改账号
    public static function editAccount($where,$param){
        $model = self::where($where)->first();
        foreach($param as $key=>$val){
            $model->$key=$val;
        }
        $model->save();
    }

    //修改密码
    public static function editAccount_password($where,$param){
            $model = self::where($where)->first();
            $model->password = $param->password;
            $model->save();
    }

    //和个人信息表一对一的关系
    public function account_info(){
        return $this->hasOne('App\Models\AccountInfo', 'account_id');
    }

    //和账号节点表一对一的关系
    public function account_node(){
        return $this->hasOne('App\Models\AccountNode', 'account_id');
    }

    //和organization表多对一的关系
    public function organization(){
        return $this->belongsTo('App\Models\Organization', 'organization_id');
    }
    //和权限角色表创建者一对多的关系
    public function roles(){
        return $this->hasMany('App\Models\OrganizationRole', 'created_by');
    }
    //简易型查询单条数据关联查询
    public static function getOne($where)
    {
        return self::with('organization')->with('account_info')->with('account_roles')->where($where)->first();
    }

    //查询获取列表
    public static function getList($where,$limit=0,$orderby,$sort='DESC'){
        $model = self::with('organization')->with('account_info')->with('account_roles');
        if(!empty($limit)){
            $model = $model->limit($limit);
        }
        return $model->where($where)->orderBy($orderby,$sort)->get();
    }

    //查询获取账户的模块和节点列表
    public static function get_module_node($where,$limit=0,$orderby,$sort='DESC'){
        $model = self::with('account_node')->with('account_info');
        if(!empty($limit)){
            $model = $model->limit($limit);
        }
        return $model->where($where)->orderBy($orderby,$sort)->get();
    }


    //登陆时通过输入的用户名或手机号查询用户
    public static function getOneForLogin($username){
        return self::with('account_info')->with('account_roles')->where('account',$username)->orWhere('mobile',$username)->first();
    }
    //添加用户
    public static function addAccount($param){
        $model = new Account();
        $model->organization_id = $param['organization_id'];//组织ID
        $model->parent_id = $param['parent_id'];//上级用户ID
        $model->parent_tree = $param['parent_tree'];//组织树
        $model->deepth = $param['deepth'];//用户在该组织里的深度
        $model->account = $param['account'];//登陆账号（零壹平台,自动生成）
        $model->password = $param['password'];//登陆密码（MD5默认32位长度）
        $model->mobile = $param['mobile'];//管理员绑定的手机号码
        $model->save();
        return $model->id;
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
    //获取单行数据的其中一列
    public static function getPluck($where,$pluck){
        return self::where($where)->pluck($pluck);
    }
    //获取分页数据
    public static function getPaginage($where,$paginate,$orderby,$sort='DESC'){
        return self::with('account_roles')->with('account_info')->where($where)->orderBy($orderby,$sort)->paginate($paginate);
    }
}
?>