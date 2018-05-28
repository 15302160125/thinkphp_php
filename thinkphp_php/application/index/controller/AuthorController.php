<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
class AuthorController extends Controller
{
    public function index()
    {
        if(session('my_user',"","my"))
        {
           return view('author/index');
        }
        
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
        $author->password=md5($postData['password']);
        $author->save();
        if($author->id)
        {
            $this->success("注册成功！","author/login");
        }
    }
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
            $this->error("密码错误！");
        }
    }
    public function logout()
    {
        session(null,"my");
        $this->success("注销成功！",url('author/login'));
    }
    public function article()
    {
        
        return view("author/article");
    }
    public function articleup()
    {
        $category=model("category")->select();
        $categoryData=$this->assign("category",$category);
        $my_user=session("my_user","","my");
        $author=model("author")->where('id',$my_user['id'])->select();
        $author=$this->assign("author",$author);
        return view("author/articleup");
    }
    public function setarticle()
    {
        if(!request()->post())
        {
            $this->error("请求错误!");
        }
        $postData=Request::instance()->post();
        $article=model("Article");
        $article->title=$postData['title'];
        $article->author_id=$postData['author_id'];
        $article->category_id=$postData['category_id'];
        $article->description=$postData['description'];
        $article->content=$postData['content'];
        $article->save();
        if($article->id)
        {
            $this->success("文章发布成功！","author/article");
        }
        dump($postData);
    }
    public function category()
    {
        return view("author/category");
    }
    public function categoryup()
    {
        return view("author/categoryup");
    }
    public function setcategory()
    {
        if(!request()->post())
        {
            $this->error("请求错误!");
        }
        $postData=Request::instance()->post();
        $category=model("Category");
        $category->categoryname=$postData['categoryname'];
        $category->save();
        if($category->id)
        {
            $this->success("类别设置成功！","author/category");
        }
        dump($postData);
    }
}
