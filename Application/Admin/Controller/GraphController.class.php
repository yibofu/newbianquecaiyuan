<?php

namespace Admin\Controller;

use Common\Controller\BaseController;
use Think\Page;

header("content-type:text/html;charset=utf-8");

class  GraphController extends BaseController
{
    public function index()
    {
        $company_id = I("company_name", 'intval');
        if ($company_id > 0) {
            $map['company_id'] = $company_id;
        }
        $tableuploadlogs = D("TableUploadLogs");
        $count = $tableuploadlogs->where($map)->group('company_id')->select();
        $counts = count($count);
        $Page = new Page($counts, 10);
        $show = $Page->show();
        //进行分页数据查询
        $list = $tableuploadlogs->where($map)->group('company_id')->order('id asc')
            ->limit($Page->firstRow, $Page->listRows)->field('*,count(1) as count')->select();
        if (is_array($list) && $list) {
            $company = D("Companys")->field('id,company_name')->select();
            $companyList = array_column($company, 'company_name', 'id');
            foreach ($list as $k => $v) {
                if (!empty($companyList[$v['company_id']])) {
                    $list[$k]["company_name"] = $companyList[$v['company_id']];
                } else {
                    $list[$k]["company_name"] = '公司不存在';
                }
            }
        }
        //管理员角色
        $this->assign('list', $list);
        $this->assign('companyList', $companyList);
        $this->assign('company_id', $company_id);
        $this->assign('page', $show);
        $this->display();
    }

    public function detail()
    {
        $company_id = I("get.company_id");

        if (I("table_name")) {
            $map['id'] = I('table_name');
        }
        if (I("create_time")) {
            $map['create_time'] = array('like', '%' . I("create_time") . '%');
        }
        $map['is_delete'] = "0";
        $map['company_id'] = $company_id;
        $condition['id'] = $company_id;
        $tableuploadlogs = D("TableUploadLogs");
        $count = $tableuploadlogs->where($map)->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        //进行分页数据查询
        $list = $tableuploadlogs->field('company_id,create_time,table_name')
            ->where($map)->order('id asc')->limit($Page->firstRow, $Page->listRows)
            ->setNotSoftDelete()->select();

        $condi = $tableuploadlogs->where($map)->select();
        $company = D("Companys");
        $com = $company->field('id,company_name')->where("id = " . $company_id)->find();

        //管理员角色
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('com', $com);
        $this->assign('condi', $condi);
        $this->display();
    }
}
