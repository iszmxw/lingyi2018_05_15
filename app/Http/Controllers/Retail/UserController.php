<?php
namespace App\Http\Controllers\Retail;
use App\Http\Controllers\Controller;
use App\Models\Label;
use App\Models\OperationLog;
use App\Models\Organization;
use App\Models\StoreUser;
use App\Models\StoreUserLog;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\UserLabel;
use App\Models\UserOrigin;
use App\Models\UserRecommender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
class UserController extends Controller{
    //粉丝用户管理
    public function user_list(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由

        $organization_id = $admin_data['organization_id'];//组织id
        $store_name = Organization::getPluck([['id',$organization_id]],'organization_name')->first();//组织名称
        $list = StoreUser::getPaginage([['store_id',$organization_id]],'10','id');
        foreach($list as $key=>$value){
            $list[$key]['nickname'] =  UserInfo::getPluck([['user_id',$value->user_id]],'nickname')->first();//微信昵称
            $recommender_id =  User::getPluck([['id',$value->userRecommender->recommender_id]],'id')->first();
            $list[$key]['recommender_name']  =  UserInfo::getPluck([['user_id',$recommender_id]],'nickname')->first();//推荐人
        }
        return view('Retail/User/user_list',['list'=>$list,'store_name'=>$store_name,'organization_id'=>$organization_id,'admin_data'=>$admin_data,'route_name'=>$route_name,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data]);
    }

    //粉丝用户管理编辑
    public function user_list_edit(Request $request){

        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $organization_id = $admin_data['organization_id'];//组织id

        $user_id = $request->id;//会员标签id
        $userInfo =  UserInfo::getOneUserInfo([['user_id',$user_id]]);//微信昵称
        $data['account'] =  User::getPluck([['id',$user_id]],'account')->first();//粉丝账号
        $data['mobile'] =  StoreUser::getPluck([['user_id',$user_id]],'mobile')->first();//手机号
        $yauntou = UserOrigin::getPluck([['user_id',$user_id]],'origin_id')->first();
        if($yauntou == $organization_id){
            $data['store_name'] = Organization::getPluck([['id',$organization_id]],'organization_name')->first();//组织名称
        }
        $recommender_id =  UserRecommender::getPluck([['user_id',$user_id]],'recommender_id')->first();//推荐人id
        if(!empty($recommender_id)){
            $list =  User::getOneUser([['id',$recommender_id]]);
            $data['recommender_name'] = $list->UserInfo->nickname;
        }
        return view('Retail/User/user_list_edit',['data'=>$data,'userInfo'=>$userInfo]);

    }
    //粉丝用户管理编辑功能提交
    public function user_list_edit_check(Request $request){

        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由

        $qq = $request->qq;//qq号
        $mobile = $request->mobile;//手机号
        $user_id = $request->user_id;//用户id
        $nickname = $request->nickname;//微信昵称
        $re = StoreUser::checkRowExists([['mobile',$mobile],['user_id','<>',$user_id]]);
        if($re == 'true'){
            return response()->json(['data' => '手机号已存在', 'status' => '0']);
        }
        DB::beginTransaction();
        try {
            StoreUser::editStoreUser(['user_id'=>$user_id],['mobile'=>$mobile]);
            UserInfo::editUserInfo(['user_id'=>$user_id],['qq'=>$qq]);
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

    //粉丝用户管理粉丝钱包
    public function user_list_wallet(Request $request){

        $user_id = $request->id;//会员标签id
        $status = $request->status;//冻结或者解锁
        $nickname =  UserInfo::getPluck([['user_id',$user_id]],'nickname')->first();//微信昵称

        return view('Retail/User/user_list_wallet',['user_id'=>$user_id,'nickname'=>$nickname,'status'=>$status]);

    }
    //粉丝用户管理冻结功能显示
    public function user_list_lock(Request $request){

        $user_id = $request->id;//会员标签id
        $status = $request->status;//冻结或者解锁
        $nickname =  UserInfo::getPluck([['user_id',$user_id]],'nickname')->first();//微信昵称

        return view('Retail/User/user_list_lock',['user_id'=>$user_id,'nickname'=>$nickname,'status'=>$status]);

    }
    //粉丝用户管理冻结功能提交
    public function user_list_lock_check(Request $request){

        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由

        $user_id = $request->user_id;//会员标签id
        $nickname = $request->nickname;//会员标签id
        $status = $request->status;//冻结或者解冻判断

        DB::beginTransaction();
        try {
            if($status == 1){
                StoreUser::editStoreUser(['user_id'=>$user_id],['status'=>'0']);
                if($admin_data['is_super'] != 2){
                    OperationLog::addOperationLog('4',$admin_data['organization_id'],$admin_data['id'],$route_name,'冻结了：'.$nickname);//保存操作记录
                }
            }else{
                StoreUser::editStoreUser(['user_id'=>$user_id],['status'=>'1']);
                if($admin_data['is_super'] != 2){
                    OperationLog::addOperationLog('4',$admin_data['organization_id'],$admin_data['id'],$route_name,'解冻了：'.$nickname);//保存操作记录
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '操作失败！', 'status' => '0']);
        }
        return response()->json(['data' => '操作成功！', 'status' => '1']);
    }
}
?>