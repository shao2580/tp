<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\model\Friend as friendModel; 
use app\admin\controller\Common;
use think\Validate;

class Friend extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {   
        $page = input('page')??1;
        $limit = input('limit')??config('pageSize');

        $count = friendModel::count();
        $data = friendModel::order('id','desc')->page($page,$limit)->select();
       if (request()->isAjax()) {
           $result = [
                'code'=>0,
                'msg'=>'',
                'count'=>$count,
                'data'=>$data
           ];
           echo json_encode($result);die;
       }
        
        
        return view();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function add()
    {
        return view();
    }
     // 验证管理员唯一性
    public function checkOnlyName(){
        $name = input('post.name');
        $id = input('post.id')??'';
        if ($name) {
            $where=[];
            if ($id) {

                $where[] =['id','<>',$id];
            }
            $count=friendModel::where('name',$name)->where($where)->count();
                return $count;
        }
    }
     public function checkgoodssn()
    {
        if (request()->isAjax()) {
            $name = input('name');

            $count = friendModel::where('name',$name)->count();
            echo $count;
        }
    }
    


    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function doadd(Request $request)
    {
       $post = $request->post(); 
       // dump($post);die;
       
       $friendModel = new friendModel;
      $res = $friendModel->allowField(true)->save($post);
       if ($res) {
           $this->redirect('Friend/index');
       }
    }
    /**
     *  监听是否显示操作
     * [ajaxupd description]
     * @return [type] [description]
     */
    public function ajaxupd(){
        if (request()->isAjax()) {
            $id = input('get.id');
            $field = input('get.key');
            $value = input('get.value');

            $data[$field] = $value==1?0:1;

            $friendModel = new friendModel;
            $res = $friendModel->save($data,['id'=>$id]);
            if ($res) {
                echo 1;
            }else{
                echo 0;
            }
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete()
    {
        if (request()->isAjax()) {
           $id = input('id');

           $res = friendModel::destroy($id); 
           if ($res) {
               echo json_encode(['code'=>0,'msg'=>'删除成功']);
           }else{
               echo json_encode(['code'=>1,'msg'=>'发送未知错误,删除失败']);
           }
        }
    }
}
