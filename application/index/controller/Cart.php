<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\model\Cart as cartModel;
use app\model\Goods;

class Cart extends Common
{
	/**1 加入购物车
	 * [addCart description]
	 */
    public function addCart(){
    	$goods_id = input('post.goods_id','intval');  		// intval  变量转整数
    	$buy_number = input('post.buy_number','intval');
    	// 验证
    	if (empty($goods_id)) {
    		fail('请选择一件商品');
    	}
    	if (empty($buy_number)) {
    		fail('请选择购买数量');
    	}

    	// 判断是否登录
    	 if ($this->checkLogin()) {
    	 	$this->saveCartDb($goods_id,$buy_number);   //登录后 存数据库
    	 }else{
    	 	$this->saveCartCookie($goods_id,$buy_number);  //没登录 存Cookie
    	 }   	
    }
    /**1-1登录后 存数据库
     * [saveCartDb description]
     * @return [type] [description]
     */
    public function saveCartDb($goods_id,$buy_number){
    	// $cart_model = model('Cart');
    	$cart_model = new cartModel;

    	$user_id = $this->getUserId();
    	//判断当前用户是否买过该商品
    	$where = [
    		['goods_id','=',$goods_id],
    		['user_id','=',$user_id],
    		['is_del','=',1]    // 要查询未删除的数据
    	];

    	// 判断用户是否买过该商品
    	$cartInfo = $cart_model->where($where)->find();
    	if (!empty($cartInfo)) {
    		// 判断库存 累加
    		$res=$this->checkGoodsNumber($goods_id,$buy_number,$cartInfo['buy_number']);
    		if ($res) {
    			$updateInfo =[
    				'buy_number'=>$cartInfo['buy_number']+$buy_number,
    				'update_time'=>time()
    			];
    			$result = $cart_model->save($updateInfo,$where);
    		}else{
    			fail('库存不足');
    		}

    	}else{
    		// 判断库存 添加
    		$res =$this->checkGoodsNumber($goods_id,$buy_number);
    		if ($res) {
    			$info = ['goods_id'=>$goods_id,'buy_number'=>$buy_number,'user_id'=>$user_id];
    			
    			$result= $cart_model->save($info);  //添加到数据库
    		}else{
    			fail('库存不足');
    		}
    	}
    	if($result){
    		successly('加入购物车成功');
    	}else{
    		fail('加入购物车失败');
    	}
    }
    /**1-2没登录 存Cookie
     * [saveCartDb description]
     * @return [type] [description]
     */
    public function saveCartCookie($goods_id,$buy_number){
    	//取出cookie的值
    	$str= cookie('cartInfo');
    	if (!empty($str)) {
    		$cartInfo=getBase64Info($str);

    		$flag=0;
    		// 判断当前商品是否在cookie中
    		foreach ($cartInfo as $k => $v) {
    			if ($goods_id==$v['goods_id']) {
    				// 检查库存
    				$res =$this->checkGoodsNumber($goods_id,$buy_number,$v['buy_number']);
    				if (!$res) {
    					fail('库存不足哦');
    				}
    			//累加
    			$cartInfo[$k]['buy_number']=$buy_number+$v['buy_number'];
    			$cartStr = createBase64Str($cartInfo);

    			cookie('cartInfo',$cartStr);
    			successly('添加购物车成功');
    			exit;
    			}else{
    				$flag=1;
    			}
    		}
    		if ($flag==1) {
    			// 上面准备 多次加购物车 存 cookie   检查库存
    			$res = $this->checkGoodsNumber($goods_id,$buy_number);
    			if (!$res) {
    				fail('库存不足哦');
    			}
    			// 添加
    			$cartInfo[]=[
    				'goods_id'=>$goods_id,
    				'buy_number'=>$buy_number,
    				'update_time'=>time()
    			];
    			$cartStr = createBase64Str($cartInfo);
    			cookie('cartInfo',$cartStr);

    		}
    	}else{
    		//首次加购物车 检查库存
    		$res = $this->checkGoodsNumber($goods_id,$buy_number);
    		if (!$res) {
    			fail('库存不足哦');
    		}

    		// 添加
    		$info[]=[
    			'goods_id'=>$goods_id,
    			'buy_number'=>$buy_number
    		];
    		$cartStr = createBase64Str($info);
    		cookie('cartInfo',$cartStr);
    		
    	}
    }

    /************************************/
    /**2 购物车列表
     * [cartList description]
     * @return [type] [description]
     */
    public function cartList(){
    	$this->getLeftCateInfo();
    	// 判断是否登录
    	if ($this->checkLogin()) {
    		$cartInfo = $this->getCartInfoDb();
    	}else{
    		$cartInfo = $this->getCartInfoCookie();
    	}
    	// print_r($cartInfo);exit;
    	
    	if (!empty($cartInfo)) {
    		foreach ($cartInfo as $k => $v) {
    			$total=$v['shop_price']*$v['buy_number'];
    			$cartInfo[$k]['total']=$total;
    			
    		}
    	}
    	// 查询当前用户收藏过的商品id
        $goods_id=$this->getCollectId();

        $this->assign('cartInfo',$cartInfo);
    	$this->assign('goods_id',$goods_id);
    	return view();
    }
    /*2-1从数据库获取购物车数据*/
    public function getCartInfoDb(){  	
    	$cart_model = new cartModel;
    	$user_id=$this->getUserId();
    	$where =[
    		['user_id','=',$user_id],
    		['is_on_sale','=',1],
    		['is_del','=',1]    // 要查询未删除的数据
    	];
    	$cartInfo =$cart_model
    			->alias('c')
    			->join("goods g","c.goods_id=g.goods_id")
    			->where($where)
    			->order('cart_id','desc')
    			->select();
    	// dump($cartInfo);exit; 
    	if (!empty($cartInfo)) {
    		return $cartInfo;
    	}else{
    		return false;
    	}
    }
    /*2-2从Cookie取购物车数据*/
    public function getCartInfoCookie(){
    	$str = cookie('cartInfo');
    	// dump($str);exit;
    	if (!empty($str)) {
    		// 解密
    		$cartInfo=getBase64Info($str);

    		// dump($cartInfo);exit;
    		// 获取商品ID
    		$goods_id=[];
    		$goods_model = new Goods;
    		$cartInfo=array_reverse($cartInfo);
    		// dump($cartInfo);exit;
    		foreach ($cartInfo as $k => $v) {
    			$goods_id[]=$v['goods_id'];
    		}
    		$goods_id=implode(',',$goods_id);

    		$where=[
    			['goods_id','in',$goods_id],
    			['is_on_sale','=',1]
    		];
    		// 根据商品id获取数据
    		$exp = new \think\db\Expression("field(goods_id,$goods_id)");  //准备自定义排序
            $info = $goods_model->where($where)->order($exp)->select();

          	// 处理商品数组 把购买数量加入
          	foreach ($info as $k => $v) {
          		foreach ($cartInfo as $key => $val) {
          			if ($v['goods_id']==$val['goods_id']) {
          				$info[$k]['buy_number']=$val['buy_number'];
          			}
          		}
          	}
          	return $info;
          
    	}else{
    		return false;
    	}
    }

    /*************************************/
    /**3 获取商品总价
     * [countTotal description]
     * @return [type] [description]
     */
    public function countTotal(){
    	$goods_id=input('post.goods_id','');
    	if (empty($goods_id)) {
    		echo 0;  exit;
    	}
    	if ($this->checkLogin()) {
    		$this->countTotalDb($goods_id); 
    	}else{
    		$this->countTotalCookie($goods_id);
    	}
    }
    /*3-1获取商品总价 登录后的 数据库 */
    public function countTotalDb($goods_id){
    	$user_id= $this->getUserId();
    	$where=[
    		['c.goods_id','in',$goods_id],
    		['is_on_sale','=',1],
    		['user_id','=',$user_id]
    	];
    	$cart_model = new cartModel;
    	$info=$cart_model
    		->field('buy_number,shop_price')
    		->alias('c')
    		->join('goods g','c.goods_id=g.goods_id')
    		->where($where)
    		->select();
    	$count=0;
    	foreach ($info as $k => $v) {
    		$count+=$v['shop_price']*$v['buy_number'];
    	}

    	echo $count;
    }
    /*3-2获取商品总价 登录前 Cookie */
    public function countTotalCookie($goods_id){
    	$g_id=explode(',',$goods_id);
    	$str=cookie('cartInfo');
    	$info=[];
    	if (!empty($str)) {
    		$cartInfo=getBase64Info($str);
    		// print_r($cartInfo);exit;
    		foreach ($cartInfo as $k => $v) {
    			if (in_array($v['goods_id'],$g_id)) {
    				$info[]=$v;
    			}
    		}
    		$goods_model=new Goods;
    		$count=0;
    		foreach ($info as $k => $v) {
    			$where=[
    				['goods_id','=',$v['goods_id']],
    				['is_on_sale','=',1]
    			];
    			$shop_price=$goods_model->where($where)->value('shop_price');
    			$count+=$shop_price*$v['buy_number'];	
    		}
    		echo $count;		
    	}
    }

    /*************************************/
    /**4 更改购买数量
     * [changeBuyNumber description]
     * @return [type] [description]
     */
    public function changeBuyNumber(){
    	$goods_id = input('post.goods_id','','intval');
    	$buy_number=input('post.buy_number','','intval');
    	if (empty($goods_id)) {
    		fail('请至少选择一个商品');
    	}
    	if (empty($buy_number)) {
    		fail('购买数量不能为空');
    	}
    	if ($this->checklogin()) {
    		$this->changeBuyNumberDb($goods_id,$buy_number);
    	}else{
    		$this->changeBuyNumberCookie($goods_id,$buy_number);
    	}
    }
    /*4-1更改 数据库中 的购买数量*/
    public function changeBuyNumberDb($goods_id,$buy_number){
    	// 检测库存
    	$res=$this->checkGoodsNumber($goods_id,$buy_number);
    	if ($res) {
    		$where=[
    			'goods_id'=>$goods_id,
    			'user_id'=>$this->getUserId()
    		];
    		$updateInfo=[
    			'buy_number'=>$buy_number,
    			'update_time'=>time()
    		];
    		$cart_model=new cartModel;
    		$result=$cart_model->save($updateInfo,$where);
    		if ($result) {
    			successly('修改数量成功');
    		}else{
    			fail('修改数量失败');
    		}
    	}else{
    		fail('购买数量超过了库存量');
    	}
    }
    /*4-2更改cookie中的购买数量*/
    public function changeBuyNumberCookie($goods_id,$buy_number){
    	$str=cookie('cartInfo');
    	if (!empty($str)) {
    		$cartInfo=getBase64Info($str);
    		foreach ($cartInfo as $k => $v) {
    			if ($goods_id==$v['goods_id']) {
    				// 检查库存
	    			if (!$this->checkGoodsNumber($goods_id,$buy_number)) {
	    				fail('购买数量超过了库存量');
	    			}
	    			$cartInfo[$k]['buy_number']=$buy_number;
	    			$cart_str=createBase64Str($cartInfo);
	    			cookie('cartInfo',$cart_str);
	    			successly('成功');
    			}
    			
    		}
    	}
    }

    /****************************************/
    /**5 获取商品小计
     * [getSubTotal description]
     * @return [type] [description]
     */
    public function getSubTotal(){
    	$goods_id=input('post.goods_id','','intval');
    	if (empty($goods_id)) {
    		echo 0;  exit;
    	}
    	if ($this->checkLogin()) {
    		$this->getSubTotalDb($goods_id);
    	}else{
    		$this->getSubTotalCookie($goods_id);
    	}
    }
    /*5-1获取商品小计 从数据库 */
    public function getSubTotalDb($goods_id){
    	// 获取商品价格
    	$goods_model=new Goods;
    	$goodsWhere=[
    		['is_on_sale','=',1],
    		['goods_id','=',$goods_id]
    	];
    	$shop_price=$goods_model->where($goodsWhere)->value('shop_price');

    	//获取商品购买数量
    	$cart_model=new cartModel;
    	$user_id=$this->getUserId();
    	$cartWhere=[
    		['goods_id','=',$goods_id],
    		['user_id','=',$user_id]
    	];
    	$buy_number = $cart_model->where($cartWhere)->value('buy_number');
    	echo $shop_price*$buy_number;
    }
    /*5-2获取商品小计 从Cookie */
    public function getSubTotalCookie($goods_id){
    	$str=cookie('cartInfo');
    	if (!empty($str)) {
    		$cartInfo=getBase64Info($str);
    		// print_r($cartInfo);exit;
    		foreach ($cartInfo as $k => $v) {
    			if ($goods_id==$v['goods_id']) {
    				$buy_number=$v['buy_number'];  //获取购买数量
    			}
    		}
    		// 获取商品价格
    		$where=[
    			['goods_id','=',$goods_id],
    			['is_on_sale','=',1]
    		];
    		$goods_model=new Goods;
    		$shop_price = $goods_model->where($where)->value('shop_price');
    		echo $buy_number*$shop_price;
    	}
    }

    /***************************************/
    /**6 删除购物车数据
     * [cartDel description]
     * @return [type] [description]
     */
    public function cartDel(){
    	$goods_id = input('post.goods_id','');
    	// 判断是否登录
    	if ($this->checkLogin()) {
    		$this->cartDelDb($goods_id);
    	}else{
    		$this->cartDelCookie($goods_id);
    	}
    }
    /*6-1删除购物车 从数据库*/
    public function cartDelDb($goods_id){
    	$cart_model=new cartModel;
    	// 拼接条件
    	$user_id=$this->getUserId();
    	$where=[
    		['user_id','=',$user_id],
    		['goods_id','in',$goods_id]
    	];  //in  单删 批删都能用 in(2)  in(2,4,5)
    	$updateInfo=[
    		'is_del'=>2
    	]; //修改的数据为一维数组 不能用中括号
    	$res=$cart_model->where($where)->update($updateInfo);   //软删除 改状态
    	if ($res) {
    		successly('删除成功');
    	}else{
    		fail('删除失败');
    	}
    }
    /*6-2删除购物车 从Cookie*/
    public function cartDelCookie($goods_id){
    	
    	$str=cookie('cartInfo');
    	$g_id=explode(',',$goods_id);
    	if (!empty($str)) {
    		$cartInfo=getBase64Info($str);
    		foreach ($cartInfo as $k => $v) {
    			if (in_array($v['goods_id'],$g_id)) {
    				unset($cartInfo[$k]);  //释放cook中的全部值			
    			}
    		}
    		if (!empty($cartInfo)) {
    			$cart_str=createBase64Str($cartInfo);
    			cookie('cartInfo',$cart_str);
    			successly('删除成功');
    		}else{
    			cookie('cartInfo',null);  //清空Cookie
    		}
    		
    	}else{
    		fail('删除失败');
    	}
    }

    /***************************************/
    // 加入收藏
    public function addCollect(){
        $goods_id=input('post.goods_id',0,'intval');
        if ($this->checkLogin()) {
            $user_id=$this->getUserId();
            $where=[
                ['u_id','=',$user_id],
                ['goods_id','=',$goods_id]
            ];
            $collect_model=model('app\model\Collect');
           $info = $collect_model->where($where)->find();
            if ($info) {
                fail('已收藏');
            }else{
                $result=$collect_model->save(['u_id'=>$user_id,'goods_id'=>$goods_id]);
                if ($result) {
                    successly('收藏成功');
                }else{
                    fail('收藏失败! ');
                }
            }
        }else{
            fail('请先登录');
        }
    }

    /*******************************/
     /*判断是否登录*/
    public function isLogin(){
        $res = $this->checkLogin();
        if ($res) {
            successly('登录成功');
        }else{
            fail('请先登录');
        }
    }
    
    /********************************/
    public function test(){
    	$str = cookie('cartInfo');
    	$cartInfo=getBase64Info($str);
    	print_r($cartInfo);
    }

}
