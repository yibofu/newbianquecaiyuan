<?php

namespace Admin\Controller;

use Common\Controller\BaseController;
use Think\Page;

class  VipDaysController extends BaseController
{

    public function index()
    {
        $map['is_delete'] = "0";
        $vip = D("VipDays");
        $count = $vip->where($map)->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        // 进行分页数据查询
        $list = $vip->where($map)->order('id asc')->limit($Page->firstRow, $Page->listRows)->select();

        $data = $vip->where("is_delete = '0'")->select();
        $this->assign('list', $list);
        $this->assign('data', $data);
        $this->assign('page', $show);
        $this->display();
    }

    public function add()
    {
        $this->display();
    }

    public function addlist()
    {
        $data['days'] = I("post.days");
        $data['money'] = I("post.money");
        $data['create_time'] = formateTime();
        $data['admin_id'] = session("admin_id");
        $vip = D("VipDays");
        $datas = $vip->create($data);
        if ($datas) {
            $result = $vip->add($datas);
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
        if ($id) {
            $vip = D("VipDays");
            $result = $vip->field('id,days,money')->where("id = " . $id)->find();
        }
        $this->assign("result", $result);
        $this->display();
    }

    //管理员权限表修改
    public function updatelist()
    {
        $id = I("post.id");
        $data['days'] = I("post.days");
        $data['money'] = I("post.money");
        $data['update_time'] = formateTime();
        $vip = D("VipDays");
        $datas = $vip->create($data);
        if ($datas) {
            $result = $vip->where('id = ' . $id)->save($datas);
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
            //模拟登陆管理员的id
            $datalog['admin_id'] = session("admin_id");
            $logdata = [
                'admin_id' => $datalog['admin_id'],
                $data['update_time'] => formateTime(),
            ];
            self::makeLog($logdata, 'admin_operate_logs');
        }
        echo json_encode($result);
    }

    public function delete()
    {
        $id = I("get.id");
        $vip = D("VipDays");
        $result = $vip->field('id')->where("id = " . $id)->find();
        $this->assign("result", $result);
        $this->display();
    }

    public function deletelist()
    {
        $id = I("get.id");
        $vip = D("VipDays");
        $data['is_delete'] = "1";
        $data['delete_time'] = formateTime();
        $datas = $vip->create($data);
        if ($datas) {
            $result = $vip->where('id = ' . $id)->save($data);

            $datalog['admin_id'] = session("admin_id");
            $logdata = [
                'admin_id' => $datalog['admin_id'],
            ];
            self::makeLog($logdata, 'admin_operate_logs');
            if ($result) {
                $this->redirect('VipDays/index');
            }
        }
    }
}
