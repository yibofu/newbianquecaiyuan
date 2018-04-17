<?php

namespace Admin\Controller;

use Common\Controller\BaseController;
use Think\Page;

header("content-type:text/html;charset=utf-8");

class DistributionController extends BaseController
{
    public function index()
    {

        if (I("phone")) {
            $map['nphone'] = array('like', '%' . I('phone') . '%');
        }

        $distribution = D("Distribution");
        $count = $distribution->where($map)->order('create_time desc')->group("uid")->select();
        $counts = count($count);
        $Page = new Page($counts, 10);
        $show = $Page->show();
        $list = $distribution->field('id,uid,nphone,count(nphone),create_time')->where($map)
            ->order('create_time desc')->group("uid")->limit($Page->firstRow, $Page->listRows)->select();

        foreach ($list as $k => $v) {
            $rrs[] = $v["uid"];
        }
        if (!empty($rrs)) {
            $user = D("Users");
            $res = $user->field('id,nick_name,phone')->where(array('id' => array('IN', $rrs)))->select();
            $sharePhone = $distribution->field('uid,nphone')->where(array('uid' => array('IN', $rrs)))->select();
            foreach ($list as $key => $val) {
                foreach ($res as $rk => $rv) {
                    if ($rv['id'] == $val['uid']) {
                        $list[$key]['id'] = $rv['id'];
                        $list[$key]['nick_name'] = $rv['nick_name'];
                        $list[$key]['phone'] = $rv['phone'];
                    }
                }
                $tmp = [];
                foreach ($sharePhone as $shareValue) {
                    if ($shareValue["uid"] == $val['uid']) {
                        $tmp[] = $shareValue["nphone"];
                    }
                }
                if (!empty($tmp)) {
                    $countNphone = $user->field("count(phone) as total")->where(array('phone' => array('IN', $tmp)))->find();
                    $list[$key]["total"] = $countNphone["total"];
                } else {
                    $list[$key]["total"] = 0;
                }
            }
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }

    public function detail()
    {
        $id = I("get.id");
        if (!empty($id)) {
            $user = D("Users");
            $username = $user->where("id = " . $id)->getField("nick_name");
            $distribution = D("Distribution");
            $count = $distribution->where("uid = " . $id)->order('create_time desc')->group("nphone")->select();
            $counts = count($count);
            $Page = new Page($counts, 10);
            $show = $Page->show();
            $list = $distribution->field('id,uid,nphone,create_time')->where("uid = " . $id)
                ->order('create_time desc')->group("nphone")->limit($Page->firstRow, $Page->listRows)->select();
            $companys = D("Companys");
            foreach ($list as $key => $value) {
                $arr[] = $value["nphone"];
            }
            if ($arr != null) {
                $res = $user->field('id,nick_name,phone,create_time')->where(array('phone' => array('IN', $arr)))->select();
                foreach ($res as $rk => $rv) {
                    $rkv[] = $rv["id"];
                }
                $userCompany = D("UserCompanyMappings");
                if ($rkv != null) {
                    $companyid = $userCompany->field("company_id,uid")->where(array('uid' => array('IN', $rkv)))->select();
                    foreach ($companyid as $ck => $cv) {
                        $cmk[] = $cv["company_id"];
                    }
                }

                if ($cmk != null) {
                    $isVip = $companys->field('id,vip_start_time')->where(array('id' => array('IN', $cmk)))->select();
                    foreach ($companyid as $k => $v) {
                        foreach ($isVip as $isk => $isv) {
                            if ($isv['id'] == $v['company_id']) {
                                $res[$k]['vip_start_time'] = $isv["vip_start_time"];
                            }
                        }
                    }
                    foreach ($res as &$va) {
                        if (!empty($va["vip_start_time"])) {
                            $va["vip_start_time"];
                        } else {
                            $va["vip_start_time"] = "未开通";
                        }
                    }
                }

                foreach ($list as $keys => $vals) {
                    foreach ($res as $rks => $rvs) {
                        if ($rvs['phone'] == $vals['nphone']) {
                            $list[$keys]['uname'] = $rvs['nick_name'];
                            $list[$keys]['registerTime'] = $rvs["create_time"];
                            $list[$keys]['vip_start_time'] = $rvs["vip_start_time"];
                        }
                    }
                    foreach ($list as &$ctv) {
                        if (!empty($ctv["registerTime"])) {
                            $ctv["registerTime"];
                        } else {
                            $ctv["registerTime"] = "未注册";
                        }
                    }

                }
            }
        }
        $this->assign("list", $list);
        $this->assign("username", $username);
        $this->assign('page', $show);
        $this->display();
    }
}
