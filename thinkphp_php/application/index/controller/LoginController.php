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
        // echo $author->password;
        // echo "</br>";
        // echo md5($postData['password']+($author->code));
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
            $this->error("密码错误！",url("author/login"));
        }
    }
    public function logout()
    {
        session(null,"my");
        $this->success("注销成功！",url('author/login'));
    }
    public function registe()
    {
        return view("author/registeration");   
    }
    public function registeration()
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
        $author=model("Author");
        $author->realname=$postData['realname'];
        $author->username=$postData['username'];
        $author->tel=$postData['tel'];
        $author->email=$postData['email'];
        $code=rand(1000,9999);
        $author->code=$code;
        $author->password=md5($postData['password']);
        $author->save();
        if($author->id)
        {
            $this->success("注册成功！","author/login");
        }
    }
    //管理员
    public function adminlogin()
    {
        if(session('my_admin',"","admin"))
        {
            $this->redirect("admin/index");
        }
        return view("admin/login");
    }
    public function adminchecklogin()
    {
        if(session('my_admin',"","admin"))
        {
            $this->redirect("admin/index");
        }
        if(!request()->post())
        {
            $this->error("请求错误!");
        }
        $postData=Request::instance()->post();
        $admin=model("admin")->where('username',$postData['username'])->find();
        // echo $author->password;
        // echo "</br>";
        // echo md5($postData['password']+($author->code));
        if(!$admin)
        {
            $this->error("不存在此用户，请重新登录！");
        }
        if($admin->password==md5($postData['password']))
        {
            session('my_admin',"","admin");
            $this->success("登录成功",url("admin/index"));
        }else
        {
            $this->error("密码错误！",url("login/adminlogin"));
        }
    }
    public function adminlogout()
    {
        session(null,"my");
        $this->success("注销成功！",url('admin/index'));
    }
}
