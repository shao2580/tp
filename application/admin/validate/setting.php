<?php

namespace app\admin\validate;

use think\Validate;

class Setting extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule =[      
        'description'=>'require',
        'create_time'=>'create_time|min:time()',
        'update_time'=>'update_time|max:create_time'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'description.require'=>'活动描述必填',
        'create_time.max'=>'开始时间大于当前时间',
        'update_time.min'=>'结束时间大于开始时间'
        
        
        

    ];
}
