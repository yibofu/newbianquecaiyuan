<?php
namespace APP\Controller;
use Think\Controller;
header("content-type:text/html;charset=utf-8");
class IndexController extends Controller {
	public function index()
	{
	    $map["is_delete"] = "0";
	    //首页最上边的轮播banner图
	    $banner = D("Banner");
	    $bannerResult = $banner->field('id,url')->where($map)->find();
	    //首页下边的资讯模块
	    $information = D("Information");
	    $informationResult = $information->field('id,title')->where($map)->select();
	    //首页资讯模块旁边的小图
        $informationimg = D("information_img");
        $informationImgResult = $informationimg->field('id,url')->where($map)->find();
        //课程安排模块(中间的橘黄色表格)
        $course = D("Course");
        $courseResult = $course->field('id,coursename,address,month')->where(array($map,is_show=>'1'))->select();
        $data = [];
        foreach($courseResult as $arr){
            $data[$arr['month']][] = $arr;
        }
        $this->assign("result",keywords());
        $this->assign("bannerResult",$bannerResult);
        $this->assign("informationResult",$informationResult);
        $this->assign("informationImgResult",$informationImgResult);
        $this->assign("courseResult",$courseResult);
        $this->assign("data",$data);
		$this->display();
	}
}