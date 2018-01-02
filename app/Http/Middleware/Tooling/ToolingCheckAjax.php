<?php
/**
 * 检测中间件囖
 */
namespace App\Http\Middleware\Tooling;
use Closure;
use Session;
use Illuminate\Support\Facades\Redis;

class ToolingCheckAjax {
    public function handle($request,Closure $next){
        $route_name = $request->path();//获取当前的页面路由
        switch($route_name){
            case "tooling/ajax/checklogin"://检测登陆数据提交
                $re = $this->checkLoginPost($request);
                return self::format_response($re,$next);
                break;

            //检测是否登陆且是否登陆且是否超级管理员
            case "tooling/ajax/account_edit":
                $re = $this->checkLoginAndSuper($request);
                return self::format_response($re,$next);
                break;

            //检测添加账号提交数据是否正确
            case "tooling/ajax/account_add_check":
                $re = $this->checkLoginAndSuperAndAccountAdd($request);
                return self::format_response($re,$next);
                break;

            //检测编辑账号提交数据是否正确
            case "tooling/ajax/account_edit_check":
                $re = $this->checkLoginAndSuperAndAccountAdd($request);
                return self::format_response($re,$next);
                break;

            //检测冻结账号提交数据是否正确
            case "tooling/ajax/account_lock":
                $re = $this->checkLoginAndSuperAndAccountLock($request);
                return self::format_response($re,$next);
                break;

            //检测冻结账号提交数据是否正确
            case "tooling/ajax/password_edit_check":
                $re = $this->checkLoginAndPasswordEdit($request);
                return self::format_response($re,$next);
                break;

            //检测添加节点提交数据是否正确
            case "tooling/ajax/node_add_check":
                $re = $this->checkLoginAndNodeAdd($request);
                return self::format_response($re,$next);
                break;

            //检测编辑节点提交数据是否正确
            case "tooling/ajax/node_edit_check":
                $re = $this->checkLoginAndNodeEdit($request);
                return self::format_response($re,$next);
                break;

            //检测添加模块提交数据是否正确
            case "tooling/ajax/module_add_check":
                $re = $this->checkLoginAndModuleAdd($request);
                return self::format_response($re,$next);
                break;

            //检测编辑模块提交数据是否正确
            case "tooling/ajax/module_edit_check":
                $re = $this->checkLoginAndModuleEdit($request);
                return self::format_response($re,$next);
                break;

            //检测添加程序提交数据是否正确
            case "tooling/ajax/program_add_check":
                $re = $this->checkLoginAndProgramAdd($request);
                return self::format_response($re,$next);
                break;

            //检测编辑程序提交数据是否正确
            case "tooling/ajax/program_edit_check":
                $re = $this->checkLoginAndProgramEdit($request);
                return self::format_response($re,$next);
                break;

            //检测添加菜单提交数据是否正确
            case "tooling/ajax/menu_add_check":
                $re = $this->checkLoginAndMenuAdd($request);
                return self::format_response($re,$next);
                break;
            //检测修改菜单数据是否合法
            case "tooling/ajax/menu_edit_check":
                $re = $this->checkLoginAndMenuEdit($request);
                return self::format_response($re,$next);
                break;
            //检测添加套餐数据是否合法
            case "tooling/ajax/package_add_check":
                $re = $this->checkLoginAndPackageAdd($request);
                return self::format_response($re,$next);
                break;
            //检测修改套餐数据是否合法
            case "tooling/ajax/package_edit_check":
                $re = $this->checkLoginAndPackageEdit($request);
                return self::format_response($re,$next);
                break;
            //检测软删除节点数据是否合法
            case "tooling/ajax/node_delete":
                $re = $this->checkLoginAndNodeDelete($request);
                return self::format_response($re,$next);
                break;
            //检测硬删除节点数据是否合法
            case "tooling/ajax/node_remove":
                $re = $this->checkLoginAndNodeRemove($request);
                return self::format_response($re,$next);
                break;
            //检测软删除模块数据是否合法
            case "tooling/ajax/module_delete":
                $re = $this->checkLoginAndModuleDelete($request);
                return self::format_response($re,$next);
                break;
            //检测硬删除模块数据是否合法
            case "tooling/ajax/module_remove":
                $re = $this->checkLoginAndModuleRemove($request);
                return self::format_response($re,$next);
                break;

            //仅检测是否登陆
            case "tooling/ajax/node_edit"://是否允许弹出修改节点页面
            case "tooling/ajax/module_edit"://是否允许弹出修改程序页面
            case "tooling/ajax/program_parents_node"://获取上级程序ID
            case "tooling/ajax/program_edit"://是否允许弹出修改程序页面
            case "tooling/ajax/menu_add"://是否允许弹出添加页面
            case "tooling/ajax/menu_edit"://是否允许弹出修改菜单页面
            case "tooling/ajax/package_edit";//是否允许弹出修改套餐的页面
                $re = $this->checkIsLogin($request);
                return self::format_response($re,$next);
                break;

        }
    }

    /**********************组合检测************************/
    //添加账号检测是否登陆 是否超级管理员 输入数据是否正确
    public function checkLoginAndSuperAndAccountEdit($request){
        $re = $this->checkLoginAndSuper($request);//判断是否登陆
        if($re['status']=='0'){
            return $re;
        }else{
            $re2 = $this->checkAccountEdit($re['response']);//判断是否超级管理员
            if($re2['status']=='0'){
                return $re2;
            }else{
                return self::res(1,$re2['response']);
            }
        }
    }

    //编辑账号检测是否登陆 是否超级管理员 输入数据是否正确
    public function checkLoginAndSuperAndAccountAdd($request){
        $re = $this->checkLoginAndSuper($request);//判断是否登陆
        if($re['status']=='0'){
            return $re;
        }else{
            $re2 = $this->checkAccountAdd($re['response']);//判断是否超级管理员
            if($re2['status']=='0'){
                return $re2;
            }else{
                return self::res(1,$re2['response']);
            }
        }
    }

    //冻结账号检测是否登陆 是否超级管理员 输入数据是否正确
    public function checkLoginAndSuperAndAccountLock($request){
        $re = $this->checkLoginAndSuper($request);//判断是否登陆
        if($re['status']=='0'){
            return $re;
        }else{
            $re2 = $this->checkAccountLock($re['response']);//判断是否超级管理员
            if($re2['status']=='0'){
                return $re2;
            }else{
                return self::res(1,$re2['response']);
            }
        }
    }

    //检测是否登陆且是否超级管理员
    public function checkLoginAndSuper($request){
        $re = $this->checkIsLogin($request);//判断是否登陆
        if($re['status']=='0'){
            return $re;
        }else{
            $re2 = $this->checkIsSuper($re['response']);//判断是否超级管理员
            if($re2['status']=='0'){
                return $re2;
            }else{
                return self::res(1,$re2['response']);
            }
        }
    }
    //修改密码 检测是否登陆 输入数据是否正确
    public function checkLoginAndPasswordEdit($request){
        $re = $this->checkIsLogin($request);//判断是否登陆
        if($re['status']=='0'){
            return $re;
        }else{
            $re2 = $this->checkPasswordEdit($re['response']);//判断修改密码提交数据
            if($re2['status']=='0'){
                return $re2;
            }else{
                return self::res(1,$re2['response']);
            }
        }
    }

    //添加节点 检测是否登陆 输入数据是否正确
    public function checkLoginAndNodeAdd($request){
        $re = $this->checkIsLogin($request);//判断是否登陆
        if($re['status']=='0'){
            return $re;
        }else{
            $re2 = $this->checkNodeAdd($re['response']);//判断节点添加数据
            if($re2['status']=='0'){
                return $re2;
            }else{
                return self::res(1,$re2['response']);
            }
        }
    }

    //编辑节点 检测是否登陆 输入数据是否正确
    public function checkLoginAndNodeEdit($request){
        $re = $this->checkIsLogin($request);//判断是否登陆
        if($re['status']=='0'){
            return $re;
        }else{
            $re2 = $this->checkNodeEdit($re['response']);//判断节点修改数据
            if($re2['status']=='0'){
                return $re2;
            }else{
                return self::res(1,$re2['response']);
            }
        }
    }

    //添加模块 检测是否登陆 输入数据是否正确
    public function checkLoginAndModuleAdd($request){
        $re = $this->checkIsLogin($request);//判断是否登陆
        if($re['status']=='0'){
            return $re;
        }else{
            $re2 = $this->checkModuleAdd($re['response']);//判断模块添加数据
            if($re2['status']=='0'){
                return $re2;
            }else{
                return self::res(1,$re2['response']);
            }
        }
    }

    //编辑模块 检测是否登陆 输入数据是否正确
    public function checkLoginAndModuleEdit($request){
        $re = $this->checkIsLogin($request);//判断是否登陆
        if($re['status']=='0'){
            return $re;
        }else{
            $re2 = $this->checkModuleEdit($re['response']);//判断模块修改数据
            if($re2['status']=='0'){
                return $re2;
            }else{
                return self::res(1,$re2['response']);
            }
        }
    }

    //添加程序 检测是否登陆 输入数据是否正确
    public function checkLoginAndProgramAdd($request){
        $re = $this->checkIsLogin($request);//判断是否登陆
        if($re['status']=='0'){
            return $re;
        }else{
            $re2 = $this->checkProgramAdd($re['response']);//判断程序添加数据
            if($re2['status']=='0'){
                return $re2;
            }else{
                return self::res(1,$re2['response']);
            }
        }
    }

    //编辑程序 检测是否登陆 输入数据是否正确
    public function checkLoginAndProgramEdit($request){
        $re = $this->checkIsLogin($request);//判断是否登陆
        if($re['status']=='0'){
            return $re;
        }else{
            $re2 = $this->checkProgramEdit($re['response']);//判断模块修改数据
            if($re2['status']=='0'){
                return $re2;
            }else{
                return self::res(1,$re2['response']);
            }
        }
    }

    //添加程序菜单 检测是否登陆 输入数据是否正确
    public function checkLoginAndMenuAdd($request){
        $re = $this->checkIsLogin($request);//判断是否登陆
        if($re['status']=='0'){
            return $re;
        }else{
            $re2 = $this->checkMenuAdd($re['response']);//判断菜单添加数据
            if($re2['status']=='0'){
                return $re2;
            }else{
                return self::res(1,$re2['response']);
            }
        }
    }
    //编辑程序菜单 检测是否登陆 输入数据是否正确
    public function checkLoginAndMenuEdit($request){
        $re = $this->checkIsLogin($request);//判断是否登陆
        if($re['status']=='0'){
            return $re;
        }else{
            $re2 = $this->checkMenuEdit($re['response']);//判断菜单添加数据
            if($re2['status']=='0'){
                return $re2;
            }else{
                return self::res(1,$re2['response']);
            }
        }
    }

    //添加程序套餐 检测是否登陆 输入数据是否正确
    public function checkLoginAndPackageAdd($request){
        $re = $this->checkIsLogin($request);//判断是否登陆
        if($re['status']=='0'){
            return $re;
        }else{
            $re2 = $this->checkPackageAdd($re['response']);//判断菜单添加数据
            if($re2['status']=='0'){
                return $re2;
            }else{
                return self::res(1,$re2['response']);
            }
        }
    }
    //编辑程序套餐 检测是否登陆 输入数据是否正确
    public function checkLoginAndPackageEdit($request){
        $re = $this->checkIsLogin($request);//判断是否登陆
        if($re['status']=='0'){
            return $re;
        }else{
            $re2 = $this->checkPackageEdit($re['response']);//判断菜单添加数据
            if($re2['status']=='0'){
                return $re2;
            }else{
                return self::res(1,$re2['response']);
            }
        }
    }
    //软删除节点 检测是否登陆 输入数据是否正确
    public function checkLoginAndNodeDelete($request){
        $re = $this->checkIsLogin($request);//判断是否登陆
        if($re['status']=='0'){
            return $re;
        }else{
            $re2 = $this->checkNodeDelete($re['response']);//判断菜单添加数据
            if($re2['status']=='0'){
                return $re2;
            }else{
                return self::res(1,$re2['response']);
            }
        }
    }
    //硬删除节点 检测是否登陆 输入数据是否正确
    public function checkLoginAndNodeRemove($request){
        $re = $this->checkIsLogin($request);//判断是否登陆
        if($re['status']=='0'){
            return $re;
        }else{
            $re2 = $this->checkNodeRemove($re['response']);//判断菜单添加数据
            if($re2['status']=='0'){
                return $re2;
            }else{
                return self::res(1,$re2['response']);
            }
        }
    }
    //软删除模块 检测是否登陆 输入数据是否正确
    public function checkLoginAndModuleDelete($request){
        $re = $this->checkIsLogin($request);//判断是否登陆
        if($re['status']=='0'){
            return $re;
        }else{
            $re2 = $this->checkNodeRemove($re['response']);//判断菜单添加数据
            if($re2['status']=='0'){
                return $re2;
            }else{
                return self::res(1,$re2['response']);
            }
        }
    }
    //硬删除模块 检测是否登陆 输入数据是否正确
    public function checkLoginAndModuleRemove($request){
        $re = $this->checkIsLogin($request);//判断是否登陆
        if($re['status']=='0'){
            return $re;
        }else{
            $re2 = $this->checkNodeRemove($re['response']);//判断菜单添加数据
            if($re2['status']=='0'){
                return $re2;
            }else{
                return self::res(1,$re2['response']);
            }
        }
    }
    /**********************单项检测************************/
    //检测硬删除模块数据提交
    public function checkModuleRemove($request){
        if(empty($request->input('id'))){
            return self::res(0,response()->json(['data' => '错误的数据传输', 'status' => '0']));
        }
        return self::res(1,$request);
    }
    //检测软删除模块数据提交
    public function checkModuleDelete($request){
        if(empty($request->input('id'))){
            return self::res(0,response()->json(['data' => '错误的数据传输', 'status' => '0']));
        }
        return self::res(1,$request);
    }
    //检测硬删除节点数据提交
    public function checkNodeRemove($request){
        if(empty($request->input('id'))){
            return self::res(0,response()->json(['data' => '错误的数据传输', 'status' => '0']));
        }
        return self::res(1,$request);
    }
    //检测软删除节点数据提交
    public function checkNodeDelete($request){
        if(empty($request->input('id'))){
            return self::res(0,response()->json(['data' => '错误的数据传输', 'status' => '0']));
        }
        return self::res(1,$request);
    }
    //检测编辑程序套餐数据提交
    public function checkPackageEdit($request){
        if(empty($request->input('id'))){
            return self::res(0,response()->json(['data' => '错误的数据传输', 'status' => '0']));
        }
        if(empty($request->input('package_name'))){
            return self::res(0,response()->json(['data' => '请输入程序套餐名称', 'status' => '0']));
        }
        if (empty($request->input('package_price'))) {
            return self::res(0, response()->json(['data' => '请输入程序套餐价格', 'status' => '0']));
        }
        if (empty($request->input('program_ids'))) {
            return self::res(0, response()->json(['data' => '请选择套餐程序', 'status' => '0']));
        }
        return self::res(1,$request);
    }
    //检测添加程序套餐数据提交
    public function checkPackageAdd($request){
        if(empty($request->input('package_name'))){
            return self::res(0,response()->json(['data' => '请输入程序套餐名称', 'status' => '0']));
        }
        if (empty($request->input('package_price'))) {
            return self::res(0, response()->json(['data' => '请输入程序套餐价格', 'status' => '0']));
        }
        if (empty($request->input('program_ids'))) {
            return self::res(0, response()->json(['data' => '请选择套餐程序', 'status' => '0']));
        }
        return self::res(1,$request);
    }

    //检测编辑菜单数据提交
    public function checkMenuEdit($request){
        if(empty($request->input('id'))){
            return self::res(0,response()->json(['data' => '数据传输错误  ', 'status' => '0']));
        }
        if(empty($request->input('program_id'))){
            return self::res(0,response()->json(['data' => '数据传输错误  ', 'status' => '0']));
        }
        if (empty($request->input('menu_name'))) {
            return self::res(0, response()->json(['data' => '请输入菜单名称', 'status' => '0']));
        }
        if($request->input('is_root')=='1') {//如果是跟节点的话一定要输入路由链接
            if (empty($request->input('menu_route'))) {
                return self::res(0, response()->json(['data' => '请输入要跳转的路由链接', 'status' => '0']));
            }
        }
        return self::res(1,$request);
    }
    //检测添加菜单数据提交
    public function checkMenuAdd($request){
        if(empty($request->input('program_id'))){
            return self::res(0,response()->json(['data' => '数据传输错误  ', 'status' => '0']));
        }
        if (empty($request->input('menu_name'))) {
            return self::res(0, response()->json(['data' => '请输入菜单名称', 'status' => '0']));
        }
        if($request->input('is_root')=='1') {//如果是跟节点的话一定要输入路由链接
            if (empty($request->input('menu_route'))) {
                return self::res(0, response()->json(['data' => '请输入要跳转的路由链接', 'status' => '0']));
            }
        }
        return self::res(1,$request);
    }

    //检测提交节点数据提交
    public function checkNodeAdd($request){
        if(empty($request->input('node_name'))){
            return self::res(0,response()->json(['data' => '请输入节点名称', 'status' => '0']));
        }
        if(empty($request->input('route_name'))){
            return self::res(0,response()->json(['data' => '请输入路由名称', 'status' => '0']));
        }
        return self::res(1,$request);
    }

    //检测编辑节点数据提交
    public function checkNodeEdit($request){
        if(empty($request->input('id'))){
            return self::res(0,response()->json(['data' => '数据传输错误', 'status' => '0']));
        }
        if(empty($request->input('node_name'))){
            return self::res(0,response()->json(['data' => '请输入节点名称', 'status' => '0']));
        }
        if(empty($request->input('route_name'))){
            return self::res(0,response()->json(['data' => '请输入路由名称', 'status' => '0']));
        }
        return self::res(1,$request);
    }

    //检测添加模块数据提交
    public function checkModuleAdd($request){
        if(empty($request->input('module_name'))){
            return self::res(0,response()->json(['data' => '请输入模块名称', 'status' => '0']));
        }
        if(empty($request->input('nodes'))){
            return self::res(0,response()->json(['data' => '请选择该模块的功能节点到右边选框', 'status' => '0']));
        }
        return self::res(1,$request);
    }

    //检测编辑模块数据提交
    public function checkModuleEdit($request){
        if(empty($request->input('id'))){
            return self::res(0,response()->json(['data' => '数据传输错误', 'status' => '0']));
        }
        if(empty($request->input('module_name'))){
            return self::res(0,response()->json(['data' => '请输入模块名称', 'status' => '0']));
        }
        if(empty($request->input('nodes'))){
            return self::res(0,response()->json(['data' => '请选择该模块的功能节点到右边选框', 'status' => '0']));
        }
        return self::res(1,$request);
    }

    //检测添加程序数据提交
    public function checkProgramAdd($request){
        if(empty($request->input('program_name'))){
            return self::res(0,response()->json(['data' => '请输入程序名称', 'status' => '0']));
        }
        if(empty($request->input('module_node_ids'))){
            return self::res(0,response()->json(['data' => '请勾选功能模块', 'status' => '0']));
        }
        return self::res(1,$request);
    }

    //检测编辑程序数据提交
    public function checkProgramEdit($request){
        if(empty($request->input('id'))){
            return self::res(0,response()->json(['data' => '数据传输错误', 'status' => '0']));
        }
        if(empty($request->input('program_name'))){
            return self::res(0,response()->json(['data' => '请输入程序名称', 'status' => '0']));
        }
        if(empty($request->input('module_node_ids'))){
            return self::res(0,response()->json(['data' => '请勾选功能模块', 'status' => '0']));
        }
        return self::res(1,$request);
    }

    //检测密码修改数据提交
    public function checkPasswordEdit($request){
        if(empty($request->input('oldpassword'))){
            return  self::res(0,response()->json(['data' => '请输入原登陆密码', 'status' => '0']));
        }
        if(empty($request->input('password'))){
            return  self::res(0,response()->json(['data' => '请输入新登陆密码', 'status' => '0']));
        }
        if(empty($request->input('repassword'))){
            return  self::res(0,response()->json(['data' => '请再次输入新登陆密码', 'status' => '0']));
        }
        if($request->input('password')==$request->input('oldpassword')){
            return  self::res(0,response()->json(['data' => '新旧密码不能相同', 'status' => '0']));
        }
        if($request->input('password')!=$request->input('repassword')){
            return  self::res(0,response()->json(['data' => '两次输入的新密码不一致', 'status' => '0']));
        }
        return self::res(1,$request);
    }

    //检测冻结账号数据提交
    public function checkAccountLock($request){
        if(empty($request->input('id'))){
            return self::res(0,esponse()->json(['data' => '数据传输错误', 'status' => '0']));
        }
        if(empty($request->input('account'))){
            return self::res(0,response()->json(['data' => '数据传输错误', 'status' => '0']));
        }
        return self::res(1,$request);
    }

    //检测编辑账号数据提交
    public function checkAccountEdit($request){
        if(empty($request->input('id'))){
            return self::res(0, response()->json(['data' => '数据传输错误', 'status' => '0']));
        }
        if(empty($request->input('password'))){
            return self::res(0,response()->json(['data' => '请输入登陆密码', 'status' => '0']));
        }
        if(empty($request->input('repassword'))){
            return self::res(0,response()->json(['data' => '请再次输入登陆密码', 'status' => '0']));
        }
        if($request->input('password')!=$request->input('repassword')){
            return self::res(0,response()->json(['data' => '两次输入密码不一致', 'status' => '0']));
        }
        return self::res(1,$request);
    }

    //检测账号添加数据提交
    public function checkAccountAdd($request){
        if(empty($request->input('account'))){
            return self::res(0,response()->json(['data' => '请输入登陆账号', 'status' => '0']));
        }
        if(empty($request->input('password'))){
            return self::res(0,response()->json(['data' => '请输入登陆密码', 'status' => '0']));
        }
        if(empty($request->input('repassword'))){
            return self::res(0,response()->json(['data' => '请再次输入登陆密码', 'status' => '0']));
        }
        if($request->input('password')!=$request->input('repassword')){
            return self::res(0,response()->json(['data' => '两次输入密码不一致', 'status' => '0']));
        }
        return self::res(1,$request);
    }

    //检测是否超级管理员
    public function checkIsSuper($request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        if($admin_data['admin_is_super']!=1){
            return self::res(0,response()->json(['data' => '您没有该功能的权限！', 'status' => '-1']));
        }else{
            return self::res(1,$request);
        }
    }

    //检测是否登陆
    public function checkIsLogin($request){
        $sess_key = Session::get('zerone_tooling_account_id');
        //如果为空返回登陆失效
        if(empty($sess_key)) {
            return self::res(0,response()->json(['data' => '登陆状态失效', 'status' => '-1']));
        }else{
            $sess_key = Session::get('zerone_tooling_account_id');//获取管理员ID
            $sess_key = decrypt($sess_key);//解密管理员ID
            Redis::connect('zeo');//连接到我的缓存服务器
            $admin_data = Redis::get('tooling_system_admin_data_'.$sess_key);//获取管理员信息
            $admin_data = unserialize($admin_data);//解序列我的信息
            $request->attributes->add(['admin_data'=>$admin_data]);//添加参数
            //把参数传递到下一个中间件
            return self::res(1,$request);
        }
    }

    //检测登陆提交数据
    public function checkLoginPost($request){
        if(empty($request->input('username'))){
            return self::res(0,response()->json(['data' => '请输入用户名', 'status' => '0']));
        }
        if(empty($request->input('password'))){
            return self::res(0,response()->json(['data' => '请输入登录密码', 'status' => '0']));
        }
        if(empty($request->input('captcha'))){
            return self::res(0,response()->json(['data' => '请输入验证码', 'status' => '0']));
        }
        if (Session::get('tooling_system_captcha') == $request->input('captcha')) {
            //把参数传递到下一个程序
            return self::res(1,$request);
        } else {
            //用户输入验证码错误
            return self::res(0,response()->json(['data' => '验证码错误', 'status' => '0']));
        }
    }
    //工厂方法返回结果
    public static function res($status,$response){
        return ['status'=>$status,'response'=>$response];
    }
    //格式化返回值
    public static function format_response($re,Closure $next){
        if($re['status']=='0'){
            return $re['response'];
        }else{
            return $next($re['response']);
        }
    }
}
?>