<?php

namespace app\index\controller;

use think\Controller;
use think\Request;

class User extends Common
{
    /*构造方法*/
    public function __construct()
    {
        parent::__construct();
        // 防非法登录
        if (!$this->checkLogin()) {
            $this->error('请先登录',url('Login/login'));exit;
        }
    }

    /**个人中心-显示资源列表
     * @return \think\Response
     */
    public function index()
    {

        return view();
    }

   
}
