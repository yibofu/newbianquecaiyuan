<?php

namespace Admin\Controller;

use Common\Controller\BaseController;

header("content-type:text/html;charset=utf-8");

class UserRoleController extends BaseController
{

    public function index()
    {
        $userauths = D('user_auths');
        $result = $userauths->field('id,auth_name')->select();
        $this->assign("result", $result);
        $this->display();
    }

    //管理员角色表的角色增加
    public function message()
    {
        $data['role_name'] = I("post.rolename");
        $data['remarks'] = I("post.remarks");
        $data['create_time'] = formateTime();
        $roles = D("user_roles");
        $datas = $roles->create($data);
        if ($datas) {
            $result = $roles->add($datas);
            if ($result) {
                $rolevalue = $roles->field('id')->where("role_name = '" . $_POST['rolename'] . "'")->find();
                $role_id = $rolevalue['id'];
                $auth_id = I("post.authname");

                $mapping = D("user_role_auth_mappings");
                $value['role_id'] = $role_id;
                $value['auth_id'] = $auth_id;
                $value['create_time'] = formateTime();
                foreach ($value['auth_id'] as $k => $v) {
                    $value['auth_id'] = $v;
                    $mapping->add($value);
                }
            }
            //模拟登陆管理员的id
            $datalog['user_id'] = 1;
            $logdata = [
                'user_id' => $datalog['user_id'],
            ];
            self::makeLog($logdata, 'user_operate_logs');
        }
    }

    //管理员角色表的角色修改
    public function update()
    {
        $id = $_GET['id'];
        $data['role_name'] = I("post.rolename");
        $data['remarks'] = I("post.remarks");
        $data['create_time'] = formateTime();
        $roles = D("user_roles");
        $datas = $roles->create($data);
        if ($datas) {
            $roles->where('id = ' . $id)->save($datas);
            //模拟登陆管理员的id
            $datalog['user_id'] = 1;
            $logdata = [
                'user_id' => $datalog['user_id'],
            ];
            self::makeLog($logdata, 'user_operate_logs');
        }
    }

    //管理员角色表的角色删除
    public function delete()
    {
        $id = $_GET['id'];
        $data['is_delete'] = "1";
        $roles = D("user_roles");
        $datas = $roles->create($data);
        if ($datas) {
            $roles->where('id = ' . $id)->save($datas);
            //模拟登陆管理员的id
            $datalog['user_id'] = 1;
            $logdata = [
                'user_id' => $datalog['user_id'],
            ];
            self::makeLog($logdata, 'user_operate_logs');
        }
    }
}
