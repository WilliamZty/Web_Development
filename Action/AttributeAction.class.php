<?php 
Class AttributeAction extends CommonAction{

	//属性列表
	Public function index(){
		$this->attr=M('attr')->select();
		$this->display();
	}

	//添加属性
	Public function addattr(){
		$this->display();

	}

	//添加属性表单处理
	Public function runaddattr(){
		//dump($_POST);
		//die;
		if(M('attr')->add($_POST)){
			$this->success('添加成功','idnex');
		}else {
			$this->error('添加失败');
		}
	}
}


 ?>