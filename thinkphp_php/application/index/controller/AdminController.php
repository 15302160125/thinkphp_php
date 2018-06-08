<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\File;
class AdminController extends AdminBase
{
    public function index()
    {
        if(session('my_admin',"","admin"))
        {
            $my_user=session('my_admin',"","admin");
            $author=model("author")->count();
            $author=$this->assign("author",$author);
            $count=model("article")->count();
            $count=$this->assign("count",$count);
            return view('admin/index');
        }       
    }
    //作者
    public function author()
    {
        $author=model("author")->select();
        $this->assign("author",$author);
        return view("admin/author");
    }
    public function authorupdate()
    {
        $postData=Request::instance()->param();
        $author=model("author")->where('id',$postData['id'])->find();
        $this->assign("author",$author);
        return view("admin/authorupdate");
    }
    public function newauthor()
    {
       if(!request()->post())
        {
            $this->error("请求错误!");
        }
        $postData=Request::instance()->post();
        //dump($postData);
        $author=model("author")->where('id',$postData['id'])->find();
        $author->status=$postData['status'];
        $author->save();
        if($author->id)
        {
            $this->success("作者状态成功！","admin/author");
        }
    }
    public function authordelete()
    {
        $postData=Request::instance()->param();
        $author=model("author")->where('id',$postData['id'])->find();
        $author->status=2;
        $author->save();
        if($author->id)
        {
            $this->success("删除成功",url('admin/author'));
        }
    }
    //文章
    public function article()
    {
        $article=model("article")->select();
        $this->assign("article",$article);
        return view("admin/article");
    }
    public function articleupdate()
    {
        $postData=Request::instance()->param();
        $article=model("article")->where('id',$postData['id'])->find();
        $this->assign("article",$article);
        return view("admin/articleupdate");
    }
    public function newarticle()
    {
       if(!request()->post())
        {
            $this->error("请求错误!");
        }
        $postData=Request::instance()->post();
        //dump($postData);
        $article=model("article")->where('id',$postData['id'])->find();
        $article->status=$postData['status'];
        $article->save();
        if($article->id)
        {
            $this->success("文章状态成功！","admin/article");
        }
    }
    public function articledelete()
    {
        $postData=Request::instance()->param();
        $article=model("article")->where('id',$postData['id'])->find();
        $article->status=2;
        $article->save();
        if($article->id)
        {
            $this->success("删除成功",url('admin/article'));
        }
    }
    //分类
    public function category()
    {
        $category=model("category")->select();
        $this->assign("category",$category);
        return view("admin/category");
    }
    public function categoryupdate()
    {
        $postData=Request::instance()->param();
        $category=model("category")->where('id',$postData['id'])->find();
        $this->assign("category",$category);
        return view("admin/categoryupdate");
    }
    public function newcategory()
    {
       if(!request()->post())
        {
            $this->error("请求错误!");
        }
        $postData=Request::instance()->post();
        //dump($postData);
        $category=model("category")->where('id',$postData['id'])->find();
        $category->status=$postData['status'];
        $category->save();
        if($category->id)
        {
            $this->success("类别状态成功！","admin/category");
        }
    }
    public function categorydelete()
    {
        $postData=Request::instance()->param();
        $category=model("category")->where('id',$postData['id'])->find();
        $category->status=2;
        $category->save();
        if($category->id)
        {
            $this->success("删除成功",url('admin/category'));
        }
    }
    //修改信息
    public function editor()
    {
        $my_admin=session("my_admin","","admin");
        $admin=model("admin")->where('id',$my_admin['id'])->select();
        $editor=$this->assign("editor",$admin);
        return view("admin/editor");
    }
    public function editorup()
    {
        $postData=Request::instance()->post();
        $admin=model("admin")->where('id',$postData['id'])->find();

        $file = request()->file('image');
        if($file){
            $info = $file->move(ROOT_PATH . DS . 'logo');
            if($info){
            $savename=$info->getSaveName();
            $logo=str_replace('\\','/',$savename);
            }else{
                echo $file->getError();
            }
        }

        $admin->username=$postData['username'];
        if($file!="")
        {
            $admin->logo=$logo;
        }
        $code=rand(1000,9999);
        $admin->code=$code;
        $admin->password=md5($postData['password'].$code);
        $admin->save();
        if($admin->id)
        {
            session(null,'admin');
            $this->success("个人信息修改成功！请重新登录","login/adminlogin");
        }
        dump($postData);
    }

  
}
