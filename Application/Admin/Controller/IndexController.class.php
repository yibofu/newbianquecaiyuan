<?php

namespace Admin\Controller;

use Common\Controller\BaseController;

header("content-type:text/html;charset=utf-8");

class IndexController extends BaseController
{
    public function common()
    {
        $adminname = session("admin_account");
        $admin = D("admins");
        $data = $admin->field('id,account,nick_name,status,role_id')->where("account = '" . $adminname . "'")->find();
        //取出角色id
        $role_id = $data["role_id"];
        if(!empty($role_id)){
            $mapping = D("admin_role_auth_mappings");
            $re = $mapping->field('auth_id')->where("role_id = " . $role_id)->select();
            foreach ($re as $k => $v) {
                $auth[] = $v['auth_id'];
            }
            if (!empty($auth)) {
                $auths = D("admin_auths");
                $res = $auths->field('id,auth_name')->where(array('id' => array('IN', $auth)))->select();
                //取出权限名称
                $resu = array_column($res, 'auth_name');
                //取出权限id
                $resul = array_column($res, 'id');
                $rules = D("admin_auth_rules");
                //根据权限Id来查询出来权限规则名称
                if (!empty($resul)) {
                    $result = $rules->field('id,auth_id,rule_name,module,controller,action')
                        ->where(array('auth_id' => array('IN', $resul)))->select();
                    //取出权限规则名称
                    $rr = array_column($result, 'rule_name');
                    //遍历出来所有权限规则下边的连接并且拼接在一起
                    foreach ($result as $k => $v) {
                        $r[] = $v['module'] . '/' . $v['controller'] . '/' . $v['action'];
                    }
                    $arr = array("resu" => $resu, "rr" => $rr, "r" => $r);
                    foreach ($res as $k => $v) {
                        $res[$k]["row"] = array();
                        foreach ($result as $k1 => $v1) {
                            if ($v["id"] == $v1["auth_id"]) {
                                $v1['path'] = $v1['module'] . '/' . $v1['controller'] . '/' . $v1['action'];
                                $res[$k]["row"][] = $v1;
                            }
                        }
                    }
                }
            }
        }else{
            $this->redirect('Login/index');
        }


        $this->assign("res", $res);
        $this->assign("adminname", $adminname);

        $this->display();
    }

    public function loginout()
    {
        session('admin_id', null);
        session('admin_account', null);
        session('admin_role_id', null);
        $this->redirect('Login/index');
    }
}
