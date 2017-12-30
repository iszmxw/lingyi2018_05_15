<?php
/**
 * 系统管理
 */
namespace App\Http\Controllers\Tooling;
use App\Models\Module;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Illuminate\Support\Facades\DB;
use App\Models\ToolingOperationLog;
use App\Models\Program;
use App\Models\ProgramModuleNode;
use App\Models\ProgramMenu;
use App\Models\PackageProgram;
use App\Models\Package;

class ProgramController extends Controller{
    public function program_add(Request $request)
    {
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $plist = Program::getList([[ 'complete_id','0' ]],0,'id');
        return view('Tooling/Program/program_add',['plist'=>$plist,'admin_data'=>$admin_data,'route_name'=>$route_name,'action_name'=>'program']);
    }

    //Ajax获取上级节点
    public function program_parents_node(Request $request){
        $pid = $request->input('pid');
        $editid = $request->input('editid');
        if(empty($pid) || $pid=='0'){//没有主程序时
            $module_list = Module::getListSimple([],0,'id');
        }else{//有主程序时
            $module_list = Module::getListProgram($pid,[],0,'id');
        }
        $selected_node = [];
        $selected_module = [];
        if(!empty($editid)) {
            $list = ProgramModuleNode::getList([[ 'program_id',$editid ]],0,'id');
            foreach ($list as $key => $val) {
                if(!in_array($val->module_id,$selected_module)){
                    $selected_module[] = $val->module_id;
                }
                $selected_node[] = $val->module_id . '_' . $val->node_id;
            }
        }
        return view('Tooling/Program/program_parents_node',['pid'=>$pid,'module_list'=>$module_list,'selected_node'=>$selected_node,'selected_module'=>$selected_module]);
    }
    //检测添加数据
    public function program_add_check(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由

        $program_name = $request->input('program_name');//程序名称
        $complete_id = $request->input('complete_id');//上级程序
        $is_asset = empty($request->input('is_asset'))?'0':'1';//是否资产程序
        $module_node_ids = $request->input('module_node_ids');//节点数组

        if(Program::checkRowExists([[ 'program_name',$program_name ]])){
            return response()->json(['data' => '程序名称已经存在', 'status' => '0']);
        }else{
            DB::beginTransaction();
            try{
                $program_id = Program::addProgram(['program_name'=>$program_name,'complete_id'=>$complete_id,'is_asset'=>$is_asset]);
                //循环节点生成多条数据
                foreach($module_node_ids as $key=>$val){
                    $arr = explode('_',$val);
                    $module_id = $arr[0];//功能模块ID
                    $node_id = $arr[1];//功能节点ID
                    ProgramModuleNode::addProgramModuleNode(['program_id'=>$program_id,'module_id'=>$module_id,'node_id'=>$node_id]);
                }
                ToolingOperationLog::addOperationLog($admin_data['admin_id'],$route_name,'添加了程序'.$program_name);//保存操作记录
                DB::commit();//提交事务
            }catch (\Exception $e) {
                DB::rollBack();//事件回滚
                return response()->json(['data' => '添加程序失败，请检查', 'status' => '0']);
            }
            return response()->json(['data' => '添加程序成功', 'status' => '1']);
        }
    }
    //程序数据列表
    public function program_list(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $program_name = $request->input('program_name');
        $search_data['program_name'] = $program_name;
        $list = Program::getPaginage([[ 'program_name','like','%'.$program_name.'%' ]],15,'id');
        $module_list = [];//功能模块列表
        $pname = [];//上级程序名称列表
        foreach($list as $key=>$val){
            $program_id = $val->id;
            $module_list[$val->id] =Module::getListProgram($program_id,[],0,'id');
            $ppname = Program::getPluck([['id',$val->complete_id]],'program_name')->toArray();//获取用户名称
            if(empty($ppname)){
                $pname[$val->id] = '独立主程序';
            }else{
                $pname[$val->id] = $ppname[0];
            }
        }
        return view('Tooling/Program/program_list',['list'=>$list,'search_data'=>$search_data,'module_list'=>$module_list,'pname'=>$pname,'admin_data'=>$admin_data,'route_name'=>$route_name,'action_name'=>'program']);
    }
    //获取编辑程序
    public function program_edit(Request $request){
        $id = $request->input('id');
        $info = Program::find($id);
        $plist = Program::getList([[ 'complete_id','0' ]],0,'id');
        return view('Tooling/Program/program_edit',['info'=>$info,'plist'=>$plist]);
    }
    //提交编辑程序数据
    public function program_edit_check(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $id = $request->input('id');
        $program_name = $request->input('program_name');//程序名称
        $complete_id = $request->input('complete_id');//上级程序
        $is_asset = empty($request->input('is_asset'))?'0':'1';//是否资产程序
        $module_node_ids = $request->input('module_node_ids');//节点数组

        if(Program::checkRowExists([[ 'program_name',$program_name],['id','!=',$id]])){
            return response()->json(['data' => '程序名称已经存在', 'status' => '0']);
        }else{
            DB::beginTransaction();
            try{
                Program::editProgram([[ 'id',$id ]],['program_name'=>$program_name,'complete_id'=>$complete_id,'is_asset'=>$is_asset]);

                $node_ids = [];
                //循环节点生成多条数据
                foreach($module_node_ids as $key=>$val){
                    $arr = explode('_',$val);
                    $module_id = $arr[0];//功能模块ID
                    $node_id = $arr[1];//功能节点ID
                    $node_ids[] = $node_id;//获取这次的ID
                    $vo = ProgramModuleNode::getOne([['program_id',$id],['module_id',$module_id],['node_id',$node_id]]);//查询是否存在数据
                    if(is_null($vo)) {//不存在生成插入数据
                        ProgramModuleNode::addProgramModuleNode(['program_id' => $id, 'module_id' => $module_id, 'node_id' => $node_id]);
                    }else{
                        continue;
                    }
                    unset($vo);
                }
                //删除数据库中不在这次插入的数据
                ProgramModuleNode::where('program_id',$id)->whereNotIn('node_id',$node_ids)->delete();
                ToolingOperationLog::addOperationLog($admin_data['admin_id'],$route_name,'编辑了程序'.$program_name);//保存操作记录
                DB::commit();//提交事务
            }catch (\Exception $e) {
                DB::rollBack();//事件回滚
                return response()->json(['data' => '编辑程序失败，请检查', 'status' => '0']);
            }
            return response()->json(['data' => '编辑程序成功', 'status' => '1']);
        }
    }
    //获取编辑获取
    public function menu_list(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $id = $request->input('id');
        $info = Program::find($id);
        $list = ProgramMenu::getList([[ 'parent_id',0],['program_id',$id]],0,'id','asc');
        $son_menu = [];
        foreach($list as $key=>$val){
            $son_menu[$val->id] = ProgramMenu::son_menu($val->id);
        }
        return view('Tooling/Program/menu_list',['list'=>$list,'son_menu'=>$son_menu,'info'=>$info,'admin_data'=>$admin_data,'route_name'=>$route_name,'action_name'=>'program']);
    }
    //添加菜单页面
    public function menu_add(Request $request){
        $id = $request->input('program_id');
        $info = Program::find($id);
        $list = ProgramMenu::getList([[ 'parent_id',0],['program_id',$id]],0,'id','asc');
        return view('Tooling/Program/menu_add',['list'=>$list,'info'=>$info,'action_name'=>'program']);
    }
    //添加菜单数据检测
    public function menu_add_check(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $program_id = $request->input('program_id');//所属程序ID
        $parent_id = $request->input('parent_id');//上级菜单ID
        $parent_tree = $parent_id=='0' ? '0' : ProgramMenu::getPluck([[ 'id',$parent_id]],'parent_tree')->toArray()[0].','.$parent_id;
        $menu_name = $request->input('menu_name');//菜单名称
        $is_root = $request->input('is_root');//是否根菜单
        $icon_class = empty($request->input("icon_class"))?'':$request->input("icon_class");//ICON样式名称
        $menu_route = empty($request->input("menu_route"))?'':$request->input("menu_route");//跳转路由
        $menu_routes_bind = empty($request->input("menu_routes_bind"))?'':$request->input("menu_routes_bind");//关联路由字符串，使用逗号分隔

        if(ProgramMenu::checkRowExists([[ 'menu_name',$menu_name ],['parent_id',$parent_id],['program_id',$program_id]])){
            return response()->json(['data' => '菜单组中菜单名称重复', 'status' => '0']);
        }else{
            DB::beginTransaction();
            try{
                ProgramMenu::addMenu(['program_id'=>$program_id,'parent_id'=>$parent_id,'parent_tree'=>$parent_tree,'menu_name'=>$menu_name,'is_root'=>$is_root,'icon_class'=>$icon_class,'menu_route'=>$menu_route,'menu_routes_bind'=>$menu_routes_bind]);
                ToolingOperationLog::addOperationLog($admin_data['admin_id'],$route_name,'添加了菜单'.$menu_name);//保存操作记录
                DB::commit();//提交事务
            }catch (\Exception $e) {
                DB::rollBack();//事件回滚
                return response()->json(['data' => '添加菜单失败，请检查', 'status' => '0']);
            }
            return response()->json(['data' => '添加菜单成功', 'status' => '1']);
        }
    }
    //编辑菜单页面
    public function menu_edit(Request $request){
        $id = $request->input('id');
        $info = ProgramMenu::find($id);
        $list = ProgramMenu::getList([[ 'parent_id',0],['program_id',$id]],0,'id','asc');
        return view('Tooling/Program/menu_edit',['list'=>$list,'info'=>$info,'action_name'=>'program']);
    }
    //编辑菜单数据检测
    public function menu_edit_check(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $id = $request->input('id');
        $program_id = $request->input('program_id');//所属程序ID
        $parent_id = $request->input('parent_id');//上级菜单ID
        $parent_tree = $parent_id=='0' ? '0' : ProgramMenu::getPluck([[ 'id',$parent_id]],'parent_tree')->toArray()[0].','.$parent_id;
        $menu_name = $request->input('menu_name');//菜单名称
        $is_root = $request->input('is_root');//是否根菜单
        $icon_class = empty($request->input("icon_class"))?'':$request->input("icon_class");//ICON样式名称
        $menu_route = empty($request->input("menu_route"))?'':$request->input("menu_route");//跳转路由
        $menu_routes_bind = empty($request->input("menu_routes_bind"))?'':$request->input("menu_routes_bind");//关联路由字符串，使用逗号分隔

        if(ProgramMenu::checkRowExists([['id','<>',$id],[ 'menu_name',$menu_name ],['parent_id',$parent_id],['program_id',$program_id]])){
            return response()->json(['data' => '菜单组中菜单名称重复', 'status' => '0']);
        }else{
            DB::beginTransaction();
            try{
                ProgramMenu::editMenu([['id',$id]],['program_id'=>$program_id,'parent_id'=>$parent_id,'parent_tree'=>$parent_tree,'menu_name'=>$menu_name,'is_root'=>$is_root,'icon_class'=>$icon_class,'menu_route'=>$menu_route,'menu_routes_bind'=>$menu_routes_bind]);
                ToolingOperationLog::addOperationLog($admin_data['admin_id'],$route_name,'编辑了菜单'.$menu_name);//保存操作记录
                DB::commit();//提交事务
            }catch (\Exception $e) {
                DB::rollBack();//事件回滚
                return response()->json(['data' => '编辑菜单失败，请检查', 'status' => '0']);
            }
            return response()->json(['data' => '编辑菜单成功', 'status' => '1']);
        }
    }
    //添加程序套餐
    public function package_add(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $list = Program::getList([['is_asset','1']],0,'id');//获取所有的资产程序
        return view('Tooling/Program/package_add',['list'=>$list,'admin_data'=>$admin_data,'route_name'=>$route_name,'action_name'=>'program']);
    }
    //检测添加程序套餐数据
    public function package_add_check(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $package_name = $request->input('package_name');
        $package_price = $request->input('package_price');
        $program_ids = $request->input('program_ids');
        if(Package::checkRowExists([[ 'package_name',$package_name ]])){
            return response()->json(['data' => '重复的套餐名称', 'status' => '0']);
        }else{
            DB::beginTransaction();
            try{
                $id = Package::addPackage(['package_name'=>$package_name,'package_price'=>$package_price]);
                foreach($program_ids as $key=>$val){
                    PackageProgram::addPackageProgram(['package_id'=>$id,'program_id'=>$val]);
                }
                ToolingOperationLog::addOperationLog($admin_data['admin_id'],$route_name,'添加了程序套餐'.$package_name);//保存操作记录
                DB::commit();//提交事务
            }catch (\Exception $e) {
                DB::rollBack();//事件回滚
                return response()->json(['data' => '添加套餐失败，请检查', 'status' => '0']);
            }
            return response()->json(['data' => '添加套餐成功', 'status' => '1']);
        }
    }
    //套餐列表
    public function package_list(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $package_name = $request->input('package_name');
        $search_data['package_name'] = $package_name;
        $list = Package::getPaginage([[ 'package_name','like','%'.$package_name.'%' ]],15,'id');

        return view('Tooling/Program/package_list',['list'=>$list,'search_data'=>$search_data,'admin_data'=>$admin_data,'route_name'=>$route_name,'action_name'=>'program']);
    }
    //修改套餐
    public function package_edit(Request $request){
        $id = $request->input('id');
        $info = Package::find($id);//获取单个套餐信息
        $selected_ids = [];//获取该套餐关联的程序
        foreach($info->programs as $key=>$val){
            $selected_ids[] = $val->id;
        }
        $list = Program::getList([['is_asset','1']],0,'id');//获取所有的资产程序
        return view('Tooling/Program/package_edit',['list'=>$list,'selected_ids'=>$selected_ids,'info'=>$info,'action_name'=>'program']);
    }
    //检测修改套餐数据
    public function package_edit_check(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $id = $request->input('id');
        $package_name = $request->input('package_name');
        $package_price = $request->input('package_price');
        $program_ids = $request->input('program_ids');

        if(Package::checkRowExists([['id','<>',$id],[ 'package_name',$package_name ]])){
            return response()->json(['data' => '重复的套餐名称', 'status' => '0']);
        }else{
            DB::beginTransaction();
            try{
                Package::editPackage([['id',$id]],['package_name'=>$package_name,'package_price'=>$package_price]);
                foreach($program_ids as $key=>$val){
                    $vo = PackageProgram::getOne([['package_id',$id],['program_id',$val]]);
                    if(is_null($vo)){
                        PackageProgram::addPackageProgram(['package_id'=>$id,'program_id'=>$val]);
                    }else{
                        continue;//存在则跳过;
                    }
                    unset($vo);
                }
                //删除原本有这次没有的程序
                PackageProgram::where('package_id',$id)->whereNotIn('program_id',$program_ids)->delete();
                ToolingOperationLog::addOperationLog($admin_data['admin_id'],$route_name,'编辑了程序套餐'.$package_name);//保存操作记录
                DB::commit();//提交事务
            }catch (\Exception $e) {
                DB::rollBack();//事件回滚
                return response()->json(['data' => '编辑套餐失败，请检查', 'status' => '0']);
            }
            return response()->json(['data' => '编辑套餐成功', 'status' => '1']);
        }
    }
}
?>