<?php

namespace Admin\Controller;

use Common\Controller\BaseController;
use Think\Upload;
use Think\Page;

header("content-type:text/html;charset=utf-8");

class AdsController extends BaseController
{
    public function index()
    {

        if (I("title")) {
            $map['title'] = array('like', '%' . I('title') . '%');
            $condition['a.title'] = $map['title'];
        }
        if (I("create_time")) {
            $map['create_time'] = array('like', '%' . I('create_time') . '%');
            $condition['a.create_time'] = $map['create_time'];
        }
        $map['is_delete'] = "0";
        $ads = D("ads");
        $count = $ads->where($map)->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        $condition['a.is_delete'] = "0";
        $condition['b.is_delete'] = "0";
        $list = $ads->table("lbqb_ads as a")->join("lbqb_admins as b on b.id = a.admin_id")
            ->field('a.id,a.title,a.picture,a.create_time,a.position,a.link,a.status,b.nick_name')
            ->where($condition)->order('a.id asc')->limit($Page->firstRow, $Page->listRows)->setNotSoftDelete()->select();
        foreach ($list as &$val) {
            if ($val['position'] == "1") {
                $val['position'] = "首页";
            } else if ($val['position'] == '2') {
                $val['position'] = "弹窗";
            } else {
                $val['position'] = "跳转";
            }
            $val['status'] = $val['status'] == 1 ? '未上线' : '<p style="color:red">已上线</p>';
        }

        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }

    public function add()
    {
        $this->display();
    }

    public function addlist()
    {
        $data['position'] = I("post.position");
        $data['title'] = I("post.title");
        $data['link'] = I("post.link");
        $data['admin_id'] = session("admin_id");
        $data['status'] = I("post.status");
        $data['picture'] = $_FILES['picture'];
        $data['create_time'] = formateTime();
        $upload = new \Think\Upload();
        $upload->maxSize = 3145728;
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
        $upload->rootPath = './Public/Uploads/';
        $info = $upload->upload();
        if (!$info) {
            $msg = $upload->getError();
        } else {
            $data['picture'] = $info['picture']['savepath'] . $info['picture']['savename'];
        }
        $ads = D('ads');
        $datas = $ads->create($data);
        if ($datas) {
            $result = $ads->add($datas);
            if ($result) {
                $this->redirect('Ads/index');
            } else {
                $result = array(
                    'error' => false,
                    'msg' => '增加失败'
                );
            }
        }
        $datalog['admin_id'] = session('admin_id');
        $logdata = [
            'admin_id' => $datalog['admin_id'],
        ];
        self::makeLog($logdata, 'admin_operate_logs');
    }

    public function update()
    {
        $id = I("get.id");
        $ads = D("ads");
        $result = $ads->field('id,admin_id,title,picture,position,link,is_delete')->where("id = " . $id)->find();
        $this->assign("result", $result);
        $this->display();
    }

    public function updatelist()
    {
        $id = I("post.id");
        $ads = D("ads");
        $ra = $ads->field('picture')->where("id = " . $id)->find();
        $pic = $ra["picture"];
        $pic = "/Public/Uploads/" . $pic;

        $data['position'] = I("post.position");
        $picture = I("post.picture");
        $data['title'] = I("post.title");
        $data['link'] = I("post.link");
        $data['status'] = I("post.status");
        $data['update_time'] = formateTime();
        $data['adminid'] = session("admin_id");
        if ($_FILES != '') {

            $upload = new \Think\Upload();
            $upload->maxSize = 3145728;
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
            $upload->rootPath = './Public/Uploads/';
            $info = $upload->upload();
            unlink($pic);
            if (!$info) {
                $msg = $upload->getError();
            } else {
                $data['picture'] = $info['picture']['savepath'] . $info['picture']['savename'];
            }
        }

        $datas = $ads->create($data);
        $result = $ads->where("id = " . $id)->save($datas);
        if ($result) {

            $this->redirect('Ads/index');

        } else {
            $result = array(
                'error' => false,
                'msg' => '修改失败'
            );
        }


        $datalog['admin_id'] = session('admin_id');
        $logdata = [
            'admin_id' => $datalog['admin_id'],
        ];
        self::makeLog($logdata, 'admin_operate_logs');
    }

    //广告展示
    public function upshow()
    {
        $id = I("get.id");
        $data['status'] = "2";
        $data['update_time'] = formateTime();
        $ads = D("ads");

        $result = $ads->where("id  = " . $id)->save($data);
        if ($result) {
            $this->redirect('Ads/index');
        }
    }

    //广告下架
    public function downshow()
    {
        $id = I("get.id");
        $data['status'] = "1";
        $data['update_time'] = formateTime();
        $ads = D("ads");

        $result = $ads->where("id  = " . $id)->save($data);
        if ($result) {
            $this->redirect('Ads/index');
        }
    }

    public function delete()
    {
        $id = I("get.id");
        $ads = D("ads");
        $result = $ads->field('id')->where("id = " . $id)->find();
        $this->assign("result", $result);
        $this->display();
    }

    public function deletelist()
    {
        $id = I("get.id");
        $ads = D("ads");
        $data['is_delete'] = "1";
        $data['delete_time'] = formateTime();
        $datas = $ads->create($data);
        if ($datas) {
            $result = $ads->where('id = ' . $id)->save($data);

            $datalog['admin_id'] = session("admin_id");
            $logdata = [
                'admin_id' => $datalog['admin_id'],
            ];
            self::makeLog($logdata, 'admin_operate_logs');
            if ($result) {
                $this->redirect('Ads/index');
            }
        }
    }
}
