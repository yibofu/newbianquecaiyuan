<?php

namespace Admin\Controller;

use Common\Controller\BaseController;

header("content-type:text/html;charset=utf-8");

class UserAdminController extends BaseController
{
    public function index()
    {
        $roles = D('user_roles');
        $result = $roles->field('id,role_name')->select();
        $this->assign("result", $result);
        $this->display();
    }

    public function add()
    {
        if (IS_POST) {
            $data['account'] = I("post.account");
            $data['password'] = password(I("post.password"));
            $data['password'] = $data['password']['password'];
            $data['admin_type'] = I("post.admin_type");
            $data['role_id'] = I("post.role_id");
            $data['status'] = I("post.status");
            $data['create_time'] = formateTime();
            $user = D('users');
            $datas = $user->create($data);
            if ($datas) {
                $user->add($datas);
                $datalog['user_id'] = session('user_id');
                $logdata = [
                    'user_id' => $datalog['user_id'],
                ];
                self::makeLog($logdata, 'user_operate_logs');
            }
        }
    }
}
