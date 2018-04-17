<?php

namespace Admin\Controller;

use Common\Controller\BaseController;
use Think\Page;

header("content-type:text/html;charset=utf-8");

class HelpController extends BaseController
{
    public function index()
    {
        if (I("question")) {
            $map['question'] = array('like', '%' . I('question') . '%');
        }
        if (I("create_time")) {
            $map['create_time'] = array('like', '%' . I('create_time') . '%');
        }
        $question = D("Questions");
        $count = $question->where()->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        $list = $question->field('id,question,answer,create_time')
            ->where($map)->order('id asc')->limit($Page->firstRow, $Page->listRows)->select();
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
        $data['question'] = I("post.question");
        $data['answer'] = I("post.answer");
        $data['create_time'] = formateTime();
        $question = D("Questions");
        $datas = $question->create($data);
        if ($datas) {
            $result = $question->add($datas);
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
        $question = D("Questions");
        $result = $question->field('id,question,answer')->where("id = " . $id)->find();
        $this->assign("result", $result);
        $this->display();
    }

    public function updatelist()
    {
        $id = I("post.id");
        $question = D("Questions");
        $data['question'] = I("post.question");
        $data['answer'] = I("post.answer");
        $data['update_time'] = formateTime();
        $datas = $question->create($data);
        $result = $question->where("id = " . $id)->save($datas);
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
        $question = D("Questions");
        $result = $question->field('id')->where("id = " . $id)->find();
        $this->assign("result", $result);
        $this->display();
    }

    public function deletelist()
    {
        $id = I("get.id");
        $question = D("Questions");
        $data['is_delete'] = "1";
        $data['delete_time'] = formateTime();
        $datas = $question->create($data);
        if ($datas) {
            $result = $question->where('id = ' . $id)->save($data);

            $datalog['admin_id'] = session("admin_id");
            $logdata = [
                'admin_id' => $datalog['admin_id'],
            ];
            self::makeLog($logdata, 'admin_operate_logs');
            if ($result) {
                $this->redirect('Help/index');
            }
        }
    }

    //反馈问题
    public function feedback()
    {

        if (I("title")) {
            $map['content'] = array('like', '%' . I('content') . '%');
            $condition['a.content'] = $map['content'];
        }
        if (I("create_time")) {
            $map['create_time'] = I("create_time");
            $condition['a.create_time'] = $map['create_time'];
        }
        $condition['a.is_delete'] = "0";
        $Message = D("Message");
        $count = $Message->where()->count();
        $Page = new Page($count, 5);
        $show = $Page->show();
        $list = $Message->table('lbqb_message as a')->join("lbqb_users as b on a.uid = b.id")
            ->field('a.id,a.uid,a.content,a.create_time,b.nick_name,b.phone')
            ->where($condition)->order("a.create_time desc")->limit($Page->firstRow, $Page->listRows)
            ->setNotSoftDelete()->select();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
}
