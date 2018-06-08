<?php 
	namespace app\index\controller;
	use think\Controller;
	use think\Request;
	use think\Session;
	class Index extends Controller
	{
		public function _initialize()
		{
			if(session('my_user',"","my"))
	        {
	            $my_user=session("my_user","","my");
	            $user=model("author")->where('id',$my_user['id'])->find();
	            $this->assign('user',$user);
	        }    
	        $category=model("Category")->where("status",1)->select();
	        $this->assign("category",$category);
	            //文章
	        $art=model("Article")->where("status",1)->select();
	        $this->assign("art",$art);
		}
	}
?>