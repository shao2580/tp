<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\controller\Common;
use app\model\Goods as goodsModel;

class Activity extends Common
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

        $count = goodsModel::count();


        $data = goodsModel::order('goods_id','desc')->page($page,$limit)->select();
        // dump($data);die;
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
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function setting()
    {   
        $goods_id=2;
        
        $goods_model=new goodsModel;
        $goodsInfo=$goods_model->where('goods_id',$goods_id)->select();
        // $this->assign('goodsInfo',$goodsInfo);
        return view();
    }


    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function doadd()
    {   
        // $goods_id = input('post.goods_id');
        $goods_id=2;
        
        $goods_model=new goodsModel;
        $goodsInfo=$goods_model->where('goods_id',$goods_id)->select('goods_name','goods_number'); 
        // dump($goodsInfo);exit;

        // 控制器验证
        $validate = new \app\admin\validate\setting;
        if (!$validate->check($goodsInfo)) {
            return view('setting',['goodsInfo'=>$goodsInfo,'error'=>$validate->getError()]);
        }



        $this->assign('goodsInfo',$goodsInfo);
        return view();

    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function add(Request $request)
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
