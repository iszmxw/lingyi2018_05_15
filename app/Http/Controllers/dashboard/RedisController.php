<?php
/**
 * Created by PhpStorm.
 * User: zeo
 * Date: 2017/11/4
 * Time: 22:45
 * 本文件用于学习Redis.
 */
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Redis\RedisManager;
use Illuminate\Support\Facades\Redis;

class RedisController extends Controller{
    public function study(){
        dump("开始学习Redis了");
        //Redis::connection(''); 默认连接default
        //Redis::connection('zeo'); 连接到我定义的redis服务器
        //Redis::connection('lingyikeji');//连接到lingyikeji集群对象

        dump("最简单的Redis的存取");
        Redis::connection('zeo');//连接到我的redis服务器
        Redis::set('test1', '这是第一个Redis示例');
        $test1 = Redis::get('test1');
        dump($test1);

        dump("同时存储多个key-value");
        $list = array(
            'zeo:0001' => '第一个值',
            'zeo:0002' => '第二个值',
            'zeo:0003' => '第三个值'
        );
        Redis::mset($list);
        $ll = Redis::mget(array_keys($list));
        dump($ll);

        dump('存储带时效的key-values');
        //Redis::setex('name', 10, '薛志豪');//存10秒
        //dump(Redis::get('name'));

        dump('添加值时不覆盖原有值');
        /*
        $aa=Redis::setnx('foo', 12) ;  // 返回 true ， 添加成功
        $bb=Redis::setnx('foo', 34) ;  // 返回 false， 添加失败，因为已经存在键名为 foo 的记录
        dump($aa);
        dump($bb);
        dump(Redis::get('foo'));
        */

        dump('替换一个值，并换回替换前的值');
        $foo = Redis::getset('foo',66);
        dump($foo);
    }
}