<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\File;
class AuthorController extends Base
{
    public function index()
    {
        if(session('my_user',"","my"))
        {
            $my_user=session("my_user","","my");
            $author=model("article")->where('author_id',$my_user['id'])->select();
            $author=$this->assign("author",$author);
            $count=model("article")->where('author_id',$my_user['id'])->count();
            $count=$this->assign("count",$count);
            return view('author/index');
        }       
    }
    public function article()
    {
        $my_user=session("my_user","","my");
        $author=model("article")->where('author_id',$my_user['id'])->select();
        $author=$this->assign("author",$author);
        $article=model("Article")->where('author_id',$my_user['id'])->select();
        $art=$this->assign("art",$article);
        return view("author/article");
    }
    public function articleup()
    {
        $category=model("category")->where("status",1)->select();
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

        $file = request()->file('image');
        if($file){
            // $name=iconv('utf-8','gbk',$file->getInfo()['name']);
            // $info = $file->move(ROOT_PATH . DS . 'uploads',$name);
            $info = $file->move(ROOT_PATH . DS . 'uploads');
            if($info){
            $savename=$info->getSaveName();
            $logo=str_replace('\\','/',$savename);
            }else{
                echo $file->getError();
            }
        }
        $article=model("Article");
        $article->title=$postData['title'];
        $article->logo=$logo;
        $article->author_id=$postData['author_id'];
        $article->category_id=$postData['category_id'];
        $article->description=$postData['description'];
        $article->content=$postData['content'];
        $article->save();
        if($article->id)
        {
            $this->success("文章发布成功！","author/article");
        }
         
    }
    public function articleupdate()
    {
        $my_user=session("my_user","","my");
        $postData=Request::instance()->post();
        $article=model("Article")->where('id',$postData['id'])->select();
        $this->assign("article",$article);
        return view("author/articleupdate");
    }
    public function newarticle()
    {
       if(!request()->post())
        {
            $this->error("请求错误!");
        }
        $postData=Request::instance()->post();

        $file = request()->file('image');
        if($file){
            $info = $file->move(ROOT_PATH . DS . 'uploads');
            if($info){
            $savename=$info->getSaveName();
            $logo=str_replace('\\','/',$savename);
            }else{
                echo $file->getError();
            }
        }
        $article=model("Article")->where('id',$postData['id'])->find();
        $article->title=$postData['title'];
        if($file!="")
        {
            $article->logo=$logo;
        }
        $article->status=$postData['status'];
        $article->author_id=$postData['author_id'];
        $article->category_id=$postData['category_id'];
        $article->description=$postData['description'];
        $article->content=$postData['content'];
        $article->save();
        if($article->id)
        {
            $this->success("文章更新成功！","author/article");
        }
    }
    public function articledelete()
    {
        $postData=Request::instance()->param();
        $article=model("Article")->where('id',$postData['id'])->find();
        $article->status=2;
        $article->save();
        if($article->id)
        {
            $this->success("删除成功",url('author/article'));
        }
    }
    public function category()
    {
        $category=model("Category")->select();
        $this->assign("category",$category);
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
    public function categoryupdate()
    {
        $postData=Request::instance()->param();
        $category=model("category")->where('id',$postData['id'])->find();
        $this->assign("category",$category);
        return view("author/categoryupdate");
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
        $category->categoryname=$postData['categoryname'];
        $category->status=$postData['status'];
        $category->save();
        if($category->id)
        {
            $this->success("分类更新成功！","author/category");
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
            $this->success("删除成功",url('author/category'));
        }
    }

    public function editor()
    {
        $my_user=session("my_user","","my");
        $author=model("author")->where('id',$my_user['id'])->select();
        $editor=$this->assign("editor",$author);
        return view("author/editor");
    }
    public function editorup()
    {
        $postData=Request::instance()->post();
        $author=model("Author")->where('id',$postData['id'])->find();

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

        $author->username=$postData['username'];
        $author->tel=$postData['tel'];
        if($file!="")
        {
            $author->logo=$logo;
        }
        $author->email=$postData['email'];
        $author->password=md5($postData['password'].$author->code);
        $author->save();
        if($author->id)
        {
            session(null,'my');
            $this->success("个人信息修改成功！请重新登录","login/login");
        }
        dump($postData);
    }

  
}
