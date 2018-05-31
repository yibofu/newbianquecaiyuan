<?php

namespace Admin\Controller;

use Common\Controller\BaseController;

class LoginController extends BaseController
{
    public function index()
    {
        $this->display();
    }

    public function login()
    {
        if (IS_POST) {
            $admin = D('admins');
            $where['account'] = $username = I('post.account');
            if (!$username || !I('post.password')) {
                $this->error("请输入完整信息");
            }

            $adminInfo = $admin->where($where)->find();
            if (!$adminInfo) {
                $this->error('管理员不存在');
            } else {
                if ($adminInfo['status'] == '0') {
                    $this->error('禁止登录');
                }
                $password =password(I("post.password"));
                if ($password['password'] == $adminInfo['password']) {
                    session('admin_id', '' . $adminInfo['id'] . '');
                    session('admin_account', '' . $adminInfo['account'] . '');
                    session('admin_role_id', '' . $adminInfo['role_id'] . '');
                    $result = array(
                        'error' => true,
                        'msg' => '登陆成功'
                    );
                } else {
                    $this->error('密码错误');
                }
            }
        } else {
            $this->display('login');
            $result = array(
                'error' => false,
                'msg' => '登录失败'
            );
        }
        echo json_encode($result);
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
