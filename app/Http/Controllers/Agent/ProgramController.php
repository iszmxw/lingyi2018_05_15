<?php
namespace App\Http\Controllers\Agent;
use App\Http\Controllers\Controller;
use App\Models\OrganizationAssets;
use App\Models\OrganizationAssetsallocation;
use App\Models\Program;
use Illuminate\Http\Request;
use Session;
class ProgramController extends Controller{
    //系统资产列表
    public function program_list(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $organization_id = $admin_data['organization_id'];//分公司id
        $list = Program::getPaginage([['is_asset','1']],15,'id');
        foreach ($list as $key=>$value) {
            $re = OrganizationAssets::getOne([['organization_id', $organization_id], ['program_id',$value['id']]]);
            $list[$key]['program_balance'] = $re['program_balance'];
            $list[$key]['program_used_num'] = $re['program_used_num'];
        }
        return view('Agent/Program/program_list',['list'=>$list,'admin_data'=>$admin_data,'route_name'=>$route_name,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data]);
    }
    //资产划拨记录
    public function program_log(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $organization_id = $admin_data['organization_id'];//分公司id
        $list = OrganizationAssetsallocation::getPaginage([['fr_organization_id',$organization_id]],[['to_organization_id',$organization_id]],'10','id');//查询操作记录
        return view('Agent/Program/program_log',['list'=>$list,'admin_data'=>$admin_data,'route_name'=>$route_name,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data]);
    }


}
?>