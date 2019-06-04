<?php

namespace app\admin\validate;

use think\Validate;

class Role extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'role_name'=>'require|max:10|min:3',
        'role_ribe'=>'require',
        
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'role_name.require'=>'角色名称必填',
        'role_name.max'=>'角色名称不能超过10个字符',
        'role_name.min'=>'角色名称不能少于3个字符',
        'role_name.chsDash'=>'角色名称只能是汉字、字母、数字和下划线_及破折号-',
        'role_ribe.require'=>'角色描述必填',
        
        

    ];
}
