<?php

namespace Admin\Controller;

use Common\Controller\BaseController;
use Think\Page;

header("content-type:text/html;charset=utf-8");

class UserController extends BaseController
{
    public function index1()
    {
        if (I("phone")) {
            $map['phone'] = array('like', '%' . I('phone') . '%');
            $condition['c.phone'] = $map['phone'];
        }
        if (I("nick_name")) {
            $map['nick_name'] = array('like', '%' . I('nick_name') . '%');
            $condition['b.nick_name'] = $map['nick_name'];
        }
        $map['a.is_delete'] = "0";
        $map['b.is_delete'] = "0";
        $map['c.is_delete'] = "0";
        $user = D("Users");
        $count = $user->table("lbqb_users as a")->join(" left join lbqb_user_company_mappings as b on b.uid = a.id")
            ->join("lbqb_companys as c on c.id = b.company_id")
            ->field('b.company_id,a.is_boss,b.role_id,a.status,c.id,c.company_name,c.is_pay,c.vip_days,c.vip_start_time,c.vip_end_time,a.create_time,a.phone,a.nick_name')
            ->where($map)->order("a.create_time desc")->group("a.phone")->setNotSoftDelete()->select();
        $counts = count($count);
        $Page = new Page($counts, 10);
        $show = $Page->show();
        //进行分页数据查询
        $condition['a.is_delete'] = "0";
        $condition['b.is_delete'] = "0";
        $condition['c.is_delete'] = "0";
        //查出来所有老板的公司
        $list = $user->table("lbqb_users as a")->join(" left join lbqb_user_company_mappings as b on b.uid = a.id")
            ->join("lbqb_companys as c on c.id = b.company_id")
            ->field('b.company_id,a.is_boss,b.role_id,a.status,c.id,c.company_name,c.is_pay,c.vip_days,c.vip_start_time,c.vip_end_time,a.create_time,a.phone,a.nick_name')
            ->where($condition)->order("a.create_time desc")->group("a.phone")->limit($Page->firstRow, $Page->listRows)
            ->setNotSoftDelete()->select();
        foreach ($list as &$val) {
            $val['status'] = $val['status'] == 1 ? '未禁用' : '<p style="color:red;">禁用</p>';
            $val['is_boss'] = $val['is_boss'] == 1 ? '是' : '不是';
        }
        foreach ($list as $key => $value) {
            $ro[] = $value["role_id"];
        }
        if ($ro != null) {
            $role = D("UserRoles");
            $roleName = $role->field('id,role_name')->where(array("id" => array('IN', $ro)))->select();
            foreach ($list as $lk => $lv) {
                foreach ($roleName as $rk => $rv) {
                    if ($lv["role_id"] == $rv["id"]) {
                        $list[$lk]["role_name"] = $rv["role_name"];
                    }
                }
            }
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }



    public function index()
    {
        if (I("phone")) {
            $map['phone'] = array('like', '%' . I('phone') . '%');
        }
        if (I("nick_name")) {
            $map['nick_name'] = array('like', '%' . I('nick_name') . '%');
        }
        $user = D("Users");
        $count = $user->where($map)->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        //进行分页数据查询
        $list = $user->field('id,nick_name,phone,create_time')
            ->where($map)->order('create_time desc')
            ->limit($Page->firstRow, $Page->listRows)->select();
        foreach($list as $key=>$value){
            $arr[] = $value["id"];
        }
        if($arr != null){
            $userCompanyMapping = D("UserCompanyMappings");
            $userCompanyMessage = $userCompanyMapping->field("company_id,uid,status,role_id,is_boss")
                ->where(array('uid' => array('IN',$arr)))->select();
            foreach($list as $lk=>$lv){
                foreach($userCompanyMessage as $ucmk=>$ucmv){
                    if($lv["id"] == $ucmv["uid"]){
                        $list[$lk]["company_id"] = $ucmv["company_id"];
                        $list[$lk]["status"] = $ucmv["status"];
                        $list[$lk]["role_id"] = $ucmv["role_id"];
                        $list[$lk]["is_boss"] = $ucmv["is_boss"];
                    }
                }
            }
            foreach($list as $kk=>$vv){
                $con[] = $vv["company_id"];
            }
            if($con != null){
                $company = D("Companys");
                $companyMessage = $company->field("id,company_name")->where(array('id' => array('IN',$con)))->select();
                foreach($list as $tk=>$tv){
                    foreach($companyMessage as $cmk=>$cmv){
                        if($tv["company_id"] == $cmv["id"]){
                            $list[$tk]["company_name"] = $cmv["company_name"];
                        }
                    }
                }
            }


            foreach ($list as $ks => $vs) {
                $ro[] = $vs["role_id"];
            }
            if ($ro != null) {
                $role = D("UserRoles");
                $roleName = $role->field('id,role_name')->where(array("id" => array('IN', $ro)))->select();
                foreach ($list as $lks => $lvs) {
                    foreach ($roleName as $rk => $rv) {
                        if ($lvs["role_id"] == $rv["id"]) {
                            $list[$lks]["role_name"] = $rv["role_name"];
                        }
                    }
                }
            }

        }
        foreach ($list as &$val) {
            $val['status'] = $val['status'] == 1 ? '未禁用' : '<p style="color:red;">禁用</p>';
            $val['is_boss'] = $val['is_boss'] == 1 ? '是' : '不是';
        }
        //管理员角色
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
}
