<?php

namespace Admin\Controller;

use Common\Controller\BaseController;
use Think\Page;

header("content-type:text/html;charset=utf-8");

class AdminController extends BaseController
{
    public function index()
    {
        if (I("account")) {
            $map['account'] = array('like', '%' . I('account') . '%');
            $condition['a.account'] = $map['account'];
        }
        $map['is_delete'] = "0";
        $rules = D("Admins");
        $count = $rules->where($map)->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        $condition['a.is_delete'] = "0";
        $condition['b.is_delete'] = "0";
        $list = $rules->table("lbqb_admins as a")->join("lbqb_admin_roles as b on b.id = a.role_id")
            ->field('a.id,a.account,a.nick_name,a.status,a.admin_type,a.create_time,b.role_name')
            ->where($condition)->order('a.id asc')->limit($Page->firstRow, $Page->listRows)->setNotSoftDelete()->select();
        foreach ($list as &$val) {
            $val['status'] = $val['status'] == 1 ? '未禁用' : '禁用';
            $val['admin_type'] = $val['admin_type'] == 1 ? '超级管理员' : '普通管理员';
        }

        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }

    public function add()
    {
        $roles = D('admin_roles');
        $result = $roles->field('id,role_name')->select();
        $auths = D('admin_auths');
        $rs = $auths->field('id,auth_name')->select();
        $this->assign("result", $result);
        $this->assign("rs", $rs);
        $this->display();
    }

    public function addlist()
    {
        if (IS_POST) {
            $data['role_id'] = I("post.role_name");//增加到admin表和mappings表里的role_id
            $data['account'] = I("post.account");//增加到admin表的account
            $data['nick_name'] = I("post.nick_name");//增加到admin表里的nick_name
            $data['password'] = password(I("post.password"));//增加到admin表里的password
            $data['password'] = $data['password']['password'];
            $data['status'] = I("post.status");//增加到admin表里的status字段
            $data['admin_type'] = I("post.admin_type");//增加到admin表里的admin_type字段
            $data['create_time'] = formateTime();//增加到admin表里的cerate_time字段
            $admin = D('admins');
            $datas = $admin->create($data);
            if ($datas) {
                $result = $admin->add($datas);
                if ($result) {
                    $condition['role_id'] = I("post.role_name");
                    $condition['auth_id'] = I("post.auth_name");
                    $condition['create_time'] = formateTime();
                    $mappings = D("admin_role_auth_mappings");
                    $conditions = $mappings->create($condition);
                    foreach ($conditions['auth_id'] as $k => $v) {
                        $conditions['auth_id'] = $v;
                        $res = $mappings->add($conditions);
                    }
                    if ($res) {
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
            }
        }
        echo json_encode($result);
    }

    public function update()
    {
        $roles = D('admin_roles');
        $result = $roles->field('id,role_name')->select();
        $auths = D('admin_auths');
        $rs = $auths->field('id,auth_name')->select();
        $this->assign("result", $result);
        $this->assign("rs", $rs);
        $this->display();
    }

    public function updatelist()
    {
        if (IS_POST) {
            $id = I("get.id");
            $data['role_id'] = I("post.role_name");
            $data['account'] = I("post.account");
            $data['nick_name'] = I("post.nick_name");
            $data['password'] = password(I("post.password"));
            $data['password'] = $data['password']['password'];
            $data['status'] = I("post.status");
            $data['admin_type'] = I("post.admin_type");
            $data['create_time'] = formateTime();
            $admin = D('admins');
            $datas = $admin->create($data);
            if ($datas) {
                $result = $admin->where("id = " . $id)->save($datas);
                if ($result) {
                    $condition['role_id'] = I("post.role_name");
                    $condition['auth_id'] = I("post.auth_name");
                    $condition['create_time'] = formateTime();
                    $mappings = D("admin_role_auth_mappings");
                    $conditions = $mappings->create($condition);
                    foreach ($conditions['auth_id'] as $k => $v) {
                        $conditions['auth_id'] = $v;
                        $res = $mappings->where()->save($conditions);
                    }
                    if ($res) {
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
                }
                $datalog['admin_id'] = session('admin_id');
                $logdata = [
                    'admin_id' => $datalog['admin_id'],
                ];
                self::makeLog($logdata, 'admin_operate_logs');
            }
        }
        echo json_encode($result);
    }

    public function delete()
    {
        $id = I("get.id");
        $admin = D("admins");
        $result = $admin->field('id')->where("id = " . $id)->find();
        $this->assign("result", $result);
        $this->display();
    }

    public function deletelist()
    {
        $id = I("get.id");
        $admin = D("admins");
        $data['is_delete'] = "1";
        $data['delete_time'] = formateTime();
        $datas = $admin->create($data);
        if ($datas) {
            $result = $admin->where('id = ' . $id)->save($data);
            $datalog['admin_id'] = session("admin_id");
            $logdata = [
                'admin_id' => $datalog['admin_id'],
            ];
            self::makeLog($logdata, 'admin_operate_logs');
            if ($result) {
                $this->redirect('Admin/index');
            }
        }
    }
}
