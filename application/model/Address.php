<?php

namespace app\model;

use think\Model;

class Address extends Model
{
    protected $pk='Address_id';
    protected $createTime='create_time';

    // 数据完成
    protected $insert=['user_id'];
    protected function setUserIdAttr(){
    	return session('userInfo.u_id');
    }

}
