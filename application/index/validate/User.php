<?php

namespace app\index\validate;

use think\Validate;

class User extends Validate
{
    /**定义验证规则
     * 
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'u_email'=>'require|email|unique:user|checkEmail',     //非空 格式 唯一
        'u_code'=>'require|checkCode',   
        'u_pwd'=>'require|checkPwd',
        'u_pwd1'=>'require|checkPwd1',
    ];
    
    /**场景
     * 
     * [$scene description]
     * @var [type]
     */
    protected $scene = [
        'checkEmail'=>['u_email'],
        'register'=>['u_email','u_code','u_pwd','u_pwd1'],
    ];

    /**定义错误信息
     * 
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'u_email.require'=>'邮箱必填',
        'u_email.email'=>'邮箱格式不正确',
        'u_email.unique'=>'邮箱已被注册', 
        'u_code.require'=>'验证码必填',
        'u_pwd.require'=>'密码必填',
        'pwd1.require'=>'确认密码必填',
        
    ];

    /**邮箱session验证
     * [checkEmail description]
     * @param  [type] $value [description]  值
     * @param  [type] $rule  [description]  规则
     * @param  array  $data  [description]  数据
     * @return [type]        [description]
     */
    protected function checkEmail($value,$rule,$data=[]){
        // 有session值 发送邮件注册验证 没session值 没有发送邮件 获取验证
        $u_email = session('emailInfo.u_email');
        if (empty($u_email)) {
            return true;
        }else{
            if ($u_email!=$value) {
                return '您发送邮件的邮箱与当前邮箱不一致';
            }else{
                return true;
            }
        }
    }

    /**验证码验证
     * [checkCode description]
     */
    protected function checkCode($value,$rule,$data=[]){
        $u_code = session('emailInfo.u_code');
        $send_time = session('emailInfo.send_time');
        if ($value!=$u_code) {
            return '验证码有误';
        }else if (time()-$send_time>30000) {
            return '验证码5分钟内输入有效';
        }else{
            return true;
        }
    }

    /**密码验证
     * [checkPwd description]
     */
    protected function checkPwd($value,$rule,$data=[]){
        $reg = '/^.{6,12}$/';
        if (!preg_match($reg,$value)) {
            return '密码必须6~12位';
        }else{
            return true;
        }
    }

    /**确认密码验证
     * [checkPwd1 description]
     */
    protected function checkPwd1($value,$rule,$data=[]){
       if ($value!=$data['u_pwd']) {
           return '确认密码必须与密码一致';
       }else{
           return true;
       }
    }
















}
