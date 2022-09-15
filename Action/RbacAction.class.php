<?php 
class RbacAction extends CommonAction{

   //用户列表
	public function user(){
		

		//$this->user=D('User')->relation(true)->select();
		$this->user=D('UserRelation')->relation(true)->select();
		//dump($this->user);die;
		$this->display();
	}

	public function wish(){
		$this->user=M('wish')->select();
		//dump($this->list);die;
		$this->display();
	}

	//角色列表
	public function role(){
		$this->role=M('role')->select();
		$this->display();

	}

	//节点列表
	public function node(){
		$field=array('id','name','title','pid');
		$node=M('node')->field($field)->order('sort')->select();
		$this->node=node_merge($node);
		//dump($this->node);
		$this->display();
	}

	//添加用户
	public function adduser(){
		$this->role=M('role')->select();
		//dump($this->role);die;
		$this->display();
	}

	public function adduserhandle(){
		$user=array(
			'username'=>I('username'),
			'password'=>I('password','','md5'),
			//'password'=>I('password'),
			'logintime'=>time(),
			'loginip'=>get_client_ip()
			);
		//dump($user);
		//dump($_POST);
		//dump($_POST['role']);
		//die;

		$data=array();
		if($this->userid=M('user')->add($user)){
			
			foreach ($_POST['role'] as $v){
				$data[]=array(
					'role_id'=>$v,
					'user_id'=>$this->userid
					);
			}
		}
		//dump($data);
		//dump($this->userid);die;
		if(M('role_user')->addAll($data))
			$this->success('添加成功','user');
			else
				$this->error('添加失败');
	}

	//添加角色
	public function addrole(){
		$this->display();
	}

	//添加角色表单处理
	public function addRoleHandle(){
		if(M('role')->add($_POST)){
			$this->redirect('role');
		}else
			$this-error('添加失败');
	}

	//添加节点
	public function addnode(){
		$this->pid=I('pid',0,'intval');
		$this->level=I('level',1,'intval');

		switch ($this->level) {
			case 1:
				$this->type='应用';
				break;
			
			case 2:
				$this->type='控制器';
				break;

			case 3:
				$this->type='动作方法';
				break;
		}

		$this->display();
	}

	public function addNodeHandle(){
		if(M('node')->add($_POST))
			$this->redirect('node');
			else
				$this->error('添加失败');
	}

	//配置权限
	public function access(){
		$rid=I('rid',0,'intval');
		//echo $rid;die;
		//$field=array('id','name','title','pid');

		//读取原有权限
		$access=M('access')->where(array('role_id'=>$rid))->getField('node_id',true);
		//dump($access);
		//die;
		$field=array('id','name','title','pid');
		$node=M('node')->order('sort')->field($field)->select();
		//dump($node);die;
		$this->node=node_merge($node,$access);

		//dump($this->node);die;
		$this->rid=$rid;
		$this->remark=M('role')->where(array('id'=>$rid))->find();
		//dump($this->remark);
		$this->display();

	}

	public function setAccess(){

		//echo $_POST['status'];
		//die;

		$rid=I('rid',0,'intval');
		$db=M('access');

		//清空原权限
		$db->where(array('role_id'=>$rid))->delete();

		//dump($_POST);
		//die;

		//组全新权限
		$data=array();
		foreach ($_POST['access'] as $v){
			$tmp=explode('_', $v);
			$data[]=array(
				'role_id'=>$rid,
				'node_id'=>$tmp[0],
				'level'=>$tmp[1]
				);
		}

		//dump($data);
		//die;

		//插入新权限

		if ($db->addAll($data) && M('role')->where(array('id'=>$rid))->save(array('status'=>$_POST['status']))) {
			$this->redirect('role');
		}else{
			$this->error('修改失败');
		}

	}

	public function deleterole(){
		$rid=I('rid',0,'intval');
		//echo $rid;
		//die;
		$db=M('role');

		//清空原权限
		if($db->where(array('id'=>$rid))->delete()){
			$this->redirect('role');
		}else{
			$this->error('删除失败');
		}
	}

	//删除方法
	public function deletenode3(){
		$id=I('id',0,'intval');

		if(M('node')->where(array('id'=>$id))->delete()){
			$this->redirect('node');
		}else{
			$this->error('删除失败');
		}
	}

	//删除控制器及以下方法
	public function deletenode2(){
		$id=I('id');

		//echo $id;

		$where['id']=$id;
		$where['pid']=$id;
		$where['_logic']='OR';

		//dump($where);
		//die;
		//if(M('node')->where(array('id'=>$id),array('pid'=>$id),'or')->delete()){
		if(M('node')->where($where)->delete()){
			$this->redirect('node');
		}else{
			//$this->error('删除失败');
		}
	}

	public function chuserrole(){
		$id=I('id');
		$this->user=D('UserRelation')->relation(true)->where(array('id'=>$id))->find();
		//dump($this->user);
		//die;
		//echo $id;
		$this->role=M('role')->select();

		//echo 111;
		//dump($this->user);
		//die;
		$this->display();
	}

	public function chuserroleHaddle(){
		//dump('POST()');
		//die;
		$user_id=I('user_id');
		$role=I('role');
		$role_id=$role[0];
		dump($role);
		echo $role_id;
		echo $user_id;
		
		M('role_user')->where(array('user_id'=>$user_id))->delete();
		//die;
		//if (M('role_user')->where(array('user_id'=>$user_id))->save(array('role_id'=>$role_id))) {
		if (M('role_user')->add(array('role_id'=>$role_id,'user_id'=>$user_id))) {
			$this->redirect('user');
		}else{
			$this->error('修改失败');
		}
	}
}

 ?>