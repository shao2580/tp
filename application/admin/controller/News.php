<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\model\News as newsModel;


class News extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // return view();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function newadd()
    {   
        if (request()->isAjax()&&request()->isPost()) {
            $data=input('post.');
            if ($data['r_time']==2) {
                $data['release_time']=strtotime($data['release_time']);
                if ($date['release_time']<=time()) {
                    fail('定时发布时间必须比当前时间大');
                }
            }else if($data['r_time']==1){
                $data['release_time']=time();
            }
           $news_model = new newsModel;
           $res = $news_model->save($data);
           if ($es) {
               successly('添加成功');
           }else{
                fail('添加失败');
           }
        }else{
            return view();
        }
        
    }

    

    function imgUp(){
   // 获取表单上传文件 例如上传了001.jpg
    $file = request()->file('file');
    // 移动到框架应用根目录/uploads/ 目录下
    $info = $file->move( './uploads');
       if($info){
            $arr=[
                'code'=>0,
                'msg'=>'',
                'data'=>[
                    'src'=>$info->getSaveName()
                ]
            ];
            echo json_encode($arr);
          
            // return ['code'=>0,'msg'=>'upload success','data'=>$data];
        }else{
             // 上传失败获取错误信息
             return ['code'=>1,'msg'=>$info->getError()];              
        }
    }
   
}
