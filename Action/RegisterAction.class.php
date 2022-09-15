<?php 

class RegisterAction extends Action{


	public function index(){


		$this->display();
	}

	//for Register_index
	public function checkName(){
		
		header('Content-type: application/json');

		$valid = true;

		$username=$_POST['username'];
		$user=D('user');
		$where['username']=$username;
		if($user->where($where)->count())
			$valid = false;

		echo json_encode(array(
		    'valid' => $valid,
		));
	}

	public function Mobileisused(){
		
		header('Content-type: application/json');

		$valid = true;

		$mobile=$_POST['mobile'];
		$user=D('user');
		$where['mobile']=$mobile;
		if($user->where($where)->count())
			$valid = false;

		echo json_encode(array(
		    'valid' => $valid,
		));
	}

	//for Register_fgtpwd
	public function checkName1(){
		
		header('Content-type: application/json');

		$valid = false;

		$username=$_POST['username'];
		$user=D('user');
		$where['username']=$username;
		if($user->where($where)->count())
			$valid = true;

		$_SESSION['username1']=$username;

		echo json_encode(array(
		    'valid' => $valid,
		));
	}

	public function checkMobile(){

		header('Content-type: application/json');

		$valid = false;

		$username=$_SESSION['username1'];
		//$username="admin";
		$mobile=$_POST['mobile'];
		$user=D('user');
		$where['username']=$username;
		$where['mobile']=$mobile;
		if($user->where($where)->count())
			$valid = true;

		echo json_encode(array(
		    'valid' => $valid,
		));
	}

	public function register() {
		//dump($_POST);
		//die;
		//$code1=$code;
		//dump($_SESSION ['code']);
		//die;
		if($_SESSION ['code']==$_POST['code']){

		
		    $user = M ( 'user' );
			if (! $user->create ()) {
				dump ( $user->getError () );
			}
			$uid = $user->add ();

			//echo $uid;
			//die;
			
			if ($uid) {
				$_SESSION['id']=$_POST['id'];
			    $_SESSION['username']==$_POST['username'];
				$data['id']=$_POST['id'];
	            $data['logintime']=time();
				$data['loginip']=get_client_ip();

				//dump($data);
			//die;
				$user->save($data);

			

			//设置session
			   

				session('logintime',date('Y-m-d H:m:i',$user->logintime));
				session('loginip',$user->loginip);

				$this->redirect ('Admin/Index/index');
			} else {
				$this->error ( "注册失败！" );
			}
		}else {
			$this->error ( "验证码错误" );
			}

	}

	public function fgtpwd(){

		$this->display();
	}

	public function chgpwd(){

		$this->display();
	}

	public function dochgpwd(){

		$data['password']=$_POST['password'];
        if(M('user')->where(array('id'=>session('id')))->save($data))
            $this->redirect('Admin/Index/search');
        else
            $this->error('修改失败');
	}

	public function resetpwd(){
		if($_SESSION ['code']==$_POST['code']){
			$data['password']=$_POST['password'];
	        if(M('user')->where(array('username'=>$_POST['username']))->save($data))
	            $this->redirect('Admin/Index/index');
	        else
	            $this->error('修改失败');
		}else{
			$this->error ( "验证码错误" );
		}
	}

	public function remote(){

	header('Content-type: application/json');

	//sleep(5);

	$valid = true;

	$users = array(
	    'admin'         => 'admin@domain.com',
	    'administrator' => 'administrator@domain.com',
	    'root'          => 'root@domain.com',
	);

	if (isset($_POST['username']) && array_key_exists($_POST['username'], $users)) {
	    $valid = false;
	} else if (isset($_POST['email'])) {
	    $email = $_POST['email'][0];
	    foreach ($users as $k => $v) {
	        if ($email == $v) {
	            $valid = false;
	            break;
	        }
	    }
	}

	echo json_encode(array(
	    'valid' => $valid,
	    //'valid' => false,
	));

	}

	public function sms(){

		//include_once("CCPRestSmsSDK.php");
		import('ORG.Util.REST');

		//主帐号,对应开官网发者主账号下的 ACCOUNT SID
		$accountSid= '8a48b5514f4fc588014f58b969390b6c';
		              //8a48b5514f4fc588014f58b969390b6c

		//主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
		//$accountToken= '035045a474e345d29d2e75189ecf2765';
		
		$accountToken= '64d18b98e5384ee79f7ea08f60cb7415';

		//应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
		//在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
		$appId='8a48b5514f4fc588014f58b9ed900b70';

		//请求地址
		//沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
		//生产环境（用户应用上线使用）：app.cloopen.com
		$serverIP='sandboxapp.cloopen.com';


		//请求端口，生产环境和沙盒环境一致
		$serverPort='8883';

		//REST版本号，在官网文档REST介绍中获得。
		$softVersion='2013-12-26';


		/**
		  * 发送模板短信
		  * @param to 手机号码集合,用英文逗号分开
		  * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
		  * @param $tempId 模板Id,测试应用和未上线应用使用测试模板请填写1，正式应用上线后填写已申请审核通过的模板ID
		  */       
		function sendTemplateSMS($to,$datas,$tempId)
		{
		     // 初始化REST SDK
		     global $accountSid,$accountToken,$appId,$serverIP,$serverPort,$softVersion;
		     $rest = new REST($serverIP,$serverPort,$softVersion);
		     $rest->setAccount($accountSid,$accountToken);
		     $rest->setAppId($appId);
		    
		     // 发送模板短信
		     //echo "Sending TemplateSMS to $to <br/>";
		     $result = $rest->sendTemplateSMS($to,$datas,$tempId); 
				     if($result == NULL ) 
		     {
		         return false;
		     }
		     if($result->statusCode!=0) 
		     {
		        //return false;
		        return $result->statusCode.$result->statusMsg;
		     }
		     else
		     {
		         return true;
     		 }
		}

		//Demo调用 
				//**************************************举例说明***********************************************************************
				//*假设您用测试Demo的APP ID，则需使用默认模板ID 1，发送手机号是13800000000，传入参数为6532和5，则调用方式为           *
				//*result = sendTemplateSMS("13800000000" ,array('6532','5'),"1");																		  *
				//*则13800000000手机号收到的短信内容是：【云通讯】您使用的是云通讯短信模板，您的验证码是6532，请于5分钟内正确输入     *
				//*********************************************************************************************************************
		$mobile=$_POST["mobile"];

		//$mobile=138;

		$code=rand(1001,9999);
		//global $code=234;
		//$code=2345;
		session('code',$code);

		$res = sendTemplateSMS($mobile,array($code,'3'),"1");
		//$res = sendTemplateSMS($mobile,array('6532','3'),"1");
		//$res=ture;//手机号码，替换内容数组，模板ID
		
		
		if($res)
		{
		    echo "success";
		}
		else
		{
		    //echo "error".$mobile; 
		    echo "error";
		}
	}
}

?>