<?php 
Class SearchAction extends CommonAction{
	//博文列表
	Public function product(){
		$this->product=M('kwdproduct')->select();
		
		$this->display();
	}

	Public function project(){
		$this->project=M('projectkwd')->select();
		
		$this->display();
	}


}
?>