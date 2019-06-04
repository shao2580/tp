<?php

namespace app\model;

use think\Model;

class Order extends Model
{
    protected $pk='order_id';
    protected $createTime='create_time';
}
