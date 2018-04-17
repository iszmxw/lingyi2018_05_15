<?php
/**
 * module_node表的模型
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes;
    protected $table = 'organization';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $dateFormat = 'U';//设置保存的created_at updated_at为时间戳格式

    //和Account表多对一的关系
    public function account()
    {
        return $this->hasOne('App\Models\Account', 'organization_id');
    }

    //和OrganizationProxyinfo表一对一的关系
    public function organizationAgentinfo()
    {
        return $this->hasOne('App\Models\OrganizationAgentinfo', 'agent_id');
    }

    //和assetsOperation表一对多的关系
    public function fr_organization_id()
    {
        return $this->hasMany('App\Models\AssetsAllocation', 'fr_organization_id', 'id');
    }

    //和assetsOperation表一对多的关系
    public function to_organization_id()
    {
        return $this->hasMany('App\Models\AssetsAllocation', 'to_organization_id', 'id');
    }

    //和organizationBranchinfo表一对一的关系
    public function organizationbranchinfo()
    {
        return $this->hasOne('App\Models\OrganizationBranchinfo', 'organization_id');
    }

    //和OrganizationRetailinfo表一对一的关系
    public function OrganizationRetailinfo()
    {
        return $this->hasOne('App\Models\OrganizationRetailinfo', 'organization_id');
    }

    //和organizationBranchinfo表一对一的关系
    public function fansmanageinfo()
    {
        return $this->hasOne('App\Models\OrganizationFansmanageinfo', 'fansmanage_id');
    }


    //和wechat_authorization表一对一的关系
    public function wechatAuthorization()
    {
        return $this->hasOne('App\Models\WechatAuthorization', 'organization_id');
    }

    //和WarzoneAgent表一对一的关系
    public function warzoneAgent()
    {
        return $this->hasOne('App\Models\WarzoneAgent', 'agent_id');
    }

    //和WarzoneAgent表 warzone表 一对一的关系
    public function warzone()
    {
        return $this->belongsToMany('App\Models\Warzone', 'warzone_agent', 'agent_id', 'zone_id')->select('zone_name');
    }

    //和RetailGoods表一对多的关系
    public function RetailGoods()
    {
        return $this->hasMany('App\Models\RetailGoods', 'restaurant_id');
    }

    //和RetailCategory表一对多的关系
    public function RetailCategory()
    {
        return $this->hasMany('App\Models\RetailCategory', 'retail_id');
    }

    //和RetailSupplier表一对多的关系
    public function RetailSupplier()
    {
        return $this->hasMany('App\Models\RetailSupplier', 'retail_id');
    }

    //和Program表一对一的关系
    public function program()
    {
        return $this->belongsTo('App\Models\Program', 'program_id', 'id');
    }

    //获取单条数据
    public static function getOne($where)
    {
        return self::with('OrganizationRetailinfo')->where($where)->first();
    }

    //获取单条信息-服务商
    public static function getOneAgent($where)
    {
        return self::with('warzoneAgent')->with('organizationAgentinfo')->where($where)->first();
    }

    //获取单条信息-商户
    public static function getOneFansmanage($where)
    {
        return self::with((['account' => function ($query) {
            $query->where('deepth', '1');
        }]))->with('fansmanageinfo')->where($where)->first();
    }

    //获取单条信息-店铺
    public static function getOneStore($where)
    {
        return self::with('OrganizationRetailinfo')->where($where)->first();
    }

    //获取-服务商列表
    public static function getListAgent($where)
    {
        return self::with('organizationAgentinfo')->with('account')->where($where)->get();
    }

    //获取多条信息商户
    public static function getListFansmanage($where)
    {
        return self::with('fansmanageinfo')->where($where)->get();
    }

    //获取多条信息
    public static function getList($where)
    {
        return self::where($where)->get();
    }

    //获取多条信息
    public static function getOneData($where)
    {
        return self::where($where)->first();
    }

    //获取分页数据-店铺
    public static function getOrganizationAndAccount($organization_name, $where, $paginate, $orderby, $sort = 'DESC')
    {
        $model = self::with('account');
        if (!empty($organization_name)) {
            $model = $model->where('organization_name', 'like', '%' . $organization_name . '%');
        }
        return $model->where($where)->orderBy($orderby, $sort)->paginate($paginate);
    }

    //添加数据
    public static function addOrganization($param)
    {
        $organization = new Organization();//实例化程序模型

        $organization->organization_name = $param['organization_name'];//组织名称
        $organization->parent_id = $param['parent_id'];//多级组织的关系
        $organization->parent_tree = $param['parent_tree'];//上级程序
        $organization->program_id = $param['program_id'];//组织关系树
        $organization->asset_id = $param['asset_id'];//下级组织使用程序id（商户使用）
        $organization->type = $param['type'];//类型 2为服务商
        $organization->status = $param['status'];//状态 1-正常 0-冻结
        $organization->save();
        return $organization->id;
    }

    //修改数据
    public static function editOrganization($where, $param)
    {
        $model = self::where($where)->first();
        foreach ($param as $key => $val) {
            $model->$key = $val;
        }
        $model->save();
    }

    //查询数据是否存在（仅仅查询ID增加数据查询速度）
    public static function checkRowExists($where)
    {
        $row = self::getPluck($where, 'id');
        if (empty($row)) {
            return false;
        } else {
            return true;
        }
    }

    //获取单行数据的其中一列
    public static function getPluck($where, $pluck)
    {
        return self::where($where)->value($pluck);
    }

    //获取分页数据-服务商
    public static function getPaginage($where, $paginate, $orderby, $sort = 'DESC')
    {
        return self::with('warzoneAgent')->with('organizationAgentinfo')->where($where)->orderBy($orderby, $sort)->paginate($paginate);
    }

    //获取分页数据-商户
    public static function getPaginageFansmanage($where, $paginate, $orderby, $sort = 'DESC')
    {
        return self::with(['account' => function ($query) {
            $query->where('deepth', '1');
        }])->with('fansmanageinfo')->where($where)->orderBy($orderby, $sort)->paginate($paginate);
    }

    //获取分页数据-商户
    public static function getPaginageFansmanage1($where,$mobile='', $paginate, $orderby, $sort = 'DESC')
    {
        return self::with(['account' => function ($query) {
            $query->where('deepth', '1');
        }])->with(['fansmanageinfo' => function ($query) use ($mobile) {
                $query->where('fansmanage_owner_mobile', $mobile);
            }])->where($where)->orderBy($orderby, $sort)->paginate($paginate);
    }


    //获取分页数据-商户
    public static function getPaginageStore($where, $paginate, $orderby, $sort = 'DESC')
    {
        return self::with(['program'])->where($where)->orderBy($orderby, $sort)->paginate($paginate);
    }

    //获取分页数据-分店
    public static function getstore($where, $paginate, $orderby, $sort = 'DESC')
    {
        return self::with('OrganizationRetailinfo')->with('account')->where($where)->orderBy($orderby, $sort)->paginate($paginate);
    }
}

?>