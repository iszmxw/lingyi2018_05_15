<?php
namespace App\Http\Controllers\Fansmanage;
use App\Http\Controllers\Controller;
use App\Models\FansmanageUserLog;
use App\Models\Label;
use App\Models\OperationLog;
use App\Models\Organization;
use App\Models\FansmanageUser;
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
    //粉丝标签管理
    public function user_tag(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由

        $fansmanage_id = $admin_data['organization_id'];//组织id
        $list = Label::getPaginage([['fansmanage_id',$fansmanage_id]],'10','id');

        return view('Fansmanage/User/user_tag',['list'=>$list,'admin_data'=>$admin_data,'route_name'=>$route_name,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data]);
    }
    //添加会员标签ajax显示页面
    public function label_add(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        return view('Fansmanage/User/label_add',['admin_data'=>$admin_data]);
    }
    //添加会员标签功能提交
    public function label_add_check(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由

        $label_name = $request->label_name; //会员标签名称
        $fansmanage_id = $admin_data['organization_id'];//组织id

        $re = Label::checkRowExists([['fansmanage_id',$fansmanage_id],['label_name',$label_name]]);
        if($re == 'true'){
            return response()->json(['data' => '会员标签名称已存在！', 'status' => '0']);
        }
        DB::beginTransaction();
        try {
            $auth_info = \Wechat::refresh_authorization_info($fansmanage_id);//刷新并获取授权令牌
            $re = \Wechat::create_fans_tag($auth_info['authorizer_access_token'],$label_name);
            $re = json_decode($re,true);
            if(!empty($re['errcode'])){
                if($re['errcode'] == '45157'){
                    return response()->json(['data' => '微信公众平台已有该标签', 'status' => '0']);
                } elseif($re['errcode'] == '45158'){
                    return response()->json(['data' => '标签名长度超过30个字节', 'status' => '0']);
                } elseif($re['errcode'] == '45056'){
                    return response()->json(['data' => '创建的标签数过多，请注意不能超过100个', 'status' => '0']);
                }
            }
            $dataLabel = [
                'fansmanage_id'=>$fansmanage_id,
                'store_id'=>0,
                'label_name'=>$label_name,
                'label_number'=>0,
                'wechat_id'=>$re['tag']['id'],
            ];
            Label::addLabel($dataLabel);
            if ($admin_data['is_super'] != 2) {
                OperationLog::addOperationLog('3', $fansmanage_id, $admin_data['id'], $route_name, '创建会员标签成功：' . $label_name);//保存操作记录
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
        return view('Fansmanage/User/label_edit',['oneLabel'=>$oneLabel]);
    }
    //编辑会员标签功能提交
    public function label_edit_check(Request $request){

        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由

        $fansmanage_id = $admin_data['organization_id'];//组织id

        $id = $request->id; //会员标签id
        $label_name = $request->label_name; //会员标签名称
        $re = Label::checkRowExists([['fansmanage_id',$fansmanage_id],['label_name',$label_name]]);
        if($re == 'true'){
            return response()->json(['data' => '会员标签名称已存在！', 'status' => '0']);
        }
        $wechat_id = Label::getPluck([['id',$id]],'wechat_id')->first();
        DB::beginTransaction();
        try {
            $auth_info = \Wechat::refresh_authorization_info($fansmanage_id);//刷新并获取授权令牌
            $re = \Wechat::create_fans_tag_edit($auth_info['authorizer_access_token'],$label_name,$wechat_id);
            $re = json_decode($re,true);
            if(!empty($re['errcode'])){
                if($re['errcode'] == '45157'){
                    return response()->json(['data' => '微信公众平台已有该标签', 'status' => '0']);
                } elseif($re['errcode'] == '45158'){
                    return response()->json(['data' => '标签名长度超过30个字节', 'status' => '0']);
                } elseif($re['errcode'] == '45058'){
                    return response()->json(['data' => '不能修改0/1/2这三个系统默认保留的标签', 'status' => '0']);
                }
            }
            Label::editLabel(['id'=>$id],['label_name'=>$label_name]);

            if($admin_data['is_super'] != 2){
                OperationLog::addOperationLog('3',$admin_data['organization_id'],$admin_data['id'],$route_name,'修改会员标签成功：'.$label_name);//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '修改会员标签失败！', 'status' => '0']);
        }
        return response()->json(['data' => '修改会员标签成功！', 'status' => '1']);

    }
    
    /*
     * 删除会员标签ajax显示页面
     */
    public function label_delete(Request $request){
        $id = $request->id; //会员标签id
        $oneLabel = Label::getOneLabel([['id',$id]]);
        return view('Fansmanage/User/label_delete',['oneLabel'=>$oneLabel]);
    }
    //删除会员标签ajax显示页面
    public function label_delete_check(Request $request){

        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由

        $id = $request->id; //会员标签id
        $label_name = $request->label_name; //会员标签名称
        $fansmanage_id = $admin_data['organization_id'];//组织id
        $wechat_id = Label::getPluck([['id',$id]],'wechat_id')->first();
        DB::beginTransaction();
        try {
            $auth_info = \Wechat::refresh_authorization_info($fansmanage_id);//刷新并获取授权令牌
            $re = \Wechat::create_fans_tag_delete($auth_info['authorizer_access_token'],$wechat_id);
            $re = json_decode($re,true);
            if(!empty($re['errcode'])){
                if($re['errcode'] == '45057'){
                    return response()->json(['data' => '该标签下粉丝数超过10w，不允许直接删除', 'status' => '0']);
                } elseif($re['errcode'] == '45058'){
                    return response()->json(['data' => '不能修改0/1/2这三个系统默认保留的标签', 'status' => '0']);
                }
            }
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
        $list = FansmanageUser::getPaginage([['fansmanage_id',$organization_id]],'','10','id');
        foreach($list as $key=>$value){
            $list[$key]['nickname'] =  UserInfo::getPluck([['user_id',$value->user_id]],'nickname')->first();//微信昵称
            $recommender_id =  User::getPluck([['id',$value->userRecommender->recommender_id]],'id')->first();
            $list[$key]['recommender_name']  =  UserInfo::getPluck([['user_id',$recommender_id]],'nickname')->first();//推荐人
            $list[$key]['label_id']  = UserLabel::getPluck([['user_id',$value->user_id],['organization_id',$organization_id]],'label_id')->first();//粉丝对应的标签id
        }
        $label = Label::ListLabel([['fansmanage_id',$organization_id]]);//会员标签
        return view('Fansmanage/User/user_list',['list'=>$list,'store_name'=>$store_name,'label'=>$label,'organization_id'=>$organization_id,'admin_data'=>$admin_data,'route_name'=>$route_name,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data]);
    }
    //粉丝用户管理
    public function store_label_add_check(Request $request){

        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由

        $label_id = $request->label_id;//会员标签id
        $user_id = $request->user_id;//用户id
        $fansmanage_id = $request->fansmanage_id;//店铺id
        $nickname = $request->nickname;//微信昵称

        DB::beginTransaction();
        try {
            $oneData = UserLabel::getOneUserLabel([['user_id',$user_id],['fansmanage_id',$fansmanage_id]]);//查询粉丝标签关联表有没有数据

            if(!empty($oneData)){
                if($oneData->label_id != 0){ //当粉丝标签关联表里标签id为0时 不执行
                    //减少原粉丝标签的人数
                    $label_number = Label::getPluck([['id',$oneData->label_id]],'label_number')->first();//获取原粉丝标签的人数
                    $number = $label_number-1;
                    Label::editLabel([['id',$oneData->label_id]],['label_number'=>$number]);//修改粉丝标签的人数
                }
                 if($label_id != 0){ //选择无标签的时候 不执行
                     //增加现有的粉丝标签人数
                     $add_label_number = Label::getPluck([['id',$label_id]],'label_number')->first();//获取粉丝标签的人数
                     $add_number = $add_label_number+1;
                     Label::editLabel([['id',$label_id]],['label_number'=>$add_number]);//修改粉丝标签的人数
                 }
                UserLabel::editUserLabel([['id',$oneData->id]],['label_id'=>$label_id]);//修改粉丝标签关联表Label_id

            }else{
                UserLabel::addUserLabel(['label_id'=>$label_id,'user_id'=>$user_id,'fansmanage_id'=>$fansmanage_id,'branch_id'=>'0']);//粉丝与标签关系表
                $label_number = Label::getPluck([['id',$label_id]],'label_number')->first();//获取粉丝标签的人数
                $number = $label_number+1;
                Label::editLabel([['id',$label_id]],['label_number'=>$number]);//修改粉丝标签的人数
            }
            if($admin_data['is_super'] != 2){
                OperationLog::addOperationLog('3',$admin_data['organization_id'],$admin_data['id'],$route_name,'修改粉丝标签：'.$nickname);//保存操作记录
            }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '操作失败！', 'status' => '0']);
        }
        return response()->json(['data' => '操作成功！', 'status' => '1']);
    }

    //粉丝用户管理编辑
    public function user_list_edit(Request $request){

        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $organization_id = $admin_data['organization_id'];//组织id

        $user_id = $request->id;//会员标签id
        $userInfo =  UserInfo::getOneUserInfo([['user_id',$user_id]]);//微信昵称
        $data['account'] =  User::getPluck([['id',$user_id]],'account')->first();//粉丝账号
        $data['mobile'] =  FansmanageUser::getPluck([['user_id',$user_id]],'mobile')->first();//手机号
        $yauntou = UserOrigin::getPluck([['user_id',$user_id]],'origin_id')->first();
        if($yauntou == $organization_id){
            $data['store_name'] = Organization::getPluck([['id',$organization_id]],'organization_name')->first();//组织名称
        }
        $recommender_id =  UserRecommender::getPluck([['user_id',$user_id]],'recommender_id')->first();//推荐人id
        if(!empty($recommender_id)){
            $list =  User::getOneUser([['id',$recommender_id]]);
            $data['recommender_name'] = $list->UserInfo->nickname;
        }
        return view('Fansmanage/User/user_list_edit',['data'=>$data,'userInfo'=>$userInfo]);

    }


    //粉丝用户管理编辑功能提交
    public function user_list_edit_check(Request $request){

        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由

        $qq = $request->qq;//qq号
        $mobile = $request->mobile;//手机号
        $user_id = $request->user_id;//用户id
        $nickname = $request->nickname;//微信昵称
        $re = FansmanageUser::checkRowExists([['mobile',$mobile],['user_id','<>',$user_id]]);
        if($re == 'true'){
            return response()->json(['data' => '手机号已存在', 'status' => '0']);
        }
        DB::beginTransaction();
        try {
            FansmanageUser::editStoreUser(['user_id'=>$user_id],['mobile'=>$mobile]);
            UserInfo::editUserInfo(['user_id'=>$user_id],['qq'=>$qq]);
            if($admin_data['is_super'] != 2){
                OperationLog::addOperationLog('3',$admin_data['organization_id'],$admin_data['id'],$route_name,'修改资料：'.$nickname);//保存操作记录
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
        return view('Fansmanage/User/user_list_wallet',['user_id'=>$user_id,'nickname'=>$nickname,'status'=>$status]);
    }
    //粉丝用户管理冻结功能显示
    public function user_list_lock(Request $request){
        $user_id = $request->id;//会员标签id
        $status = $request->status;//冻结或者解锁
        $nickname =  UserInfo::getPluck([['user_id',$user_id]],'nickname')->first();//微信昵称
        return view('Fansmanage/User/user_list_lock',['user_id'=>$user_id,'nickname'=>$nickname,'status'=>$status]);
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
                FansmanageUser::editStoreUser(['user_id'=>$user_id],['status'=>'0']);
                if($admin_data['is_super'] != 2){
                    OperationLog::addOperationLog('3',$admin_data['organization_id'],$admin_data['id'],$route_name,'冻结了：'.$nickname);//保存操作记录
                }
            }else{
                FansmanageUser::editStoreUser(['user_id'=>$user_id],['status'=>'1']);
                if($admin_data['is_super'] != 2){
                    OperationLog::addOperationLog('3',$admin_data['organization_id'],$admin_data['id'],$route_name,'解冻了：'.$nickname);//保存操作记录
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '操作失败！', 'status' => '0']);
        }
        return response()->json(['data' => '操作成功！', 'status' => '1']);

    }
    //粉丝用户足迹
    public function user_timeline(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由

        $fansmanage_id = $admin_data['organization_id'];//组织id
        $list = FansmanageUserLog::getPaginage([['fansmanage_id',$fansmanage_id]],'5','id');
        foreach($list as $key=>$value){
            $list[$key]['nickname'] = UserInfo::getPluck([['user_id', $value->user_id]],'nickname')->first();//微信昵称
        }
        return view('Fansmanage/User/user_timeline',['list'=>$list,'admin_data'=>$admin_data,'route_name'=>$route_name,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data]);
    }
}
?>