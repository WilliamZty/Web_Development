<?php 

class RegisterAction extends Action{

	public function index(){


		$this->display();
	}

	public function checkName(){
		$username=$_GET['username'];
		$user=D('user');
		$where['username']=$username;
		if($user->where($where)->count())
			echo 0;
		else
			echo 1;
	}

	public function register() {
		//dump($_POST);
		//die;
		$user = M ( 'user' );
		if (! $user->create ()) {
			dump ( $user->getError () );
		}
		$uid = $user->add ();

		//echo __URL__;
		//die;
		
		if ($uid) {
			$_SESSION ['username'] = $_POST ['username'];
			$this->redirect ('Admin/Index/index');
		} else {
			$this->error ( "注册失败！" );
		}
	}

	public function sms(){

		//include_once("CCPRestSmsSDK.php");
		import('ORG.Util.REST');

		//主帐号,对应开官网发者主账号下的 ACCOUNT SID
		$accountSid= '8a48b5514f4fc588014f58b969390b6c';

		//主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
		$accountToken= '035045a474e345d29d2e75189ecf2765';

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
		      echo "Sending TemplateSMS to $to <br/>";
		     $result = $rest->sendTemplateSMS($to,$datas,$tempId); 
		     if($result == NULL ) {
		         echo "result error!"; 
		         break; 
		     }
		     if($result->statusCode!=0) {
		         echo "模板短信发送失败!<br/>";
		         echo "error code :" . $result->statusCode . "<br>";
		         echo "error msg :" . $result->statusMsg . "<br>";
		         //下面可以自己添加错误处理逻辑
		     }else{
		         echo "模板短信发送成功!<br/>";
		         // 获取返回信息
		         $smsmessage = $result->TemplateSMS; 
		         echo "dateCreated:".$smsmessage->dateCreated."<br/>";
		         echo "smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
		         //下面可以自己添加成功处理逻辑
		     }
		}

		//Demo调用 
				//**************************************举例说明***********************************************************************
				//*假设您用测试Demo的APP ID，则需使用默认模板ID 1，发送手机号是13800000000，传入参数为6532和5，则调用方式为           *
				//*result = sendTemplateSMS("13800000000" ,array('6532','5'),"1");																		  *
				//*则13800000000手机号收到的短信内容是：【云通讯】您使用的是云通讯短信模板，您的验证码是6532，请于5分钟内正确输入     *
				//*********************************************************************************************************************
		$mobile = $_POST["mobile"];

		$res = sendTemplateSMS($mobile,array('周庆芸是只小兔子','3'),"1");
		//$res=ture;//手机号码，替换内容数组，模板ID
		//dump($mobile);
		//die;
		if($res)
		{
		    echo "success";
		}
		else
		{
		    echo $mobile;
		}
	}


}

 ?>