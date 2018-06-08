<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
class IndexController extends Index
{
    public function index()
    {
        return view("index/index");
    }
    public function contact()
    {
        return view("index/contact");
    }
    public function single()
    {
        return view("index/single");
    }
    public function archive()
    {
        return view("index/archive");
    }
    public function main()
    {
        if(!request()->post())
        {
            $this->error("请求错误!","index/index");
        }
        $postData=Request::instance()->post();
        //文章
        $art=model("Article")->where("status",1)->where('id',$postData['id'])->select();

        if($postData['status']!=1)
        {
            $this->error('不存在此文章！');
        }
        
        $this->assign("art",$art);
        return view("index/main");
    }
}
