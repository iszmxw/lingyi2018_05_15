<?php
/**
 *新版本登陆界面
 *
 **/
namespace App\Http\Controllers\dashboard;
use App\Http\Controllers\Controller;

class LoginController extends Controller{
    public function display(){
        echo asset('/dashboard/library/bootstrap/test.js');
        return "123456";
    }
}
?>