<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Common extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function initialize()
    {
        $this->checkLogin();
    }

    // 防非法登录
    public function checkLogin(){     
        if (!session('adminuser')) {
            $this->redirect('Login/index');
        }
    }
    
       /**
     * 单文件 生成缩略图 图片上传  app\commom.php 下面函数 upload
     * [upload description]
     * @return [type] [description]
    //  */
    public function upload(){
        // type：1 普通上传  type：2 上传并生成缩略图
            // dump($_FILES);die;
            $type = input('type')??1;
            // echo $type;die;
            $domain = input('domain')??''; 
            $result = upload('file');
            // dump($result);die;
            if ($type ==1) {
                if ($domain) {
                    $result['data']['src'] = config('UPLOAD').$result['data']['src'];
                }
                echo json_encode($result,true);die;
            }
           
            // 生成缩略图
            // echo $result['code'];die;
           if ($result['code']==0) {
               $thumb = $this->imgthumb($result['data']['src']);
               $result['data']['thumb'] = $thumb;
               echo json_encode($result);
           }else{
               echo $result;
           }
            
        }
    
}
