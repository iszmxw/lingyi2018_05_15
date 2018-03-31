<?php
/**
 * Android接口
 */
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use Session;
class AndroidApiController extends Controller{
    /**
     * 登入检测
     */
    public function login(Request $request){
        $account = $request->account;//登入账号
        echo $account;
        $password = $request->password;//登入密码
        $data = Account::where([['account',$account]])->orWhere([['mobile',$account]])->first();//根据账号进行查询
        if(empty($data)){
            return response()->json(['msg' => '用户不存在', 'status' => '0']);
        }
        $key = config("app.retail_encrypt_key");//获取加密盐
        $encrypted = md5($password);//加密密码第一重
        $encryptPwd = md5("lingyikeji".$encrypted.$key);//加密密码第二重
        if($encryptPwd != $data['password']){
            return response()->json(['msg' => '密码不正确', 'status' => '0']);
        }
        $data = ['status' => '1', 'msg' => '登陆成功', 'data' => ['account_id' => $data['id'], 'account' => $data['account'], 'organization_id' => $data['organization_id'], 'uuid' => $data['uuid']]];
        return response()->json($data);
    }

}
?>