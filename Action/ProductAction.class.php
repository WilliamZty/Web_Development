<?php 
class ProductAction extends CommonAction{
	Public function index(){
	
		
		$product=M('product')->select();
		$this->product=$product;
			//dump($product);
			//die;
		$this->display();
	}

	Public function home(){
		$this->product1=M('product')->where(array('apr'=>2,'differ'=>0))->where('homepage>0')->order('homepage desc')->limit(6)->select();
		$product=M('product')->where('homepage>0')->order('homepage desc')->select();
		$this->product=$product;
			//dump($product);
			//die;
		$this->display();
	}

	Public function sethomerank(){

		$count = count($_POST["homepage"]);
		
		for($i = 0; $i < $count; $i++) {

		    $data["product_id"] = $_POST["id"][$i];
		    $data["homepage"] = $_POST["homepage"][$i];
		   	M("product")->save($data);
		}

		$this->redirect('home');	
	}

	Public function deletehomepage(){
		

		$product_id=I('product_id',0,'intval');

		$data['homepage']=0;

		if(M('product')->where(array('product_id'=>$product_id))->save($data))
			$this->redirect('King/Product/home');
		else
			$this->error('修改失败');

	}

	public function need(){
		$this->display();
	}

	Public function trash(){
	
		$product=M('product')->where(array('del'=>2))->select();

		$this->product=$product;
		
		$this->display();

	}


	public function detail(){

		$product_id=I('product_id',0,'intval');

		$product=M('product')->where(array('product_id'=>$product_id))->find();
		$this->product=$product;
		
		/**
		if(strlen($product['cover'])==282){
            $isall=1;
        }else
            $isall=0;
        $this->isall=$isall;
        **/

		//$this->count=M('comment')->where(array('product_id'=>$product_id))->count();
		//$comment=M('comment');
		//import('ORG.Util.Page');// 导入分页类

		//dump($comment);
		//die;

		
		//$count=$comment->where(array('product_id'=>$product_id))->count();// 查询满足要求的总记录数

		//$Page=new Page($count,4);// 实例化分页类 传入总记录数和每页显示的记录数

		//die;

		//引入bootstrap样式
		//$Page->setConfig('theme',"<ul class='pagination'><li><a> %nowPage%/%totalPage% 页</a></li><li>%upPage%</li><li>%first%</li><li>%prePage%</li><li>%linkPage%</li><li>%nextPage%</li><li>%end%</li><li>%downPage%</li></ul>");

		//$Page->setConfig('theme',"<ul class='pagination'><li><a>%totalRow% %header% %nowPage%/%totalPage% 页</a></li><li>%upPage%</li><li>%first%</li><li>%prePage%</li><li>%linkPage%</li><li>%nextPage%</li><li>%end%</li><li>%downPage%</li></ul>");

		//$show=$Page->show();// 分页显示输出
		 // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		//$list = $comment->where(array('product_id'=>$product_id))->limit($Page->firstRow.','.$Page->listRows)->select();
		//$this->assign('list',$list);// 赋值数据集
		//$this->assign('page',$show);// 赋值分页输出
		//$this->count=$count;

		$comment=D('Comment');
        $this->count=$comment->where(array('product_id'=>$product_id))->count();
        $this->list=$comment->where(array('product_id'=>$product_id))->relation(true)->select();

		$this->display();

	}

	

	public function comment(){
		
		$data['content']=$_POST['content'];
		$data['product_id']=$_POST['product_id'];
		$data['cmtime']=time();
		$data['commenter']=session('username');
		$data['commenter_id']=session('id');

		//dump($data);
		//die;
		

		if(M('comment')->add($data))
			//$this->success('发表成功',U('Admin/Product/detail',array('product_id'=>$_POST['product_id']),''));
		  $this->redirect('Admin/Product/detail',array('product_id'=>$_POST['product_id']),0,'页面跳转中...');
		else
			$this->error('发表失败');
	}

	public function delcmt(){

		if(M('comment')->where(array('id'=>I('id')))->delete())
			//$this->success('发表成功',U('Admin/Product/detail',array('product_id'=>$_POST['product_id']),''));
		  $this->redirect('King/Product/detail',array('product_id'=>I('product_id')),0,'页面跳转中...');
		else
			$this->error('删除失败');

	}


	public function add(){
		//$user=M('User')->where(array('id'=>session('id'))->find();
		$user=M('user')->where(array('id'=>session('id')))->find();
		$this->company=$user['company'];
		//echo $this->company;
		//die;
		$this->display();
	}

	public function doadd(){
		
		//$this->display();
		$product=M('product');
		$data['product_title']=$_POST['product_title'];
		$data['borrower']=implode("、",$_POST['borrower']);
		$data['period']=$_POST['period'];
		$data['commit_lower']=$_POST['commit_lower'];
		$data['commit_upper']=$_POST['commit_upper'];
		$data['guarantee']=implode("、",$_POST['guarantee']);
		$data['require']=$_POST['require'];
		$data['detail']=$_POST['detail'];
		
		$data['publish_time']=time();
		$data['publisher']=session('username');

		//增加字段
		$data['company']=$_POST['company'];
		$data['cover']=implode("、",$_POST['cover']);
		$data['repay']=implode("、",$_POST['repay']);
		$data['price_lower']=$_POST['price_lower'];
		$data['price_upper']=$_POST['price_upper'];
		if($data['price_lower']==null)
			$data['price_lower']=0;
		if($data['price_upper']==null)
			$data['price_upper']=0;
		$data['funduse']=$_POST['funduse'];
		$data['doc']=$_POST['doc'];
		$data['exclude']=$_POST['exclude'];

		$data['dizhiya']=$_POST['dizhiya'];

		if($data['dizhiya']==null)
			$data['dizhiya']='';
		
		if($product->add($data))
			$this->success('添加成功',U('Admin/Product/index'));
		else
			$this->error('添加失败');
	}

	public function update(){
		
		$product_id=I('product_id',0,'intval');
		
		$product=M('product')->where(array('product_id'=>$product_id))->find();
		$this->product=$product;
		//dump($this->product);
		//die();
		$this->display();
	}

	public function updatedaikuan(){
		$product_id=I('product_id',0,'intval');
		
		$product=M('product')->where(array('product_id'=>$product_id,'publisher'=>session('username')))->find();
		$this->product=$product;
		
		$this->display();
	}

	public function updatezulin(){
		$product_id=I('product_id',0,'intval');
		
		$product=M('product')->where(array('product_id'=>$product_id,'publisher'=>session('username')))->find();
		$this->product=$product;
		
		$this->display();
	}

	public function updategqtz(){
		$product_id=I('product_id',0,'intval');
		
		$product=M('product')->where(array('product_id'=>$product_id,'publisher'=>session('username')))->find();
		$this->product=$product;
		
		$this->display();
	}

	public function doupdate(){
		//dump($_POST);
		//die();

		$product=M('product');
		
		$data['product_title']=$_POST['product_title'];
		$data['borrower']=implode("、",$_POST['borrower']);
		$data['period_lower']=$_POST['period_lower'];
		$data['period_upper']=$_POST['period_upper'];
		$data['commit_lower']=$_POST['commit_lower'];
		$data['commit_upper']=$_POST['commit_upper'];
		$data['guarantee']=implode("、",$_POST['guarantee']);
		if($data['guarantee']==null)
			$data['guarantee']='';
		$data['require']=$_POST['require'];
		$data['detail']=$_POST['detail'];
		
		//$data['publish_time']=time();
		//$data['publisher']=session('username');

		//增加字段
		$data['company']=$_POST['company'];
		$data['cover']=implode("、",$_POST['cover']);
		$data['repay']=implode("、",$_POST['repay']);
		if($data['repay']==null)
			$data['repay']='';
		$data['price_lower']=I('price_lower',0);
		$data['price_upper']=I('price_upper',0);
		$data['funduse']=$_POST['funduse'];
		
		$data['doc']=I('doc','');
		//$data['doc']=$_POST['doc'];
		$data['exclude']=$_POST['exclude'];
		$data['dizhiya']=$_POST['dizhiya'];

		if($data['dizhiya']==null)
			$data['dizhiya']='';

		$product_id=I('product_id');
		
		if(M('product')->where(array('product_id'=>$product_id))->save($data) !== false)
			//$this->success('修改成功',U('Admin/Product/index'));
			$this->redirect('King/Product/detail',array('product_id'=>$product_id),0,'页面跳转中...');
		else
			$this->error('修改失败');


	}

	Public function delete(){
		

		$product_id=I('product_id',0,'intval');

		$data['del']=2;

		if(M('product')->where(array('product_id'=>$product_id))->save($data))
			$this->redirect('King/Product/index');
		else
			$this->error('删除失败');

	}

	Public function homepage(){
		

		$product_id=I('product_id',0,'intval');

		$product = M('product')->where(array('product_id'=>$product_id))->find();

		if ($product['homepage'] == 0)
			$data['homepage']=1;
		else
			$data['homepage']=0;

		if(M('product')->where(array('product_id'=>$product_id))->save($data))
			$this->redirect('King/Product/index');
		else
			$this->error('修改失败');

	}

	Public function restore(){
	
		$product_id=I('product_id',0,'intval');

		$data['del']=1;

		if(M('product')->where(array('product_id'=>$product_id))->save($data))
			$this->redirect('King/Product/trash');
		else
			$this->error('还原失败');

	}


	



}


 ?>