<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\model\Menu as MenuModel;
use app\admin\controller\Common;

class Menu extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {   
        //分页
        $page = input('page')??1;
        $limit = input('limit')??config('pageSize');
        // dump($limit);die;
        $count = MenuModel::count();


        $data = MenuModel::order('menu_id','desc')->page($page,$limit)->select();
     
        // $data = MenuModel::select();
        // 无限极分类
        $data =createTree($data,0,1,'menu_id');
        if ($data) {
            foreach ($data as $key => $v) {
                $data[$key]['level'] = str_repeat("☆",$v['level']-1);
            }
        }

        // 判断是否Ajax 提交
        if (request()->isAjax()) {
            $result = [
                  'code'=>0,
                  'msg'=>'success',
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
        $data = MenuModel::select();
        // 无限极分类
    
        $data = createTree($data,0,1,'menu_id');
        
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
        $res = MenuModel::create($post);
        if ($res) {
            $this->redirect('Menu/index');
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
            $data = MenuModel::get($id);
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
        if (!intval($id)) {
            $this->error('非法请求');
        }
        $post = $request->post();
        $MenuModel = new MenuModel;
        $res = $MenuModel->allowField(true)->save($post,['menu_id'=>$id]);
        if ($res) {
            $this->redirect('Menu/index');
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
            $menu_id = input('menu_id');
            $res = MenuModel::destroy($menu_id);
            if ($res) {
                echo json_encode(['code'=>0,'msg'=>'删除成功']);
            }else{
                echo json_encode(['code'=>1,'msg'=>'发送未知错误，删除失败']);
            }
        }
    }
}
