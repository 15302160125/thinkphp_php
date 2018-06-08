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
        if($author->status!=1)
        {
            $this->error("此用户状态异常！");
        }
        if($author->password==md5($postData['password'].$author->code))
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
        $aut=model("Author")->where("username",$postData['username'])->select();
        $author=model("Author");
        if(!$aut)
        {
            $author->realname=$postData['realname'];
            $author->username=$postData['username'];
            $author->tel=$postData['tel'];
            $author->email=$postData['email'];
            $code=rand(1000,9999);
            $author->code=$code;
            $author->password=md5($postData['password'].$code);
            $author->save();
            if($author->id)
            {
                $this->success("注册成功！","author/login");
            }
        }
        else
        {
            $this->error("账号已存在！","author/registeration");
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
        if(!$admin)
        {
            $this->error("不存在此用户，请重新登录！");
        }
        if($admin->password==md5($postData['password'].($admin->code)))
        {
            session('my_admin',$admin,"admin");
            $this->success("登录成功",url("admin/index"));
        }else
        {
            $this->error("密码错误！",url("admin/adminlogin"));
        }
    }
    public function adminlogout()
    {
        session(null,"admin");
        $this->success("注销成功！",url('admin/index'));
    }
    public function adminregiste()
    {
        return view("admin/registeration");   
    }
    public function adminregisteration()
    {
        if(!request()->post())
        {
            $this->error("请求错误!");
        }
        $postData=Request::instance()->post();
        $aut=model("Admin")->where("username",$postData['username'])->select();
        $admin=model("Admin");
        if(!$aut)
        {
            $admin->realname=$postData['realname'];
            $admin->username=$postData['username'];
            $code=rand(1000,9999);
            $admin->code=$code;
            $admin->password=md5($postData['password'].$code);
            $admin->save();
            if($admin->id)
            {
                $this->success("注册成功！","admin/login");
            }
        }
        else
        {
            $this->error("账号已存在！","admin/registeration");
        }
    }
}
