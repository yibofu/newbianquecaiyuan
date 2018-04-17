<?php
namespace Admin\Controller;
use Common\Controller\BaseController;
use Think\Page;

class FunctionUsingController extends BaseController{
    public function index(){
        if (I("create_time")) {
            $map['create_time'] = array('like', '%' . I('create_time') . '%');
        }
        if (I("phone")) {
            $condition['phone'] = I('phone');
        }
        $user = D("Users");
        $count = $user->field('id,nick_name,phone')->order("create_time desc")->count();
        $Page = new Page($count,10);
        $show = $Page->show();
        $list = $user->field('id,nick_name,phone,create_time')->where($condition)->limit($Page->firstRow,$Page->listRows)->order("create_time desc")->select();
        $userOperteLog = D("UserOperateLogs");
        $tableUploadLog = D("TableUploadLogs");
        foreach($list as $key=>$value){
            //登录次数
            $list[$key]["loginNum"] = $userOperteLog->logNum($value["id"],$map);
            //仪表盘查看次数
            $list[$key]["ybpNum"] = $userOperteLog->ybpNum($value["id"],$map);
            //关注查看次数
            $list[$key]["focusNum"] = $userOperteLog->ycNum($value["id"],$map);
            //预测查看次数
            $list[$key]["ycNum"] = $userOperteLog->yuceNum($value["id"],$map);
            //上传表格次数
            $list[$key]["uploadNum"] = $tableUploadLog->getCompanyTableNum($value["id"],$map);
            //下载表格次数
            $list[$key]["downloadNum"] = $userOperteLog->downLoadNum($value["id"],$map);
        }
        $this->assign("list",$list);
        $this->assign('page',$show);
        $this->display();
    }
}