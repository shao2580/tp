<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\App;
use think\facade\Request as Frequest;
use app\model\Category;  //分类
use app\model\History;  //浏览记录
use app\model\Goods;    //商品
use app\model\Cart;     //购物车
use app\model\Address;  //个人中心-收货地址
use app\model\Area;     //省市区

class Common extends Controller
{
    /** 构造方法--显示资源列表
     * @return \think\Response
     */   
    public function __construct(App $app = null)
    {
        parent::__construct($app);

        // 获取访问控制器的名称
        $controller_name = Frequest::controller();
        $this->assign('controller_name',$controller_name);
    }

    /**左侧 导航
     * [getLeftCateInfo description]
     * @return [type] [description]
     */
    public function getLeftCateInfo(){
        // 获得导航分类
        $cate_model = new Category;
        $navWhere = [
            'is_nav_show'=>1,
        ];
        $navInfo = $cate_model->where($navWhere)->select();

        // 获得左侧分类
        $cateWhere = [
            'is_show'=>1,
        ];
        $cateInfo = $cate_model->where($cateWhere)->select();

        $cateInfo = getLeftCateInfo($cateInfo);
        // dump($cateInfo);exit;
        $this->assign('navInfo',$navInfo);
        $this->assign('cateInfo',$cateInfo);
    }

    /**检测是否登录
     * [checkLogin description]
     * @return [type] [description]
     */
    public function checkLogin(){
       return session('userInfo');
    }

    /**获取用户Id
     * [getUserId description]
     * @return [type] [description]
     */
    public function getUserId(){
        return session('userInfo.u_id');
    }

    /**同步浏览记录
     * [asyncHistory description]
     * @return [type] [description]
     */
    public function asyncHistory(){
        // 把cookie中的数据取出
       $str= cookie('history');
        // 判断是否有浏览记录信息
        if (!empty($str)) {
            // 解密
           $historyInfo = getBase64Info($str);
           // 数组中补全 用户id
           foreach ($historyInfo as $k => $v) {
               $historyInfo[$k]['user_id']=$this->getUserId();
           }
           // 添加到浏览历史表中
           $history_model = new History;
           $res = $history_model->saveAll($historyInfo);
           if ($res) {
               cookie('history',null);
           }
        }
    }

    /**检测库存
     * [checkGoodsNumber description]
     * @param  [type]  $goods_id   [description]
     * @param  [type]  $buy_number [description]
     * @param  integer $number     [description]
     * @return [type]              [description]
     */
    public function checkGoodsNumber($goods_id,$buy_number,$number=0){
        // 根据商品id 查询商品库存
        $goods_model =new Goods;
        $goods_number = $goods_model->where('goods_id',$goods_id)->value('goods_number');
        if (($buy_number+$number)>$goods_number) {
            return false;
        }else{
            return true;
        }
    }

    /**同步购物车
     * [asyncCart description]
     * @return [type] [description]
     */
    public function asyncCart(){
      $str =cookie('cartInfo');
      if (!empty($str)) {
        $user_id=$this->getUserId();
        $cookieInfo = getBase64Info($str);
        // print_r($cookieInfo);exit;
        $cart_model=new Cart;
        foreach ($cookieInfo as $k => $v) {
            $where=[
                'user_id'=>$user_id,
                'goods_id'=>$v['goods_id'],
                'is_del'=>1
            ];
        //查询每条商品是否已经加入购物车
        $info =$cart_model->where($where)->find();
        if (!empty($info)) {
          // 检测库存
          $res=$this->checkGoodsNumber($v['goods_id'],$v['buy_number'],$info['buy_number']);
          if ($res) {
            // 累加
            $updateInfo=[
                'buy_number'=>$v['buy_number']+$info['buy_number'],
                'update_time'=>time()
            ];
            $cart_model->where($where)->update($updateInfo);
          }
          
          
        }else{
          // 检测库存
          $res=$this->checkGoodsNumber($v['goods_id'],$v['buy_number']);
          if ($res) {
            // 添加
            $arr=[
                'goods_id'=>$v['goods_id'],
                'buy_number'=>$v['buy_number'],
                'user_id'=>$user_id
            ];
            Cart::create($arr);

          }
        }

        }
      }
    }

    /**查询收货地址数据
     * [getAddressInfo description]
     * @return [type] [description]
     */
    public function getAddressInfo(){
        $user_id=$this->getUserId();
        $where=[
            ['is_del','=',1],
            ['user_id','=',$user_id]
        ];
        $address_model=new Address;
        $addressInfo=$address_model->where($where)->order('is_default','sort')->select()->toArray();
        if (!empty($addressInfo)) {
            // 处理省市区
            $area_model=new Area;   //或者 不用上面 use 直接 model('app\model\Area')  
            foreach ($addressInfo as $k => $v) {
                $addressInfo[$k]['province']=$area_model->where('id',$v['province'])->value('name');
                $addressInfo[$k]['city']=$area_model->where('id',$v['city'])->value('name');
                $addressInfo[$k]['area']=$area_model->where('id',$v['area'])->value('name');
            }

            return $addressInfo;
        }else{
            return false;
        }
    }

    /*获取当前用户已收藏的商品*/
    public function getCollectId(){
        $user_id=$this->getUserId();
       $collect_model= model('app\model\Collect');
         $goods_id=$collect_model->where('u_id',$user_id)->column('goods_id');
        return $goods_id;
    }


}