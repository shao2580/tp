<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**生成盐值
 *	 
 * [setSalt description]
 */
function setSalt(){
	$pool = '0123456789abcdefghijklmnopqrstyvwsyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*';
	return substr(str_shuffle($pool),rand(1,60),6);
}

/**随机生成邮箱验证码
 * 
 * [createCode description]
 * @return [type] [description]
 */
function createCode(){
	return rand(111111,999999);
}

/**qq邮箱发送验证码 
 * [sendEmail description]
 * @param  [type] $address [description] 收件人地址
 * @param  [type] $subject [description] 标题	
 * @return [type] $bodys   [description] 发送内容
 */
function sendEmail($address,$subject,$bodys){
	 		$mail = new emailer\PHPMailer(); 
	 		   // print_r($mail);die;
            // print_r($mail);die();
            $mail->CharSet = "UTF-8";
            $mail->IsSMTP(); // telling the class to use SMTP
            $mail->Host       = "smtp.qq.com"; // SMTP server  //主人
            // $mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
            //                                            // 1 = errors and messages
            //                                            // 2 = messages only
            $mail->SMTPAuth   = true;                  // enable SMTP authentication
            $mail->Host       = "smtp.qq.com"; // sets the SMTP server
            $mail->Port       = 25;          //接口号          // set the SMTP port for the GMAIL server
            //   服务器的 应户名 密码
            $mail->Username   = "280654805@qq.com"; // SMTP account username
            $mail->Password   = "ofbaipsyztcjbgfh";        // SMTP account password
            // 发件人邮箱，发件人姓名
            $mail->SetFrom('280654805@qq.com', '尤洪-恒望科技');
            $mail->AddReplyTo("280654805@qq.com","尤洪-恒望科技");
            //  标题
            $mail->Subject    = "$subject";
            $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";// optional, comment out and test
            //  内容
           	$mail->Body = "$bodys";
            $mail->MsgHTML($bodys);
            //  收件人邮箱 ，收件人名称
            $mail->AddAddress($address, $address);
            // 附件
            //$mail->AddAttachment("images/phpmailer.gif");      // attachment
            //$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
            
            // 返回值
           	// $status = $mail->Send()
            // 判断 邮箱是否 发送
            if($mail->Send()) {
                return true;
            } else {
                return false;
            }
}

/**生成密码
 * 
 * [createPwd description]
 * @param  [type] $pwd  [description]   密码
 * @param  [type] $salt [description]	盐值
 * @return [type]       [description]
 */
function createPwd($pwd,$salt){
	return md5(md5($pwd.$salt));
}

/**无限极分类 后台
 * 
 * [CreateTree description]
 * @param [type]  $data      [description]  要循环的数据
 * @param integer $parent_id [description]	父级id  默认为0 代表一级
 * @param integer $level     [description]	级别		默认为1
 * @return array 
 */
function createTree($data,$parent_id=0,$level=1,$field='cate_id'){
	static $result = [];

	if ($data) {
		foreach ($data as $key => $val) {
			if ($val['parent_id']==$parent_id) {
				$val['level'] = $level;
				

				$result[]=$val;
			
				createTree($data,$val[$field],$level+1,$field);
			}
		}
		return $result;
	}
}

/**无限极分类 前台
 * 
 * [CreateTree description]
 * @param [type]  $data      [description]  要循环的数据
 * @param integer $parent_id [description]	父级id  默认为0 代表一级
 * @param integer $level     [description]	级别		默认为1
 * @return array 
 */
function createSonTree($data,$parent_id=0,$level=1){
 $result = [];
	if ($data) {
		foreach ($data as $key => $val) {
			if ($val['parent_id']==$parent_id) {

				$result[$key] = $val;
				$result[$key]['son']=createSonTree($data,$val['cate_id']);
			}
		}
		return $result;
	}
}

/**图片上传
     * 
     * [upload description]
     * @return [type] [description]
     */
function upload($img){
   // 获取表单上传文件 例如上传了001.jpg
	$file = request()->file($img);
    // 移动到框架应用根目录/uploads/ 目录下
    $info = $file->move( './uploads');
       if($info){
         // 成功上传后 获取上传信息
         // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
        	 $data['src'] =$info->getSaveName();
         	return ['code'=>0,'msg'=>'upload success','data'=>$data];
        }else{
             // 上传失败获取错误信息
             return ['code'=>1,'msg'=>$info->getError()];              
        }
}

/**成功后相应的信息
 * 
 * [successly description]
 * @param  string $font [description]
 * @return [type]       [description]
 */
function successly($font=''){
	$message=[
        'font'=>$font,
        'code'=>1
      ];
    echo json_encode($message);
}

/**失败后响应的信息
 * [successly description]
 * @param  string $font [description]
 * @return [type]       [description]
 */
function fail($font=''){
	$message=[
        'font'=>$font,
        'code'=>2
      ];
    echo json_encode($message);exit;
}

/**主页 左侧分类 导航
 * [getLeftCateInfo description]
 * @param  [type]  $cateInfo  [description]
 * @param  integer $parent_id [description]
 * @return [type]             [description]
 */
function getLeftCateInfo($cateInfo,$parent_id=0){
	$info=[];
	foreach ($cateInfo as $k => $v) {
			if ($v['parent_id']==$parent_id) {
			$son = getLeftCateInfo($cateInfo,$v['cate_id']);
			$v['son'] =$son;
			$info[]= $v;
		}	
	}
	return $info;
}

/**获取给定分类下的所有子类cate_id 
 * [getCateId description]
 * @param  [type] $cateInfo  [description]
 * @param  [type] $parent_id [description]
 * @return [type]            [description]
 */
function getCateId($cateInfo,$parent_id){
	static $id = [];  //静态属性
	foreach ($cateInfo as $k => $v) {
		if ($v['parent_id']==$parent_id) {
			$id[]=$v['cate_id'];
			getCateId($cateInfo,$v['cate_id']);
		}
	}
	return $id;
}

/*加密成--序列化 字符串*/
function createBase64Str($info){
   return base64_encode(serialize($info));
}

/*解密成--反序列化  数组*/
function getBase64Info($str){
    return unserialize(base64_decode($str));
}




?>