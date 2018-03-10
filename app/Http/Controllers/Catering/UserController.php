<?php
namespace App\Http\Controllers\Catering;
use App\Http\Controllers\Controller;
use App\Models\Label;
use App\Models\OperationLog;
use App\Models\Organization;
use App\Models\StoreUser;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\UserOrigin;
use App\Models\UserRecommender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
class UserController extends Controller{
    //粉丝标签管理
    public function user_tag(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由

        $store_id = $admin_data['organization_id'];//组织id
        $list = Label::getPaginage([['store_id',$store_id]],'10','id');

        return view('Catering/User/user_tag',['list'=>$list,'admin_data'=>$admin_data,'route_name'=>$route_name,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data]);
    }
    //添加会员标签ajax显示页面
    public function label_add(Request $request){

        return view('Catering/User/label_add');
    }
    //添加会员标签功能提交
    public function label_add_check(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由

        $label_name = $request->label_name; //会员标签名称
        $store_id = $admin_data['organization_id'];//组织id

        $re = Label::checkRowExists([['store_id',$store_id],['label_name',$label_name]]);
        if($re == 'true'){
            return response()->json(['data' => '会员标签名称已存在！', 'status' => '0']);
        }

        DB::beginTransaction();
        try {
            $dataLabel = [
                'store_id'=>$store_id,
                'branch_id'=>0,
                'label_name'=>$label_name,
                'label_number'=>0,
            ];
           Label::addLabel($dataLabel);
            if($admin_data['is_super'] != 2){
                OperationLog::addOperationLog('4',$admin_data['organization_id'],$admin_data['id'],$route_name,'创建会员标签成功：'.$label_name);//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '创建会员标签失败！', 'status' => '0']);
        }
        return response()->json(['data' => '创建会员标签成功！', 'status' => '1']);



    }
    //编辑会员标签ajax显示页面
    public function label_edit(Request $request){
        $id = $request->id; //会员标签id
        $oneLabel = Label::getOneLabel([['id',$id]]);
        return view('Catering/User/label_edit',['oneLabel'=>$oneLabel]);
    }
    //编辑会员标签功能提交
    public function label_edit_check(Request $request){

        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由

        $store_id = $admin_data['organization_id'];//组织id

        $id = $request->id; //会员标签id
        $label_name = $request->label_name; //会员标签名称
        $re = Label::checkRowExists([['store_id',$store_id],['label_name',$label_name]]);
        if($re == 'true'){
            return response()->json(['data' => '会员标签名称已存在！', 'status' => '0']);
        }
        DB::beginTransaction();
        try {
            Label::editLabel(['id'=>$id],['label_name'=>$label_name]);
            if($admin_data['is_super'] != 2){
                OperationLog::addOperationLog('4',$admin_data['organization_id'],$admin_data['id'],$route_name,'修改会员标签成功：'.$label_name);//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '修改会员标签失败！', 'status' => '0']);
        }
        return response()->json(['data' => '修改会员标签成功！', 'status' => '1']);

    }

    //删除会员标签ajax显示页面
    public function label_delete(Request $request){
        $id = $request->id; //会员标签id
        $oneLabel = Label::getOneLabel([['id',$id]]);
        return view('Catering/User/label_delete',['oneLabel'=>$oneLabel]);
    }
    //删除会员标签ajax显示页面
    public function label_delete_check(Request $request){

        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由

        $id = $request->id; //会员标签id
        $label_name = $request->label_name; //会员标签名称
        DB::beginTransaction();
        try {
            Label::where('id',$id)->forceDelete();
            if($admin_data['is_super'] != 2){
                OperationLog::addOperationLog('4',$admin_data['organization_id'],$admin_data['id'],$route_name,'删除会员标签：'.$label_name);//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '删除会员标签失败！', 'status' => '0']);
        }
        return response()->json(['data' => '删除会员标签成功！', 'status' => '1']);
    }
    //粉丝用户管理
    public function user_list(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由

        $organization_id = $admin_data['organization_id'];//组织id
        $store_name = Organization::getPluck([['id',$organization_id]],'organization_name')->first();//组织名称
        $list = StoreUser::getListStoreUser([['store_id',$organization_id]],'10','id');
        foreach($list as $key=>$value){
            $list[$key]['nickname'] =  UserInfo::getPluck([['user_id',$value->user_id]],'nickname')->first();//微信昵称
            $user_id =  User::getPluck([['id',$value->userRecommender->recommender_id]],'id')->first();
            $list[$key]['recommender_name']  =  UserInfo::getPluck([['user_id',$user_id]],'nickname')->first();//推荐人
        }
        $label = Label::ListLabel([['store_id',$organization_id]]);//会员标签
        return view('Catering/User/user_list',['list'=>$list,'store_name'=>$store_name,'label'=>$label,'organization_id'=>$organization_id,'admin_data'=>$admin_data,'route_name'=>$route_name,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data]);
    }
    //粉丝用户管理
    public function store_member_add_check(Request $request){

        dd(1);
//        $member_id = $request->member_id;//会员标签id
//        $user_id = $request->user_id;//用户id
//
//        DB::beginTransaction();
//        try {
//            MemberLabel::where('id',$id)->forceDelete();
//            if($admin_data['is_super'] != 2){
//                OperationLog::addOperationLog('4',$admin_data['organization_id'],$admin_data['id'],$route_name,'删除会员标签：'.$member_name);//保存操作记录
//            }
//            DB::commit();
//        } catch (\Exception $e) {
//            DB::rollBack();//事件回滚
//            return response()->json(['data' => '删除会员标签失败！', 'status' => '0']);
//        }
    }

    //粉丝用户管理编辑
    public function user_list_edit(Request $request){

        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $organization_id = $admin_data['organization_id'];//组织id

        $user_id = $request->id;//会员标签id
        $userInfo =  UserInfo::getOneUserInfo([['user_id',$user_id]]);//微信昵称
        $data['account'] =  User::getPluck([['id',$user_id]],'account')->first();//粉丝账号
        $yauntou = UserOrigin::getPluck([['user_id',$user_id]],'origin_id')->first();
        if($yauntou == $organization_id){
            $data['store_name'] = Organization::getPluck([['id',$organization_id]],'organization_name')->first();//组织名称
        }
        $recommender_id =  UserRecommender::getPluck([['user_id',$user_id]],'recommender_id')->first();//推荐人id
        if(!empty($recommender_id)){
            $list =  User::getOneUser([['id',$recommender_id]]);
            $data['recommender_name'] = $list->UserInfo->nickname;
        }
        return view('Catering/User/user_list_edit',['data'=>$data,'userInfo'=>$userInfo]);

    }
    //粉丝用户管理编辑功能提交
    public function user_list_edit_check(Request $request){

        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由

        $qq = $request->qq;//qq号
        $mobile = $request->mobile;//手机号
        $user_id = $request->user_id;//用户id
        $nickname = $request->nickname;//微信昵称
        $re = StoreUser::checkRowExists([['mobile',$mobile]]);
        if($re == 'true'){
            return response()->json(['data' => '手机号已存在', 'status' => '0']);
        }
        DB::beginTransaction();
        try {
            User::editUser(['id'=>$user_id],['mobile'=>$mobile,]);
            StoreUser::editStoreUser(['user_id'=>$user_id],['qq'=>$qq,]);
            if($admin_data['is_super'] != 2){
                OperationLog::addOperationLog('4',$admin_data['organization_id'],$admin_data['id'],$route_name,'修改资料：'.$nickname);//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '修改资料失败！', 'status' => '0']);
        }
        return response()->json(['data' => '修改资料成功！', 'status' => '1']);

    }

    //粉丝用户足迹
    public function user_timeline(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由

        return view('Catering/User/user_timeline',['admin_data'=>$admin_data,'route_name'=>$route_name,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data]);
    }
}
?>