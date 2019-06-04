<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\controller\Common;
use app\model\Category as CategoryModel;

class Category extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // $page = input('page')??1;  //当前页
        // $limit = input('limit')??config('pageSize');  //当页显示多少条

        $count = CategoryModel::count();  //计算总条数
        $data = CategoryModel::order('cate_id','desc')->select();
        $data = CategoryModel::select();    //查询所有数据
        // 无限极分类
       $data = createTree($data);
       if ($data) {
           foreach ($data as $key => $v) {
               $data[$key]['level'] = str_repeat("☆", $v['level']-1);
           }
       }
       // 判断是否是 Ajax 提交
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
       $data = CategoryModel::select();
       // 无限极分类
       $data = createTree($data);
        $this->assign('data',$data);
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
        $res = CategoryModel::create($post);
       if ($res) {
           $this->redirect('Category/index');
       }
        
    }
    /**
     *  监听是否显示操作
     * [ajaxupd description]
     * @return [type] [description]
     */
    public function ajaxupd(){
        if (request()->isAjax()) {
            $cate_id = input('get.cate_id');
            $field = input('get.key');
            $value = input('get.value');

            $data[$field] = $value==1?0:1;

            $CategoryModel = new CategoryModel;
            $res = $CategoryModel->save($data,['cate_id'=>$cate_id]);
            if ($res) {
                echo 1;
            }else{
                echo 0;
            }
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
    public function edit($id)
    {
         if ($id) {
            $data = CategoryModel::get($id);
            $this->assign('data',$data);
             $datas = CategoryModel::select();
            // 无限极分类
            $datas = createTree($datas);
             $this->assign('datas',$datas);
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
        if (!intval($id)) {
            $this->error('非法请求');
        }
        $post = $request->post();
        $CategoryModel = new CategoryModel;
        // 有 过滤字段
        // $res = $CategoryModel->allowField(true)->save($post,['cate_id'=>$id]);
        
         $res = $CategoryModel->save($post,['cate_id'=>$id]);
       if ($res) {
           $this->redirect('Category/index');
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
            $cate_id =input('cate_id');
            $res = CategoryModel::destroy($cate_id);
            if ($res) {
                echo json_encode(['code'=>0,'msg'=>'删除成功']);
            }else{
                echo json_encode(['code'=>1,'msg'=>'发送未知错误,删除失败']);
            }
        }
    }
}
