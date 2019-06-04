<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\admin\controller\Common;

class Role extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
       
        $keywords = input('keyword');

        $data = Db::name('role')->where('role_name','like',"%$keywords%")->order('role_id','desc')->paginate(config('pageSize'),false,['query'=>['keyword'=>$keywords]]);
          // echo Db::name('admin')->getLastSql();
        $this->assign('data',$data);
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
        $validate = new \app\admin\validate\Role;
        if (!$validate->check($post)) {
            return view('add',['data'=>$post,'error'=>$validate->getError()]);
        }

        $res = Db::name('role')->strict(false)->insert($post);
        if ($res) {
            $this->success('添加成功','Role/index');
            // $this->redirect('Admin/index');
        }
    }
    public function checkOnlyName(){
        $role_name = input('post.name');
        $role_id = input('post.role_id');

        if ($role_name) {
            $where=[];
            if ($role_id) {
                $where[]=['role_id','<>',$role_id];
            }
            $count=Db::name('role')->where('role_name',$role_name)->where($where)->count();

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
            
            $data = Db::name('role')->where('role_id',$id)->find();
            // $data = db('admin')->where('admin_id',$id)->find();
            
            $this->assign('data',$data);
            return view();
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
            $post =input('post.');

            // 验证 走edit验证场景 
            $validate = new \app\admin\validate\Role;
            if (!$validate->scene('edit')->check($post)) {
                return view('add',['data'=>$post,'error'=>$validate->getError()]);
            }

            // Db入库
            $res = Db::name('role')->where('role_id',$id)->strict(false)->update($post);
            if ($res) {
                $this->redirect('Role/index');
            }else{
                 $this->redirect('Role/index');
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
           $res =\think\Db::name('role')->delete($id);
           if($res){
            $this->redirect('Role/index');
           }
       }
    }
}
