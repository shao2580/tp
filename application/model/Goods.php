<?php

namespace app\model;

use think\Model;

class Goods extends Model
{
    protected $pk = 'goods_id';

    // 显示 √ 错
    
    //   public function getIsNewAttr($value)
    //   {
    // 	    $is_new = [1='√',0='×'];
    //  	return $is_new[$value];
    //   }
}
