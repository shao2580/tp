<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\controller\Common;
use think\facade\Request as Frequest;
use app\model\Category;
use app\model\Brand;
use app\model\GoodsPhoto;
use app\model\History;
use app\model\Goods as goodsModel;

class Goods extends Common
{
    /**商品列表展示
     * @return \think\Response
     */
    public function goodsList()
    {   
       $this->getLeftCateInfo();

       $cate_id = input('get.cate_id','');
       $cate_model = new Category;
       $goods_model = new goodsModel;
       $brand_model = new Brand;


       // 品牌
       if (empty($cate_id)) {
           $where=[];
       }else{
         //根据顶级id分类查找子类
          $cateInfo = $cate_model->where('is_show','=',1)->select();
        
         $c_id = getCateId($cateInfo,$cate_id);
         $c_id = implode(',',$c_id);
         // dump($c_id);
         $where[] = [
            ['cate_id','in',$c_id]
         ];
       }
       // 根据子类 条件 查一列 在去重复
       $brand_id = $goods_model->where($where)->column('brand_id');
       if (!empty($brand_id)) {
           $brand_id = array_unique($brand_id);
           // dump($brand_id);exit;
           $brandWhere = [
                ['is_show','=',1],
                ['brand_id','in',$brand_id]
           ];
           // 根据条件查所有
           $brandInfo =$brand_model->where($brandWhere)->select();
           // dump($brandInfo);exit;
           // 价格区间
           $max_price = $goods_model->where($where)->order('shop_price','desc')->value('shop_price');
           $priceInfo = $this->getPrice($max_price);
           // dump($priceInfo);exit;

           /*获取浏览记录*/
           // 判断是否登录
           if ($this->checkLogin()) {
              $historyInfo = $this->getHistoryDb();
           }else{
              $historyInfo = $this->getHistoryCookie();
           }
         
             $this->assign('historyInfo',$historyInfo);
           

           //商品数据+分页
           $page_num = 4;
           $page = 1;
           $where[]=['is_on_sale','=',1];
           $goodsInfo = $goods_model->where($where)->page($page,$page_num)->select();
           // 分页
           $count = $goods_model->where($where)->count();
           $page_class = new \page\AjaxPage();
           $str = $page_class->ajaxpager($page,$count,$page_num);

           
           // dump($goodsInfo);die;
          
           $this->assign('brandInfo',$brandInfo);   //品牌
           $this->assign('priceInfo',$priceInfo);   //价格区间
           $this->assign('goodsInfo',$goodsInfo);   //商品展示
           $this->assign('str',$str);               //分页

           $this->assign('cate_id','$cate_id');
           // exit;
           return view();
       }else{
            $this->redirect('Goods/goodslist');
       }    
    }

    /**从数据库获取浏览记录 用于展示
     * [getHistoryDb description]
     * @return [type] [description]
     */
    public function getHistoryDb(){
        $where = [
            'user_id'=>$this->getUserId()
        ];
        $history_model = new History;
        $goods_id = $history_model->where($where)->order('look_time','desc')->column('goods_id');    //在浏览表中查当前用户浏览商品id
        if (!empty($goods_id)) {
          $goods_model = new goodsModel;
          $goos_id = array_slice(array_unique($goods_id),0,2);  // 将商品id去重 在截取  0~2 条数据
          $goods_id = implode(',',$goods_id);  //id转 字符串
          $goodsWhere = [
              ['goods_id','in',$goods_id]
          ];  
          $exp = new \think\db\Expression("field(goods_id,$goods_id)");     //准备自定义排序
          $historyInfo = $goods_model->where($goodsWhere)->order($exp)->select();
          // dump($historyInfo);exit;
          return $historyInfo;
        }else{
            return false;
        }
     }

     /**从cookie中获取浏览记录数据
      * [getHistoryCookie description]
      * @return [type] [description]
      */
     public function getHistoryCookie(){
        $str = cookie('history');
        if (!empty($str)) {
          $history = getBase64Info($str); // 取出数据
          $goods_id = []; 
          foreach ($history as $k => $v) {  //循环取商品id 放到一维数组
                $goods_id[]=$v['goods_id'];
              }  
          $goods_id=array_slice(array_unique(array_reverse($goods_id)),0,2);  //倒序 、去重、分割
          $goods_id = implode(',',$goods_id);
          // dump($goods_id);exit;
          $goods_model = new goodsModel;
          $goodsWhere = [
                ['goods_id','in',$goods_id]
            ];
          $exp = new \think\db\Expression("field(goods_id,$goods_id)");  //准备自定义排序
          $historyInfo = $goods_model->where($goodsWhere)->order($exp)->select();
          return $historyInfo;  
        }else{
          return false;
        }
     }

    // 获取价格区间方法
    public function getPrice($max_price)
    {
        $arr = [];
        $price = $max_price/7;
        for($i=0; $i<7; $i++){
            $start = $price*$i;
            $end = $price*($i+1);   /* 有小数点  $end = $price*($i+1)-0.01;  */
            $arr[]=number_format($start,0,'.',',').'-'.number_format($end,0,'.',',');
        }
        // dump($arr);die;
        $arr[]=$end.'以上';
        return $arr;
    }

    /**重新获取价格区间
    * [getPriceInfo description]
    * @return [type] [description]
    */
    public function getPriceInfo()
    {
        $brand_id =input('post.brand_id','');
        $cate_id  =input('post.cate_id','');
        $cate_model = new Category;
        $goods_model = new goodsModel;
        if (empty($cate_id)) {
            $where = [
              ['brand_id','=',$brand_id]
            ];
        }else{
          
            //根据顶级id分类查找子类
            $cateInfo = $cate_model->where('is_show','=',1)->select();
          
           $c_id = getCateId($cateInfo,$cate_id);
           $c_id = implode(',',$c_id);
           // dump($c_id);
           $where = [
              ['brand_id','=',$brand_id],
              ['cate_id','in',$c_id]
           ];
        }
        // 价格区间
           $max_price = $goods_model->where($where)->order('shop_price','desc')->value('shop_price');
           $priceInfo = $this->getPrice($max_price);
           // print_r($priceInfo);die;
           echo json_encode($priceInfo);
    }

    /**商品数据+分页
     * [getGoodsInfo description]
     * @return [type] [description]
     */
    public function getGoodsInfo()
    {
      $p = input('post.p',1);                       //页码
      $brand_name = input('post.brand_name','');    //品牌
      $shop_price = input('post.shop_price','');    //价格
      $order_field = input('post.order_field','');  //面包屑 筛选
      $order_type = input('post.order_type','');    //筛选 类别
      $field = input('post.field','');           
      $cate_id = input('post.cate_id','');
      // 处理条件
      $where=[
        ['is_on_sale','=',1],
      ];
      if (!empty($cate_id)) {
        // 根据顶级分类 查找子类
           $cate_model = new Category;
          $cateInfo = $cate_model->where('is_show','=',1)->select();
         $c_id = getCateId($cateInfo,$cate_id);
         $c_id = implode(',',$c_id);
         // dump($c_id);
         $where[] = ['cate_id','in',$c_id];
      }

      if (!empty($brand_name)) {
        $where[]=['brand_name','=',$brand_name];
      }

      if (!empty($shop_price)) {
        if (substr_count($shop_price,'-')>0) {
          $price = explode('-',$shop_price);
          $price[0] = str_replace(',','',$price[0]);
          $price[1] = str_replace(',','',$price[1]);
          $where[]=['shop_price','between',$price];
        }else{
          $shop_price = (float)$shop_price;
          $where[] =['shop_price','>',$shop_price];
        }
      }
      if (!empty($field)) {
        $where[]=['is_new','=',1];
      }
        
        //获取数据
        $goods_model = new goodsModel;
        $page_num = 4;
        if (!empty($order_field)) {
           $goodsInfo=$goods_model
           ->alias('g')
           ->leftJoin('brand b',"g.brand_id=b.brand_id")
           ->where($where)
           ->order($order_field,$order_type)
           ->page($p,$page_num)
           ->select(); 
        }else{
           $goodsInfo=$goods_model
           ->alias('g')
           ->leftJoin('brand b',"g.brand_id=b.brand_id")
           ->where($where)
           ->page($p,$page_num)
           ->select(); 
        }
       // echo $goods_model->getLastSql();exit;
       $count=$goods_model
           ->alias('g')
           ->leftJoin('brand b',"g.brand_id=b.brand_id")
           ->where($where)
           ->count();
       $page_class=new \page\AjaxPage();
       $str=$page_class->ajaxpager($p,$count,$page_num);
       $this->assign('goodsInfo',$goodsInfo);
       $this->assign('str',$str);
       $this->assign('cate_id',$cate_id);
       $this->view->engine->layout(false);
       echo $this->fetch('show_info');
    }

    /**商品详情页
     * [goodsDetail description]
     * @return [type] [description]
     */
    public function goodsDetail()
    {
      // 获取左侧分类数据
      $this->getLeftCateInfo();
      // 接收商品ID
      $goods_id=input('get.goods_id','','intval');
      if (empty($goods_id)) {
        $this->redirect('Goods/goodslist');
      }
      // 根据商品id 查一条数据
      $goods_model = new goodsModel;
      $goodsInfo = $goods_model->where('goods_id',$goods_id)->find();
      if (empty($goods_id)) {
        $this->redirect('Goods/goodslist');
      }
      // dump($goodsInfo);die;
      
       /*获取浏览记录*/
           // 判断是否登录
           if ($this->checkLogin()) {
              $historyInfo = $this->getHistoryDb();
           }else{
              $historyInfo = $this->getHistoryCookie();
           }
         
             $this->assign('historyInfo',$historyInfo);
             
      // 根据商品id 查询商品相册
      $goods_photo=new GoodsPhoto;
      $photoInfo = $goods_photo->where('goods_id',$goods_id)->column('url');
      // print_r($photoInfo);exit;
      
      // 记录浏览历史
      if ($this->checkLogin()) {
        // 已登录
        $this->saveHistoryDb($goods_id);
      }else{
        // 未登录
        $this->saveHistoryCookie($goods_id);
      }

      $this->assign('goodsInfo',$goodsInfo);    //商品数据
      $this->assign('photoInfo',$photoInfo);    //相册图片
      return view();
    }

   

    /**储存浏览记录到数据库
     * 
     * @param  [type] $goods_id [description]
     * @return [type]           [description]
     */
    public function saveHistoryDb($goods_id)
    {
        $historyInfo = [
          'goods_id'=>$goods_id,
          'look_time'=>time(),
          'user_id'=>$this->getUserId()
        ];
        $history_model =new History;
        $res=$history_model->save($historyInfo);
        if ($res) {
          return true;
        }else{
          return false;
        }
    } 

    /**存到coolie中
     * 
     * @param  [type] $goods_id [description]
     * @return [type]           [description]
     */
    public function saveHistoryCookie($goods_id)
    {
      // 判断是否有cookie历史记录
      $str = cookie('history');
      // dump($str);exit;
          if (!empty($str)) {
              $historyInfo = getBase64Info($str);   //取 cookie
              if (strlen($str)>=4000) {       //判断cookie容量 超过 要去除第一个cookie 再加入
                array_shift($historyInfo);
              }
              $historyInfo[] = ['goods_id'=>$goods_id,'look_time'=>time()];
          }else{
              $historyInfo= [
                  ['goods_id'=>$goods_id,'look_time'=>time()]
              ];
          }

      
      $historyStr = createBase64Str($historyInfo);  //存 cookie
      cookie('history',$historyStr);
    }
   

    /*检测cookie */
    public function test(){
      print_r(serialize(base64_decode(cookie('history'))));
    }
}
