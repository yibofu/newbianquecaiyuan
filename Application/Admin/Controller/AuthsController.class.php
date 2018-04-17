<?php

namespace Admin\Controller;

use Common\Controller\BaseController;
use Think\Page;

class AuthsController extends BaseController
{

    public function index()
    {
        if (I("authname")) {
            $map['id'] = I('authname');
        }
        $auth = D("AdminAuths");
        $count = $auth->where($map)->order('id asc')->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        $list = $auth->where($map)->order('id asc')->limit($Page->firstRow, $Page->listRows)->select();
        $data = $auth->field('id,auth_name')->select();
        $this->assign('list', $list);
        $this->assign('data', $data);
        $this->assign('page', $show);
        $this->display();
    }

    public function add()
    {
        $this->display();
    }

    //管理员权限表
    public function addlist()
    {
        $id = session("admin_id");
        $data['auth_name'] = I("post.authname");
        $data['remarks'] = I("post.remarks");
        $data['create_time'] = formateTime();
        $auths = D("admin_auths");
        $datas = $auths->create($data);
        if ($datas) {
            $result = $auths->add($datas);
            $admins = D("admins");
            $res = $admins->field('role_id')->find();
            $roleid = $res["role_id"];
            $authid = $result;
            $mapping = D("admin_role_auth_mappings");
            $daa = $mapping->create();
            $daa["role_id"] = $roleid;
            $daa["auth_id"] = $authid;
            $daa["create_time"] = formateTime();
            $ras = $mapping->add($daa);
            if ($ras) {
                $result = array(
                    'error' => true,
                    'msg' => '权限增加成功'
                );
            } else {
                $result = array(
                    'error' => false,
                    'msg' => '权限增加失败'
                );
            }
            $datalog['admin_id'] = $id;
            $logdata = [
                'admin_id' => $datalog['admin_id'],
                $data['create_time'] => formateTime(),
            ];
            self::makeLog($logdata, 'admin_operate_logs');
        }
        echo json_encode($result);
    }

    public function update()
    {
        $id = I("get.id");
        if ($id) {
            $auth = D("admin_auths");
            $result = $auth->field('id,auth_name,remarks')->where("id = " . $id)->find();
        }
        $this->assign("result", $result);
        $this->display();
    }

    //管理员权限表修改
    public function updatelist()
    {
        $id = I("post.id");
        $data['auth_name'] = I("post.authname");
        $data['remarks'] = I("post.remarks");
        $data['update_time'] = formateTime();
        $auths = D("admin_auths");
        $datas = $auths->create($data);
        if ($datas) {
            $result = $auths->where('id = ' . $id)->save($datas);
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
        $auths = D("admin_auths");
        $result = $auths->field('id')->where("id = " . $id)->find();
        $this->assign("result", $result);
        $this->display();

    }

    //管理员权限表删除
    public function deletelist()
    {
        $id = I("get.id");
        $data['is_delete'] = "1";
        $data['delete_time'] = formateTime();
        $auths = D("admin_auths");
        $datas = $auths->create($data);
        if ($datas) {
            $result = $auths->where('id = ' . $id)->save($datas);
            $authrule = D("AdminAuthRules");
            $authrule->where("auth_id = " . $id)->save($datas);
            if ($result) {
                $this->redirect('Auths/index');
            }
            //模拟登陆管理员的id
            $datalog['admin_id'] = session("admin_id");
            $logdata = [
                'admin_id' => $datalog['admin_id'],
                $data['delete_time'] => formateTime(),
            ];
            self::makeLog($logdata, 'admin_operate_logs');
        }

    }
}
