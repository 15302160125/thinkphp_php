<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
class LoginController extends Controller
{
    public function login()
    {
        if(session('my_user',"","my"))
        {
            $this->redirect("author/index");
        }
        return view("author/login");
    }
    public function checklogin()
    {
        if(session('my_user',"","my"))
        {
            $this->redirect("author/index");
        }
        if(!request()->post())
        {
            $this->error("请求错误!");
        }
        $postData=Request::instance()->post();
        $author=model("author")->where('username',$postData['username'])->find();
        if(!$author)
        {
            $this->error("不存在此用户，请重新登录！");
        }
        if($author->password==md5($postData['password']))
        {
            session("my_user",$author,"my");
            $this->success("登录成功",url("author/index"));
        }else
        {
            $this->error("密码错误！",url("login/login"));
        }
    }
    public function logout()
    {
        session(null,"my");
        $this->success("注销成功！",url('login/login'));
    }
}
