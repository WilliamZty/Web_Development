<?php 
class ApproveAction extends CommonAction{
	
	Public function user(){
			

			$user=M('user')->where(array('apr'=>1))->select();
			$this->user=$user;
			//dump($this->user);

			//$product0=M('product')->where(array('apr'=>1))->select();
			//$this->product0=$product0;
			$this->display();
	}

	Public function approveuser(){
				
		$id=I('id',0,'intval');

		$username=I('username');

		$data['apr']=2;

		if(M('user')->where(array('id'=>$id))->save($data)){

			$messagetext=M('messagetext');
			$data['message']="你已通过身份认证";
			$data['pdate']=time();
			$data['sender']=session('username');
			$data['senderid']=(int)session('id');
			$data['reciever']=$username;
			$data['recieverid']=0;
			$data['status']=1;

			$messagetext->add($data);

			$this->redirect('King/Approve/user');
		}	
		else
			$this->error('失败');
	}

	public function identify(){

		$id=I('id',0,'intval');

		//echo $id;

		$user=M('user')->where(array('id'=>$id))->find();

		$location=explode("、", $user['location']);
		$this->province=$location[0];
		$this->city=$location[1];
		$this->area=$location[2];

		$this->user=$user;
		$this->display();
	}

	public function doidentify(){
		$id=I('id',0,'intval');
		
		$data['sex']=$_POST['sex'];
		$data['company']=$_POST['company'];
		//$data['location']=$_POST['location'];
		$data['location']=implode("、",$_POST['location']);
		$data['mobile']=$_POST['mobile'];
		$data['email']=$_POST['email'];
		$data['note']=$_POST['note'];
		$data['update_time']=time();

		$data['mobileopen']=$_POST['mobileopen'];
		$data['emailopen']=$_POST['emailopen'];

		$data['truename']=$_POST['truename'];
		$data['jigou']=$_POST['jigou'];
		$data['branch']=$_POST['branch'];
		$data['position']=$_POST['position'];
		$data['introducer']=$_POST['introducer'];
		$data['birthday']=$_POST['birthday'];
		$data['product']=implode("、",$_POST['product']);

		$data['apr']=2;

		if(M('user')->where(array('id'=>$id))->save($data)){

			$messagetext=M('messagetext');
			$data1['message']="你已通过身份认证";
			$data1['pdate']=time();
			$data1['sender']=session('username');
			$data1['senderid']=(int)session('id');
			$data1['reciever']=$_POST['username'];
			$data1['recieverid']=0;
			$data1['status']=1;

			$messagetext->add($data1);

			$this->redirect('user');
		}
			
		else
			$this->error('修改失败');
	}

	Public function product(){
			
			$product0=M('product')->where(array('apr'=>0))->select();
			$product1=M('product')->where(array('apr'=>1))->select();
			$this->product0=$product0;
			$this->product1=$product1;
			//dump($this->product1);
			//die;
			
			$this->display();
	}

	Public function approveproduct(){
				
		$product_id=I('product_id',0,'intval');

		$publisher=I('publisher');

		$product_title=I('product_title');

		$data['apr']=2;

		if(M('product')->where(array('product_id'=>$product_id))->save($data)){

			$messagetext=M('messagetext');
			$data['message']="你的产品——".$product_title."已通过审核";
			$data['pdate']=time();
			$data['sender']=session('username');
			$data['senderid']=(int)session('id');
			$data['reciever']=$publisher;
			$data['recieverid']=0;
			$data['status']=1;

			$messagetext->add($data);

			$this->redirect('King/Approve/product');
		}
			
		else
			$this->error('删除失败');
	}

	Public function project(){
			
			$project0=M('project')->where(array('apr'=>0))->select();
			$project1=M('project')->where(array('apr'=>1))->select();
			$this->project0=$project0;
			$this->project1=$project1;
			//dump($this->project1);
			//die;
			
			$this->display();
	}

	Public function approveproject(){
				
		$id=I('id',0,'intval');

		$publisher=I('publisher');

		$company=I('company');

		$data['apr']=2;

		if(M('project')->where(array('id'=>$id))->save($data)){

			$messagetext=M('messagetext');
			$data['message']="你的项目——".$company."已通过审核";
			$data['pdate']=time();
			$data['sender']=session('username');
			$data['senderid']=(int)session('id');
			$data['reciever']=$publisher;
			$data['recieverid']=0;
			$data['status']=1;

			$messagetext->add($data);

			$this->redirect('King/Approve/project');
		}

		else
			$this->error('删除失败');
	}

}


 ?>