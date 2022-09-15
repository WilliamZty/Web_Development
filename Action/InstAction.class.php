<?php 
Class InstAction extends CommonAction{
	//博文列表
	Public function index(){
		$this->inst=M('Institution')->where(array('del'=>0))->select();
		//dump($this->blog);
		//die;
		$this->display();
	}

	public function detail(){



		$id=I('id',0,'intval');
		//echo $id;
		$this->blog=D('blog')->where(array('id'=>$id))->find();
		//$this->blog=$blog;

		//dump($this->blog);
		
		
		$commentblog=D('Commentblog');
        $this->count=$commentblog->where(array('blog_id'=>$id))->count();
        $this->list=$commentblog->where(array('blog_id'=>$id))->relation(true)->select();
		
		$this->display();

	}

	//添加博文
	Public function addinst(){
		//所属分类
		/**
		import('Class.Category',APP_PATH);
		$cate=M('cate')->order('sort')->select();
		$cate=Category::unlimitedForLevel($cate);
		$this->cate=$cate;
		//所属属性
		$this->attr=M('attr')->select();
		**/
		//echo 111;
		$this->display();
	}

	//添加博文
	Public function addpic(){
		//所属分类
		$id=I('id',0,'intval');
		$this->inst=M('institution')->where(array('id'=>$id))->find();
		//echo 111;
		//die;
		/**
		$name = $this->inst['name'];

		$m = new MongoClient();
   		//echo "Connection to database successfully";

   		$db = $m->mydb;
	   	//echo "Database mydb selected";
	   	$collection = $db->mycol;
	   	//echo "Collection selected succsessfully";
	   	$query = array( "title" => $name );
	   	$cursor = $collection->find($query);
	   	// 迭代显示文档标题
	   	foreach ($cursor as $document) {
	      //echo $document["title"] . "\n";
	   	}

	   	$this->document=$document;

   		//dump($document);
   		**/
		$this->display();
	}

	
	public function chgface(){
		//echo $_POST['avatar_src'];
		//die;
		import('ORG.Util.CropAvatarInst');
		$id=I('id',0,'intval');
		session('instid',$id);

		$inst=M('institution')->where(array('id'=>$id))->find();
		
		unlink($inst['facem']);
		unlink($inst['faceb']);

		//echo $id;
		//echo $_POST['avatar_src'];
		//echo $_POST['avatar_data'];
		//echo $id;
		
		

	  	$crop = new CropAvatar($_POST['avatar_src'], $_POST['avatar_data'], $_FILES['avatar_file']);

	  	$response = array(
			'state'  => 200,
		    'message' => $crop -> getMsg(),
		    //'result' => $crop -> getResult()
		    'result' => str_replace("./","/blog1/",$crop -> getResult())
		    //str_replace("./","/blog1/",$user['facem'])
		);

	  	echo json_encode($response);

	  	$inst=M('institution')->where(array('id'=>$id))->find();
	  	$data['facem'] = $inst['facem'];
	  	$data['abbr'] = $inst['abbr'];
	  	M('product')->where(array('company'=>$inst['name']))->save($data);
	  	M('productrc')->where(array('company'=>$inst['name']))->save($data);

	}
	

	//处理添加博文
	Public function doaddinst(){
		dump($_POST);
		//die;
		//$blog=M('blog');
		$data['name']=$_POST['name'];
		$data['abbr']=$_POST['abbr'];
		$data['intr']=$_POST['intr'];
		$data['del']=0;

		if($bid=M('institution')->add($data)){
			$this->success('添加成功',U('King/Inst/index'));
		}else
			$this->error('添加失败');

	}

	//删除文章
	Public function delete(){
		

		$id=I('id',0,'intval');

		$data['del']=2;

		if(M('institution')->where(array('id'=>$id))->save($data))
			$this->redirect('King/Inst/index');
		else
			$this->error('删除失败');

	}

	//发布文章
	Public function pub(){
		
		$id=I('id',0,'intval');

		$data['pub']=1;

		if(M('blog')->where(array('id'=>$id))->save($data))
			$this->redirect('King/Blog/index');
		else
			$this->error('发布失败');

	}

	//撤回文章
	Public function withdraw(){
		
		$id=I('id',0,'intval');

		$data['pub']=0;

		if(M('blog')->where(array('id'=>$id))->save($data))
			$this->redirect('King/Blog/index');
		else
			$this->error('撤回失败');

	}


	Public function update(){
		
		$id=I('id',0,'intval');
		
		$inst=M('institution')->where(array('id'=>$id))->find();


		$this->inst=$inst;
		//$this->type=$product['type'];
		//dump($this->product);
		//die();
		$this->display();
	}

	public function doupdate(){

		$data['name']=$_POST['name'];
		$data['abbr']=$_POST['abbr'];
		$data['intr']=$_POST['intr'];

		$id=$_POST['id'];

		//dump($_POST);
		//die;
		
		if(M('institution')->where(array('id'=>$id))->save($data) !== false){

			//$inst=M('institution')->where(array('id'=>$id))->find();
	  	
	  		$data1['abbr'] = $_POST['abbr'];
	  		M('product')->where(array('company'=>$_POST['name']))->save($data1);
	  		M('productpv')->where(array('company'=>$_POST['name']))->save($data1);

			$this->redirect('index');

		}else

			$this->error('修改失败');

	}

	public function comment(){
		//dump($_POST);
		//die;
		$data['content']=$_POST['content'];
		$data['blog_id']=$_POST['blog_id'];
		$data['cmtime']=time();
		$data['commenter']=session('username');
		$data['commenter_id']=session('id');

		//dump($data);
		//die;
		

		if(M('commentblog')->add($data))
			//$this->success('发表成功',U('Admin/Product/detail',array('product_id'=>$_POST['product_id']),''));
		  $this->redirect('King/Blog/detail',array('id'=>$_POST['blog_id']),0,'页面跳转中...');
		else
			$this->error('发表失败');
	}
	//删评论
	public function delcmt(){
		echo I('id');
		//die;
		if(M('commentblog')->where(array('id'=>I('id')))->delete())
			//$this->success('发表成功',U('Admin/Product/detail',array('product_id'=>$_POST['product_id']),''));
		  $this->redirect('King/Blog/detail',array('id'=>I('blog_id')),0,'页面跳转中...');
		else
			$this->error('删除失败');

	}

	//编辑图片上传处理
	Public function upload(){
		import('ORG.Net.UploadFile');
		$upload=new UploadFile();
		$upload->autoSub=true;
		$upload->subType='date';
		$upload->dataformat='Ym';

		if($upload->upload('./uploads/')){
			$info=$upload->getUploadFileInfo();
			echo json_encode(array(
				'url'=>$info[0]['savename'], 
				'title'=>htmlspecialchars($_POST['pictitle'],ENT_QUOTES),
				'original'=>$info[0]['name'],
				'state'=>'SUCCESS'
				));
		}else{
			echo json_encode(array(
				'state'=>$upload->getErrorMsg()
				));
		}
	}
}

 ?>