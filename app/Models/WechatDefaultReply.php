<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class WechatDefaultReply extends Model{
    use SoftDeletes;
    protected $table = 'wechat_default_reply';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $dateFormat = 'U';//设置保存的created_at updated_at为时间戳格式


    //简易型查询单条数据关联查询
    public static function getOne($where)
    {
        return self::where($where)->first();
    }

    public static function addWechatDefaultReply($param){
        $model = new WechatDefaultReply();
        $model->organization_id = $param['organization_id'];
        $model->authorizer_appid = $param['authorizer_appid'];
        $model->reply_type = $param['reply_type'];
        $model->text_info = !isset($param['text_info'])?"":$param["text_info"];
        $model->image_media_id = !isset($param['image_media_id'])?"":$param["image_media_id"];
        $model->article_media_id = !isset($param['article_media_id'])?"":$param["article_media_id"];
        $model->save();
        return $model->id;
    }

    //查询数据是否存在（仅仅查询ID增加数据查询速度）
    public static function checkRowExists($where){
        $row = self::getPluck($where,'id')->toArray();
        if(empty($row)){
            return false;
        }else{
            return true;
        }
    }
    //获取单行数据的其中一列
    public static function getPluck($where,$pluck){
        return self::where($where)->pluck($pluck);
    }

    //获取分页数据-分公司
    public static function getPaginage($where,$paginate,$orderby,$sort='DESC'){
        return self::where($where)->orderBy($orderby,$sort)->paginate($paginate);
    }

    //获取列表
    public static function getList($where,$limit=0,$orderby,$sort='DESC'){
        $model = self::where($where)->orderBy($orderby,$sort);
        if(!empty($limit)){
            $model = $model->limit($limit);
        }
        return $model->get();
    }

    //修改
    public static function editWechatDefaultReply($where,$param){
        $model = self::where($where)->first();
        foreach($param as $key=>$val){
            $model->$key=$val;
        }
        $model->save();
    }

}
?>