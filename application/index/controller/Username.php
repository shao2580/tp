<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\User as UserModel;
use think\Db;

class Username extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $date = UserModel::select();
        // $this->assign('vv',$date);
        // return $this->fetch('index',['vv'=>$date]);
        
        return view('index',['vv'=>$date]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function add(Request $request)
    {
        $res = $request->ispost();
       if ($res) {
        //接收方式 input 助手函数
         $post=input('post.');

         // Db 类 添加 insert insertGetId strict(false) 过滤字段
         
         // $res = Db::name('user')->strict(false)->insertGetId($post);
         // $res = Db::name('user')->strict(false)->insert($post);
         
         // Model 静态化添加
         $res = UserModel::create($post);
         // Model save() allowField(true)字段过滤 
         // $user = new UserModel;
         // $res = $user->save($post);
         // 查看最后一个sqi语句
         // echo $user->getLastSql();
         // 获取自增id
         // echo $user->uid;
         

         if ($res) {
             $this->success('添加成功','User/index');
         }else{
            $this->error('添加失败','User/add');
         }
         
       }

        return view();
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
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
    // 编辑--查询
    public function edit($id)
    {
        if ($id) {
             //db 类操作查询单条
           // $data = Db::table('user')->where('uid',$id)->find();
           // $data = Db::name('user')->where('uid',$id)->find();
          //  $data = db('user')->where('uid',$id)->find();
          //  $data = db('user')->where("uid=$id")->find();
           // $data = db('user')->where(['uid'=>$id])->find();
            
            //model 模型层查询单条
            $date = UserModel::get($id);
            
            $this->assign('vv',$date);
            
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
    // 修改
    public function update(Request $request, $id)
    {
         $post = $request->post();

        //db 更新
        //$res = Db::table('user')->where('uid='.$id)->update($post);
        //$res = Db::name('user')->where('uid='.$id)->update($post);
        //$res = db('user')->where('uid='.$id)->update($post);
        //$res = db('user')->where('uid',$id)->update($post);
        //$res = db('user')->where(['uid'=>$id])->update($post);
        //model 更新
        //$res = UserModel::where(['uid'=>$id])->update($post);
        
        $user = new UserModel;
        $res = $user->save($post,['uid'=>$id]);
        
        if( $res ){
            $this->redirect('User/index');
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
          if( $id ){
            //db  delete()
           // $res = Db::table('user')->delete($id);
            //db  delete() 删除多条
           // $res = Db::table('user')->delete([3,4,5]);
            
            //model delete()
            //$user = new UserModel;
            //$res=$user->where('uid',$id)->delete();
            
            $res = UserModel::destroy($id);
            
            if( $res ){
                $this->redirect('User/index');
            }   
            
        }
    }
    public function checkedUsername(){
        $username = input('get.name');
        
        $res=UserModel::where(['name'=>$username])->count();
        if ($res) {
            echo 1;
        }else{
            echo 0;
        }
    }
}
