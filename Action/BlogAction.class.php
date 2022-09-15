<?php 
Class BlogAction extends CommonAction{
	//博文列表
	Public function index(){
		$this->blog=D('BlogRelation')->relation(true)->where(array('del'=>0))->order('id desc')->select();
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
	Public function addblog(){
		//所属分类
		import('Class.Category',APP_PATH);
		$cate=M('cate')->order('sort')->select();
		$cate=Category::unlimitedForLevel($cate);
		$this->cate=$cate;
		//所属属性
		$this->attr=M('attr')->select();
		$this->display();
	}

	//添加博文
	Public function addpic(){
		//所属分类
		$id=I('id',0,'intval');
		$this->blog=D('blog')->where(array('id'=>$id))->find();
		//echo 111;
		//die;
		$this->display();
	}

	
	public function chgface(){
		//echo $_POST['avatar_src'];
		//die;
		import('ORG.Util.CropAvatarBlog');
		$id=I('id',0,'intval');
		session('blogid',$id);

		$blog=M('blog')->where(array('id'=>$id))->find();
		
		unlink($blog['facem']);
		unlink($blog['faceb']);

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
	}
	

	//处理添加博文
	Public function doaddblog(){
		dump($_POST);
		//die;
		//$blog=M('blog');
		$data['title']=$_POST['title'];
		$data['source']=$_POST['source'];
		$data['author']=$_POST['author'];
		$data['cid']=(int)$_POST['cid'];
		//$data['aid']=3;
		$data['content']=$_POST['content'];
		$data['time']=time();
		

		if($bid=M('blog')->add($data)){
			if(isset($_POST['aid'])){
				$sql='INSERT INTO '.C('DB_PREFIX').'blog_attr (bid,aid) VALUES';
					foreach($_POST['aid'] as $v){
						$sql.='('.$bid.','.$v.'),';
					}
					$sql=rtrim($sql,',');
					M('blog_attr')->query($sql);
			}
			$this->success('添加成功',U('King/Blog/index'));
		}else
			$this->error('添加失败');

	}

	//删除文章
	Public function delete(){
		

		$id=I('id',0,'intval');

		$data['del']=2;

		if(M('blog')->where(array('id'=>$id))->save($data))
			$this->redirect('King/Blog/index');
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
		
		$blog=M('blog')->where(array('id'=>$id))->find();


		$this->blog=$blog;
		//$this->type=$product['type'];
		//dump($this->product);
		//die();
		$this->display();
	}

	public function doupdate(){
		//dump($_POST);
		//die();

		$product=M('product');
		
		$data['product_title']=$_POST['product_title'];
		$data['borrower']=implode("、",$_POST['borrower']);
		$data['period']=$_POST['period'];
		$data['commit_lower']=$_POST['commit_lower'];
		$data['commit_upper']=$_POST['commit_upper'];
		$data['guarantee']=implode("、",$_POST['guarantee']);
		$data['require']=$_POST['require'];
		$data['detail']=$_POST['detail'];
		
		$data['update_time']=time();
		//$data['publisher']=session('username');

		//增加字段
		$data['company']=$_POST['company'];
		$data['cover']=implode("、",$_POST['cover']);
		$data['repay']=implode("、",$_POST['repay']);
		$data['price_lower']=$_POST['price_lower'];
		$data['price_upper']=$_POST['price_upper'];
		$data['funduse']=$_POST['funduse'];
		$data['doc']=$_POST['doc'];
		$data['exclude']=$_POST['exclude'];
		$data['dizhiya']=$_POST['dizhiya'];

		$product_id=I('product_id');
		
		if(M('product')->where(array('product_id'=>$product_id))->save($data))
			//$this->success('修改成功',U('Admin/Product/index'));
			$this->redirect('Admin/Product/detail',array('product_id'=>$product_id),0,'页面跳转中...');
		else
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