<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\model\Book as bookModel;
use app\admin\controller\Common;

class Book extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {   
         $bookModel = new bookModel;
         $data = $bookModel->select();
       if (request()->isAjax()) {
           $result = [
                'code'=>0,
                'msg'=>'',
                'count'=>'$count',
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


    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function doadd(Request $request)
    {
         $post = $request->post(); 
        $bookModel = new bookModel;
      $res = $bookModel->allowField(true)->save($post);
       if ($res) {
           $this->redirect('Book/index');
       }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {   

         
        if ($id) {
            $bookModel = new bookModel;
            $data = $bookModel->get($id);

          // dump($data);exit;
            $this->assign('data',$data);
            return view();
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
           $book_id = input('book_id');
           $bookModel = new bookModel;
           $res = bookModel::destroy($book_id); 
           if ($res) {
               echo json_encode(['code'=>0,'msg'=>'删除成功']);
           }else{
               echo json_encode(['code'=>1,'msg'=>'发送未知错误,删除失败']);
           }
        }
    }
}
