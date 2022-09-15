<?php 

	/**
	* 
	*/
class ProjectAction extends CommonAction{
		
	Public function index(){
		$project=M('project')->select();
		$this->project=$project;
			//dump($product);
			//die;
		$this->display();
	}

	Public function home(){
		$this->project1=M('project')->where(array('apr'=>2,'differ'=>0))->where('homepage>0')->order('homepage desc')->limit(6)->select();
		$project=M('project')->where('homepage>0')->order('homepage desc')->select();
		$this->project=$project;
			//dump($product);
			//die;
		$this->display();
	}

	Public function homepage(){
		

		$id=I('id',0,'intval');

		$project = M('project')->where(array('id'=>$id))->find();

		if ($project['homepage'] == 0)
			$data['homepage']=1;
		else
			$data['homepage']=0;

		if(M('project')->where(array('id'=>$id))->save($data))
			$this->redirect('King/Project/index');
		else
			$this->error('修改失败');

	}

	Public function sethomerank(){

		$count = count($_POST["homepage"]);
		
		for($i = 0; $i < $count; $i++) {

		    $data["id"] = $_POST["id"][$i];
		    $data["homepage"] = $_POST["homepage"][$i];
		   	M("project")->save($data);
		}

		$this->redirect('home');	
	}

	Public function deletehomepage(){
		

		$id=I('id',0,'intval');

		$data['homepage']=0;

		if(M('project')->where(array('id'=>$id))->save($data))
			$this->redirect('King/Project/home');
		else
			$this->error('修改失败');

	}

	public function need(){
		$this->display();
	}

	public function detailprj(){

		$id=I('id',0,'intval');

		$project=M('project')->where(array('id'=>$id))->find();
		$this->project=$project;
		//dump($this->project);
		//die;
		
		$commentprj=D('Commentprj');
        $this->count=$commentprj->where(array('project_id'=>$id))->count();
        $this->list=$commentprj->where(array('project_id'=>$id))->relation(true)->select();
		
		$this->display();

	}

	public function commentprj(){
		//dump($_POST);
		//die;
		$data['content']=$_POST['content'];
		$data['project_id']=$_POST['id'];
		$data['cmtime']=time();
		$data['commenter']=session('username');
		$data['commenter_id']=session('id');

		//dump($data);
		//die;
		

		if(M('commentprj')->add($data))
			//$this->success('发表成功',U('Admin/Product/detail',array('product_id'=>$_POST['product_id']),''));
		  $this->redirect('Admin/Project/detailprj',array('id'=>$_POST['id']),0,'页面跳转中...');
		else
			$this->error('发表失败');
	}

	Public function addprj(){

		$this->display();
	}

	Public function doaddprj(){
		
		//dump($_POST);
		//die;

		$data['company']=$_POST['company'];
		$data['demand']=$_POST['demand'];
		$data['foundtime']=$_POST['foundtime'];

		$data['site']=implode("、",$_POST['site']);

		$data['bussiness']=$_POST['bussiness'];
		$data['pawn']=$_POST['pawn'];
		$data['income_2015']=$_POST['income_2015'];
		$data['asset_2015']=$_POST['asset_2015'];
		$data['debt_2015']=$_POST['debt_2015'];
		$data['audit_2015']=$_POST['audit_2015'];
		$data['income_2014']=$_POST['income_2014'];
		$data['asset_2014']=$_POST['asset_2014'];
		$data['debt_2014']=$_POST['debt_2014'];
		$data['audit_2014']=$_POST['audit_2014'];
		$data['other']=$_POST['other'];
		$data['open']=$_POST['open'];

		$data['publish_time']=time();
		$data['publisher']=session('username');
		//$data['ap']=3;//待审批

		//dump($data);
		//die;

		if(M('project')->add($data))
			$this->success('添加成功',U('Admin/Project/index'));
		else
			$this->error('添加失败');


		//$this->display();
	}

	public function updateprj(){
		//echo 111;
		//die;
		$id=I('id',0,'intval');
		
		$project=M('project')->where(array('id'=>$id))->find();

		$site=explode("、", $project['site']);
		$this->province=$site[0];
		$this->city=$site[1];
		$this->area=$site[2];
		

		$this->project=$project;
		//dump($this->product);
		//die();
		$this->display();
	}

	Public function doupdateprj(){
		
		//dump($_POST);
		//die;

		$data['company']=$_POST['company'];
		$data['demand']=$_POST['demand'];
		$data['foundtime']=$_POST['foundtime'];

		$data['site']=implode("、",$_POST['site']);

		$data['bussiness']=$_POST['bussiness'];
		$data['pawn']=$_POST['pawn'];
		$data['income_2015']=$_POST['income_2015'];
		$data['asset_2015']=$_POST['asset_2015'];
		$data['debt_2015']=$_POST['debt_2015'];
		$data['audit_2015']=$_POST['audit_2015'];
		$data['income_2014']=$_POST['income_2014'];
		$data['asset_2014']=$_POST['asset_2014'];
		$data['debt_2014']=$_POST['debt_2014'];
		$data['audit_2014']=$_POST['audit_2014'];
		$data['other']=$_POST['other'];
		$data['open']=$_POST['open'];

		$data['update_time']=time();
		$data['apr']=1;//待审批
		//$data['publisher']=session('username');

		//dump($data);
		//die;
		/**
		if(M('project')->add($data))
			$this->success('添加成功',U('Admin/Project/index'));
		else
			$this->error('添加失败');
		**/

		$id=I('id');
		
		if(M('project')->where(array('id'=>$id))->save($data))
			//$this->success('修改成功',U('Admin/Product/index'));
			$this->redirect('Admin/Project/index',array('id'=>$id),0,'页面跳转中...');
		else
			$this->error('修改失败');


		//$this->display();
	}

	//收藏
	Public function collect(){
		
		$data['project_id']=I('project_id');
		$data['project']=I('project');
		$data['user_id']=session('id');
		$data['user']=session('username');
		$data['publisher']=I('publisher');
		$data['company']=I('company');
		/**

		$data['project_id']=2;
		$data['project']='美帆金属';
		$data['user_id']=session('id');
		$data['user']=session('username');
		$data['publisher']='admin';
		$data['company']='光大资产';
**/
		
		$data['collect_time']=time();
		$data['del']=0;

		//$collect=M('collectproduct');
		if($collect=M('collectproject')->where(array('project_id'=>$data['project_id'],'user_id'=>$data['user_id']))->find($data)){
			if($collect['del']==1){
				M('collectproject')->where(array('project_id'=>$data['project_id'],'user_id'=>$data['user_id']))->save($data);
				//$collect->save($data);
				echo 1;//以前收藏过，删除后重新收藏
			}else{
				echo 2;//已收藏，不必重复收藏
			}
		}else{
			M('collectproject')->add($data);
			echo 3;//第一次收藏
		}
			
	}

	//放弃搜藏
	Public function discard(){
	
		//$data['product_id']=I('product_id');
		//$data['product']=I('product');
		//$data['user_id']=I('user_id');
		//$data['user']=I('user');

		//$data['product_id']=1;
		//$data['product']=产品1;
		//$data['user_id']=2;
		//$data['user']=用户2;
		//$data['product_id']=I('product_id');
		//$data['user_id']=session('id');
		$data['del']=1;
		//dump($data);
		//dump($product_id);
		//die;

		//$collect=M('collectproduct');
		//if($collect=M('collectproduct')->where(array('$product_id'=>I('product_id'),'user_id'=>session('id')))->find())
		if($collect=M('collectproject')->where(array('id'=>I('id')))->save($data))
			//$id=$collect->id;
			//dump($collect);
			//die;
			//if(M('collectproduct')->where('id'=>$id)->save($data))
			//dump($collect);
			//die;
			$this->redirect('Admin/User/index');
		else
			$this->error('删除失败');
		
	}


}

 ?>