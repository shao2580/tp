<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\admin\controller\Common;

class Admin extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $fields = input('get.field');
        $keywords = input('keywords');
        $where=[];
        if ($fields=='email') {
            $where['email'] = $keywords;
        }
        if ($fields=='admin_name') {
            $where[] = ['admin_name','like',"%$keywords%"];
        }

        $data = Db::name('admin')->where($where)->order('admin_id','desc')->paginate(config('pageSize'),false,['query'=>['field'=>$fields,'keywords'=>$keywords]]);
        // echo Db::name('admin')->getLastSql();
        
        $this->assign('data',$data);
        // 无刷新分页使用
        if (request()->isAjax()) {
           
            return view('ajaxlist');
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

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function doadd(Request $request)
    {
        $post = input('post.');
        // $post = $request->post();
        // dump($post);
        
        // 控制器验证
        $validate = new \app\admin\validate\Admin;
        if (!$validate->check($post)) {
            return view('add',['data'=>$post,'error'=>$validate->getError()]);
        }

        // 生成密码--盐值--生成随机密码+自己的密码后 在加密
        $post['salt'] = setSalt();
        $post['pwd']=createPwd($post['pwd'],$post['salt']);
        
        // Db 入库
        $post['ip']=$request->ip();
        $post['addtime']=time();
        $post['lasttime']=time();

        $res = Db::name('admin')->strict(false)->insert($post);
        if ($res) {
            $this->success('添加成功','Admin/index');
            // $this->redirect('Admin/index');
        }
    }
     // 验证管理员唯一性
    public function checkOnlyName(){
        $admin_name = input('post.name');
        $admin_id = input('post.admin_id')??'';
        if ($admin_name) {
            $where=[];
            if ($admin_id) {

                $where[] =['admin_id','<>',$admin_id];
            }
            $count=Db::name('admin')->where('admin_name',$admin_name)->where($where)->count();
                return $count;
        }
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
    // 编辑--查询
    public function edit($id)
    {
        if ($id) {
            // Db类 查询单条
            
            $data = Db::name('admin')->where('admin_id',$id)->find();
            // $data = db('admin')->where('admin_id',$id)->find();
            
            $this->assign('data',$data);
            return view();
        }
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
        if ($id) {
           // dump($request->param('id')); 
            $post = input('post.');
            // dump($post);die;

            // 验证 走edit验证场景 把pwd和repwd必填验证移除
            $validate = new \app\admin\validate\Admin;
            if (!$validate->scene('edit')->check($post)) {
                return view('add',['data'=>$post,'error'=>$validate->getError()]);
            }
            // 如果输入密码 就要进行取盐值加密 否则删除pwd 保持原密码
            if ($post['pwd']) {
               // 生成密码--盐值--生成随机密码+自己的密码后 在加密
                $post['salt'] = setSalt();
                $post['pwd']=createPwd($post['pwd'],$post['salt']);  
            }else{
                unset($post['pwd']);
            }
         
            // Db 入库
            $post['ip']=$request->ip();
            // dump($post);die;
            
             $res =  Db::name('admin')->where('admin_id',$id)->strict(false)->update($post);

            // echo  Db::name('admin')->getLastSql();
               if ( $res ) {
               
                    $this->redirect('Admin/index');
               }else{
                     $this->redirect('Admin/index');
               }
            
        }
       
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $id = input('id');
       if ($id) {
           $res =\think\Db::name('admin')->delete($id);
           if($res){
            $this->redirect('Admin/index');
           }
       }
    }
    // 批删
    public function del(){
        $ids = input('ids');
        if (is_array($ids) && $ids) {
            $res =\think\Db::name('admin')->delete($ids);
            echo $res;
        }
    }

    // 即点即改
    public function ajaxupd(Request $request){
        if ($request->isAjax()) {
            $admin_name = input('admin_name');
            $admin_id = input('admin_id');

           $res = Db::name('admin')->where('admin_id',$admin_id)->update(['admin_name'=>$admin_name]);
           if ($res) {
               echo 1;
           }else{
               echo 0;
           }
        }
    }
}
