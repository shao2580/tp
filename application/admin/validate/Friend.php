<?php

namespace app\admin\validate;

use think\Validate;

class Friend extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'name'=>'require|max:10|min:3',
        'url'=>'require|url'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'name.require'=>'友链名称必填',
        'name.max'=>'友链名称不能超过30个字符',
        'name.min'=>'友链名称不能少于6个字符',
        'name.chsDash'=>'友链名称只能是汉字、字母、数字和下划线_及破折号-',
        'url.require'=>'url网址必填'
        
       
    ];

 


    
}
