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
    protected $guarded = [];

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

    //和OrganizationSimpleinfo表一对一的关系
    public function OrganizationSimpleinfo()
    {
        return $this->hasOne('App\Models\OrganizationSimpleinfo', 'organization_id');
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
        return $this->hasMany('App\Models\RetailGoods', 'retail_id');
    }

    //和SimpleGoods表一对多的关系
    public function SimpleGoods()
    {
        return $this->hasMany('App\Models\SimpleGoods', 'simple_id');
    }

    //和RetailCategory表一对多的关系
    public function RetailCategory()
    {
        return $this->hasMany('App\Models\RetailCategory', 'retail_id');
    }

    //和SimpleCategory表一对多的关系
    public function SimpleCategory()
    {
        return $this->hasMany('App\Models\SimpleCategory', 'simple_id');
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
        return self::with('fansmanageinfo')->with('OrganizationRetailinfo')->with('OrganizationSimpleinfo')->where($where)->first();
    }

    //获取单条信息-分公司
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

    //获取-分公司列表
    public static function getListAgent($where)
    {
        return self::with('organizationAgentinfo')->with('account')->where($where)->get();
    }

    //获取多条信息商户
    public static function getListFansmanage($where)
    {
        return self::with('fansmanageinfo')->where($where)->get();
    }

    //获取多条信息零售简版
    public static function getListSimple($where)
    {
        return self::with('OrganizationSimpleinfo')->where($where)->get();
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
        $organization->type = $param['type'];//类型 2为分公司
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

    //获取分页数据-分公司
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
    public static function getPaginageStore($where, $paginate, $orderby, $sort = 'DESC')
    {
        return self::with(['program'])->where($where)->orderBy($orderby, $sort)->paginate($paginate);
    }

    /**
     * 获取跟组织有关的程序列出来
     * 通过 组织 id 获取 program_id 然后 通过 program_id 找到 他的名称 并且  complete_id = program_id 并且 is_asset = 1 的值给拿出来
     * @param $organization_id
     * @return bool
     */
    public static function getProgramAsset($organization_id)
    {
        $res_organization = Organization::select(["organization.id", "organization.program_id"])
            ->where(["organization.id" => $organization_id])
            ->first();
        if (!empty($res_organization)) {
            $res_organization = $res_organization->toArray();

            $res = Program::select(["id", "program_name"])->where(["complete_id" => $res_organization["program_id"], "is_asset" => 1])->orWhere(["id" => $res_organization["program_id"]])->get();
            if (!empty($res)) {
                return $res->toArray();
            }
        }
        return false;
    }

    //获取分页数据-分店
    public static function getstore($where, $paginate, $orderby, $sort = 'DESC')
    {
        return self::with('OrganizationRetailinfo')->with('account')->where($where)->orderBy($orderby, $sort)->paginate($paginate);
    }


    /**
     * 添加数据
     * @param $param
     * @param $type
     * @param $where
     * @return bool
     */
    public static function insertData($param, $type = "update_create", $where = [])
    {
        switch ($type) {
            case "update_create":
                $res = self::updateOrCreate($where, $param);
                break;
            case "first_create":
                $res = self::firstOrCreate($param);
                break;
        }

        if (!empty($res)) {
            return $res->toArray();
        } else {
            return false;
        }
    }


    public function getSimpleOrder()
    {
        return $this->hasMany('App\Models\SimpleOrder', 'simple_id', "id");
    }


    public function getRetailOrder()
    {
        return $this->hasMany('App\Models\RetailOrder', 'retail_id', "id");
    }

    /**
     * 获取营收情况数据
     * @param $organization_id
     * @return array|bool|\Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getRevenueInfo($organization_id)
    {
        // 找到该组织id 下面的 店铺
        $res = self::select(["asset_id", "parent_tree"])->where(["id" => $organization_id])->first();
        if (!empty($res)) {
            $res = $res->toArray();
            // 通过结构树找到所有的店铺信息
            $parent_tree = "{$res["parent_tree"]}{$organization_id}";
            // 如果为10 就是零售的 订单信息，12 就是 简版餐饮 的订单信息
            if ($res["asset_id"] == 10) {
                $model = self::with(["getRetailOrder" => function ($query) {
                    $query->select(["order_price", "retail_id", "id", "created_at"])->where(["status" => 1]);
                }]);
            } else {
                $model = self::with(["getSimpleOrder" => function ($query) {
                    $query->select(["order_price", "simple_id", "id", "created_at"])->where(["status" => 1]);
                }]);
            }
            // 获取数据
            $res_shop = $model->select(["id", "organization_name"])->where("parent_tree", "like", "%$parent_tree%")->where(["type" => 4])->get();

            // 今天开始的时间戳
            $today_start = strtotime(date("Y-m-d") . " 00:00:00");
            // 今天结束的时间戳
            $today_end = strtotime("+1 day", $today_start);

            if (!empty($res_shop)) {
                $res_shop = $res_shop->toArray();
                foreach ($res_shop as $key => $val) {
                    // 今天的营收金额情况
                    $today_revenue_order_money = 0;
                    // 今天的营收订单情况
                    $today_revenue_order_num = 0;
                    // 今天的营收金额情况
                    $before_revenue_order_money = 0;
                    // 历史的营收订单情况
                    $before_revenue_order_num = 0;
                    if ($res["asset_id"] == 10) {
                        $order_info = $val["get_retail_order"];
                    }elseif (($res["asset_id"] == 12)){
                        $order_info = $val["get_simple_order"];

                    }
                    foreach ($order_info as $k => $v) {
                        if ($v["created_at"] > $today_start && $v["created_at"] <= $today_end) {
                            // 累加金额
                            $today_revenue_order_money += $v["order_price"];
                            // 累加订单
                            $today_revenue_order_num += 1;
                        } else {
                            $before_revenue_order_money += $v["order_price"];
                            $before_revenue_order_num += 1;
                        }
                    }
                    // 处理数据
                    $res_shop[$key]["today_order_money"] = $today_revenue_order_money;
                    $res_shop[$key]["today_order_num"] = $today_revenue_order_num;
                    $res_shop[$key]["before_order_money"] = $before_revenue_order_money;
                    $res_shop[$key]["before_order_num"] = $before_revenue_order_num;
                    $res_shop[$key]["all_order_money"] = $today_revenue_order_money + $before_revenue_order_money;
                }
            }
            // 返回数据
            return $res_shop;
        }
        return false;
    }


    public static function getShopSimpleInfo($organization_id)
    {
        $res = self::select(["asset_id", "parent_tree"])->where(["id" => $organization_id])->first();


        if (!empty($res)) {
            $res = $res->toArray();
            // 通过结构树找到所有的店铺信息
            $parent_tree = "{$res["parent_tree"]}{$organization_id}";
            // 获取数据
            $res_shop = self::select(["id", "organization_name"])->where("parent_tree", "like", "%$parent_tree%")->where(["type" => 4])->get();

            if (!empty($res_shop)) {
                $res_shop = $res_shop->toArray();
                $res_shop["shop_num"] = count($res_shop);
                $res_shop["order_money"] = 0;
                $res_shop["fans_num"] = 0;

                foreach ($res_shop as $val) {
                    $res_shop["fans_num"] += FansmanageUser::where(["id" => $val["id"]])->count("id");
                    if ($res["asset_id"] == 10) {
                        $res_shop["order_money"] += RetailOrder::where(["status" => 1, "retail_id" => $val["id"]])->sum("order_price");
                    } else {
                        $res_shop["order_money"] += SimpleOrder::where(["status" => 1, "simple_id" => $val["id"]])->sum("order_price");
                    }
                }
                return $res_shop;
            }
        }
        return false;
    }
}

