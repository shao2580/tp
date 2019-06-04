<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\facade\Config;
// use app\model\Cart;

class Order extends Common
{
    /**确认订单 --显示资源列表
     * @return \think\Response
     */
    public function confirmOrder()
    {
        $goods_id=input('get.goods_id','');
        // dump($goods_id);exit;
        // 检测是否有商品
        if (empty($goods_id)) {
            $this->error('请至少选择一件商品进行结算',url('Cart/cartList'));
            exit;
        };
        //左侧 导航 分类
        $this->getLeftCateInfo();
        // 获取购物车商品数据
        $info=$this->getCartInfo($goods_id);

        // 获取收货地址信息
        $addressInfo=$this->getAddressInfo();
        
        $this->assign('cartData',$info['cartData']);  //商品详情数据
        $this->assign('count',$info['count']);        //商品--总价
        $this->assign('addressInfo',$addressInfo);  //收货地址
        return view();
    }

    /**获取购买的购物车数据
     * [getCartInfo description]
     * @param  [type] $goods_id [description]
     * @return [type]           [description]
     */
    public function getCartInfo($goods_id){
        // dump($goods_id);exit;
        $cart_model =model('app\model\Cart');
        $user_id=$this->getUserId();
        $where=[
            ['c.goods_id','in',$goods_id],
            ['user_id','=',$user_id],
            ['is_del','=',1],
            ['is_on_sale','=',1]
        ];
        $cartData=$cart_model
                ->field('g.goods_id,goods_name,goods_img,shop_price,buy_number')
                ->alias('c')
                ->join('goods g','c.goods_id=g.goods_id')
                ->where($where)
                ->select();
        $count=0;
        foreach ($cartData as $k => $v) {
            $cartData[$k]['subTotal']=$subTotal=$v['shop_price']*$v['buy_number'];
            $count+=$subTotal;
        }
        $info['cartData']=$cartData;
        $info['count']=$count;
        // print_r($info);exit;

        return $info;
    }

    /**提交订单
     * [submitOrder description]
     * @return [type] [description]
     */
    public function submitOrder(){
        // 获取数据
        $goods_id=input('post.goods_id','');   //商品ID
        $address_id=input('post.address_id','','intval'); //收货地址id 
        $pay_type=input('post.pay_type','','intval');  //支付方式
        $order_talk=input('post.order_talk','');  //订单留言
        // 验证
        if (empty($goods_id)) {
            fail('请至少选择一件商品');
        }
        if (empty($address_id)) {
            fail('请选择一个收货地址');
        }
        if (empty($pay_type)) {
            fail('请选择支付方式');
        }

        $order_model=model('app\model\Order');  //订单表！！！
        try{  //试运行
            $user_id=$this->getUserId();  //获取用户id
            // 开启事务
            $order_model->startTrans();

            /****添加订单表数据****/   // 单号(生成订单规则)？？ 总金额？？
            $order_no=$this->createOrderNo();   //订单号
            $order_amount=$this->getOrderAmount($goods_id); //订单总金额
            $orderInfo['pay_type']=$pay_type;
            $orderInfo['order_talk']=$order_talk;
            $orderInfo['user_id']=$user_id;
            $orderInfo['order_no']=$order_no;
            $orderInfo['order_amount']=$order_amount;

            $res1=$order_model->save($orderInfo);
            if (empty($res1)) {
                throw new Exception('订单信息添加失败');
            }
            
            /*****订单详情表添加******/  //订单id(获取刚刚添加的自增id)？？  多条数据的添加准备的数组格式？？
            $order_id=$order_model->order_id;    // TP5.1中直接调自增ID
            $goodsInfo=$this->getOrderDetail($goods_id);  //商品详情

            foreach ($goodsInfo as $k => $v) {
                $goodsInfo[$k]['order_id']=$order_id;
                $goodsInfo[$k]['user_id']=$user_id;
            }
            if (empty($goodsInfo)) {
                throw new Exception('没有商品详情信息');
            }

            $order_detail=model('app\model\OrderDetail');    //商品详情表！！！
            $res2=$order_detail->allowField(true)->saveAll($goodsInfo);
            if (empty($res2)) {
                throw new Exception('订单详情写入失败');
            }

            /*******订单收货地址添加********/
            $addressWhere=[
                ['address_id','=',$address_id],
                ['is_del','=',1]
            ];
            $address_model=model('app\model\Address');   //收货地址表！！！
            $addressInfo=$address_model->where($addressWhere)->find()->toArray();
            if (empty($addressInfo)) {
                throw new Exception('没有此收货地址,请重新输入');
            }
            unset($addressInfo['create_time']);
            unset($addressInfo['update_time']);
            $addressInfo['order_id']=$order_id;
            $order_address=model('app\model\OrderAddress');  //定单收货地址表
            $res3=$order_address->allowField(true)->save($addressInfo);
            if (empty($res3)) {
                throw new Exception('订单收货地址添加失败');
            }
           
            /*******删除付过款 的购物车数据***软删除*********/
            $cart_model=model('app\model\Cart');    //购物车表
            $cartWhere=[
                ['user_id','=',$user_id],
                ['goods_id','in',$goods_id],
                ['is_del','=',1]
            ];
            $res4=$cart_model->where($cartWhere)->update(['is_del'=>2]);
            if (empty($res4)) {
                throw new Exception('购物车删除失败');
            }

            /***************减少库存*******************/
            $goods_model=model('app\model\Goods');    //商品表
            foreach ($goodsInfo as $k => $v) {
                $goodsWhere=[
                    ['goods_id','=',$v['goods_id']]
                ];
                $updateInfo=[
                    'goods_number'=>$v['goods_number']-$v['buy_number']
                ];
                $res5=$goods_model->where($goodsWhere)->update($updateInfo);
                if (empty($res5)) {
                   throw new Exception('减少库存修改失败'); 
                }
            }
              
            /***提交**执行***下单成功****/
                $order_model->commit();
                $arr=[
                    'code'=>1,
                    'font'=>'下单成功',
                    'order_id'=>$order_id
                ];
                echo json_encode($arr);

           }catch(Exception $e){
            $order_model->rollback();
            fail($e->getMessage());
           }
    
    }

    /*生成订单号*/
    public function createOrderNo(){
         return date('Ymd').rand(1000,9999).$this->getUserId();
    }

    /*计算订单总金额*/
    public function getOrderAmount($goods_id){
        $cart_model=model('app\model\Cart');   //购物车表
        $user_id=$this->getUserId();
        $where=[
            ['c.goods_id','in',$goods_id],
            ['user_id','=',$user_id],
            ['is_on_sale','=',1],
            ['is_del','=',1]
        ];
        $count=0;
        $cartInfo=$cart_model
            ->field('shop_price,buy_number')
            ->alias('c')
            ->join('Goods g','c.goods_id=g.goods_id')
            ->where($where)
            ->select()
            ->toArray();

        foreach ($cartInfo as $k => $v) {
            $count+=$v['shop_price']*$v['buy_number'];
        }
        return $count;
    }

    /*获取商品详情信息*/
    public function getOrderDetail($goods_id){
        $cart_model=model('app\model\Cart');   //购物车表
        $user_id=$this->getUserId();
        $where=[
            ['c.goods_id','in',$goods_id],
            ['user_id','=',$user_id],
            ['is_on_sale','=',1],
            ['is_del','=',1]
        ];
        $goodsInfo=$cart_model
            ->field('g.goods_id,goods_name,goods_img,goods_number,shop_price,buy_number')
            ->alias('c')
            ->join('Goods g','c.goods_id=g.goods_id')
            ->where($where)
            ->select()
            ->toArray();
        return $goodsInfo;  
    }

    /*下单成功*/
    public function successOrder(){
        $order_id=input('get.order_id','','intval');
        
        try{
           // 验证订单号
            if (empty($order_id)) {
                throw new \Exception('请选择正确的订单');
            }
            $user_id=$this->getUserId();
            $where=[
                ['order_id','=',$order_id],
                ['is_del','=',1],
                ['user_id','=',$user_id]
            ];
            $order_model=model('app\model\Order');
            $orderInfo=$order_model->where($where)->find();
            // print_r($orderInfo);exit;
            if (empty($orderInfo)) {
                throw new \Exception();
            }

            $this->assign('orderInfo',$orderInfo);
            $this->getLeftCateInfo();  //导航 分类
            return view();
        }catch(\Exception $e){
            echo $e->getMessage();
        }      
    }

    /*支付*/
    public function payMoney(){
        $id=input('get.id');
        $order_model=model('app\model\Order');
        $where=[
            'order_id'=>$id,
            'is_del'=>1
        ];
        $data=$order_model->where($where)->find();
        // dump($data);exit;

        $config=Config::get('pay.');    //配置
        // print_r($config);exit;
        require_once '../extend/alipay/pagepay/service/AlipayTradeService.php';   
        require_once '../extend/alipay/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';

          //商户订单号，商户网站订单系统中唯一订单号，必填
            $out_trade_no = "{$data['order_no']}";

            //订单名称，必填
            $subject = '支付宝支付';

            //付款金额，必填
            $total_amount = "{$data['order_amount']}";

            //商品描述，可空
            $body = "{$data['order_talk']}";
            
            //构造参数
            $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
            $payRequestBuilder->setBody($body);
            $payRequestBuilder->setSubject($subject);
            $payRequestBuilder->setTotalAmount($total_amount);
            $payRequestBuilder->setOutTradeNo($out_trade_no);
            
            $aop = new \AlipayTradeService($config);

            /**
             * pagePay 电脑网站支付请求
             * @param $builder 业务参数，使用buildmodel中的对象生成。
             * @param $return_url 同步跳转地址，公网可以访问
             * @param $notify_url 异步通知地址，公网可以访问
             * @return $response 支付宝返回的信息
            */
            
            $response = $aop->pagePay($payRequestBuilder,$config['return_url'],$config['notify_url']);
          
            //输出表单
         //   var_dump($response);

    }

    public function paySuccess(){
        //接受数据
        
        //验证订单号是否正确
        
        //验证订单金额是否正确
        
        //验证签名是否正确
        
        // 提示支付成功
        

        // 异步通知中改 支付状态 订单状态
        
        $this->redirect('art/cartlist');
    }      

}
