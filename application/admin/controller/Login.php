<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\captcha\Captcha;

class Login extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // session_start();
        if (request()->isPost()) {
           $post = request()->post(); 
        
            $captcha = $post['verify'];
            if(!captcha_check($captcha)){
                 // 验证失败
                return view('',['data'=>$post,'error'=>'验证码错误']);
            };
            // 根据用户名查询盐值
            $where['admin_name'] = $post['admin_name'];
            $user = \think\Db::name('admin')->where($where)->find();
            if (!$user) {
                return view('',['data'=>$post,'error'=>'查无此人']);
            }
            $pwd = createPwd($post['admin_pwd'],$user['salt']);
            if ($pwd!==$user['pwd']) {
                return view('',['data'=>$post,'error'=>'密码错误']);
            }

            session('adminuser',$user);
            $this->redirect('Index/index');

        }
        return view();
    }
    /**
     * 生成验证码
     * [verify description]
     * @return [type] [description]
     */
    public function verify()
    {   //清空缓冲区
        ob_clean();
        $config =    [
            // 验证码字体大小
            'fontSize'    =>    50,    
            // 验证码位数
            'length'      =>    3,   
            // 关闭验证码杂点
            'useNoise'    =>    false,
            // 关闭混淆曲线
            'useCurve'    =>    false,
            
        ];

        $captcha = new Captcha($config);

        return $captcha->entry();    
    }

    public function logout(){
        session('adminuser',null);
        $this->redirect('Login/index');
    }
    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
