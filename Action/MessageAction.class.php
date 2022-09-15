<?php 

class MessageAction extends CommonAction{


	public function index(){

		$this->messagetext1=M('messagetext')->where(array('reciever'=>session('username'),'status'=>1))->field('sender,max(pdate),sum(status)')->group('sender')->order('max(pdate) desc')->select();

		$this->messagetext0=M('messagetext')->where(array('reciever'=>session('username'),'status'=>0))->field('sender,max(pdate),count(status)')->group('sender')->order('max(pdate) desc')->select();

		$this->messagetext2=M('messagetext')->where(array('sender'=>session('username')))->order('pdate desc')->select();

		$this->count=M('messagetext')->where(array('reciever'=>session('username'),'status'=>1))->count();

		$this->display();
	}

	public function ct(){
		$count=M('messagetext')->where(array('reciever'=>session('username'),'status'=>1))->count();
		echo $count;
	}

	public function view(){
		
		//$this->messagetext=M('messagetext')->where(array('reciever'=>session('username'),'status'=>$_GET['status'],'sender'=>$_GET['sender']))->select();

		$messagetext=D('Messagetext');
		$this->messagetext=$messagetext->where(array('reciever'=>session('username'),'status'=>$_GET['status'],'sender'=>$_GET['sender']))->relation(true)->select();
		
		$data['status']=0;
		$messagetext->where(array('reciever'=>session('username'),'sender'=>$_GET['sender']))->save($data);
		
		//$this->count=M('messagetext')->where(array('reciever'=>session('username'),'status'=>1))->count();

		//echo 1111;
		//dump($this->messagetext);
		//die;

		$this->display();

	}

	

	public function readview(){
		//dump($_GET);
		//dump($_GET['sender']);
		//die;

		$messagetext=D('Messagetext');
		$this->messagetext=$messagetext->where(array('reciever'=>session('username'),'sender'=>$_GET['sender']))->order('pdate desc')->relation(true)->select();
		
		//$this->count=M('messagetext')->where(array('reciever'=>session('username'),'status'=>1))->count();

		//echo time_tran("1435728935");
		//echo time_tran("2014-7-8 19:22:01");  

		//echo pls(1);

		//dump($this->$this->messagetext);
		//die;

		$this->display();

	}

	public function sentview(){
		//dump($_GET);
		//dump($_GET['sender']);
		//die;
		$this->messagetext=M('messagetext')->where(array('id'=>$_GET['id']))->order('pdate desc')->select();
		
		$this->count=M('messagetext')->where(array('reciever'=>session('username'),'status'=>1))->count();

		//dump($this->messagetext);
		//die;

		$this->display();

	}


	public function showall(){

		$messagetext=$messagetext=D('Messagetext');
    	//或者使用 $Dao = new Model();
    	//dump($messagetext);
		//die;
    	
    	$username=session('username');
    	$geter=$_GET['sender'];

    	$this->myface=M('user')->where(array('username'=>$username))->getField('facem');
    	$this->herface=M('user')->where(array('username'=>$geter))->getField('facem');

    	//echo $myface;
    	//echo $herface;
    	//die;

    	$this->messagetext=$messagetext->relation(true)->query("select * from ws_messagetext where sender='$username' and reciever='$geter' or sender='$geter' and reciever='$username' order by pdate desc");

    	//dump($this->messagetext);
		//die;

		// "+字段+" = "+ 值+"
		// sql="select * from biao where o='"+a+"'"

		$this->display();

	}

	public function read(){

		$this->messagetext=D('messagetext')->where(array('reciever'=>session('username'),'status'=>0))->field('sender,max(pdate),count(status)')->group('sender')->order('max(pdate) desc')->select();

		//$this->count=M('messagetext')->where(array('reciever'=>session('username'),'status'=>1))->count();

		$this->display();
	}

	public function sent(){

		$this->messagetext=M('messagetext')->where(array('sender'=>session('username')))->order('pdate desc')->select();
		
		//$this->count=M('messagetext')->where(array('reciever'=>session('username'),'status'=>1))->count();

		$this->display();
	}

	public function send(){
		//dump($_GET);
		//die;
		$this->reciever=$_GET['reciever'];
		$this->recieverid=$_GET['recieverid'];
		//$this->count=D('message')->relation(true)->where(array('reciever'=>session('username'),'status'=>1))->count();
		$this->display();
	}

	public function suggest(){
		//dump($_GET);
		//die;
		$this->reciever=admin;
		$this->recieverid=1;
		//$this->count=D('message')->relation(true)->where(array('reciever'=>session('username'),'status'=>1))->count();
		$this->display();
	}



	public function dosend(){

		//dump($_POST);
		//die;
		
		$messagetext=M('messagetext');
		$data['message']=$_POST['message'];
		$data['pdate']=time();
		$data['sender']=session('username');
		$data['senderid']=(int)session('id');
		$data['reciever']=$_POST['reciever'];
		$data['recieverid']=(int)$_POST['recieverid'];
		$data['status']=1;

		//dump($data);
		//die;
		
		if($messagetext->add($data))
			//$this->redirect('sent');
			$this->success('消息发送成功');
		else
			$this->error('发送失败');
	}

	public function printr(){
		//dump($_SERVER);
		p($_SERVER);
		$pd=time();
		get_long_time($pd);
		die;
	}


}

 ?>