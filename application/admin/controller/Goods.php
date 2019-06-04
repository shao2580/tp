<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\controller\Common;
use app\model\Category;
use app\model\Brand;
use app\model\Goods_photo;
use app\model\Goods as goodsModel;
use think\facade\Log; 

class Goods extends Common
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
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function add()
    {
        $cate = Category::select();
        // 无限极分类
        
        $cate = createTree($cate);
        $this->assign('cate',$cate);
        // dump($cate);die;
        $brand = Brand::where('is_show',1)->select();   
        $this->assign('brand',$brand);
        return view();
    }
    /**
     * 表单验证 货号唯一性 add.html\form表单验证
     * [checkgoodssn description]
     * @return [type] [description]
     */
    public function checkgoodssn()
    {
        if (request()->isAjax()) {
            $goods_sn = input('goods_sn');

            $count = goodsModel::where('goods_sn',$goods_sn)->count();
            echo $count;
        }
    }
    /**
     * s生成缩略图
     * [imgthumb description]
     * @return [type] [description]
     */
    public function imgthumb($img){
        // dirname 上一级
        // $img = dirname(dirname(dirname(dirname(__FILE__)))).'/public/uploads/'.$img;
        $image = \think\Image::open('./uploads/'.$img);
        // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.png
        $length = stripos($img,'.');
        $thumb = substr($img,0,$length).'_thumb'.substr($img,$length);
        $image->thumb(150, 150)->save('./uploads/'.$thumb);

        return $thumb;
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
        // dump($post);die;
        // 开启事务
        goodsModel::startTrans();
        try {
                
            $post['content'] = $post['editorValue'];

            $goods = new goodsModel();
            $goods_id = $goods->strict(false)->insertGetId($post);

            // 商品货号 如果不存在则系统生成
            $post['goods_sn']='';

            $goods_sn=isset($post['goods_sn']) && $post['goods_sn']!=''??$this->createGoodsSn($goods_id);
            // dump($goods_sn);die;
           $res = $goods->save(['goods_sn'=>$goods_sn],['goods_id'=>$goods_id]);
           // dump($res);die;
            // 商品相册
            $this->addGoodsPhoto($goods_id,$post['goods_photo']);
            // 提交事务
            goodsModel::commit();

        } catch (\think\Exception $ex) {
            // 回滚事务
            goodsModel::rollback();
            Log::write('错误文件是：'.$ex->getFile()."\r\n错误信息是：".$ex->getMessage(),'info');
              
            
        }
        $this->redirect('Goods/index');
    }
    // 随机生成货号 
    private function createGoodsSn($goods_id){
        $goods_sn = 'UE0000'.$goods_id;
         
             $count = goodsModel::where('goods_sn',$goods_sn)->count();
          if ($count) {
              $newgoods_id = $goods_id.'_'.rand(10,99);
              $this->createGoodsSn($newgoods_id);
              // dump($newgoods_id);die;
           }
         
         // dump($goods_sn);die;
        
         return $goods_sn;
    }
    // 商品相册
    private function addGoodsPhoto($goods_id,$photos){
        if ($photos) {
            $temp_array = explode('|',trim($photos,"|"));
            $data = [];
            foreach ($temp_array as $val) {
                $data[] = [
                    'goods_id'=>$goods_id,
                    'url'=>$val
                ];
            }
            $goods_photo = new goods_photo();
            $goods_photo->allowField(true)->saveAll($data);
            
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
