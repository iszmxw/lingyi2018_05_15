<?php
/**
 * module表的模型
 *
 */
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Module extends Model{
    protected $table = 'node';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
?>