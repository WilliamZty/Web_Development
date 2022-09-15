<?php 
class MsgmanageAction extends CommonAction{
	Public function index(){
	
		import('ORG.Util.Page');//引入分页类

		$wish=M('wish');
		$count=$wish->count();

		$page=new Page($count,3);//实例化分页类
		$list=$wish->order('time DESC')->limit($page->firstRow.','.$page->listRows)->select();
		//分页查询
		
		$this->list=$list;//传递查询结果
		$this->page=$page->show();//传递分页控件
		$this->display();
	}

	Public function delete(){
		$id=$_GET['id'];
		if(M('wish')->delete($id)){
			$this->success('成功',U('Admin/Msgmanage/index'));
		}
		else
			$this->error('失败');
	}

}


 ?>