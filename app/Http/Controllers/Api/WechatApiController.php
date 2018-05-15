<?php
/**
 * Wechat接口
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\DispatchProvince;
use App\Models\Province;
use App\Models\SimpleAddress;
use App\Models\Dispatch;
use App\Models\Organization;
use App\Models\SimpleOnlineAddress;
use App\Models\SimpleOnlineGoods;
use App\Models\SimpleOnlineOrder;
use App\Models\SimpleSelftake;
use App\Models\SimpleCategory;
use App\Models\SimpleConfig;
use App\Models\SimpleGoods;
use App\Models\SimpleGoodsThumb;
use App\Models\SimpleSelftakeGoods;
use App\Models\SimpleSelftakeOrder;
use App\Models\SimpleSelftakeUser;
use App\Models\SimpleStock;
use App\Models\SimpleStockLog;
use App\Services\ZeroneRedis\ZeroneRedis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Session;

class WechatApiController extends Controller

{
    /**
     * 店铺列表
     */
    public function store_list(Request $request)
    {
        // 商户id
        $fansmannage_id = $request->organization_id;

        // 纬度
        $lat = $request->lat;
        // 经度
        $lng = $request->lng;
        $url = "http://api.map.baidu.com/geocoder/v2/?ak=Xv2dLyXPQEWxRVZ3GVGWE9SkkfhS4WBW&location={$lat},{$lng}&output=json&pois=1";

        $return = \HttpCurl::doGet($url);

        $return = json_decode($return);
//        foreach($return as $key=>$value){
//            print_r($value['0stdClass Object']);
//        }exit;
//        print_r($return['result']);exit;
//        echo $return['result']['addressComponent']['province'];exit;
        // 精度维度转换（wgs80转gcj02）
        $re = $this->wgs84togcj02($lng, $lat);

        // 查询条件
        $where[] = ['parent_id', $fansmannage_id];
        // 前端页面搜索
        $keyword = $request->keyword;

        // 是否存在搜索条件
        if ($keyword) {
            $where[] = ['organization_name', 'LIKE', "%{$keyword}%"];
        }
        // 查询店铺信息
        $Orgdata = Organization::getListSimple($where)->toArray();
        // 是否存在店铺
        if (empty($Orgdata)) {
            return response()->json(['msg' => '查无店铺', 'status' => '0', 'data' => '']);
        }
        foreach ($Orgdata as $key => $value) {
            if ($value['organization_simpleinfo']) {
                // 计算距离
                $Orgdata[$key]['distance'] = $this->GetDistance($re['1'], $re['0'], $value['organization_simpleinfo']['lat'], $value['organization_simpleinfo']['lng']);
            } else {
                $Orgdata[$key]['distance'] = '9999';
            }
        }
        // 冒泡距离排序
        $Orgdata = $this->order($Orgdata);
        foreach ($Orgdata as $k => $v) {
            $storelist[$k]['id'] = $v['id'];
            $storelist[$k]['name'] = $v['organization_name'];
            $storelist[$k]['distance'] = $v['distance'];
            $storelist[$k]['logo'] = $v['organization_simpleinfo']['simple_logo'];
            $storelist[$k]['address'] = $v['organization_simpleinfo']['simple_address'];
        }
        // 数据返回
        $data = ['status' => '1', 'msg' => '数据获取成功', 'data' => ['storelist' => $storelist]];

        return response()->json($data);
    }

    //organization_id=2&lat=22.724083&lng=114.260654

    /**
     * 分类接口列表
     */
    public function category(Request $request)
    {
        // 联盟主id
        $fansmanage_id = $request->fansmanage_id;
        // 店铺id
        $store_id = $request->store_id;
        // 分类列表
        $category = SimpleCategory::getList([['fansmanage_id', $fansmanage_id], ['simple_id', $store_id]], 0, 'id', 'DESC', ['id', 'name', 'displayorder']);

        // 数据返回
        $data = ['status' => '1', 'msg' => '数据获取成功', 'data' => ['categorylist' => $category]];

        return response()->json($data);
    }

    /**
     * 商品列表接口
     */
    public function goods_list(Request $request)
    {
        // 联盟主id
        $fansmanage_id = $request->fansmanage_id;
        // 店铺id
        $store_id = $request->store_id;
        // 关键字
        $keyword = $request->keyword;
        // 条码
        $scan_code = $request->scan_code;
        // 分类id
        $category_id = $request->category_id;
        // 分页
        $limit = $request->limit;
        // 条件
        $where = [['fansmanage_id', $fansmanage_id], ['simple_id', $store_id], ['status', '1']];

        if ($keyword) {
            $where[] = ['name', 'LIKE', '%' . $keyword . '%'];
        }
        if ($scan_code) {
            $where[] = ['barcode', $scan_code];
        }
        if ($category_id) {
            $where[] = ['category_id', $category_id];
        }
        $goodslist = SimpleGoods::getListApi($where, $limit, 'displayorder', 'asc', ['id', 'name', 'category_id', 'details', 'price', 'stock']);
        if (empty($goodslist->toArray())) {
            return response()->json(['status' => '0', 'msg' => '没有商品', 'data' => '']);
        }
        foreach ($goodslist as $key => $value) {
            $goodslist[$key]['price'] = round($value['price'], 2);
            $goodslist[$key]['category_name'] = SimpleCategory::getPluck([['id', $value['category_id']]], 'name');
            $goodslist[$key]['thumb'] = SimpleGoodsThumb::where([['goods_id', $value['id']]])->select('thumb')->get();
            if (count($goodslist[$key]['thumb']) == 0) {
                $goodslist[$key]['thumb'] = [['thumb' => 'public/thumb.png']];
            }
        }
        $data = ['status' => '1', 'msg' => '获取商品成功', 'data' => ['goodslist' => $goodslist]];
        return response()->json($data);
    }

    /**
     * 购物车添加商品
     */
    public function shopping_cart_add(Request $request)
    {
        // 用户店铺id
        $user_id = $request->user_id;
        // 用户零壹id
        $zerone_user_id = $request->zerone_user_id;
        // 联盟主id
        $fansmanage_id = $request->fansmanage_id;
        // 店铺id
        $store_id = $request->store_id;
        // 商品id
        $goods_id = $request->goods_id;
        // 商品名称
        $goods_name = $request->goods_name;
        // 商品价格
        $goods_price = $request->goods_price;
        // 商品图片
        $goods_thumb = $request->goods_thumb;
        // 商品数量
        $num = $request->num;
        // 商品库存
        $stock = $request->stock;

        // 查询该店铺是否可以零库存开单
        $config = SimpleConfig::getPluck([['simple_id', $store_id], ['cfg_name', 'allow_zero_stock']], 'cfg_value');
        // 缓存键值
        $key_id = 'simple' . $user_id . $zerone_user_id . $fansmanage_id . $store_id;
        // 查看缓存是否存有商品
        $cart_data = Redis::get($key_id);
        // 如果有商品
        if ($cart_data) {
            // 序列化转成数组
            $cart_data = unserialize($cart_data);
            $total = 0;
            $goods_repeat = [];
            foreach ($cart_data as $key => $value) {
                // 查询缓存中的商品是否存在添加的商品
                if ($value['goods_id'] == $goods_id) {
                    // 添加商品数量
                    $num += $value['num'];
                    // 如果值为1 表示不能
                    if ($config != '1') {
                        // 库存不足
                        if ($stock - $num < 0) {
                            return response()->json(['status' => '0', 'msg' => '商品' . $goods_name . '库存不足', 'data' => '']);
                        }
                    }
                    // 添加商品数量
                    $cart_data[$key]['num'] = $num;
                    $stock -= $num;
                    // 缓存的库存
                    $cart_data[$key]['stock'] = $stock;
                }
                //储存商品id
                $goods_repeat[] = $value['goods_id'];
                // 购物车总数量
                $total += $cart_data[$key]['num'];

            }

            // 查询缓存中是否有该商品
            $re = in_array($goods_id, $goods_repeat);
            // 如果没有该商品
            if (empty($re)) {
                // 如果值为1 表示不能
                if ($config != '1') {
                    // 库存不足
                    if ($stock - $num < 0) {
                        return response()->json(['status' => '0', 'msg' => '商品' . $goods_name . '库存不足', 'data' => '']);
                    }
                }
                // 库存
                $stock -= $num;
                // 数据处理
                $cart_data[] = [
                    'store_id' => $store_id,
                    'goods_id' => $goods_id,
                    'goods_name' => $goods_name,
                    'goods_price' => $goods_price,
                    'goods_thumb' => $goods_thumb,
                    'num' => $num,
                    'stock' => $stock,
                ];
                // 购物车总数量
                $total += $num;
            }
            // 更新缓存
            ZeroneRedis::create_shopping_cart($key_id, $cart_data);
        } else {
            // 如果值为1 表示不能
            if ($config != '1') {
                // 库存不足
                if ($stock - $num < 0) {
                    return response()->json(['status' => '0', 'msg' => '商品' . $goods_name . '库存不足', 'data' => '']);
                }
            }
            // 库存
            $stock -= $num;
            // 数据处理
            $cart_data[] = [
                'store_id' => $store_id,
                'goods_id' => $goods_id,
                'goods_name' => $goods_name,
                'goods_price' => $goods_price,
                'goods_thumb' => $goods_thumb,
                'stock' => $stock,
                'num' => $num,
            ];
            // 新增缓存
            ZeroneRedis::create_shopping_cart($key_id, $cart_data);
            // 购物车商品总数
            $total = $num;
        }
        // 数据处理
        $goods_data = [
            // 商品ID
            'goods_id' => $goods_id,
            //商品名称
            'goods_name' => $goods_name,
            // 商品图片
            'goods_thumb' => $goods_thumb,
            // 商品单价
            'goods_price' => $goods_price,
            // 购物车中商品的数量
            'num' => $num,
            // 减去购物车种商品数量后的库存
            'stock' => $stock,
            // 购物车商品总数
            'total' => $total
        ];
        $data = ['status' => '1', 'msg' => '添加成功', 'data' => $goods_data];

        return response()->json($data);
    }

    /**
     * 购物车减商品
     */
    public function shopping_cart_reduce(Request $request)
    {

        // 用户店铺id
        $user_id = $request->user_id;
        // 用户零壹id
        $zerone_user_id = $request->zerone_user_id;
        // 联盟主id
        $fansmanage_id = $request->fansmanage_id;
        // 店铺id
        $store_id = $request->store_id;
        // 商品id
        $goods_id = $request->goods_id;
        // 商品名称
        $goods_name = $request->goods_name;
        // 商品价格
        $goods_price = $request->goods_price;
        // 商品图片
        $goods_thumb = $request->goods_thumb;
        // 商品数量
        $num = $request->num;
        // 商品库存
        $stock = $request->stock;
        // 缓存键值
        $key_id = 'simple' . $user_id . $zerone_user_id . $fansmanage_id . $store_id;
        // 查看缓存是否存有商品
        $cart_data = Redis::get($key_id);
        // 如果有商品
        if (empty($cart_data)) {
            return response()->json(['status' => '0', 'msg' => '购物车没商品，无法操作', 'data' => '']);
        } else {
            // 序列化转成数组
            $cart_data = unserialize($cart_data);
            $total = 0;
            $goods_repeat = [];
            foreach ($cart_data as $key => $value) {
                // 查询缓存中的商品是否存在减少的商品
                if ($value['goods_id'] == $goods_id) {
                    // 减少商品数量
                    $cart_data[$key]['num'] = $value['num'] - $num;
                    // 库存
                    $stock -= $cart_data[$key]['num'];
                    // 库存
                    $cart_data[$key]['stock'] = $stock;
                    // 如果数量为0
                    if ($cart_data[$key]['num'] == '0') {
                        // 删除缓存中的商品
                        unset($cart_data[$key]);
                        // 防止跳出循环，查不到商品
                        $goods_repeat[] = $value['goods_id'];
                        // 购物车中商品的数量
                        $num = $value['num'] - $num;
                        // 跳出这次循环
                        continue;
                        // 如果商品减少为负数
                    } elseif ($cart_data[$key]['num'] < 0) {
                        return response()->json(['status' => '0', 'msg' => '购物车商品数量不足，无法减少', 'data' => '']);
                    }
                    // 购物车中商品的数量
                    $num = $value['num'] - $num;

                }
                //储存商品id
                $goods_repeat[] = $value['goods_id'];
                // 购物车总数量
                $total += $cart_data[$key]['num'];
            }
            // 查询缓存中是否有该商品
            $re = in_array($goods_id, $goods_repeat);
            // 如果没有该商品
            if (empty($re)) {
                return response()->json(['status' => '0', 'msg' => '购物车没商品，无法操作', 'data' => '']);
            }
            // 更新缓存
            ZeroneRedis::create_shopping_cart($key_id, $cart_data);
        }
        // 数据处理
        $goods_data = [
            // 商品ID
            'goods_id' => $goods_id,
            //商品名称
            'goods_name' => $goods_name,
            // 商品图片
            'goods_thumb' => $goods_thumb,
            // 商品单价
            'goods_price' => $goods_price,
            // 购物车中商品的数量
            'num' => $num,
            // 减去购物车种商品数量后的库存
            'stock' => $stock,
            // 购物车商品总数
            'total' => $total
        ];
        $data = ['status' => '1', 'msg' => '减少商品成功', 'data' => $goods_data];

        return response()->json($data);
    }

    /**
     * 查询购物车
     */
    public function shopping_cart_list(Request $request)
    {
        // 用户店铺id
        $user_id = $request->user_id;
        // 用户零壹id
        $zerone_user_id = $request->zerone_user_id;
        // 联盟主id
        $fansmanage_id = $request->fansmanage_id;
        // 店铺id
        $store_id = $request->store_id;
        // 缓存键值
        $key_id = 'simple' . $user_id . $zerone_user_id . $fansmanage_id . $store_id;
        // 查看缓存是否存有商品
        $cart_data = Redis::get($key_id);
        // 如果有商品
        if (empty(unserialize($cart_data))) {
            return response()->json(['status' => '0', 'msg' => '购物车没有商品', 'data' => '']);
        } else {
            // 序列化转成数组
            $cart_data = unserialize($cart_data);
            $total = 0;
            $goods_list = [];
            foreach ($cart_data as $key => $value) {
                // 数据处理
                $goods_list[$key] = [
                    // 商品ID
                    'goods_id' => $value['goods_id'],
                    //商品名称
                    'goods_name' => $value['goods_name'],
                    // 商品图片
                    'goods_thumb' => $value['goods_thumb'],
                    // 商品单价
                    'goods_price' => $value['goods_price'],
                    // 购物车中商品的数量
                    'num' => $value['num'],
                    // 商品库存
                    'stock' => $value['stock'],
                ];
                // 购物车总数量
                $total += $value['num'];
            }
        }
        $goods_list = array_values($goods_list);

        $data = ['status' => '1', 'msg' => '查询成功', 'data' => ['goods_list' => $goods_list, 'total' => $total]];

        return response()->json($data);
    }

    /**
     * 清空购物车
     */
    public function shopping_cart_empty(Request $request)
    {
        // 用户店铺id
        $user_id = $request->user_id;
        // 用户零壹id
        $zerone_user_id = $request->zerone_user_id;
        // 联盟主id
        $fansmanage_id = $request->fansmanage_id;
        // 店铺id
        $store_id = $request->store_id;
        // 缓存键值
        $key_id = 'simple' . $user_id . $zerone_user_id . $fansmanage_id . $store_id;
        // 定义空数组
        $cart_data = [];
        // 清空购物车
        ZeroneRedis::create_shopping_cart($key_id, $cart_data);

        $data = ['status' => '1', 'msg' => '清空成功', 'data' => ['user_id' => $user_id]];

        return response()->json($data);

    }

    /**
     * 查询用户默认收货地址信息
     */
    public function address(Request $request)
    {
        // 用户零壹id
        $zerone_user_id = $request->zerone_user_id;
        // 查询默认收货地址
        $address = SimpleAddress::getone([['zerone_user_id', $zerone_user_id], ['status', '1']]);
        if (empty($address)) {
            return response()->json(['status' => '0', 'msg' => '没有收货地址', 'data' => '']);
        }
        // 数据处理
        $address_info = [
            // ID
            'id' => $address['id'],
            // 省份
            'province_name' => $address['province_name'],
            // 区县
            'city_name' => $address['city_name'],
            // 城市
            'area_name' => $address['area_name'],
            // 详细地址
            'address' => $address['address'],
            // 收货人姓名
            'realname' => $address['realname'],
            // 手机号码
            'mobile' => $address['mobile'],
        ];
        $data = ['status' => '1', 'msg' => '查询成功', 'data' => ['address_info' => $address_info]];
        return response()->json($data);
    }


    /**
     * 查询店铺运费
     */
    public function dispatch_mould(Request $request)
    {
        // 联盟主id
        $fansmanage_id = $request->fansmanage_id;
        // 店铺id
        $store_id = $request->store_id;
        // 重量
        $weight = $request->weight;
        // 地址id
        $address_id = $request->address_id;
        // 查询默认收货地址
        $address = SimpleAddress::getone([['id', $address_id]]);
        // 运费模板
        $dispatch = Dispatch::getList([['fansmanage_id', $fansmanage_id], ['store_id', $store_id], ['status', '1']], '', 'id');
        $freight = 0;
        if ($dispatch->toArray()) {
            foreach ($dispatch->toArray() as $key => $value) {
                $dispatch_info = DispatchProvince::getOne([['dispatch_id', $value['id']], ['province_id', 'LIKE', "%{$address['province_id']}%"]]);
                if ($dispatch_info) {
                    if ($weight < $dispatch_info['first_weight']) {
                        $freight = $dispatch_info['freight'];
                    } else {
                        // 续重
                        $additional_weight = $weight - $dispatch_info['first_weight'];
                        // 续重费用
                        $freight = $dispatch_info['freight'] + ceil($additional_weight * $dispatch_info['renewal'] / 1000);
                    }
                    break;
                }
            }
        }
        if ($freight == 0) {
            return response()->json(['status' => '0', 'msg' => '没有设置该地区物流', 'data' => '']);
        }
        $data = ['status' => '1', 'msg' => '查询成功', 'data' => ['freight' => $freight]];

        return response()->json($data);
    }


    /**
     * 查询用户默认取货信息
     */
    public function selftake(Request $request)
    {
        // 用户零壹id
        $zerone_user_id = $request->zerone_user_id;
        // 查询默认取货信息
        $selftake = SimpleSelftake::getone([['zerone_user_id', $zerone_user_id], ['status', '1']]);
        if (empty($selftake)) {
            return response()->json(['status' => '0', 'msg' => '没有取货信息', 'data' => '']);
        }
        // 数据处理
        $selftakeinfo = [
            // id
            'id' => $selftake['id'],
            // 性别
            'sex' => $selftake['sex'],
            // 手机号
            'mobile' => $selftake['mobile'],
            // 真实姓名
            'realname' => $selftake['realname'],
        ];

        $data = ['status' => '1', 'msg' => '查询成功', 'data' => ['selftake_info' => $selftakeinfo]];

        return response()->json($data);
    }


    /**
     * 查询收货地址详情
     */
    public function address_info(Request $request)
    {
        // 地址id
        $address_id = $request->address_id;
        // 查询默认收货地址
        $address = SimpleAddress::getone([['id', $address_id]]);
        if (empty($address)) {
            return response()->json(['status' => '0', 'msg' => '没有收货地址', 'data' => '']);
        }
        // 数据处理
        $address_info = [
            // ID
            'id' => $address['id'],
            // 省份
            'province_name' => $address['province_name'],
            // 区县
            'city_name' => $address['city_name'],
            // 地区
            'area_name' => $address['area_name'],
            // 详细地址
            'address' => $address['address'],
            // 收货人姓名
            'realname' => $address['realname'],
            // 手机号码
            'mobile' => $address['mobile'],
        ];
        $data = ['status' => '1', 'msg' => '查询成功', 'data' => ['address_info' => $address_info]];

        return response()->json($data);
    }


    /**
     * 查询用户取货信息
     */
    public function selftake_info(Request $request)
    {
        // 用户零壹id
        $zerone_user_id = $request->zerone_user_id;
        // 取货信息id
        $selftake_id = $request->self_take_id;
        // 查询默认取货信息
        $selftake = SimpleSelftake::getone([['zerone_user_id', $zerone_user_id], ['id', $selftake_id]]);
        if (empty($selftake)) {
            return response()->json(['status' => '0', 'msg' => '没有取货信息', 'data' => '']);
        }
        // 数据处理
        $selftakeinfo = [
            // id
            'id' => $selftake['id'],
            // 性别
            'sex' => $selftake['sex'],
            // 手机号
            'mobile' => $selftake['mobile'],
            // 真实姓名
            'realname' => $selftake['realname'],
            // 默认值
            'status' => $selftake['status'],
        ];

        $data = ['status' => '1', 'msg' => '查询成功', 'data' => ['selftake_info' => $selftakeinfo]];

        return response()->json($data);
    }

    /**
     * 添加收货地址
     */
    public function address_add(Request $request)
    {
        // 用户零壹id
        $zerone_user_id = $request->zerone_user_id;
        // 省份 城市 地区
        $address_info = $request->address_info;
        // 转为数组
        $address_info = explode(" ", $address_info);
        // 获取省份id 和 名字
        $province = Province::provinceOne([['province_name', $address_info['0']]]);
        // 获取城市id 和 名字
        $city = City::getOne([['city_name', $address_info['1']]]);
        if (count($address_info) == 3) {
            $area = Area::getOne([['area_name', $address_info['2']]]);
        } else {
            $area = [
                'id' => '',
                'area_name' => ''
            ];
        }
        // 详细地址
        $address = $request->address;
        // 收货人真实姓名
        $realname = $request->realname;
        // 手机号码
        $mobile = $request->mobile;
        // 默认收货地址 1为默认
        $status = $request->status;
        // 1为男，2为女
        $sex = $request->sex;
        // 如果没传值，查询是否设置有地址，没有的话为默认地址
        if (empty($status)) {
            $status = SimpleAddress::checkRowExists([['zerone_user_id', $zerone_user_id]]) ? '0' : '1';
        }
        DB::beginTransaction();
        try {
            if ($status && !empty(SimpleAddress::checkRowExists([['zerone_user_id', $zerone_user_id]]))) {
                SimpleAddress::editAddress([['zerone_user_id', $zerone_user_id]], ['status' => '0']);
            }
            // 数据处理
            $addressData = [
                'zerone_user_id' => $zerone_user_id,
                'province_id' => $province['id'],
                'province_name' => $province['province_name'],
                'city_id' => $city['id'],
                'city_name' => $city['city_name'],
                'area_id' => $area['id'],
                'area_name' => $area['area_name'],
                'address' => $address,
                'realname' => $realname,
                'mobile' => $mobile,
                'status' => $status,
                'sex' => $sex
            ];
            $address_id = SimpleAddress::addAddress($addressData);
            // 提交事务
            DB::commit();
        } catch (Exception $e) {
            // 事件回滚
            DB::rollBack();
            return response()->json(['status' => '0', 'msg' => '添加失败', 'data' => '']);
        }
        $data = ['status' => '1', 'msg' => '添加成功', 'data' => ['address_id' => $address_id, 'return' => 'address']];
        return response()->json($data);
    }

    /**
     * 查询用户地址信息列表
     */
    public function address_list(Request $request)
    {
        // 用户零壹id
        $zerone_user_id = $request->zerone_user_id;
        // 查询收货地址列表
        $address = SimpleAddress::getList([['zerone_user_id', $zerone_user_id]]);
        if (empty($address->toArray())) {
            return response()->json(['status' => '0', 'msg' => '没有收货地址', 'data' => '']);
        }
        $address_list = [];
        foreach ($address as $key => $value) {
            $address_list[$key] = [
                "address_id" => $value['id'],
                "province_id" => $value['province_id'],
                "province_name" => $value['province_name'],
                "city_id" => $value['city_id'],
                "city_name" => $value['city_name'],
                "area_id" => $value['area_id'],
                "area_name" => $value['area_name'],
                "address" => $value['address'],
                "realname" => $value['realname'],
                "mobile" => $value['mobile'],
                "status" => $value['status'],
            ];
        }

        $data = ['status' => '1', 'msg' => '查询成功', 'data' => ['address_list' => $address_list]];

        return response()->json($data);
    }

    /**
     * 修改用户收货地址信息
     */
    public function address_edit(Request $request)
    {
        // 地址id
        $address_id = $request->address_id;
        // 查询是否存在
        if (empty(SimpleAddress::checkRowExists([['id', $address_id]]))) {
            return response()->json(['status' => '0', 'msg' => '查无数据', 'data' => '']);
        };


        // 省份 城市 地区
        $address_info = $request->address_info;
        // 转为数组
        $address_info = explode(" ", $address_info);
        // 获取省份id 和 名字
        $province = Province::provinceOne([['province_name', $address_info['0']]]);
        // 获取城市id 和 名字
        $city = City::getOne([['city_name', $address_info['1']]]);
        if (count($address_info) == 3) {
            $area = Area::getOne([['area_name', $address_info['2']]]);
        } else {
            $area = [
                'id' => '',
                'area_name' => ''
            ];
        }
        // 详细地址
        $address = $request->address;
        // 收货人真实姓名
        $realname = $request->realname;
        // 手机号码
        $mobile = $request->mobile;
        // 默认收货地址 1为默认
        $status = $request->status ? '1' : '0';
        // 如果没传值，查询是否设置有地址，没有的话为默认地址
        // 1为男，2为女
        $sex = $request->sex;
        // 数据处理
        $editData = [
            'province_id' => $province['id'],
            'province_name' => $province['province_name'],
            'city_id' => $city['id'],
            'city_name' => $city['city_name'],
            'area_id' => $area['id'],
            'area_name' => $area['area_name'],
            'address' => $address,
            'realname' => $realname,
            'mobile' => $mobile,
            'status' => $status,
            'sex' => $sex
        ];

        SimpleAddress::editAddress([['id', $address_id]], $editData);

        $data = ['status' => '1', 'msg' => '编辑成功', 'data' => ['address_id' => $address_id, 'return' => 'address']];

        return response()->json($data);
    }

    /**
     * 删除用户收货地址信息
     */
    public function address_delete(Request $request)
    {
        // 地址id
        $address_id = $request->address_id;
        // 查询是否存在
        if (empty(SimpleAddress::checkRowExists([['id', $address_id]]))) {
            return response()->json(['status' => '0', 'msg' => '查无数据', 'data' => '']);
        };

        // 删除数据
        SimpleAddress::deleteAddress([['id', $address_id]]);

        $data = ['status' => '1', 'msg' => '删除成功', 'data' => ['address_id' => $address_id]];

        return response()->json($data);
    }

    /**
     * 设置为默认收货地址
     */
    public function address_status(Request $request)
    {
        // 地址id
        $address_id = $request->address_id;
        // 零壹id
        $zerone_user_id = $request->zerone_user_id;
        // 查询是否存在
        if (empty(SimpleAddress::checkRowExists([['id', $address_id]]))) {
            return response()->json(['status' => '0', 'msg' => '查无数据', 'data' => '']);
        };

        DB::beginTransaction();
        try {
            $id = SimpleAddress::getPluck([['zerone_user_id', $zerone_user_id], ['status', '1']], 'id');
            if ($id) {
                SimpleAddress::editAddress([['id', $id]], ['status' => '0']);
            }
            SimpleAddress::editAddress([['id', $address_id]], ['status' => '1']);
            // 提交事务
            DB::commit();
        } catch (Exception $e) {
            // 事件回滚
            DB::rollBack();
            return response()->json(['status' => '0', 'msg' => '修改失败', 'data' => '']);
        }

        $data = ['status' => '1', 'msg' => '修改成功', 'data' => ['address_id' => $address_id]];

        return response()->json($data);
    }


    /**
     * 添加用户取货信息
     */
    public function selftake_add(Request $request)
    {
        // 用户零壹id
        $zerone_user_id = $request->zerone_user_id;
        // 真实姓名
        $realname = $request->realname;
        // 性别
        $sex = $request->sex;
        // 手机号
        $mobile = $request->mobile;
        // 默认取货信息 1为默认
        $status = $request->status;
        // 如果没传值，查询是否设置有地址，没有的话为默认地址
        if (empty($status)) {
            $status = SimpleSelftake::checkRowExists([['zerone_user_id', $zerone_user_id]]) ? '0' : '1';
        }
        DB::beginTransaction();
        try {
            if ($status && !empty(SimpleSelftake::checkRowExists([['zerone_user_id', $zerone_user_id]]))) {
                SimpleSelftake::editaa([['zerone_user_id', $zerone_user_id]], ['status' => '0']);
            }
            // 数据处理
            $selftakeData = [
                'zerone_user_id' => $zerone_user_id,
                'realname' => $realname,
                'sex' => $sex,
                'mobile' => $mobile,
                'status' => $status,
            ];
            $selftake_id = SimpleSelftake::addSelftake($selftakeData);
            // 提交事务
            DB::commit();
        } catch (Exception $e) {
            // 事件回滚
            DB::rollBack();
            return response()->json(['status' => '0', 'msg' => '添加失败', 'data' => '']);
        }
        $data = ['status' => '1', 'msg' => '添加成功', 'data' => ['selftake_id' => $selftake_id, 'return' => 'selftake']];
        return response()->json($data);
    }

    /**
     * 用户取货信息列表
     */
    public function selftake_list(Request $request)
    {
        // 用户零壹id
        $zerone_user_id = $request->zerone_user_id;
        // 查询用户取货信息列表
        $self_take_info = SimpleSelftake::getList([['zerone_user_id', $zerone_user_id]]);

        $data = ['status' => '1', 'msg' => '查询成功', 'data' => ['self_take_info' => $self_take_info]];

        return response()->json($data);
    }

    /**
     * 用户取货信息编辑
     */
    public function selftake_edit(Request $request)
    {
        // 自取表id
        $self_take_id = $request->self_take_id;
        // 真实姓名
        $realname = $request->realname;
        // 性别
        $sex = $request->sex;
        // 手机号
        $mobile = $request->mobile;
        // 默认取货地址 1为默认
        $status = $request->status;
        // 零壹id
        $zerone_user_id = $request->zerone_user_id;

        if (empty(SimpleSelftake::checkRowExists([['id', $self_take_id]]))) {
            return response()->json(['status' => '0', 'msg' => '查无数据', 'data' => '']);
        };
        if ($status) {
            SimpleSelftake::editaa([['zerone_user_id', $zerone_user_id]], ['status' => '0']);
            SimpleSelftake::editSelftake([['id', $self_take_id]], ['realname' => $realname, 'sex' => $sex, 'mobile' => $mobile, 'status' => $status]);
        } else {
            SimpleSelftake::editSelftake([['id', $self_take_id]], ['realname' => $realname, 'sex' => $sex, 'mobile' => $mobile]);

        }

        $data = ['status' => '1', 'msg' => '修改成功', 'data' => ['self_take_id' => $self_take_id, 'return' => 'selftake']];
        return response()->json($data);
    }

    /**
     * 删除用户取货信息
     */
    public function selftake_delete(Request $request)
    {
        // 自取表id
        $self_take_id = $request->self_take_id;
        // 用户零壹id
        $zerone_user_id = $request->zerone_user_id;

        if (empty(SimpleSelftake::checkRowExists([['id', $self_take_id]]))) {
            return response()->json(['status' => '0', 'msg' => '查无数据', 'data' => '']);
        };

        DB::beginTransaction();
        try {
            if (SimpleSelftake::getPluck([['id', $self_take_id]], 'status')) {
                $id = SimpleSelftake::getPluck([['zerone_user_id', $zerone_user_id]], 'id');
                if ($id) {
                    // 修改信息为默认地址
                    SimpleSelftake::editSelftake([['id', $id]], ['status' => '1']);
                }
            }
            // 删除用户取货信息
            SimpleSelftake::where([['id', $self_take_id]])->forceDelete();

            // 提交事务
            DB::commit();
        } catch (Exception $e) {
            // 事件回滚
            DB::rollBack();
            return response()->json(['status' => '0', 'msg' => '修改失败', 'data' => '']);
        }
        $data = ['status' => '1', 'msg' => '删除成功', 'data' => ['self_take_id' => $self_take_id]];
        return response()->json($data);
    }

    /**
     * 设置为默认收货地址
     */
    public function selftake_status(Request $request)
    {
        // 取货id
        $self_take_id = $request->self_take_id;
        // 零壹id
        $zerone_user_id = $request->zerone_user_id;
        // 查询是否存在
        if (empty(SimpleSelftake::checkRowExists([['id', $self_take_id]]))) {
            return response()->json(['status' => '0', 'msg' => '查无数据', 'data' => '']);
        };

        DB::beginTransaction();
        try {
            $id = SimpleSelftake::getPluck([['zerone_user_id', $zerone_user_id], ['status', '1']], 'id');
            if ($id) {
                SimpleSelftake::editSelftake([['id', $id]], ['status' => '0']);
            }
            SimpleSelftake::editSelftake([['id', $self_take_id]], ['status' => '1']);
            // 提交事务
            DB::commit();
        } catch (Exception $e) {
            // 事件回滚
            DB::rollBack();
            return response()->json(['status' => '0', 'msg' => '修改失败', 'data' => '']);
        }

        $data = ['status' => '1', 'msg' => '修改成功', 'data' => ['self_take_id' => $self_take_id]];

        return response()->json($data);
    }

    /**
     * 订单提交
     */
    public function order_submit(Request $request)
    {
        // 用户id
        $user_id = $request->user_id;
        // 零壹id
        $zerone_user_id = $request->zerone_user_id;
        // 联盟id
        $fansmanage_id = $request->fansmanage_id;
        // 店铺id
        $store_id = $request->store_id;
        // 配送方式（1-快递配送，2-到店自提）
        $shipping_type = $request->shipping_type;
        // 库存扣减方式（1-下单减库存，2-付款减库存）
        $stock_type = $request->stock_type;
        // 备注
        $remarks = $request->remarks;
        // 收货信息
        if($request->address_info){
            $address_info = json_decode($request->address_info, TRUE);
        }
        // 取货信息
        if($request->self_take_info){
            $self_take_info = json_decode($request->self_take_info, TRUE);
        }
        // 商品信息
        $goods_list = json_decode($request->goods_list, TRUE);
        $order_price = 0;
        foreach ($goods_list as $key => $value) {
            // 查询商品是否下架
            $goods_status = SimpleGoods::getPluck(['id' => $value['goods_id']], 'status');
            if ($goods_status == '0') {
                return response()->json(['msg' => '对不起就在刚刚部分商品被下架了，请返回首页重新选购！', 'status' => '0', 'data' => '']);
            }
            $order_price += $value['goods_price'] * $value['num'];
        }

        DB::beginTransaction();
        try {
            if ($shipping_type == 1) {
                // 查询订单今天的数量
                $num = SimpleOnlineOrder::where([['fansmanage_id', $fansmanage_id], ['simple_id', $store_id], ['ordersn', 'LIKE', '%' . date("Ymd", time()) . '%']])->count();
                $sort = 100001 + $num;
                // 订单号
                $ordersn = 'online' . date("Ymd", time()) . '_' . $store_id . '_' . $sort;
                // 数据处理
                $orderData = [
                    'ordersn' => $ordersn,
                    'order_price' => $order_price,
                    'remarks' => $remarks,
                    'fansmanage_id' => $fansmanage_id,
                    'simple_id' => $store_id,
                    'user_id' => $user_id,
                    'status' => '0',
                ];
                // 添加入订单表
                $order_id = SimpleOnlineOrder::addSimpleOnlineOrder($orderData);

                foreach ($goods_list as $key => $value) {
                    $details = SimpleGoods::getPluck([['id', $value['goods_id']]], 'details');
                    $goodsdata = [
                        'order_id' => $order_id,
                        'goods_id' => $value['goods_id'],
                        'title' => $value['goods_name'],
                        'thumb' => $value['goods_thumb'],
                        'details' => $details,
                        'total' => $value['num'],
                        'price' => $value['goods_price'],
                    ];
                    SimpleOnlineGoods::addSimpleOnlineGoods($goodsdata);//添加商品快照
                }

                $address_data = [
                    'order_id' => $order_id,
                    'province_name' => $address_info['province_name'],
                    'city_name' => $address_info['city_name'],
                    'district_name' => $address_info['district_name'],
                    'address' => $address_info['address'],
                    'realname' => $address_info['realname'],
                    'mobile' => $address_info['mobile'],
                ];
                SimpleOnlineAddress::addSimpleOnlineAddress($address_data);

                // 说明下单减库存
                if ($stock_type == '1') {
                    // 减库存
                    $re = $this->reduce_stock($order_id, '1', 'online');
                    if ($re != 'ok') {
                        return $re;
                    }
                }

                /******运费模板（待完成）**********/
            } else {
                // 查询订单今天的数量
                $num = SimpleSelftakeOrder::where([['fansmanage_id', $fansmanage_id], ['simple_id', $store_id], ['ordersn', 'LIKE', '%' . date("Ymd", time()) . '%']])->count();
                $sort = 100001 + $num;
                // 订单号
                $ordersn = 'selftake' . date("Ymd", time()) . '_' . $store_id . '_' . $sort;
                // 提取码
//                $rand = rand(100000, 999999);
                // 数据处理
                $orderData = [
                    'ordersn' => $ordersn,
                    'order_price' => $order_price,
                    'remarks' => $remarks,
                    'fansmanage_id' => $fansmanage_id,
                    'simple_id' => $store_id,
                    'user_id' => $user_id,
                    'selftake_mobile' => $self_take_info['mobile'],
//                    'selftake_code' => $rand,
                    'status' => '0',
                ];
                // 添加入订单表
                $order_id = SimpleSelftakeOrder::addSimpleSelftakeOrder($orderData);

                foreach ($goods_list as $key => $value) {
                    $details = SimpleGoods::getPluck([['id', $value['goods_id']]], 'details');
                    $goodsdata = [
                        'order_id' => $order_id,
                        'goods_id' => $value['goods_id'],
                        'title' => $value['goods_name'],
                        'thumb' => $value['goods_thumb'],
                        'details' => $details,
                        'total' => $value['num'],
                        'price' => $value['goods_price'],
                    ];
                    SimpleSelftakeGoods::addSimpleSelftakeGoods($goodsdata);//添加商品快照
                }

                $selftake_data = [
                    'order_id' => $order_id,
                    'sex' => $self_take_info['sex'],
                    'realname' => $self_take_info['realname'],
                    'mobile' => $self_take_info['mobile'],
//                    'code' => $rand,
                ];
                SimpleSelftakeUser::addSimpleSelftakeUser($selftake_data);

                // 说明下单减库存
                if ($stock_type == '1') {
                    // 减库存
                    $re = $this->reduce_stock($order_id, '1', 'selftake');
                    if ($re != 'ok') {
                        return $re;
                    }
                }
            }
            // 提交事务
            DB::commit();
        } catch (\Exception $e) {
            // 事件回滚
            DB::rollBack();
            return response()->json(['msg' => '提交订单失败', 'status' => '0', 'data' => '']);
        }
        return response()->json(['status' => '1', 'msg' => '提交订单成功', 'data' => ['order_id' => $order_id]]);
    }


    /**
     * 线上订单列表接口
     */
    public function online_order_list(Request $request)
    {
        // 联盟id
        $fansmanage_id = $request->fansmanage_id;
        // 店铺id
        $store_id = $request->store_id;
        // 订单状态
        $status = $request->status;
        // 用户联盟id
        $user_id = $request->user_id;
        // 页数
        $page = $request->page;

        $where = [['simple_id', $store_id], ['fansmanage_id', $fansmanage_id], ['user_id', $user_id]];
        if ($status) {
            if ($status != '-1') {
                $status = preg_match('/(^[0-9]*$)/', $status, $a) ? $a[1] : 0;
                $status = (string)$status;
            }
            $where[] = ['status', $status];
        }
        $order_list = SimpleOnlineOrder::getListApi($where, $page, 'id', 'DESC', ['id', 'ordersn', 'order_price', 'status', 'created_at']);
        if (empty($order_list->toArray())) {
            return response()->json(['status' => '0', 'msg' => '没有订单', 'data' => '']);
        }
        return response()->json(['status' => '1', 'msg' => '订单列表查询成功', 'data' => ['order_list' => $order_list]]);
    }

    /**
     * 自取订单列表接口
     */
    public function selftake_order_list(Request $request)
    {

        // 联盟id
        $fansmanage_id = $request->fansmanage_id;
        // 店铺id
        $store_id = $request->store_id;
        // 订单状态
        $status = $request->status;
        // 用户联盟id
        $user_id = $request->user_id;
        // 页数
        $page = $request->page;

        $where = [['simple_id', $store_id], ['fansmanage_id', $fansmanage_id], ['user_id', $user_id]];
        if ($status) {
            if ($status != '-1') {
                $status = preg_match('/(^[0-9]*$)/', $status, $a) ? $a[1] : 0;
                $status = (string)$status;
            }
            $where[] = ['status', $status];
        }
        $order_list = SimpleSelftakeOrder::getListApi($where, $page, 'id', 'DESC', ['id', 'ordersn', 'order_price', 'status', 'created_at']);
        if (empty($order_list->toArray())) {
            return response()->json(['status' => '0', 'msg' => '没有订单', 'data' => '']);
        }
        return response()->json(['status' => '1', 'msg' => '订单列表查询成功', 'data' => ['order_list' => $order_list]]);
    }


    /**
     * 线上订单详情接口
     */
    public function online_order_detail(Request $request)
    {
        // 订单id
        $order_id = $request->order_id;
        // 订单详情
        $order = SimpleOnlineOrder::getOneJoin([['id', $order_id]]);
        if (empty($order)) {
            return response()->json(['status' => '0', 'msg' => '不存在订单', 'data' => '']);
        }
        $order = $order->toArray();
        $goods_list = [];
        foreach ($order['goods'] as $key => $value) {
            // 商品id
            $goods_list[$key]['goods_id'] = $value['goods_id'];
            // 商品名字
            $goods_list[$key]['goods_name'] = $value['title'];
            // 商品图片
            $goods_list[$key]['goods_thumb'] = $value['thumb'];
            // 商品数量
            $goods_list[$key]['num'] = $value['total'];
            // 商品价格
            $goods_list[$key]['goods_price'] = $value['price'];
        }
        $address_info = [
            'province_name' => $order['address']['province_name'],
            'city_name' => $order['address']['city_name'],
            'district_name' => $order['address']['district_name'],
            'address' => $order['address']['address'],
            'realname' => $order['address']['realname'],
            'mobile' => $order['address']['mobile'],
        ];
        $data = [
            // 订单id
            'order_id' => $order['id'],
            // 订单编号
            'ordersn' => $order['ordersn'],
            // 订单价格
            'order_price' => $order['order_price'],
            // 订单备注
            'remarks' => $order['remarks'],
            // 订单状态
            'status' => $order['status'],
            // 订单商品
            'goods_list' => $goods_list,
            // 收货地址
            'address_info' => $address_info,
            /*******运费金额(待完成)*******/
            'dispatch_price' => '运费金额(待完成)',
            /*******退款原因(待完成)*******/
            'rejected_info' => '退款原因（待完成）',
            // 添加时间
            'created_at' => $order['created_at'],
        ];
        return response()->json(['status' => '1', 'msg' => '订单详情查询成功', 'data' => $data]);
    }

    /**
     * 自取订单详情接口
     */
    public function selftake_order_detail(Request $request)
    {
        // 订单id
        $order_id = $request->order_id;
        // 订单详情
        $order = SimpleSelftakeOrder::getOneJoin([['id', $order_id]]);
        if (empty($order)) {
            return response()->json(['status' => '0', 'msg' => '不存在订单', 'data' => '']);
        }
        $order = $order->toArray();
        $goods_list = [];
        foreach ($order['goods'] as $key => $value) {
            // 商品id
            $goods_list[$key]['goods_id'] = $value['goods_id'];
            // 商品名字
            $goods_list[$key]['goods_name'] = $value['title'];
            // 商品图片
            $goods_list[$key]['goods_thumb'] = $value['thumb'];
            // 商品数量
            $goods_list[$key]['num'] = $value['total'];
            // 商品价格
            $goods_list[$key]['goods_price'] = $value['price'];
        }
        $selftake_info = [
            'realname' => $order['address']['realname'],
            'sex' => $order['address']['sex'],
            'mobile' => $order['address']['mobile'],
        ];
        $data = [
            // 订单id
            'order_id' => $order['id'],
            // 订单编号
            'ordersn' => $order['ordersn'],
            // 订单价格
            'order_price' => $order['order_price'],
            // 订单备注
            'remarks' => $order['remarks'],
            // 订单状态
            'status' => $order['status'],
            // 订单商品
            'goods_list' => $goods_list,
            // 收货地址
            'selftake_info' => $selftake_info,
            /*******取货时间(待完成)*******/
            'selftake_time' => '取货时间(待完成)',
            // 添加时间
            'created_at' => $order['created_at'],
        ];
        return response()->json(['status' => '1', 'msg' => '订单详情查询成功', 'data' => $data]);
    }


    /**
     * 取消订单接口
     */
    public function cancel_online_order(Request $request)
    {
        // 订单id
        $order_id = $request->order_id;
        // 订单详情
        $order = SimpleOnlineOrder::getOne([['id', $order_id]]);
        if ($order['status'] != '0') {
            return response()->json(['msg' => '订单状态不是待付款，不能取消', 'status' => '0', 'data' => '']);
        }
        DB::beginTransaction();
        try {
            // 说明该订单的库存还未退回，这里的判断是为了防止用户频繁切换下单减库存，付款减库存设置的检测
            if ($order['stock_status'] == '1') {
                // 归还库存
                $re = $this->reduce_stock($order_id, '-1', 'online');
                if ($re != 'ok') {
                    return response()->json(['msg' => '取消订单失败', 'status' => '0', 'data' => '']);
                }
            }
            // 修改订单状态为取消
            SimpleOnlineOrder::editSimpleOnlineOrder([['id', $order_id]], ['status' => '-1']);
            // 提交事务
            DB::commit();
        } catch (\Exception $e) {
            // 事件回滚
            DB::rollBack();
            return response()->json(['msg' => '取消订单失败', 'status' => '0', 'data' => '']);
        }
        return response()->json(['status' => '1', 'msg' => '取消订单成功', 'data' => ['order_id' => $order_id]]);
    }

    /**
     * 取消订单接口
     */
    public function cancel_selftake_order(Request $request)
    {
        // 订单id
        $order_id = $request->order_id;
        // 订单详情
        $order = SimpleSelftakeOrder::getOne([['id', $order_id]]);
        if ($order['status'] != '0') {
            return response()->json(['msg' => '订单状态不是待付款，不能取消', 'status' => '0', 'data' => '']);
        }
        DB::beginTransaction();
        try {
            // 说明该订单的库存还未退回，这里的判断是为了防止用户频繁切换下单减库存，付款减库存设置的检测
            if ($order['stock_status'] == '1') {
                // 归还库存
                $re = $this->reduce_stock($order_id, '-1', 'selftake');
                if ($re != 'ok') {
                    return response()->json(['msg' => '取消订单失败', 'status' => '0', 'data' => '']);
                }
            }
            // 修改订单状态为取消
            SimpleSelftakeOrder::editSimpleSelftakeOrder([['id', $order_id]], ['status' => '-1']);
            // 提交事务
            DB::commit();
        } catch (\Exception $e) {
            // 事件回滚
            DB::rollBack();
            return response()->json(['msg' => '取消订单失败', 'status' => '0', 'data' => '']);
        }
        return response()->json(['status' => '1', 'msg' => '取消订单成功', 'data' => ['order_id' => $order_id]]);
    }

    /**
     * 取消订单接口
     */
    public function select_address(Request $request)
    {
        $list = Province::provinceList()->toArray();
        $address_info = [];
        foreach ($list as $key => $value) {
            $re = $this->city($value['id']);

            $address_info[$key] = [
                'name' => $value['province_name'],
                'sub' => $re['data'],
                'type' => $re['type']
            ];
        }
        return response()->json(['status' => '1', 'msg' => '查询成功', 'data' => ['address_info' => $address_info]]);
    }


    private function city($province_id)
    {
        $city = City::getList([['province_id', $province_id]]);
        $data = [];
        $type = '0';
        foreach ($city as $key => $value) {
            $re = $this->area($value['id']);
            if ($re) {
                $data[$key] = [
                    'name' => $value['city_name'],
                    'sub' => $re,
                    'type' => '0'
                ];
                $type = '1';
            } else {
                $data[$key] = [
                    'name' => $value['city_name'],
                ];
            }
        }
        $return = [
            'data' => $data,
            'type' => $type
        ];
        return $return;
    }

    private function area($city_id)
    {
        $area = Area::getList([['city_id', $city_id]]);
        $data = [];
        foreach ($area as $key => $value) {
            $data[$key] = [
                'name' => $value['area_name']
            ];
        }
        return $data;
    }

    /**
     * WGS84转GCj02(北斗转高德)
     * @param lng
     * @param lat
     * @returns {*[]}
     */
    private function wgs84togcj02($lng, $lat)
    {
        $PI = 3.1415926535897932384626;
        $a = 6378245.0;
        $ee = 0.00669342162296594323;
        if ($this->out_of_china($lng, $lat)) {
            return array($lng, $lat);
        } else {
            $dlat = $this->transformlat($lng - 105.0, $lat - 35.0);
            $dlng = $this->transformlng($lng - 105.0, $lat - 35.0);
            $radlat = $lat / 180.0 * $PI;
            $magic = sin($radlat);
            $magic = 1 - $PI * $magic * $magic;
            $sqrtmagic = sqrt($magic);
            $dlat = ($dlat * 180.0) / (($a * (1 - $ee)) / ($magic * $sqrtmagic) * $PI);
            $dlng = ($dlng * 180.0) / ($a / $sqrtmagic * cos($radlat) * $PI);
            $mglat = $lat + $dlat;
            $mglng = $lng + $dlng;
            return array($mglng, $mglat);
        }
    }

    private function transformlat($lng, $lat)
    {
        $PI = 3.1415926535897932384626;

        $ret = -100.0 + 2.0 * $lng + 3.0 * $lat + 0.2 * $lat * $lat + 0.1 * $lng * $lat + 0.2 * sqrt(abs($lng));
        $ret += (20.0 * sin(6.0 * $lng * $PI) + 20.0 * sin(2.0 * $lng * $PI)) * 2.0 / 3.0;
        $ret += (20.0 * sin($lat * $PI) + 40.0 * sin($lat / 3.0 * $PI)) * 2.0 / 3.0;
        $ret += (160.0 * sin($lat / 12.0 * $PI) + 320 * sin($lat * $PI / 30.0)) * 2.0 / 3.0;
        return $ret;
    }


    private function transformlng($lng, $lat)
    {
        $PI = 3.1415926535897932384626;

        $ret = 300.0 + $lng + 2.0 * $lat + 0.1 * $lng * $lng + 0.1 * $lng * $lat + 0.1 * sqrt(abs($lng));
        $ret += (20.0 * sin(6.0 * $lng * $PI) + 20.0 * sin(2.0 * $lng * $PI)) * 2.0 / 3.0;
        $ret += (20.0 * sin($lng * $PI) + 40.0 * sin($lng / 3.0 * $PI)) * 2.0 / 3.0;
        $ret += (150.0 * sin($lng / 12.0 * $PI) + 300.0 * sin($lng / 30.0 * $PI)) * 2.0 / 3.0;
        return $ret;
    }

    /**
     * 判断是否在国内，不在国内则不做偏移
     * @param $lng
     * @param $lat
     * @returns {boolean}
     */
    private function out_of_china($lng, $lat)
    {
        return ($lng < 72.004 || $lng > 137.8347) || (($lat < 0.8293 || $lat > 55.8271) || false);
    }


    /**
     *  计算两组经纬度坐标 之间的距离
     *   params ：lat1 纬度1； lng1 经度1； lat2 纬度2； lng2 经度2； len_type （1:m or 2:km);
     *   return m or km
     */
    private function GetDistance($lat1, $lng1, $lat2, $lng2, $len_type = 2, $decimal = 2)
    {
        $PI = 3.1415926;
        $radLat1 = $lat1 * $PI / 180.0;   //PI圆周率
        $radLat2 = $lat2 * $PI / 180.0;
        $a = $radLat1 - $radLat2;
        $b = ($lng1 * $PI / 180.0) - ($lng2 * $PI / 180.0);
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $s = $s * 6378.137;
        $s = round($s * 1000);
        if ($len_type-- > 1) {
            $s /= 1000;
        }
        return round($s, $decimal);
    }


    private function order($arr)
    {
        $len = count($arr);//6
        for ($k = 0; $k <= $len; $k++) {
            for ($j = $len - 1; $j > $k; $j--) {
                if ($arr[$j]['distance'] < $arr[$j - 1]['distance']) {
                    $temp = $arr[$j];
                    $arr[$j] = $arr[$j - 1];
                    $arr[$j - 1] = $temp;
                }
            }
        }
        return $arr;
    }


    /**
     * 减库存
     * @order_id 订单id
     * @status 1表示减库存，-1表示加库存
     */
    private function reduce_stock($order_id, $status, $type)
    {
        // 订单详情
        if ($type == 'online') {
            $data = SimpleOnlineOrder::getOne([['id', $order_id]]);
        } else {
            $data = SimpleSelftakeOrder::getOne([['id', $order_id]]);
        }
        // 查询是否可零库存开单
        $config = SimpleConfig::getPluck([['simple_id', $data['simple_id']], ['cfg_name', 'allow_zero_stock']], 'cfg_value');

        DB::beginTransaction();
        try {
            if ($type == 'online') {
                $goodsdata = SimpleOnlineGoods::where([['order_id', $order_id]])->get();
            } else {
                $goodsdata = SimpleSelftakeGoods::where([['order_id', $order_id]])->get();
            }
            if ($status == '1') {

                foreach ($goodsdata as $key => $value) {
                    // 商品详情
                    $goods = SimpleGoods::getOne([['id', $value['goods_id']]]);
                    // 如果不允许零库存开单
                    if ($config != '1') {
                        // 库存小于0 打回
                        if ($goods['stock'] - $value['total'] < 0) {
                            return response()->json(['msg' => '商品' . $goods['name'] . '库存不足', 'status' => '0', 'data' => '']);
                        }
                    }
                    $stock = $goods['stock'] - $value['total'];
                    SimpleGoods::editSimpleGoods([['id', $value['goods_id']]], ['stock' => $stock]);//修改商品库存
                    $stock_data = [
                        'fansmanage_id' => $data['fansmanage_id'],
                        'simple_id' => $data['simple_id'],
                        'goods_id' => $value['goods_id'],
                        'amount' => $value['total'],
                        'ordersn' => $data['ordersn'],
                        'operator_id' => $data['operator_id'],
                        'remark' => $data['remarks'],
                        // 销售出库类型
                        'type' => '6',
                        'status' => '1',
                    ];
                    // 商品操作记录
                    SimpleStockLog::addStockLog($stock_data);
                    $re = SimpleStock::getOneSimpleStock([['simple_id', $data['simple_id']], ['goods_id', $value['goods_id']]]);
                    $simple_stock = $re['stock'] - $value['total'];
                    SimpleStock::editStock([['id', $re['id']]], ['stock' => $simple_stock]);
                    // 修改stock_status为1表示该订单的库存状态已经减去
                    if ($type == 'online') {
                        SimpleOnlineOrder::editSimpleOnlineOrder(['id' => $order_id], ['stock_status' => '1']);
                    } else {
                        SimpleSelftakeOrder::editSimpleSelftakeOrder(['id' => $order_id], ['stock_status' => '1']);
                    }

                }
            } else {
                foreach ($goodsdata as $key => $value) {
                    // 商品剩下的库存
                    $stock = SimpleGoods::getPluck([['id', $value['goods_id']]], 'stock');
                    $stock = $stock + $value['total'];
                    // 修改商品库存
                    SimpleGoods::editSimpleGoods([['id', $value['goods_id']]], ['stock' => $stock]);
                    $stock_data = [
                        'fansmanage_id' => $data['fansmanage_id'],
                        'simple_id' => $data['simple_id'],
                        'goods_id' => $value['goods_id'],
                        'amount' => $value['total'],
                        'ordersn' => $data['ordersn'],
                        'operator_id' => $data['operator_id'],
                        'remark' => $data['remarks'],
                        // 退货入库类型
                        'type' => '7',
                        'status' => '1',
                    ];
                    // 商品操作记录
                    SimpleStockLog::addStockLog($stock_data);
                    $re = SimpleStock::getOneSimpleStock([['simple_id', $data['simple_id']], ['goods_id', $value['goods_id']]]);
                    $simple_stock = $re['stock'] + $value['total'];
                    SimpleStock::editStock([['id', $re['id']]], ['stock' => $simple_stock]);
                    // 修改stock_status为-1表示该订单的库存状态已经退回
                    if ($type == 'online') {
                        SimpleOnlineOrder::editSimpleOnlineOrder(['id' => $order_id], ['stock_status' => '-1']);
                    } else {
                        SimpleSelftakeOrder::editSimpleSelftakeOrder(['id' => $order_id], ['stock_status' => '-1']);
                    }
                }
            }
            // 提交事务
            DB::commit();
        } catch (\Exception $e) {
            // 事件回滚
            DB::rollBack();
            return response()->json(['msg' => '提交失败', 'status' => '0', 'data' => '']);
        }
        return 'ok';
    }


}