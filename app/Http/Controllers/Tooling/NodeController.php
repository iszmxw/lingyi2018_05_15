<?php
namespace App\Http\Controllers\Tooling;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Illuminate\Support\Facades\DB;
use App\Models\Node;
use App\Models\ModuleNode;
use App\Models\ProgramModuleNode;
use App\Models\ToolingOperationLog;
use App\Models\RoleNode;
class NodeController extends Controller{
    //添加节点
    public function node_add(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        return view('Tooling/Node/node_add',['admin_data'=>$admin_data,'route_name'=>$route_name,'action_name'=>'node']);
    }
    //提交添加节点数据
    public function node_add_check(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $current_route_name = $request->path();//获取当前的页面路由

        $node_name = $request->input('node_name');//提交上来的节点名称
        $route_name = $request->input('route_name');//提交上来的路由名称

        if(Node::checkRowExists([[ 'node_name',$node_name ]])){
            return response()->json(['data' => '节点名称已经存在', 'status' => '0']);
        }else{
            DB::beginTransaction();
            try{
                Node::addNode(['node_name'=>$node_name,'route_name'=>$route_name]);
                ToolingOperationLog::addOperationLog($admin_data['admin_id'],$current_route_name,'新增了节点'.$node_name);//保存操作记录
                DB::commit();//提交事务
            }catch (\Exception $e) {
                dump($e);
                DB::rollBack();//事件回滚
                return response()->json(['data' => '添加节点失败，请检查', 'status' => '0']);
            }
            return response()->json(['data' => '添加节点成功', 'status' => '1']);
        }
    }
    //节点列表
    public function node_list(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $node_name = $request->input('node_name');
        $search_data = ['node_name'=>$node_name];
        $list = Node::getPaginage([[ 'node_name','like','%'.$node_name.'%' ]],15,'id');
        return view('Tooling/Node/node_list',['list'=>$list,'search_data'=>$search_data,'admin_data'=>$admin_data,'route_name'=>$route_name,'action_name'=>'node']);
    }

    //编辑节点
    public function node_edit(Request $request){
        $id = $request->input('id');
        $info = Node::find($id);
        return view('Tooling/Node/node_edit',['info'=>$info]);
    }

    //提交编辑节点数据
    public function node_edit_check(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $current_route_name = $request->path();//获取当前的页面路由

        $id = $request->input('id');//提交上来的ID
        $node_name = $request->input('node_name');//提交上来的节点名称
        $route_name = $request->input('route_name');//提交上来的路由名称

        if(Node::checkRowExists([['id','<>',$id],[ 'node_name',$node_name ]])){
            return response()->json(['data' => '节点名称已经存在', 'status' => '0']);
        }else{
            DB::beginTransaction();
            try{
                Node::editNode([['id',$id]],['node_name'=>$node_name,'route_name'=>$route_name]);//编辑节点
                ToolingOperationLog::addOperationLog($admin_data['admin_id'],$current_route_name,'修改了节点'.$node_name);//保存操作记录
                DB::commit();//提交事务
            }catch (\Exception $e) {
                DB::rollBack();//事件回滚
                return response()->json(['data' => '修改节点失败，请检查', 'status' => '0']);
            }
            return response()->json(['data' => '修改节点成功', 'status' => '1']);
        }
    }

    //软删除节点
    public function node_delete(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $current_route_name = $request->path();//获取当前的页面路由
        $id = $request->input('id');//提交上来的ID
        DB::beginTransaction();
        try{
           // Node::editNode([['id',$id]],['is_delete'=>'1']);//软删除节点表数据
           // ModuleNode::editModuleNode([['node_id',$id]],['is_delete'=>'1']);//软删除模块节点表对应数据
            //RoleNode::editRoleNode([['node_id',$id]],['is_delete'=>'1']);//软删除角色程序节点表对应数据
            //ProgramModuleNode::editProgramModuleNode([['node_id',$id]],['is_delete'=>'1']);//软删除程序模块节点表对应数据
            Node::where('id',$id)->delete();
            ToolingOperationLog::addOperationLog($admin_data['admin_id'],$current_route_name,'删除了节点，ID为：'.$id);//保存操作记录
            DB::commit();//提交事务
        }catch (\Exception $e) {
            dump($e);
            DB::rollBack();//事件回滚
            return response()->json(['data' => '删除节点失败，请检查', 'status' => '0']);
        }
        return response()->json(['data' => '删除节点成功', 'status' => '1']);
    }
}
?>