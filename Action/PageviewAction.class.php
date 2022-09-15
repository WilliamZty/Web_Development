<?php 
Class PageviewAction extends CommonAction{
	//博文列表
	Public function product(){
		$this->product=M('productpv')->select();

		//import('ORG.Net.IpLocation');// 导入IpLocation类
		//$Ip = new IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件
		//$area = $Ip->getlocation('203.34.5.66'); // 获取某个IP地址所在的位置
		//dump($Ip);
		//dump($area);
		//die;
		
		//$this->$Ip=$Ip;
		$this->display();
	}

	Public function project(){
		$this->project=M('projectpv')->select();
		
		$this->display();
	}

	Public function blog(){
		$this->blog=M('blogpv')->select();
		
		$this->display();
	}

}
?>