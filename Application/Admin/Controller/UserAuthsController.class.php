<?php

namespace Admin\Controller;

use Common\Controller\BaseController;
use Think\Page;

class UserAuthsController extends BaseController
{

    public function index()
    {
        if (I("authname")) {
            $map['id'] = array('eq', I("authname"));
        }
        $map['is_delete'] = "0";
        $auth = D("UserAuths");
        $count = $auth->where($map)->count();// 查询满足要求的总记录数 $map表示查询条件
        $Page = new Page($count, 10);// 实例化分页类 传入总记录数
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询
        $list = $auth->where($map)->order('id asc')->limit($Page->firstRow, $Page->listRows)->select(); // $Page->firstRow 起始条数 $Page->listRows 获取多少条

        $this->assign('list', $list);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出
        $this->display(); // 输出模板
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
        $auths = D("UserAuths");
        $datas = $auths->create($data);
        if ($datas) {
            $result = $auths->add($datas);
            if ($result) {
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
            $auth = D("UserAuths");
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
        $auths = D("UserAuths");
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
        $auths = D("UserAuths");
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
        $auths = D("user_auths");
        $datas = $auths->create($data);
        if ($datas) {
            $result = $auths->where('id = ' . $id)->save($datas);
            $authrule = D("UserAuthRules");
            $res = $authrule->where("auth_id = " . $id)->save($datas);
            if ($result) {
                $this->redirect('UserAuths/index');
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
