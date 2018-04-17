<?php

namespace Admin\Controller;

use Common\Controller\BaseController;
use Think\Page;

header("content-type:text/html;charset=utf-8");

class NoticeController extends BaseController
{
    public function index()
    {

        if (I("title")) {
            $map['title'] = array('like', '%' . I('title') . '%');
            $condition['a.title'] = $map['title'];
        }
        if (I("create_time")) {
            $map['create_time'] = I("create_time");
            $condition['a.create_time'] = $map['create_time'];
        }
        $map['is_delete'] = "0";
        $notice = D("Notice");
        $count = $notice->where($map)->count();// 查询满足要求的总记录数 $map表示查询条件
        $Page = new Page($count, 10);// 实例化分页类 传入总记录数
        $show = $Page->show();// 分页显示输出
        //进行分页数据查询
        $condition['a.is_delete'] = "0";
        $condition['b.is_delete'] = "0";

        $list = $notice->table("lbqb_notice as a")->join("lbqb_admins as b on b.id = a.admin_id")
            ->field('a.id,a.title,a.content,a.create_time,b.nick_name')
            ->where($condition)->order('a.id asc')->limit($Page->firstRow, $Page->listRows)->setNotSoftDelete()->select();


        //管理员角色
        $this->assign('list', $list);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出
        $this->display(); // 输出模板
    }

    public function add()
    {
        $this->display();
    }

    public function addlist()
    {
        $data['title'] = I("post.title");
        $data['content'] = I("post.content");
        $data['create_time'] = formateTime();
        $data['admin_id'] = session("admin_id");
        $notice = D('Notice');
        $datas = $notice->create($data);
        if ($datas) {
            $result = $notice->add($datas);
            if ($result) {
                $result = array(
                    'error' => true,
                    'msg' => '增加成功'
                );
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
        echo json_encode($result);
    }

    public function update()
    {
        $id = I("get.id");
        $notice = D('Notice');
        $result = $notice->field('id,title,content')->where("id = " . $id)->find();
        $this->assign("result", $result);
        $this->display();
    }

    public function updatelist()
    {
        $id = I("post.id");
        $notice = D('Notice');
        $data['title'] = I("post.title");
        $data['content'] = I("post.content");
        $data['update_time'] = formateTime();
        $data['admin_id'] = session("admin_id");
        $datas = $notice->create($data);
        $result = $notice->where("id = " . $id)->save($datas);
        if ($result) {
            $result = array(
                'error' => true,
                'msg' => '修改成功'
            );
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
        echo json_encode($result);
    }

    public function delete()
    {
        $id = I("get.id");
        $notice = D('Notice');
        $result = $notice->field('id')->where("id = " . $id)->find();
        $this->assign("result", $result);
        $this->display();
    }

    public function deletelist()
    {
        $id = I("get.id");
        $notice = D('Notice');
        $data['is_delete'] = "1";
        $data['delete_time'] = formateTime();
        $datas = $notice->create($data);
        if ($datas) {
            $result = $notice->where('id = ' . $id)->save($data);

            $datalog['admin_id'] = session("admin_id");
            $logdata = [
                'admin_id' => $datalog['admin_id'],
            ];
            self::makeLog($logdata, 'admin_operate_logs');
            if ($result) {
                $this->redirect('Notice/index');
            }
        }
    }
}
