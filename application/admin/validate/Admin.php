<?php

namespace app\admin\validate;

use think\Validate;

class Admin extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'admin_name'=>'require|max:10|min:3',
        'email'=>'require|email',
        'pwd'=>'require|max:18|min:6',
        'repwd'=>'require|confirm:pwd',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'admin_name.require'=>'管理员名称必填',
        'admin_name.max'=>'管理员名称不能超过10个字符',
        'admin_name.min'=>'管理员名称不能少于3个字符',
        'admin_name.chsDash'=>'管理员名称只能是汉字、字母、数字和下划线_及破折号-',
        'email.require'=>'邮箱必填',
        'email.email'=>'邮箱格式不否合规定',
        'pwd.require'=>'密码必填',
        'pwd.min'=>'密码不能少于3个字符',
        'pwd.min'=>'密码不能超过10个字符',
        'repwd.require'=>'确认密码必填',
        'repwd.confirm'=>'两次密码不一致',

    ];

    // edit 验证场景定义
    public function sceneEdit()
    {
        return $this->only(['admin_name','email','pwd','repwd'])
            ->remove('pwd','require')
            ->remove('repwd','require');
    }



    
}
