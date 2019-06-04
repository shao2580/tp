<?php

namespace app\model;

use think\Model;

class News extends Model
{
    protected $pk = 'new_id';
    protected $autoWriteTimeStamp = true;
    // protected $createTime = 'release_time';
}
