<?php

namespace Admin\Controller;

use Common\Controller\BaseController;
use Think\Page;

header("content-type:text/html;charset=utf-8");

class MemberController extends BaseController
{
    public function index()
    {
        if (I("phone")) {
            $map['phone'] = array('like', '%' . I('phone') . '%');
            $condition['c.phone'] = $map['phone'];
        }
        if (I("company_name")) {
            $map['company_name'] = array('like', '%' . I('company_name') . '%');
            $condition['b.company_name'] = $map['company_name'];
        }
        if (I("is_pay")) {
            $map['is_pay'] = I('is_pay');
            $condition['b.is_pay'] = $map['is_pay'];
        }

        $map['a.is_delete'] = "0";
        $map['b.is_delete'] = "0";
        $map['c.is_delete'] = "0";
        $map['a.is_boss'] = "1";
        $UserCompanysMapping = D("UserCompanyMappings");
        $count = $UserCompanysMapping->table("lbqb_user_company_mappings as a")
            ->join("lbqb_companys as b on b.id = a.company_id")->join("lbqb_users as c on c.id = a.uid")
            ->field('b.id,b.company_name,b.is_pay,b.vip_days,b.vip_start_time,b.vip_end_time,b.create_time,c.nick_name')
            ->where($map)->order("b.create_time desc")->group("c.phone")->setNotSoftDelete()->select();
        $counts = count($count);
        $Page = new Page($counts, 10);
        $show = $Page->show();
        //进行分页数据查询
        $condition['a.is_delete'] = "0";
        $condition['b.is_delete'] = "0";
        $condition['c.is_delete'] = "0";
        $condition['a.is_boss'] = "1";
        //查出来所有老板的公司
        $list = $UserCompanysMapping->table("lbqb_user_company_mappings as a")
            ->join("lbqb_companys as b on b.id = a.company_id")->join("lbqb_users as c on c.id = a.uid")
            ->field('a.company_id,b.id,b.company_name,b.is_pay,b.vip_days,b.vip_start_time,b.vip_end_time,b.create_time,c.phone,c.nick_name,c.create_time as ctime')
            ->where($map)->order("b.create_time desc")->group("c.phone")->limit($Page->firstRow, $Page->listRows)
            ->setNotSoftDelete()->select();
        foreach ($list as $k => $v) {
            $rrs[] = $v["company_id"];
        }
        //计算出公司会员的使用人数
        if ($rrs != null) {
            $res = $UserCompanysMapping->field('id,company_id,uid,is_boss')->where(array('company_id' => array('IN', $rrs), 'is_boss' => '0'))->select();
            //查出来公司的id
            foreach ($res as $key => $value) {
                $re[] = $value["company_id"];
            }
            $arr = array_count_values($re);
            //循环遍历公司的信息并且得到公司id
            foreach ($list as $rk => $rv) {
                foreach ($arr as $key => $val) {
                    if ($rv['id'] == $key) {
                        $list[$rk]['count'] = $val;
                    }
                }
            }

            foreach ($list as &$va) {
                $va['count'] = $va['count'] == null ? '0' : $va['count'];
            }
            foreach ($list as &$val) {
                $val['is_pay'] = $val['is_pay'] == 0 ? '未付费' : '<p style="color:red">已付费</p>';
            }
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('res', $res);
        $this->display();
    }


    public function detail()
    {
        $id = I("get.id");
        $company = D("Companys");
        $result = $company->field('company_name')->where('id = ' . $id)->find();

        //公司员工关系表
        $userCompanyMapping = D("UserCompanyMappings");
        $count = $userCompanyMapping->field('company_id,uid,role_id')
            ->where("company_id = " . $id . " and is_boss = '0'")->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        //查出来所有老板的公司
        $list = $userCompanyMapping->field('company_id,uid,role_id')
            ->where("company_id = " . $id . " and is_boss = '0'")->limit($Page->firstRow, $Page->listRows)
            ->select();
        //得到list里的uid
        foreach ($list as $userkey => $uservalue) {
            $userid[] = $uservalue["uid"];
        }
        //查出来员工的昵称和电话
        if ($userid != null) {
            $user = D("Users");
            $userResult = $user->field('id,nick_name,phone')->where(array('id' => array('IN', $userid)))->select();
            foreach ($list as $lkey => $lvalue) {
                foreach ($userResult as $mkey => $mvalue) {
                    $list[$mkey]["nick_name"] = $mvalue["nick_name"];
                    $list[$mkey]["phone"] = $mvalue["phone"];
                }
            }
        }

        //得到list里的role_id
        foreach ($list as $rolekey => $rolevalue) {
            $roleid[] = $rolevalue["role_id"];
        }
        //查出来角色id和角色名称
        if ($roleid != null) {
            $role = D("UserRoles");
            $roleResult = $role->field('id,role_name')->where(array('id' => array('IN', $roleid)))->select();
            $userAuth = D("user_auths");
            //遍历出角色id
            foreach ($list as $rok => $rov) {
                foreach ($roleResult as $kr => $vr) {
                    if ($rov['role_id'] == $vr['id']) {
                        $list[$rok]['role_name'] = $vr["role_name"];
                    }
                }
            }
            //角色权限关系表
            $userRoleAuthMappings = D("UserRoleAuthMappings");
            $authid = $userRoleAuthMappings->field('role_id,auth_id')->where(array('role_id' => array('IN', $roleid)))->select();
            foreach ($list as $rkk => $rvv) {
                $tmp = [];
                foreach ($authid as $kra => $vra) {
                    if ($rvv['role_id'] == $vra['role_id']) {
                        $tmp[] = $vra["auth_id"];
                    }
                    if ($tmp != null) {
                        $auths = $userAuth->field('id,auth_name')->where(["id" => ['in', $tmp]])->select();
                        $auth_name = array_column($auths, 'auth_name');
                        $list[$rkk]["auth_name"] = $auth_name;
                    }
                }
            }
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign("result", $result);
        $this->display();
    }
}
