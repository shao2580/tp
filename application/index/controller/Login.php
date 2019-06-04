<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use PHPMailer\PHPMailer;  //邮寄者
use app\index\validate\User;  //控制器验证
use app\model\User as UserModel;  //Model
use think\facade\Request as Re;

class Login extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    // 前台首页
    public function login()
    {   
        // if(Request()->isPost && Request()->isAjax())
        if (Re::isPost()&&Re::isAjax()) {
            $account = input('post.account','default');
            $u_pwd = input('post.u_pwd','default');
            $remember_me = input('post.remember_me','default');

            // 验证
            if (!$account) {
                fail('账号必填');
            }
            if (!$u_pwd) {
                fail('密码必填');
            }

            // where数组中 账号 输入手机或邮箱
            $where = [
                 'u_email'=>$account

            ];

            $UserModel = new UserModel;
            $userInfo = $UserModel->where($where)->find();
            // print_r($UserInfo);exit;
            if (!empty($userInfo)) { 
                $now = time();   // 当前时间
                $last_error_time = $userInfo['last_error_time'];  //最后错误时间
                $error_num = $userInfo['error_num'];   //错误次数
                $UserModel = new UserModel;      
                $where = [
                    'u_id'=>$userInfo['u_id']
                ];

                // 账号正确 
                if ($userInfo['u_pwd']==md5($u_pwd)) {
                    // successly('密码正确');
                    if ($error_num>=3 && $now-$last_error_time<3600 ) {
                         $second =60-ceil(($now-$last_error_time)/60);
                        fail('账号已锁定,请'.$second.'分钟后重新登录');exit;
                    }

                    // 错误次数清零 时间改为null
                     $updateInfo = [
                            'error_num'=>0,
                            'last_error_time'=>null
                        ];
                        $UserModel->save($updateInfo,$where); 

                    // 判断是否记住账号密码10天  cookie
                    if ($remember_me=='true') {
                        $rememberInfo = [
                            'account'=>$account,
                            'u_pwd'=>$u_pwd
                        ]; 
                       cookie('rememberInfo',$rememberInfo,60*60*24*10); 
                    }

                    // 存入用户id 账号进入session
                    $userInfos = [
                        'u_id'=> $userInfo['u_id'],
                        'account'=> $account
                    ];
                    session('userInfo',$userInfos);

                    // 提示登录成功
                     successly('登陆成功');

                     // 同步浏览记录
                    $this->asyncHistory();

                    // 同步购物车记录
                    $this->asyncCart();

                    exit;

                }else{
                    // fail('密码错误');
                    if ($now-$last_error_time>3600) {
                        $updateInfo = [
                            'error_num'=>1,
                            'last_error_time'=>$now
                        ];
                        $res = $UserModel->save($updateInfo,$where);  
                        if ($res) {
                              fail('账号或密码有误,您还有2次机会');
                          }  
                    }else{
                        if ($error_num>=3) {
                            $second =60-ceil(($now-$last_error_time)/60);
                            fail('账号已锁定,请'.$second.'分钟后重新登录');
                        }else{   
                            $updateInfo = [
                                'error_num'=>$error_num+1,
                                'last_error_time'=>$now
                            ];
                            $res = $UserModel->save($updateInfo,$where);
                            if ($res) {
                                $count = 3-($error_num+1);
                                fail('账号或密码有误,您还有'.$count.'次机会');
                             } 
                        }
                    }
                }

            }else{
                fail('账号或密码有误,请重新输入');
            }


        }else{
            return view();
        }    
    }

    // 退出
    public function quit(){
        session('userInfo',null);
        $this->redirect('Login/login');
    }
    /**前台邮箱注册页
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function register()
    {   
        if (Re::isPost() && Re::isAjax()) {
            $data = input('post.');
            // 控制器验证  实例化
            $User = new User;
            $res = $User->scene('register')->check($data);
            if (!$res) {
                fail($User->getError()) ;
            }

            // 入库
            $UserModel = new UserModel;
            $res = $UserModel->allowField(true)->save($data);
            if ($res) {
                successly('注册成功');
            }else{
                fail('注册失败');
            }
        }else{
            // 配置中开启了layout 在这要关闭layui
            // $this->view->engine->layout(false);  //相当于注册页的 {__NOLAYOUT__}
            return view();
        }     
    }

    /**检测邮箱
     * [checkEmail description]
     * @return [type] [description]
     */
    public function checkEmail(){
        $email = input('post.email');
        // $user_model=model('UserModel');
        $user_model = new UserModel;
        $where=[
            'u_email'=>$email
        ];
        $data=$user_model->where($where)->find();
        if (empty($data)) {
            successly();
        }else{
            fail('此邮箱已经被注册');
        }
    }
    
    /**发送邮件
     * [send description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function send(Request $request)
    {
       $email = input('post.email'); 
       // 清空上一次的session信息
       session('emailInfo',null);
       // 验证
       $data = [
            'u_email'=>$email
       ];
       $user = new User();
       $res = $user->check($data);
       if ($res) {
           fail($user->getError);
       }

        // 发送邮件
        // 标题
        $subject = "会员注册验证码";
        // 随机生成6位验证码
        $code = createCode();
        $bodys = "尊敬的 先生/女士：<br/> 您正在验证身份,验证码是：<br/> <h3> $code </h3> <br/> 5分钟内有效，为了你的账号安全，请勿泄漏给他人。";

        $res1 = sendEmail($email,$subject,$bodys);
        if ($res1) {
            // 把邮箱、验证码、发送时间 存入session中
            $emailInfo=[
                'u_email'=>$email,
                'u_code'=>$code,
                'send_time'=>time(),
            ];
            session('emailInfo',$emailInfo);
            successly('邮件已发送,注意查收'); 
        }else{
            fail('发送失败');
        }
    }
   
    /**找回密码页面
     * [forgetPwd description]
     * @return [type] [description]
     */
    public function forgetPwd(){
        // 配置中开启了layout 在这要关闭layui
        // $this->view->engine->layout(false);  //相当于注册页的 {__NOLAYOUT__}
        return view();
    }

    //找回密码处理器    
    public function forgetPwdDo(){
        // 获取邮箱
        $u_email = input('post.u_email');
        
        $where = [
            'u_email'=>$u_email
        ];
        $userModel = new userModel;
        // $userModel = model('userModel'); 
        $emailInfo = $userModel->where($where)->find();
        if (empty($emailInfo)) {
            fail('邮箱不存在');exit;
        }
        // 发送邮件
        // 标题
        $subject = "会员找回密码";
       
        $bodys = "尊敬的 先生/女士：<br/> 您正在使用邮箱找回密码,请点击一下链接：<br/><a href='http://www.1810.com/index/login/showpwd.html?token='>确认找回密码</a><br/>或者复制以下链接http://www.1810.com/index/login/showpwd.html";

        sendEmail($u_email,$subject,$bodys);
    
    }

    // 修改密码页
    public function showPwd(){
        input('get.token','');
        return view();
    }

    /**************************************************/
    // // 邮箱控制器
    // public function send($address,$subject,$code){
    //         error_reporting(E_STRICT);
    //         //date_default_timezone_set('America/Toronto');
    //         $mail = new PHPMailer();    
    //         // print_r($mail);die();
    //         $mail->CharSet = "UTF-8";
    //         $mail->IsSMTP(); // telling the class to use SMTP
    //         $mail->Host       = "smtp.qq.com"; // SMTP server  //主人
    //         // $mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
    //         //                                            // 1 = errors and messages
    //         //                                            // 2 = messages only
    //         $mail->SMTPAuth   = true;                  // enable SMTP authentication
    //         $mail->Host       = "smtp.qq.com"; // sets the SMTP server
    //         $mail->Port       = 25;          //接口号          // set the SMTP port for the GMAIL server
    //         //   服务器的 应户名 密码
    //         $mail->Username   = "280654805@qq.com"; // SMTP account username
    //         $mail->Password   = "ofbaipsyztcjbgfh";        // SMTP account password
    //         // 发件人邮箱，发件人姓名
    //         $mail->SetFrom('280654805@qq.com', '尤洪-悦桔拉拉-恒望科技');
    //         $mail->AddReplyTo("280654805@qq.com","尤洪-悦桔拉拉-恒望科技");
    //         //  标题
    //         $mail->Subject    = "$subject";
    //         $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";// optional, comment out and test
    //         //  内容
    //         $body = "尊敬的 先生/女士：<br/> 您正在验证身份,验证码是：<br/> <h3> $code </h3> <br/> 5分钟内有效，为了你的账号安全，请勿泄漏给他人。";
    //         $mail->MsgHTML($body);
    //         //  收件人邮箱 ，收件人名称
    //         $mail->AddAddress($address, $address);
    //         // 附件
    //         //$mail->AddAttachment("images/phpmailer.gif");      // attachment
    //         //$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
    //         // 判断 邮箱是否 发送
    //         if(!$mail->Send()) {
    //             echo json_encode(['code'=>1,'msg'=>'send error']);
  
    //         } else {
    //             // 验证码存入session
    //             session('code',$code);
    //             echo json_encode(['code'=>0,'msg'=>'send success']);
    //         }
    // }
    /**************************************************/

   
}
