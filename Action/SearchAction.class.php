<?php 
Class SearchAction extends CommonAction{
	//ๅๆๅ่กจ
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