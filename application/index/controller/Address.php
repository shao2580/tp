<?php
namespace app\index\controller;

use think\Controller;
use think\Request;

class Address extends Common
{
    /*构造方法*/
    public function __construct()
    {
        parent::__construct();
        // 防非法登录
        if (!$this->checkLogin()) {
            $this->error('请先登录',url('Login/login'));exit;
        }
    }

    /**收货地址-添加-列表
     * @return \think\Response
     */
    public function address()
    {
       // 查询收货地址列表
       $addressInfo=$this->getAddressInfo();
       // 查询省份
       $provinceInfo=$this->getAreaInfo(0);
       

       $this->assign('provinceInfo',$provinceInfo);
       $this->assign('addressInfo',$addressInfo);
       // print_r($addressInfo);
       return view();
    }

    /**地址列表展示
     * [addressDo description]
     * @return [type] [description]
     */
    public function addressDo(){
        $data=input('post.');
        // validat验证？？？
        
        $address_model=model('app\model\Address');
        if ($data['is_default']==1) {
            $user_id=$this->getUserId();
            $address_model->where('user_id',$user_id)->update(['is_default'=>2]);
        }
        $res=$address_model->save($data);
    }

    /*软删除*/
    public function del(){
        $address_id=input('post.address_id','','intval');
        // dump($id);exit;
        $delRess_model=model('app\model\Address');
      
       $res= $delRess_model->where('address_id',$address_id)->update(['is_del'=>2]);
        
        if ($res) {
            successly('删除成功');
        }else{
            fail('删除失败');
        }
        // dump($res);exit;  
    }

    /*设置默认*/
    public function setAddressDefault(){
        $address_id=input('get.address_id','','intval');
        // dump($address_id);
        if (empty($address_id)) {
            $this->error('请至少选择一个收货地址',url('Address/address'));exit;
        }
        $address_model=model('app\model\Address');
        // 开启事务    为了同时完成多个 sql语句
        $address_model->startTrans();
            // 把所有收货地址的状态都改为 2
            $user_id=$this->getUserId();
            $where=[
                ['user_id','=',$user_id],
                ['is_del','=',1]
            ];
            $res1=$address_model->where($where)->update(['is_default'=>2]);

            // 把当前收货地址的状态改为 1
            $res2=$address_model->where('address_id',$address_id)->update(['is_default'=>1]);
         if ($res1 && $res2) {
                // 提交
                $address_model->commit();
                $this->redirect('Address/address');
            }else{
                // 回滚
                $address_model->rollback();
                $this->error('设置失败');
            }         
    }

    /*修改*/
    public function addressUpdate(){
        $address_id=input('get.address_id','','intval');
        // dump($address_id);
            if (empty($address_id)) {
                $this->error('请至少选择一个收货地址',url('Address/address'));exit;
            }

            $address_model=model('app\model\Address');
        // 根据收货地址id 查询出要修改的一条数据
            $where=[
                ['address_id','=',$address_id],
                ['is_del','=',1]
            ];
            $addressInfo=$address_model->where($where)->find();
        // print_r($addressInfo);

        //查询省份
            $provinceInfo=$this->getAreaInfo(0);
        //查询市区
            $cityInfo=$this->getAreaInfo($addressInfo['province']);
        //查询县
            $areaInfo=$this->getAreaInfo($addressInfo['city']);
            
            $this->assign('provinceInfo',$provinceInfo);  //省份     
            $this->assign('cityInfo',$cityInfo);          //市区
            $this->assign('areaInfo',$areaInfo);          //县

            $this->assign('addressInfo',$addressInfo);    //要修改的数据
            return view();
    }

    /*执行修改*/
    public function addressUpdateDo(){
        $data=input('post.','');
        // print_r($data);exit;
        $address_model=model('app\model\Address');
        if ($data['is_default']==1) {
               $user_id=$this->getUserId();
               $where=[
                    ['user_id','=',$user_id]
               ];
           //开启事务 
             $address_model->startTrans();

             $res1=$address_model->where($where)->update(['is_default'=>2]);   //不会报错 sql成功 受影响行数为0
             $res2=$address_model->where('address_id',$data['address_id'])->update($data);
             if ($res1!==false && $res2!==false) {
                 // 执行-提交
                 $address_model->commit();
                 $this->success('修改成功');
             }else{
                // 回滚
                $address_model->rollback();
                 $this->error('修改失败');
             }
        }else{
            $res=$address_model->where('address_id',$data['address_id'])->update($data);  //数据没有发生改变
            if ($res!==false) {
                $this->success('修改成功');
            }else{
                $this->error('修改失败');
            } 
        }       
    }

    /*获取区域*/
    public function getArea(){
        $id=input('post.id','','intval');
        if (empty($id)) {
            fail('请至少选择一个');
        }
       $areaInfo= $this->getAreaInfo($id);
       // print_r($areaInfo);exit;
       if (!empty($areaInfo)) {
           echo json_encode($areaInfo);
       }
    }

    /*获取-地区 地域*/
    public function getAreaInfo($pid){
        $area_model=model('app\model\Area');
        $where=[
            ['pid','=',$pid]
        ];
        $areaInfo=$area_model->where($where)->select()->toArray();
        if (!empty($areaInfo)) {
            return $areaInfo;
        }else{
            return false;
        }
    }











}
