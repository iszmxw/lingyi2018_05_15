<?php
/**
 * 零售店铺管理系统
 * 商品管理
 **/

namespace App\Http\Controllers\Retail;

use App\Http\Controllers\Controller;
use App\Models\RetailCategory;
use App\Models\RetailGoods;
use App\Models\RetailGoodsThumb;
use App\Models\OperationLog;
use App\Models\Organization;
use App\Models\RetailStock;
use App\Models\RetailStockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Session;

class GoodsController extends Controller
{
    //添加商品
    public function goods_add(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');            //中间件产生的菜单数据参数
        $son_menu_data = $request->get('son_menu_data');    //中间件产生的子菜单数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $where = [
            'retail_id' => $admin_data['organization_id'],
        ];
        $category = RetailCategory::getList($where, '0', 'displayorder', 'DESC');
        return view('Retail/Goods/goods_add', ['category' => $category, 'admin_data' => $admin_data, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data, 'route_name' => $route_name]);
    }

    //添加商品数据操作
    public function goods_add_check(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $category_id = $request->get('category_id');        //栏目ID
        $name = $request->get('name');                      //商品名称
        $price = $request->get('price');                    //商品价格
        $barcode = $request->get('barcode');                //商品条码
        $stock = $request->get('stock');                    //商品库存
        $displayorder = $request->get('displayorder');      //商品排序
        $details = $request->get('details');                //商品详情
        $fansmanage_id = Organization::getPluck(['id'=>$admin_data['organization_id']],'parent_id')->first();
        $goods_name = RetailGoods::checkRowExists(['fansmanage_id' => $fansmanage_id,'retail_id' => $admin_data['organization_id'],'name' => $name]);
        if($goods_name) {//判断商品名称是已经存在
            return response()->json(['data' => '商品名称重名，请重新输入！', 'status' => '0']);
        }
        if ($category_id == 0) {
            return response()->json(['data' => '请选择分类！', 'status' => '0']);
        }
        //商品数据
        $goods_data = ['fansmanage_id' => $fansmanage_id,'retail_id' => $admin_data['organization_id'],'created_by' => $admin_data['id'],'category_id' => $category_id,'name' => $name, 'price' => $price, 'stock' => $stock, 'barcode'=>$barcode,'displayorder' => $displayorder, 'details' => $details];
        DB::beginTransaction();
        try {
            $goods_id = RetailGoods::addRetailGoods($goods_data);   //添加商品基本信息
            //商品库存信息
            $stock_data = ['fansmanage_id' => $fansmanage_id,'retail_id' => $admin_data['organization_id'],'category_id' => $category_id,'goods_id' => $goods_id, 'stock' => $stock,];
            RetailStock::addStock($stock_data); //添加商品库信息存到库存表
            //添加操作日志
            if ($admin_data['is_super'] == 1) {//超级管理员操作商户的记录
                OperationLog::addOperationLog('1', '1', '1', $route_name, '在零售管理系统添加了商品！');//保存操作记录
            } else {//商户本人操作记录
                OperationLog::addOperationLog('10', $admin_data['organization_id'], $admin_data['id'], $route_name, '添加了商品！');//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();//事件回滚
            return response()->json(['data' => '添加商品失败，请检查', 'status' => '0']);
        }
        return response()->json(['data' => '添加商品成功', 'status' => '1', 'goods_id' => $goods_id]);
    }


    //编辑商品
    public function goods_edit(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');            //中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');    //中间件产生的管理员数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $goods_id = $request->get('goods_id');              //获取当前的页面路由
        $where = [
            'retail_id' => $admin_data['organization_id'],
        ];
        $goods_thumb = RetailGoodsThumb::getList(['goods_id'=>$goods_id],0,'created_at','DESC');
        $goods = RetailGoods::getOne(['id' => $goods_id, 'retail_id' => $admin_data['organization_id']]);   //商品信息
        $category = RetailCategory::getList($where, '0', 'displayorder', 'DESC');   //所有栏目
        return view('Retail/Goods/goods_edit', ['goods_thumb'=>$goods_thumb,'category' => $category, 'goods' => $goods, 'admin_data' => $admin_data, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data, 'route_name' => $route_name]);
    }

    //编辑商品操作
    public function goods_edit_check(Request $request)
    {
        $admin_data = $request->get('admin_data');      //中间件产生的管理员数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $goods_id = $request->get('goods_id');              //商品ID
        $category_id = $request->get('category_id');        //栏目ID
        $name = $request->get('name');                      //商品名称
        $price = $request->get('price');                    //商品价格
        $barcode = $request->get('barcode');                //商品条码
        $stock = $request->get('stock');                    //商品库存
        $displayorder = $request->get('displayorder');      //商品排序
        $details = $request->get('details');                //商品详情
        $fansmanage_id = Organization::getPluck(['id'=>$admin_data['organization_id']],'parent_id')->first();
        if ($category_id == 0) {
            return response()->json(['data' => '请选择分类！', 'status' => '0']);
        }
        $where = ['id' => $goods_id];
        //商品数据
        $goods_data = ['fansmanage_id' => $fansmanage_id,'retail_id' => $admin_data['organization_id'],'created_by' => $admin_data['id'], 'category_id' => $category_id, 'name' => $name,'price' => $price,'barcode'=>$barcode,'stock' => $stock,'displayorder' => $displayorder,'details' => $details];
        DB::beginTransaction();
        try {
            RetailGoods::editRetailGoods($where,$goods_data);
            $stock_data = ['category_id' => $category_id,'stock' => $stock,];
            RetailStock::editStock(['goods_id'=>$goods_id],$stock_data); //修改商品库信息存到库存表
            //添加操作日志
            if ($admin_data['is_super'] == 1) {//超级管理员操作零售店铺的记录
                OperationLog::addOperationLog('1', '1', '1', $route_name, '在零售管理系统编辑了商品！');//保存操作记录
            } else {//零售店铺本人操作记录
                OperationLog::addOperationLog('10', $admin_data['organization_id'], $admin_data['id'], $route_name, '编辑了商品！');//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '编辑商品失败，请检查', 'status' => '0']);
        }
        return response()->json(['data' => '编辑商品信息成功', 'status' => '1', 'goods_id' => $goods_id]);
    }
    //删除商品图片弹窗
    public function goods_thumb_delete(Request $request)
    {
        $goods_thumb_id = $request->get('id');              //图片的id

        return view('Retail/Goods/goods_thumb_delete',['goods_thumb_id'=>$goods_thumb_id]);
    }
    //删除商品图片操作
    public function goods_thumb_delete_check(Request $request)
    {
        $admin_data = $request->get('admin_data');           //中间件产生的管理员数据参数
        $route_name = $request->path();                          //获取当前的页面路由
        $goods_thumb_id = $request->get('goods_thumb_id');              //获取图片ID
        $goods_thumb = RetailGoodsThumb::getPluck(['id'=>$goods_thumb_id],'thumb')->first();

        $thumb = 'uploads/catering/20180409105716752.png';


        /*
        if(Storage::exists($thumb))
            return response()->json(['data' => '商品图片存在', 'status' => '1']);
        else
            return response()->json(['data' => '商品图片不存在，请检查', 'status' => '0']);
*/

        if( Storage::disk('app')->delete($thumb))
             return response()->json(['data' => '删除商品图片成功', 'status' => '1']);
        else
            return response()->json(['data' => '删除商品图片失败，请检查', 'status' => '0']);
        //  $id = RetailStock::getPluck(['goods_thumb_id'=>$goods_thumb_id],'id')->first();
        DB::beginTransaction();
        try {

            RetailGoodsThumb::deleteGoodsThumb($goods_thumb_id);

          //  http://o2o.01nnt.com/uploads/catering/20180409105716752.png

         //   RetailStock::select_delete($id);
            //添加操作日志
            if ($admin_data['is_super'] == 1) {//超级管理员删除零售店铺商品的操作记录
                OperationLog::addOperationLog('1', '1', '1', $route_name, '在零售店铺管理系统删除了商品图片！');//保存操作记录
            } else {//零售店铺本人操作记录
                OperationLog::addOperationLog('10', $admin_data['organization_id'], $admin_data['id'], $route_name, '删除商品图片！');//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '删除商品图片失败，请检查', 'status' => '0']);
        }


        return response()->json(['data' => '删除商品图片成功'.$goods_thumb, 'status' => '1']);
    }
    //图片异步加载部分
    public function goods_thumb(Request $request)
    {
        $goods_id = $request->get('goods_id');              //商品的ID
        $goods_thumb = RetailGoodsThumb::getList(['goods_id'=>$goods_id],0,'created_at','DESC');    //商品图片
        return view('Retail/Goods/goods_thumb', ['goods_thumb'=>$goods_thumb]);
    }


    //上传图片处理
    public function upload_thumb_check(Request $request)
    {
        $admin_data = $request->get('admin_data');           //中间件产生的管理员数据参数
        $route_name = $request->path();                          //获取当前的页面路由
        $goods_id = $request->get('goods_id');              //获取商品ID
        $file = $request->file('upload_thumb');             //获取上传文件
        $file_path = '';                                        //初始化文件路径
        if ($request->hasFile('upload_thumb') && $file->isValid()){
            //检验文件是否有效
            $entension = $file->getClientOriginalExtension();                           //获取上传文件后缀名
            $new_name = date('Ymdhis') . mt_rand(100, 999) . '.' . $entension;  //重命名
            $file->move(base_path() . '/uploads/catering/', $new_name);         //$path上传后的文件路径
            $file_path =  'uploads/catering/'.$new_name;
        }
        $goods_thumb = ['goods_id' => $goods_id,'thumb' => $file_path];         //商品图片信息
        DB::beginTransaction();
        try {
            RetailGoodsThumb::addGoodsThumb($goods_thumb);
            //添加操作日志
            if ($admin_data['is_super'] == 1) {//超级管理员零售商品图片添加的记录
                OperationLog::addOperationLog('1', '1', '1', $route_name, '在零售管理系统上传了商品图片！');//保存操作记录
            } else {//零售店铺本人操作记录
                OperationLog::addOperationLog('10', $admin_data['organization_id'], $admin_data['id'], $route_name, '上传了商品图片！');//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '上传商品图片失败，请检查', 'status' => '0']);
        }
        return response()->json(['data' => '上传商品图片信息成功','file_path' => $file_path, 'status' => '1']);
    }

    //商品列表
    public function goods_list(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');            //中间件产生的菜单数据参数
        $son_menu_data = $request->get('son_menu_data');    //中间件产生的子菜单数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $goods_name = $request->get('goods_name');          //接收搜索参数
        $category_id = $request->get('category_id');        //接收搜索参数
        $search_data = ['goods_name' => $goods_name,'category_id' => $category_id]; //处理搜索参数
        $fansmanage_id = Organization::getPluck(['id'=>$admin_data['organization_id']],'parent_id')->first();    //获取粉丝管理平台的组织id
        $where = ['fansmanage_id'=>$fansmanage_id,'retail_id' => $admin_data['organization_id']];
        $category = RetailCategory::getList($where,0,'created_at','DESC');
        $goods = RetailGoods::getPaginage($where,$search_data,'10','displayorder','ASC'); //查询商品信息
        return view('Retail/Goods/goods_list', ['goods' => $goods,'search_data'=>$search_data,'category'=>$category,'admin_data' => $admin_data, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data, 'route_name' => $route_name]);
    }


    //删除商品弹窗
    public function goods_delete(Request $request)
    {
        $goods_id = $request->get('id');              //分类栏目的id
        return view('Retail/Goods/goods_delete',['goods_id'=>$goods_id]);
    }

    //删除商品操作
    public function goods_delete_check(Request $request)
    {
        $admin_data = $request->get('admin_data');           //中间件产生的管理员数据参数
        $route_name = $request->path();                          //获取当前的页面路由
        $goods_id = $request->get('goods_id');              //获取分类栏目ID
        $id = RetailStock::getPluck(['goods_id'=>$goods_id],'id')->first();
        DB::beginTransaction();
        try {
            RetailGoods::select_delete($goods_id);
            RetailStock::select_delete($id);
            //添加操作日志
            if ($admin_data['is_super'] == 1) {//超级管理员删除零售店铺商品的操作记录
                OperationLog::addOperationLog('1', '1', '1', $route_name, '在零售店铺管理系统删除了商品！');//保存操作记录
            } else {//零售店铺本人操作记录
                OperationLog::addOperationLog('10', $admin_data['organization_id'], $admin_data['id'], $route_name, '删除商品！');//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '删除商品失败，请检查', 'status' => '0']);
        }
        return response()->json(['data' => '删除商品成功', 'status' => '1']);
    }
}

?>