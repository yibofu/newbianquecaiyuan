<?php

namespace Admin\Controller;

use Common\Controller\BaseController;
use Think\Page;

class  MessageController extends BaseController
{

    public function index()
    {
        if (I("content")) {
            $map['content'] = array('like', '%' . I('content') . '%');
            $condition['a.content'] = $map['content'];
        }
        $map['is_delete'] = "0";
        $message = D("Message");
        $count = $message->where($map)->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        $condition['a.is_delete'] = "0";
        $condition['b.is_delete'] = "0";

        $list = $message->table("lbqb_message as a")->join("lbqb_users as b on b.id = a.uid")
            ->field('a.id,a.content,a.create_time,b.phone,b.nick_name')
            ->where($condition)->order('a.id asc')->limit($Page->firstRow, $Page->listRows)->setNotSoftDelete()->select();


        //管理员角色
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }

    public function show()
    {
        $id = I("get.id");
        $message = D("Message");
        $result = $message->field("content")->where("id = " . $id)->find();
        $this->assign("result", $result);
        $this->display();
    }

    public function delete()
    {
        $id = I("get.id");
        $message = D("Message");
        $result = $message->field('id')->where("id = " . $id)->find();
        $this->assign("result", $result);
        $this->display();
    }

    //管理员权限表删除
    public function deletelist()
    {
        $id = I("get.id");
        $data['is_delete'] = "1";
        $data['delete_time'] = formateTime();
        $message = D("Message");
        $datas = $message->create($data);
        if ($datas) {
            $result = $message->where('id = ' . $id)->save($datas);
            if ($result) {
                $result = array(
                    'error' => true,
                    'msg' => '删除成功'
                );
            } else {
                $result = array(
                    'error' => false,
                    'msg' => '删除失败'
                );
            }

            $datalog['admin_id'] = session("admin_id");
            $logdata = [
                'admin_id' => $datalog['admin_id'],
                $data['delete_time'] => formateTime(),
            ];
            self::makeLog($logdata, 'admin_operate_logs');
            if ($result) {
                $this->redirect('Message/index');
            }
        }
    }
}
