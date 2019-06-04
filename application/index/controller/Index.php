<?php
namespace app\index\controller;
use think\Controller;
use app\model\Category;
use app\model\Goods;
use app\index\controller\Common;

class index extends Common
{
	/**前台首页
	 * [index description]
	 * @return [type] [description]
	 */
    public function index()
    {
       // 查询左侧分类数据 左侧分类 导航分类
       $this->getLeftCateInfo();

       // 获取楼层数据
       $cate_id = 1;
       $floorInfo = $this->getFloorInfo($cate_id);
       $floorInfo['floorNum'] =1;

       $this->assign('floorInfo',$floorInfo);
       // dump($floorInfo);die;
      
       return view();
    }

    // 获取楼层数据方法
    public function getFloorInfo($cate_id){
    	$cate_model = new Category;
    	$info = [];

    	// 1、顶级分类数据
    	$where = [
    		'is_show'=>1,
    		'cate_id'=>$cate_id
    	];
    	$info['topCate'] = $cate_model->field('cate_id,cate_name')->where($where)->find();
    	// dump($info);die;
    	
    	// 2、顶级分类下二级分类
    	$where1 =[
    		'is_show'=>1,
    		'parent_id'=>$cate_id
    	];
    	$info['sonCate'] = $cate_model->field('cate_id,cate_name')->where($where1)->select();
    	// dump($info);die;
    	
    	// 3、商品数据
    	// 获取当前分类下的所有末级（子级）分类的cate_id
    	$cateInfo = $cate_model->where(['is_show'=>1])->select();
    	$c_id = getCateId($cateInfo,$cate_id);
    	$c_id = implode(',',$c_id);

    	$goods_model =new Goods;
    	$info['goodsInfo'] = $goods_model->where('cate_id','in',$c_id)->select();
    	// dump($info);die;
    	return $info;
    }

    // 获得更多楼层数据
    public function getMore(){
    	$cate_id = input('post.cate_id');  //当前楼层id
    	
    	$floorNum = input('post.floorNum'); //楼层数字
    	$cate_model = new Category;
    	//获取比当前分类id大的 id中最小的分类id
    	$where = [
    		['parent_id','=',0],
    		['cate_id','>',$cate_id]
    	];
    	$c_id = $cate_model->where($where)->order('cate_id','asc')->value('cate_id');
   		
    	// 获取下一级的分类id
    	$floorInfo = $this->getFloorInfo($c_id);
    	$floorInfo['floorNum'] = $floorNum+1;

    	// 把楼层数据显示到一个div视图页面，获取这个div视图页面 响应给js
    	$this->view->engine->layout(false);
    	$this->assign('floorInfo',$floorInfo);
    	echo $this->fetch('div');

    }
    
}
