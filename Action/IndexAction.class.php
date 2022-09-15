<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends CommonAction {
	
    Public function index(){
        //echo 111;
		$this->display();
	}

    Public function index1(){

        $this->display();
    }

    public function detail(){

        $product_id=I('product_id',0,'intval');

        $product=M('product')->where(array('product_id'=>$product_id))->find();

        //$click=$product['click']+1;

        $data['click']=$product['click']+1;
        //echo $data['click'];
        //die;

        $product=M('product')->where(array('product_id'=>$product_id))->save($data);

        $product=M('product')->where(array('product_id'=>$product_id))->find();
        $this->product=$product;

        if(strlen($product['cover'])==282){
            $isall=1;
        }else
            $isall=0;
        $this->isall=$isall;

        /*

        //$this->count=M('comment')->where(array('product_id'=>$product_id))->count();
        $comment=M('comment');
        import('ORG.Util.Page');// 导入分页类

        //dump($comment);
        //die;

        
        $count=$comment->where(array('product_id'=>$product_id))->count();// 查询满足要求的总记录数

        $Page=new Page($count,4);// 实例化分页类 传入总记录数和每页显示的记录数

        //die;

        //引入bootstrap样式
        $Page->setConfig('theme',"<ul class='pagination'><li><a> %nowPage%/%totalPage% 页</a></li><li>%upPage%</li><li>%first%</li><li>%prePage%</li><li>%linkPage%</li><li>%nextPage%</li><li>%end%</li><li>%downPage%</li></ul>");

        //$Page->setConfig('theme',"<ul class='pagination'><li><a>%totalRow% %header% %nowPage%/%totalPage% 页</a></li><li>%upPage%</li><li>%first%</li><li>%prePage%</li><li>%linkPage%</li><li>%nextPage%</li><li>%end%</li><li>%downPage%</li></ul>");

        $show=$Page->show();// 分页显示输出
         // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $comment->where(array('product_id'=>$product_id))->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->count=$count;
        */
        $comment=D('Comment');
        $this->count=$comment->where(array('product_id'=>$product_id))->count();
        //$this->list=$comment->where(array('product_id'=>$product_id))->select();
        $this->list=$comment->where(array('product_id'=>$product_id))->relation(true)->select();
        //dump($this->list);
        //die;

        $user=M('user')->where(array('username'=>$product['publisher']))->find();
        //dump($user);
        //die;
        $this->user=$user;
        $this->display();

    }

    public function search(){
        //echo __URL__;
        $this->display();
    }

    public function result1(){
        $amount=I("amount");
        //$guarantee=I("guarantee");
        $keyword=I("keyword");

        $sphinx=new SphinxClient();
        $sphinx->SetServer("localhost",9312);
        $sphinx->setMatchMode(SPH_MATCH_ANY);
        $sphinx->setLimits (0,1000);
        //$sphinx->SetSortMode(SPH_SORT_RELEVANCE);
        $sphinx->SetFilterRange("commit_lower",0,$amount);
        $sphinx->SetFilterRange("commit_upper",$amount,10000000);

        $result=$sphinx->query($keyword,"*");

        $map['product_id']  = array('in',array_keys($result['matches']));

        //dump($map['product_id']);

        $opts=array(
            "before_match"=>"<font style='color:red'>",
            "after_match"=>"</font>"
        );

        //import('ORG.Util.AjaxPage');// 导入分页类
        $count=$result['total'];
        //$p= new AjaxPage($count,100,"user"); //第三个参数是你需要调用换页的ajax函数名
        //$limit_value =$p->firstRow.",".$p->listRows;

        $product=M('product');
       
        $product=$product->where($map)->select();
        //$product=$product->where($map)->limit($limit_value)->select();
 
        //dump($product);

        foreach($product as $pr){
                //dump($pr);
                $product_hl[] =$sphinx->buildExcerpts($pr,"main",$keyword,$opts);

        }

        
       //dump($product_hl);
        //die;
        //$product_hl = array_slice($product_hl,$Page->firstRow,$Page->listRows);
        //$page=$p->show();// 分页显示输出
       
        $this->product=$product_hl;

        $this->keyword=$keyword;

        //dump($product_hl);
        //dump($this->product);
        //die;
        //echo $amount;

        //$this->page=$page;// 赋值分页输出

        //$this->amount=$amount;
        //$this->guarantee=$guarantee;
        //$this->keyword=$keyword;

        //$this->pagenum=$pagenum;
        //$this->num=$num;
        $this->count=$count;

        //dump($count);


        $this->display();
    }

    public function addcomment(){
        
        $data['content']=I('content');
        $data['product_id']=I('product_id');
        $data['cmtime']=time();
        $data['commenter']=session('username');
        $data['commenter_id']=session('id');

        $product_id=I('product_id');

        $this->product_id=I('product_id');

        //dump($data);
        //die;
        

        if(M('comment')->add($data)){
            $comment=D('Comment');
            $this->count=$comment->where(array('product_id'=>$product_id))->count();
            $this->list=$comment->where(array('product_id'=>$product_id))->relation(true)->select();

            $this->display();
        }
            //$this->success('发表成功',U('Admin/Product/detail',array('product_id'=>$_POST['product_id']),''));
          //$this->redirect('Index/Index/detail',array('product_id'=>$_POST['product_id']),0,'页面跳转中...');
        else{
            $this->error('发表失败'); 
        }

            
    }


    public function logout(){
    	session_unset();
    	session_destroy();
    	$this->redirect('Index/Index/index');
    }

    public function changekey(){
    	
        $this->display();
    }

    public function dochangekey(){
        $data['password']=$_POST['password'];
        if(M('user')->where(array('id'=>session('id')))->save($data))
            $this->success('修改成功',U('Admin/Index/index'));
        else
            $this->error('修改失败');
        
        $this->display();
    }

    
}

?>