<?php 

class UserAction extends CommonAction{
	
	public function detail(){
		
		$id=I('id');
		$user=M('user')->where(array('id'=>$id))->find();
		$this->user=$user;

		import('ORG.Net.IpLocation');// 导入IpLocation类
		$Ip = new IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件
		$area = $Ip->getlocation($user['loginip']); // 获取某个IP地址所在的位置
		$this->area=$area;
		$this->display();
	}
	

	public function index(){
		$user=M('user')->select();
		$this->user=$user;
		//dump($user);
		//die;
		$this->display();
	}
	
	
	public function lock(){

		$data['lock']=1;
		$id=I('id');

		if(M('user')->where(array('id'=>$id))->save($data))
			$this->redirect('King/User/index');
		else
			$this->error('锁定失败');
	}

	public function unlock(){

		$data['lock']=0;
		$id=I('id');

		if(M('user')->where(array('id'=>$id))->save($data))
			$this->redirect('King/User/index');
		else
			$this->error('解锁失败');
	}
	
	

	public function update(){
		$user=M('user')->where(array('id'=>session('id')))->find();
		$this->user=$user;
		$this->display();
	}

	public function doupdate(){
		//dump($_POST);
		//die;
		//$product=M('user');
		$data['sex']=$_POST['sex'];
		$data['company']=$_POST['company'];
		$data['location']=$_POST['location'];
		$data['mobile']=$_POST['mobile'];
		$data['email']=$_POST['email'];
		$data['note']=$_POST['note'];
		$data['update_time']=time();

		//$id=I('id');

		//echo $id;
		//dump($data);
		//echo session('id');
		//die;
		//M('user')->where(array('id'=>$id))->save($data);
		if(M('user')->where(array('id'=>session('id')))->save($data))
			$this->redirect('King/User/index');
		else
			$this->error('修改失败');


	}

	public function publisher(){

		$username=I('username');
		$user=M('user')->where(array('username'=>$username))->find();
		$this->user=$user;
		$this->display();
	}

	public function face(){
		$this->user=M('user')->where(array('id'=>session('id')))->find();
		//$src=$user['faceb'];
		//$this->src=str_replace("./","/blog1/",$src);
		
		//echo $src;
		//$this->src=$src;
		//die;
		$this->display();
	}

	public function chgface(){
		//echo $_POST['avatar_src'];
		//die;

		$user=M('user')->where(array('id'=>session('id')))->find();
		
		unlink($user['facem']);
		unlink($user['faceb']);
		
		import('ORG.Util.CropAvatar');

	  	$crop = new CropAvatar($_POST['avatar_src'], $_POST['avatar_data'], $_FILES['avatar_file']);

	  	$response = array(
			'state'  => 200,
		    'message' => $crop -> getMsg(),
		    //'result' => $crop -> getResult()
		    'result' => str_replace("./","/blog1/",$crop -> getResult())
		    //str_replace("./","/blog1/",$user['facem'])
		);

	  	echo json_encode($response);
	}


}



 ?>