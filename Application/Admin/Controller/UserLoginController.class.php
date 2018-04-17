<?php

namespace Admin\Controller;

use Common\Controller\BaseController;

class UserLoginController extends BaseController
{
    public function index()
    {
        $this->display();
    }

    public function login()
    {
        if (IS_POST) {
            $user = D('users');
            $where['account'] = $username = I('post.account');
            if (!$username || !I('post.password')) {
                $this->error("请输入完整信息");
            }

            $adminInfo = $user->where($where)->find();
            if (!$adminInfo) {
                $this->error('管理员不存在');
            } else {
                if ($adminInfo['status'] == '0') {
                    $this->error('禁止登录');
                }
                $password = password(I("post.password"));
                if ($password['password'] == $adminInfo['password']) {
                    cookie('admin_id', '' . $adminInfo['id'] . '');
                    session('admin_id', '' . $adminInfo['id'] . '');
                    session('admin_account', '' . $adminInfo['account'] . '');
                    session('admin_nick_name', '' . $adminInfo['nick_name']);
                    session('admin_role_id', '' . $adminInfo['role_id'] . '');
                    $this->success('登录成功', '' . __MODULE__ . '');
                } else {
                    $this->error('密码错误');
                }
            }
        } else {
            $this->display('login');
        }
    }

    //退出
    public function logout()
    {
        cookie('admin_id', null);
        session('admin_id', null);
        session('admin_account', null);
        session('admin_nick_name', null);
        session('admin_role_id', null);
        session('ADMIN_MENU_LIST', null);
        layout(false);
        $this->redirect('Login/index');
    }
}
