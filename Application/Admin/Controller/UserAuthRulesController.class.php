<?php

namespace Admin\Controller;

use Common\Controller\BaseController;
use Think\Page;

class UserAuthRulesController extends BaseController
{
    public function index()
    {
        if (I("authname")) {
            $map['id'] = I("authname");
            $condition['a.id'] = $map['id'];
        }
        $map['is_delete'] = "0";
        $rules = D("UserAuthRules");
        $count = $rules->where($map)->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        //进行分页数据查询
        $condition['a.is_delete'] = "0";
        $condition['b.is_delete'] = "0";
        $list = $rules->table("lbqb_user_auth_rules as a")->join("lbqb_user_auths as b on b.id = a.auth_id")
            ->field('a.id,a.auth_id,a.rule_name,a.remarks,a.module,a.controller,a.action,a.create_time,b.auth_name')
            ->where($condition)->order('a.id asc')->limit($Page->firstRow, $Page->listRows)->setNotSoftDelete()->select();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }

    public function add()
    {
        $rules = D("UserAuths");
        $result = $rules->field('id,auth_name')->select();
        $this->assign("result", $result);
        $this->display();
    }

    //管理员权限规则
    public function addlist()
    {
        $id = session("admin_id");
        $data["auth_id"] = I("post.authname");
        $data["rule_name"] = I("post.rulename");
        $url = I("post.url");
        $path = explode('/', $url);
        $data["module"] = $path[0];
        $data["controller"] = $path[1];
        $data["action"] = $path[2];
        $data["remarks"] = I("post.remarks");
        $data['create_time'] = formateTime();
        $authrules = D("UserAuthRules");
        $datas = $authrules->create($data);
        if ($datas) {
            $result = $authrules->add($datas);
            if ($result) {
                $result = array(
                    'error' => true,
                    'msg' => '权限规则增加成功'
                );
            } else {
                $result = array(
                    'error' => false,
                    'msg' => '权限规则增加失败'
                );
            }
            $datalog['admin_id'] = $id;
            $logdata = [
                'admin_id' => $datalog['admin_id'],
            ];
            self::makeLog($logdata, 'admin_operate_logs');
        }
        echo json_encode($result);
    }

    public function update()
    {
        $id = I("get.id");
        if ($id) {
            $rules = D("UserAuthRules");
            $result = $rules->field('id,rule_name,remarks,controller,action')->where("id = " . $id)->find();
            $auths = D("UserAuths");
            $res = $auths->field('id,auth_name')->select();
            $data['con'] = $result['controller'];
            $data['action'] = $result['action'];
            $url = implode("/", $data);
        }
        $this->assign("result", $result);
        $this->assign("res", $res);
        $this->assign("url", $url);
        $this->display();
    }

    public function updatelist()
    {
        $id = I("post.id");
        $data["auth_id"] = I("post.authname");
        $data["rule_name"] = I("post.rulename");
        $url = I("post.url");
        $path = explode('/', $url);
        $data["controller"] = $path[0];
        $data["action"] = $path[1];
        $data["remarks"] = I("post.remarks");
        $data['update_time'] = formateTime();

        $authrules = D("UserAuthRules");
        $datas = $authrules->create($data);
        if ($datas) {
            $result = $authrules->where('id = ' . $id)->save($datas);
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
            ];
            self::makeLog($logdata, 'admin_operate_logs');
        }
        echo json_encode($result);
    }

    public function delete()
    {
        $id = I("get.id");
        $authrules = D("UserAuthRules");
        $result = $authrules->field('id')->where("id = " . $id)->find();
        $this->assign("result", $result);
        $this->display();
    }

    public function deletelist()
    {
        $id = I("get.id");
        $data['is_delete'] = "1";
        $authrules = D("UserAuthRules");
        $datas = $authrules->create($data);
        if ($datas) {
            $authrules->where('id = ' . $id)->save($data);

            $datalog['admin_id'] = session("admin_id");
            $logdata = [
                'admin_id' => $datalog['admin_id'],
            ];
            self::makeLog($logdata, 'admin_operate_logs');
        }
    }
}
