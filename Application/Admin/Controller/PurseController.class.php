<?php

namespace Admin\Controller;

use Common\Controller\BaseController;
use Think\Page;

header("content-type:text/html;charset=utf-8");

class PurseController extends BaseController
{
    public function index()
    {

        if (I("content")) {
            $map['content'] = array('like', '%' . I('content') . '%');
        }
        if (I("create_time")) {
            $map['create_time'] = array('like', '%' . I('create_time') . '%');
        }
        $customization = D("Customization");
        $count = $customization->where($map)->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        //进行分页数据查询
        $list = $customization->field('id,name,phone,content,is_contact,create_time')
            ->where($map)->order('create_time desc')
            ->limit($Page->firstRow, $Page->listRows)->select();
        foreach ($list as &$val) {
            $val['is_contact'] = $val['is_contact'] == 1 ? '未联系' : '<p style="color:red">已联系</p>';
        }
        //管理员角色
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }

    public function sure()
    {
        $id = I("get.id");
        $data['is_contact'] = "2";
        $data['update_time'] = formateTime();
        $customization = D("Customization");

        $result = $customization->where("id  = " . $id)->save($data);
        if ($result) {
            $this->redirect('Purse/index');
        }
    }

    public function detail()
    {
        $id = I("get.id");
        $customization = D("Customization");
        $list = $customization->field('id,content,name,phone')->where("id = " . $id)->find();
        //管理员角色
        $this->assign('list', $list);// 赋值数据集
        $this->display();
    }
}
