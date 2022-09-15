<?php 
class CommonAction extends Action{
	Public function _initialize(){
		If(!isset($_SESSION['id'])||!isset($_SESSION['username']))
			$this->redirect('King/Login/index');
			//echo '这是自动执行';
	}


}

 ?>