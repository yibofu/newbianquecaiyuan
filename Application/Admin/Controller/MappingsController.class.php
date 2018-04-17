<?php

namespace Admin\Controller;

use Common\Controller\BaseController;

class MappingsController extends BaseController
{
    public function index()
    {
        $adminroles = D("admin_roles");
        $result = $adminroles->field('id,role_name')->select();
        $adminauths = D("admin_auths");
        $arr = $adminauths->field('id,auth_name')->select();
        $this->assign("result", $result);
        $this->assign("arr", $arr);
        $this->display();
    }

    public function add()
    {
        $data["role_id"] = I("post.role");
        $data["auth_id"] = I("post.auth");
        $data['create_time'] = formateTime();
        $mappings = D("admin_role_auth_mappings");
        $datas = $mappings->create($data);
        if ($datas) {
            $mappings->add($datas);
            $datalog['admin_id'] = session("admin_id");
            $logdata = [
                'admin_id' => $datalog['admin_id'],
                $data['delete_time'] => formateTime(),
            ];
            self::makeLog($logdata, 'admin_operate_logs');
        }
    }
}
