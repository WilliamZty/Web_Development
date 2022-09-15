<?php 

class PublicAction extends Action
{
	//定义验证码设置
	Public function verify(){
    	import('ORG.Util.Image');
        ob_end_clean();
        Image::buildImageVerify(3,1,'png');
 	}

}
?>