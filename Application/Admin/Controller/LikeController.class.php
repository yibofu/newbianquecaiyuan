<?php

namespace Admin\Controller;

use Common\Controller\BaseController;
use Think\Page;

class  LikeController extends BaseController
{

    public function index()
    {

        $map['is_delete'] = "0";
        $like = D("Like");
        $count = $like->where($map)->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        $list = $like->field('uid,pid,good_num')->where($map)->limit($Page->firstRow, $Page->listRows)
            ->setNotSoftDelete()->select();
        foreach ($list as $ukey => $uvalue) {
            $uarr[] = $uvalue["uid"];
        }
        foreach ($list as $pkey => $pvalue) {
            $parr[] = $pvalue["pid"];
        }
        if (!empty($uarr) && !empty($parr)) {
            $user = D("Users");
            $uresult = $user->field('id,nick_name')->where(array('id' => array('IN', $uarr)))->select();
            $presult = $user->field('id,nick_name')->where(array('id' => array('IN', $parr)))->select();
            foreach ($list as $lukey => $luvalue) {
                foreach ($uresult as $rukey => $ruvalue) {
                    if ($luvalue["uid"] == $ruvalue["id"]) {
                        $list[$lukey]['uname'] = $ruvalue["nick_name"];
                    }
                }
            }
            foreach ($list as $lpkey => $lpvalue) {
                foreach ($presult as $rpkey => $rpvalue) {
                    if($lpvalue["pid"] == $rpvalue["id"]){
                        $list[$lpkey]['pname'] = $rpvalue["nick_name"];
                    }
                }
            }
                //管理员角色
                $this->assign('list', $list);
                $this->assign('page', $show);
                $this->display();
        }
    }
}
