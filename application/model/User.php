<?php

namespace app\model;

use think\Model;

class User extends Model
{
    protected $pk = 'u_id';
    // 开启自动写入事件戳 
    // protected $autoWriteTimeStamp = true;
    protected $createTime = 'create_time';
    //关闭更新时间
    protected $updateTime = false;

    // 修改器
    protected function setUPwdAttr($value){
    	return md5($value);
    }
}
