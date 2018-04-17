<?php

namespace Admin\Controller;

use Common\Controller\BaseController;

class UserMappingsController extends BaseController
{
    public function index()
    {
        $userroles = D("user_roles");
        $result = $userroles->field('id,role_name')->select();
        $userauths = D("user_auths");
        $arr = $userauths->field('id,auth_name')->select();
        $this->assign("result", $result);
        $this->assign("arr", $arr);
        $this->display();
    }

    public function add()
    {
        $data["role_id"] = I("post.role");
        $data["auth_id"] = I("post.auth");
        $data['create_time'] = formateTime();
        $mappings = D("user_role_auth_mappings");
        $datas = $mappings->create($data);
        if ($datas) {
            $mappings->add($datas);
            $datalog['user_id'] = 1;
            $logdata = [
                'user_id' => $datalog['user_id'],
            ];
            self::makeLog($logdata, 'user_operate_logs');
        }
    }
}

