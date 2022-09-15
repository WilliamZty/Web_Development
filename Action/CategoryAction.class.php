<?php 
class CategoryAction extends CommonAction{

	Public function index(){
		//echo APP_PATH;
		
		import('Class.Category',APP_PATH);
		//die;
		$cate=M('cate')->order('sort ASC')->select();
		$cate=Category::getChilds($cate,1);
		//$cate=Category::unlimitedForLayer($cate);
		//$cate=Category::unlimitedForLevel($cate,'&nbsp;&nbsp;--');
		dump($cate);die;
		$this->cate=$cate;
		$this->display();
	}

	Public function addcate(){
		$this->pid=I('pid',0,'intval');
		$this->display();
	}

	Public function runaddcate(){
		if(M('cate')->add($_POST)){
			$this->success('添加成功','index');
		}else{
			$this->error('添加失败');
		}
	}

	Public function sortcate(){
		$db=M('cate');
		//dump($_POST);
		//die;
		foreach ($_POST as $id => $sort) {
			$db->where(array('id' =>$id))->setfield('sort',$sort);
		}
		$this->redirect('index');
		
	}


}

?>