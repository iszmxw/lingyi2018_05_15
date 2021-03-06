<?php
/**
 * 用户管理 模块，包括：
 *   粉丝标签，粉丝用户，用户足迹
 */

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

class UserController extends Controller
{
    // +----------------------------------------------------------------------
    // | Start - 粉丝标签
    // +----------------------------------------------------------------------
    /**
     * 粉丝标签列表管理
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function user_tag()
    {
        // 中间件参数 集合
        $this->getRequestInfo();
        // 组织id
        $fansmanage_id = $this->admin_data['organization_id'];
        // 获取标签列表
        $list = Label::getPaginage([['fansmanage_id', $fansmanage_id]], '10', 'id');
        // 渲染页面
        return view('Fansmanage/User/user_tag', ['list' => $list, 'admin_data' => $this->admin_data, 'route_name' => $this->route_name, 'menu_data' => $this->menu_data, 'son_menu_data' => $this->son_menu_data]);
    }

    /**
     * 添加会员标签ajax显示页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function label_add()
    {
        // 中间件参数 集合
        $this->getRequestInfo();
        // 渲染页面
        return view('Fansmanage/User/label_add', ['admin_data' => $this->admin_data]);
    }

    /**
     * 添加会员标签功能提交,并且同步到微信公众号
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function label_add_check()
    {
        // 中间件参数 集合
        $this->getRequestInfo();
        // 会员标签名称
        $label_name = request()->label_name;
        // 组织id
        $fansmanage_id = $this->admin_data['organization_id'];

        // 判断标签是否已经存在
        $re = Label::checkRowExists([['fansmanage_id', $fansmanage_id], ['label_name', $label_name]]);
        // 如果存在就返回报错
        if ($re === true) {
            return $this->getResponseMsg(0, "会员标签名称已存在！");
        }

        // 事务处理
        DB::beginTransaction();
        try {
            // 刷新并获取授权令牌
            $auth_info = \Wechat::refresh_authorization_info($fansmanage_id);
            // 创建微信公众号粉丝标签
            $re = \Wechat::create_fans_tag($auth_info['authorizer_access_token'], $label_name);
            $re = json_decode($re, true);


            // 判断微信公众号返回的消息
            if (!empty($re['errcode']) && $re["errcode"] != 0) {
                $msg = \WechatError::getCodeToMsg($re['errcode']);
                return $this->getResponseMsg(0, $msg);
            }

            // 处理数据
            $dataLabel = [
                'fansmanage_id' => $fansmanage_id,
                'store_id' => 0,
                'label_name' => $label_name,
                'label_number' => 0,
                // 微信公众号标签返回的id
                'wechat_id' => $re['tag']['id'],
            ];
            // 添加标签
            Label::addLabel($dataLabel);


            // 添加操作记录
            if ($this->admin_data['is_super'] != 2) {
                $this->insertOperationLog(3, '创建会员标签成功：' . $label_name);
            }
            DB::commit();
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            // 事件回滚
            DB::rollBack();
            return $this->getResponseMsg(0, "创建会员标签失败！");
        }
        return $this->getResponseMsg(1, "创建会员标签成功！");
    }

    /**
     * 编辑会员标签ajax显示页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function label_edit()
    {
        // 获取会员标签id
        $id = request()->id;
        // 获取标签信息
        $oneLabel = Label::getOneLabel([['id', $id]]);
        // 渲染页面
        return view('Fansmanage/User/label_edit', ['oneLabel' => $oneLabel]);
    }

    /**
     * 编辑会员标签功能提交,并且同步到微信公众号
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function label_edit_check()
    {
        // 中间件参数 集合
        $this->getRequestInfo();
        // 组织id
        $fansmanage_id = $this->admin_data['organization_id'];
        // 会员标签id
        $id = request()->id;
        // 会员标签名称
        $label_name = request()->label_name;

        // 检测标签是否已经存在
        $re = Label::checkRowExists([['fansmanage_id', $fansmanage_id], ['label_name', $label_name]]);
        if ($re === true) {
            return $this->getResponseMsg(0, '会员标签名称已存在！');
        }

        // 获取标签的 由微信公众号返回的 微信 id
        $wechat_id = Label::getPluck([['id', $id]], 'wechat_id')->first();
        // 事务处理
        DB::beginTransaction();
        try {
            // 刷新并获取授权令牌
            $auth_info = \Wechat::refresh_authorization_info($fansmanage_id);
            // 编辑微信公众号粉丝标签
            $re = \Wechat::create_fans_tag_edit($auth_info['authorizer_access_token'], $label_name, $wechat_id);
            $re = json_decode($re, true);
            // 判断微信公众号返回的消息
            if (!empty($re['errcode']) && $re["errcode"] != 0) {
                $msg = \WechatError::getCodeToMsg($re['errcode']);
                return $this->getResponseMsg(0, $msg);
            }
            // 修改数据库里面的标签
            Label::editLabel(['id' => $id], ['label_name' => $label_name]);
            // 添加操作记录
            if ($this->admin_data['is_super'] != 2) {
                $this->insertOperationLog(3, '修改会员标签成功：' . $label_name);
            }
            DB::commit();
        } catch (\Exception $e) {
            // 事件回滚
            DB::rollBack();
            return $this->getResponseMsg(0, '修改会员标签失败！');
        }
        return $this->getResponseMsg(1, '修改会员标签成功！');
    }

    /**
     * 删除会员标签ajax显示页面,并且同步到微信公众号
     */
    public function label_delete()
    {
        // 会员标签id
        $id = request()->id;
        // 获取标签信息
        $oneLabel = Label::getOneLabel([['id', $id]]);
        // 渲染页面
        return view('Fansmanage/User/label_delete', ['oneLabel' => $oneLabel]);
    }

    /**
     * 删除会员标签ajax数据检测
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function label_delete_check()
    {
        // 中间件参数 集合
        $this->getRequestInfo();
        // 会员标签id
        $id = request()->id;
        // 会员标签名称
        $label_name = request()->label_name;
        // 组织id
        $fansmanage_id = $this->admin_data['organization_id'];
        // 获取微信公众号返回的 标签的微信id
        $wechat_id = Label::getPluck([['id', $id]], 'wechat_id')->first();

        // 事务处理
        DB::beginTransaction();
        try {
            // 刷新并获取授权令牌
            $auth_info = \Wechat::refresh_authorization_info($fansmanage_id);
            // 删除微信公众号上面的标签
            $re = \Wechat::create_fans_tag_delete($auth_info['authorizer_access_token'], $wechat_id);
            $re = json_decode($re, true);
            // 判断微信公众号返回的消息
            if (!empty($re['errcode']) && $re["errcode"] != 0) {
                $msg = \WechatError::getCodeToMsg($re['errcode']);
                return $this->getResponseMsg(0, $msg);
            }
            // 标签删除, 标签里面配置了软删除,所以用这个方法进行强制删除
            Label::where('id', $id)->forceDelete();
            // 添加操作记录
            if ($this->admin_data['is_super'] != 2) {
                $this->insertOperationLog("3", '删除会员标签：' . $label_name);
            }
            DB::commit();
        } catch (\Exception $e) {
            // 事件回滚
            DB::rollBack();
            return $this->getResponseMsg(0, '删除会员标签失败！');
        }
        return $this->getResponseMsg(1, '删除会员标签成功！');
    }


    /**
     * 微信同步粉丝标签ajax显示页面
     * 主要是可以同步部分直接在公众号后台直接进行添加标签的步骤
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function label_wechat()
    {
        // 渲染页面
        return view('Fansmanage/User/label_wechat');
    }

    /**
     * 微信同步粉丝标签功能提交
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function label_wechat_check()
    {
        // 中间件参数 集合
        $this->getRequestInfo();

        // 组织id
        $fansmanage_id = $this->admin_data['organization_id'];
        // 刷新并获取授权令牌
        $auth_info = \Wechat::refresh_authorization_info($fansmanage_id);
        // 获取微信公众号标签列表
        $re = \Wechat::create_fans_tag_list($auth_info['authorizer_access_token']);
        $re = json_decode($re, true);

        // 判断微信公众号返回的消息
        if (!empty($re['errcode']) && $re["errcode"] != 0) {
            $msg = \WechatError::getCodeToMsg($re['errcode']);
            return $this->getResponseMsg(0, $msg);
        }

        // 获取本地标签列表
        $list = Label::getPluck(['fansmanage_id' => $fansmanage_id], "label_name");
        if (!empty($list)) {
            $local_label = $list->toArray();
        }

        // 获取微信公众号标签
        $wechat_label = array_column($re['tags'], "name");
        // 获取微信公众号标签和本地标签的差异
        $data = array_diff($wechat_label, $local_label);

        // 事务处理
        DB::beginTransaction();
        try {
            $dataLabel["fansmanage_id"] = $fansmanage_id;
            $dataLabel["store_id"] = 0;
            // 将线上存在，但是本地不存在的数据保存到本地
            foreach ($data as $key => $val) {
                $re_tags_val = $re['tags'][$key];
                $dataLabel["label_name"] = $re_tags_val['name'];
                $dataLabel["label_number"] = $re_tags_val['count'];
                $dataLabel["wechat_id"] = $re_tags_val['id'];
                Label::addLabel($dataLabel);
            }
            DB::commit();
        } catch (\Exception $e) {
            // 事件回滚
            DB::rollBack();
            return response()->json(['data' => '同步失败！', 'status' => '0']);
        }
        return response()->json(['data' => '同步成功！', 'status' => '1']);
    }

    // +----------------------------------------------------------------------
    // | End - 粉丝标签
    // +----------------------------------------------------------------------


    // +----------------------------------------------------------------------
    // | Start - 粉丝用户管理
    // +----------------------------------------------------------------------
    /**
     * 粉丝用户管理 页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function user_list()
    {
        // 中间件参数 集合
        $this->getRequestInfo();
        // 组织id
        $organization_id = $this->admin_data['organization_id'];
        // 组织名称
        $store_name = Organization::where(['id' => $organization_id])->value("organization_name");

        // 获取粉丝列表
        $list = FansmanageUser::getPaginage([['fansmanage_id', $organization_id]], '', '10', 'id', "DESC");
        // 处理数据
        foreach ($list as $key => $value) {
            if (!empty($value["user"])) {
                // 微信昵称
                $list[$key]['nickname'] = $value["userInfo"]['nickname'];
                // 微信头像
                $list[$key]['head_imgurl'] = $value["userInfo"]['head_imgurl'];
                // 获取推荐人信息
                // 推荐人id
                $recommender_id = User::where(['id' => $value["userRecommender"]["recommender_id"]])->value("id");
                // 推荐人名称
                $list[$key]['recommender_name'] = UserInfo::where(['user_id' => $recommender_id])->value("nickname");
                // 粉丝对应的标签id
                $list[$key]['label_id'] = $value["userLabel"]['label_id'];
            }
        }

        // 粉丝标签列表
        $label = Label::ListLabel([['fansmanage_id', $organization_id], ['store_id', '0']]);

        // 渲染页面
        return view('Fansmanage/User/user_list', ['list' => $list, 'store_name' => $store_name, 'label' => $label, 'organization_id' => $organization_id, 'admin_data' => $this->admin_data, 'route_name' => $this->route_name, 'menu_data' => $this->menu_data, 'son_menu_data' => $this->son_menu_data]);
    }


    /**
     * 列表搜索 页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function user_list_search()
    {
        // 中间件参数 集合
        $this->getRequestInfo();
        // 搜索内容
        $search_content = request()->input("search_content");
        // 组织id
        $organization_id = $this->admin_data['organization_id'];
        // 组织名称
        $store_name = Organization::getPluck([['id', $organization_id]], 'organization_name');
        if (empty($search_content)) {
            $search_content = '';
        }
        // 获取粉丝列表
        $list = FansmanageUser::getPaginage([['fansmanage_id', $organization_id]], '', '10', 'id', "DESC", $search_content);
        // 处理数据
        foreach ($list as $key => $value) {
            if (!empty($value["user"])) {
                // 微信昵称
                $list[$key]['nickname'] = $value["userInfo"]['nickname'];
                // 微信头像
                $list[$key]['head_imgurl'] = $value["userInfo"]['head_imgurl'];
                // 获取推荐人信息
                // 推荐人id
                $recommender_info = User::select("id")->where(['id' => $value["userRecommender"]["recommender_id"]])->first();
                // 推荐人名称
                $userInfo = UserInfo::select("nickname")->where(['user_id' => $recommender_info['id']])->first();
                $list[$key]['recommender_name'] = $userInfo["nickname"];
                // 粉丝对应的标签id
                $list[$key]['label_id'] = $value["userLabel"]['label_id'];
            }
        }
        // 粉丝标签列表
        $label = Label::ListLabel([['fansmanage_id', $organization_id], ['store_id', '0']]);
        // 渲染页面
        return view('Fansmanage/User/user_list_search', ['list' => $list, 'store_name' => $store_name, 'label' => $label, 'organization_id' => $organization_id]);
    }

    /**
     * 粉丝用户管理编辑
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function user_list_edit()
    {
        // 中间件参数 集合
        $this->getRequestInfo();
        // 组织id
        $organization_id = $this->admin_data["organization_id"];
        // 会员标签id
        $user_id = request()->id;
        // 微信昵称
        $userInfo = UserInfo::getOneUserInfo([['user_id', $user_id]]);
        // 粉丝账号
        $data['account'] = User::where(['id' => $user_id])->value("account");
        // 手机号
        $data['mobile'] = FansmanageUser::where(['user_id' => $user_id])->value("mobile");
        // 获取推荐人的id
        $yauntou = UserOrigin::where(['user_id' => $user_id])->value("store_id");

        // 如果推荐人id 同 组织id 相同, 则返回 组织名称,否则 默认为联盟商户
        if ($yauntou == $organization_id) {
            // 组织名称
            $data['store_name'] = Organization::where(['id' => $organization_id])->value("organization_name");
        }
        // 推荐人id
        $recommender_id = UserRecommender::where(['user_id' => $user_id])->value("recommender_id");
        if (!empty($recommender_id)) {
            // 获取推荐人信息
            $list = User::getOneUser([['id', $recommender_id]]);
            $data['recommender_name'] = $list->UserInfo->nickname;
        }
        // 渲染页面
        return view('Fansmanage/User/user_list_edit', ['data' => $data, 'userInfo' => $userInfo]);
    }

    /**
     * 粉丝用户管理编辑功能提交,修改手机号码和QQ 号码
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function user_list_edit_check()
    {
        // 中间件参数 集合
        $this->getRequestInfo();
        // qq号
        $qq = request()->qq;
        // 手机号
        $mobile = request()->mobile;
        // 用户id
        $user_id = request()->user_id;
        // 微信昵称
        $nickname = request()->nickname;
        // 判断手机号码是否存在
        $re = FansmanageUser::checkRowExists([['mobile', $mobile], ['user_id', '<>', $user_id]]);
        if ($re == 'true') {
            return response()->json(['data' => '手机号已存在', 'status' => '0']);
        }
        // 事务处理
        DB::beginTransaction();
        try {
            // 修改粉丝的 手机号码
            FansmanageUser::editStoreUser(['user_id' => $user_id], ['mobile' => $mobile]);
            // 修改粉丝的 QQ号码
            UserInfo::editUserInfo(['user_id' => $user_id], ['qq' => $qq]);
            // 保存操作记录
            if ($this->admin_data['is_super'] != 2) {
                $this->insertOperationLog(3, '修改资料：' . $nickname);
            }
            DB::commit();
        } catch (\Exception $e) {
            // 事件回滚
            DB::rollBack();
            return $this->getResponseMsg(0, '修改资料失败！');
        }
        return $this->getResponseMsg(1, '修改资料成功！');
    }


    /**
     * 粉丝用户管理冻结功能显示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function user_list_lock()
    {
        // 会员标签id
        $user_id = request()->id;
        // 冻结或者解锁, 1 为 冻结, 0 为解冻
        // 这里的用途只是渲染 语句而已
        $status = request()->status;
        // 微信昵称
        $nickname = UserInfo::getPluck([['user_id', $user_id]], 'nickname')->first();
        // 渲染页面
        return view('Fansmanage/User/user_list_lock', ['user_id' => $user_id, 'nickname' => $nickname, 'status' => $status]);
    }

    /**
     * 粉丝用户管理 冻结/解冻 功能提交
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function user_list_lock_check()
    {
        // 中间件参数 集合
        $this->getRequestInfo();
        // 会员标签id
        $user_id = request()->user_id;
        // 会员标签id
        $nickname = request()->nickname;
        // 冻结或者解冻判断
        $status = request()->status;
        // 事务处理
        DB::beginTransaction();
        try {
            // 修改账号状态
            $status = $status == 1 ? 1 : 0;
            $log_string = $status == 1 ? "解冻了：{$nickname}" : "冻结了：{$nickname}";

            // 修改账户信息
            FansmanageUser::editStoreUser(['user_id' => $user_id], ['status' => $status]);
            // 保存操作记录
            if ($this->admin_data['is_super'] != 2) {
                $this->insertOperationLog(3, $log_string);
            }

            DB::commit();
        } catch (\Exception $e) {
            // 事件回滚
            DB::rollBack();
            return response()->json(['data' => '操作失败！', 'status' => '0']);
        }
        return response()->json(['data' => '操作成功！', 'status' => '1']);
    }


    /**
     * 粉丝用户管理 粉丝钱包
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function user_list_wallet()
    {
        // 会员标签id
        $user_id = request()->id;
        // 冻结或者解锁
        $status = request()->status;
        // 微信昵称
        $nickname = UserInfo::where(["user_id"=>$user_id])->value("nickname");
        // 渲染页面
        return view('Fansmanage/User/user_list_wallet', ['user_id' => $user_id, 'nickname' => $nickname, 'status' => $status]);
    }

    /**
     * 粉丝用户管理
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store_label_add_check()
    {
        // 中间件参数 集合
        $this->getRequestInfo();
        // 会员标签id
        $label_id = request()->label_id;
        // 用户id
        $user_id = request()->user_id;
        // 组织id
        $organization_id = $this->admin_data['organization_id'];
        // 微信昵称
        $nickname = request()->nickname;
        // 事务处理
        DB::beginTransaction();
        try {
            $oneData = UserLabel::getOneUserLabel([['user_id', $user_id], ['organization_id', $organization_id]]);//查询粉丝标签关联表有没有数据
            if (!empty($oneData)) {
                // 当粉丝标签关联表里标签id为0时 不执行
                if ($oneData->label_id != 0) {
                    // 减少原粉丝标签的人数
                    $label_number = Label::getPluck([['id', $oneData->label_id]], 'label_number')->first();//获取原粉丝标签的人数
                    $number = $label_number - 1;
                    Label::editLabel([['id', $oneData->label_id]], ['label_number' => $number]);//修改粉丝标签的人数
                }
                // 选择无标签的时候 不执行
                if ($label_id != 0) {
                    // 增加现有的粉丝标签人数
                    // 获取粉丝标签的人数
                    $add_label_number = Label::getPluck([['id', $label_id]], 'label_number')->first();
                    $add_number = $add_label_number + 1;
                    // 修改粉丝标签的人数
                    Label::editLabel([['id', $label_id]], ['label_number' => $add_number]);
                }
                // 修改粉丝标签关联表Label_id
                UserLabel::editUserLabel([['id', $oneData->id]], ['label_id' => $label_id]);

            } else {
                // 粉丝与标签关系表
                UserLabel::addUserLabel(['label_id' => $label_id, 'user_id' => $user_id, 'organization_id' => $organization_id]);
                // 获取粉丝标签的人数
                $label_number = Label::getPluck([['id', $label_id]], 'label_number')->first();
                $number = $label_number + 1;
                // 修改粉丝标签的人数
                Label::editLabel([['id', $label_id]], ['label_number' => $number]);
            }
            $dataUser = FansmanageUser::getOneFansmanageUser([['id', $user_id]]);
            $tag_id = Label::getPluck([['id', $label_id]], 'wechat_id')->first();
            // 选择无标签的时候 不执行
            if ($label_id != 0) {
                $data = [
                    'openid_list' => [$dataUser['open_id']],
                    'tagid' => $tag_id
                ];
                // 刷新并获取授权令牌
                $auth_info = \Wechat::refresh_authorization_info($this->admin_data['organization_id']);
                $re = \Wechat::add_fans_tag_label($auth_info['authorizer_access_token'], $data);
                $re = json_decode($re, true);
                if ($re['errmsg'] != 'ok') {
                    return response()->json(['data' => '操作失败！', 'status' => '0']);
                }
            }
            if ($this->admin_data['is_super'] != 2) {
                // 保存操作记录
                OperationLog::addOperationLog('3', $this->admin_data['organization_id'], $this->admin_data['id'], $this->route_name, '修改粉丝标签：' . $nickname);
            }
            DB::commit();
        } catch (\Exception $e) {
            // 事件回滚
            DB::rollBack();
            return response()->json(['data' => '操作失败！', 'status' => '0']);
        }
        return response()->json(['data' => '操作成功！', 'status' => '1']);
    }


    // +----------------------------------------------------------------------
    // | End - 粉丝用户管理
    // +----------------------------------------------------------------------

    // +----------------------------------------------------------------------
    // | Start - 粉丝足迹
    // +----------------------------------------------------------------------
    //粉丝用户足迹
    public function user_timeline()
    {
        // 中间件参数 集合
        $this->getRequestInfo();

        $fansmanage_id = $this->admin_data['organization_id'];//组织id
        $list = FansmanageUserLog::getPaginage([['fansmanage_id', $fansmanage_id]], '5', 'id');
        foreach ($list as $key => $value) {
            $list[$key]['nickname'] = UserInfo::getPluck([['user_id', $value->user_id]], 'nickname')->first();//微信昵称
        }
        return view('Fansmanage/User/user_timeline', ['list' => $list, 'admin_data' => $this->admin_data, 'route_name' => $this->route_name, 'menu_data' => $this->menu_data, 'son_menu_data' => $this->son_menu_data]);
    }
    // +----------------------------------------------------------------------
    // | End - 粉丝用户管理
    // +----------------------------------------------------------------------

    // +----------------------------------------------------------------------
    // | Start - 公共方法
    // +----------------------------------------------------------------------
    protected $admin_data = [];
    protected $menu_data = [];
    protected $son_menu_data = [];
    protected $route_name = '';

    /**
     * 请求参数的获取
     */
    public function getRequestInfo()
    {
        // 中间件产生的 管理员数据参数
        $this->admin_data = request()->get('admin_data');
        // 中间件产生的 菜单参数
        $this->menu_data = request()->get('menu_data');
        // 中间件产生的 子菜单参数
        $this->son_menu_data = request()->get('son_menu_data');
        // 获取当前的页面路由
        $this->route_name = request()->path();
    }

    /**
     * 返回消息提示
     * @param string $status 状态码
     * @param string $data 状态信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function getResponseMsg($status, $data)
    {
        $responseData = [
            "status" => $status,
            "data" => $data
        ];
        return response()->json($responseData);
    }

    /**
     * 添加操作记录
     * @param $program_id
     * @param $info
     * @param $organization_id
     * @param $id
     */
    public function insertOperationLog($program_id, $info, $organization_id = '', $id = '')
    {
        $this->getRequestInfo();
        // 判断是否有传送组织id
        $organization_id = !empty($organization_id) ? $organization_id : $this->admin_data["organization_id"];
        // 判断是否有传送id
        $id = !empty($id) ? $id : $this->admin_data['id'];

        OperationLog::addOperationLog($program_id, $organization_id, $id, $this->route_name, $info);
    }
    // +----------------------------------------------------------------------
    // | End - 公共方法
    // +----------------------------------------------------------------------
}