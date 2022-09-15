<?php 
	class LoginAction extends Action{
		public function login(){
			$this->display();
			
		}

		public function index(){
			$this->display();
			
		}

		public function dologin(){
			//排除非post访问
			if(!IS_POST)
				//$this->error('该页面不存在');
				
				halt('这是定制页面该页面不存在');

			//echo 1111;die;
			//if(I('code','','md5')!=session('verify'))
			
			//验证码
			//$code=md5($_POST['code']);
			//if($code!=session('verify'))
			//	$this->error('验证码不正确');
			//	
			//dump($_POST);
			//die;
			
			//验证用户名和密码
			$user=M('user');
			//dump($user);
			//die;
			$where['username']=$_POST['username'];
			//$where['password']=md5($_POST['password']);
			$where['password']=md5($_POST['password']);
			//
			//dump($where);
			//die;

			if(!$user->where($where)->find())
				$this->error('用户不存在或密码错误');

			//更新记录
			$data['id']=$user->id;
            $data['logintime']=time();
			$data['loginip']=get_client_ip();
			$user->save($data);

			//dump($user);
			
			/**
			if($_POST['username']!='admin'||$_POST['password']!=11)
				$this->error('用户不存在或密码错误');
			**/	
			//设置session
			//session('id',$user->id);
			session('id',$user->id);
			session('username',$_POST['username']);
			//session('logintime',date('Y-m-d H:m:i',$user->logintime));
			//session('loginip',$user->loginip);

			//自动登录（记住我）
			//$auto=$_POST['auto'];
			//echo $auto;
			//die;
			//if($auto=='on'){
			//	setcookie(session_name(),session_id(),C('COOKIE_TIME'),'/');
			//}

			


			import('ORG.Util.RBAC');
			RBAC::saveAccessList();
			//dump($_SESSION);
			
			//die;

			//页面跳转
			$this->redirect('King/Index/index');
	
		}
	
	}

 ?>

