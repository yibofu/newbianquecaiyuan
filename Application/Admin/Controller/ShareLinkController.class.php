<?php
namespace Admin\Controller;
use Common\Controller\BaseController;
use Think\Page;
class ShareLinkController extends BaseController{
    public function index()
    {
        $sharelink = D("Sharelink");
        $count = $sharelink->where()->order("id desc")->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        $list = $sharelink->where()->order("id desc")->limit($Page->firstRow, $Page->listRows)->select();
        foreach($list as $key=>$value){
            $arr[] = $value["aid"];
        }
        if(!empty($arr)){
            $registernum = D("Registernum");
            $result = $registernum->field('count(uid) as cou,sid')->where(array("aid" => array('IN',$arr)))->select();
            foreach($list as $k=>$v){
                foreach($result as $rk=>$rv){
                    if($v["id"] == $rv["sid"]){
                        $list[$k]["renum"] = $rv["cou"];
                    }
                }
            }
        }
        $this->assign("list", $list);
        $this->assign("page", $show);
        $this->display();
    }
    public function add(){
        $this->display();
    }

    public function addlist(){
        $data['linkname'] = I("post.linkname");
        $data['linkaddress'] = "http://app.laobanqianbao.com/Distribution/scanCode?num=".rand(1,100);
        $data['sharepeople'] = session('admin_account');
        $data['aid'] = session("admin_id");
        $data['use'] = I("post.use");
        $data['create_time'] = formateTime();
        $sharelink = D("Sharelink");
        $datas = $sharelink->create($data);
        if ($datas) {
            $result = $sharelink->add($datas);
            if ($result) {
                $result = array(
                    'error' => true,
                    'msg' => '增加成功'
                );
            } else {
                $result = array(
                    'error' => false,
                    'msg' => '增加失败'
                );
            }
        }
        $datalog['admin_id'] = session('admin_id');
        $logdata = [
            'admin_id' => $datalog['admin_id'],
        ];
        self::makeLog($logdata, 'admin_operate_logs');
        echo json_encode($result);
    }



    public function update()
    {
        $id = I("get.id");
        if ($id) {
            $sharelink = D("Sharelink");
            $result = $sharelink->field('id,linkname,linkaddress,use,sharepeople')->where("id = " . $id)->find();
        }
        $this->assign("result", $result);
        $this->display();
    }

    //管理员权限表修改
    public function updatelist()
    {
        $id = I("post.id");
        $data['linkname'] = I("post.linkname");
        $data['use'] = I("post.use");
        $data['update_time'] = formateTime();
        $sharelink = D("Sharelink");
        $datas = $sharelink->create($data);
        if ($datas) {
            $result = $sharelink->where('id = ' . $id)->save($datas);
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
                $data['update_time'] => formateTime(),
            ];
            self::makeLog($logdata, 'admin_operate_logs');
        }
        echo json_encode($result);
    }

    public function delete()
    {
        $id = I("get.id");
        $sharelink = D("Sharelink");
        $result = $sharelink->field('id')->where("id = " . $id)->find();
        $this->assign("result", $result);
        $this->display();

    }

    //管理员权限表删除
    public function deletelist()
    {
        $id = I("get.id");
        $sharelink = D("Sharelink");
        $data['is_delete'] = "1";
        $data['delete_time'] = formateTime();
        $datas = $sharelink->create($data);
        if ($datas) {
            $result = $sharelink->where('id = ' . $id)->save($data);

            $datalog['admin_id'] = session("admin_id");
            $logdata = [
                'admin_id' => $datalog['admin_id'],
            ];
            self::makeLog($logdata, 'admin_operate_logs');
            if ($result) {
                $this->redirect('ShareLink/index');
            }
        }
    }
    public function code(){
        $id = I("get.id");
        if(!empty($id)){
            $sharelink = D("Sharelink");
            $result = $sharelink->field('id,linkname,linkaddress,use,sharepeople,create_time')->where("id = ".$id)->find();
            $this->assign("result",$result);
        }else{
            return "没有传入id值";
        }

        $this->display();
    }

    public function detail(){
        $id = I("get.id");
        if(!empty($id)){
            $sharelink = D("Sharelink");
            $aid = $sharelink->where("id = ".$id)->getField('aid');
            $registernum = D("Registernum");
            $count = $registernum->where("sid = ".$id." and aid =".$aid)->order("id desc")->count();
            $Page = new Page($count, 10);
            $show = $Page->show();
            //得到这条消息下的这个管理员下的注册用户的uid
            $result = $registernum->field('id,sid,aid,uid')->where("sid = ".$id." and aid =".$aid)->order("id desc")
                ->limit($Page->firstRow, $Page->listRows)->select();
            foreach($result as $key=>$value){
                $arr[] = $value["uid"];
            }
            if(!empty($arr)){
                $user = D("Users");
                $list = $user->field("id,phone,nick_name,create_time")->where(array("id" => array('IN',$arr)))->select();
                $usercompanymap = D("UserCompanyMappings");
                $companyResult = $usercompanymap->field("uid,company_id")->where(array("uid" => array('IN',$arr)))->select();
                foreach($companyResult as $ckey=>$cval){
                    $companyid[] = $cval["company_id"];
                }

                foreach($result as $rk=>$rv){
                    foreach($list as $lk=>$lv){
                        if($rv["uid"] == $lv["id"]){
                            $result[$rk]["phone"] = $lv["phone"];
                            $result[$rk]["nick_name"] = $lv["nick_name"];
                            $result[$rk]["create_time"] = $lv["create_time"];
                        }
                    }
                    foreach($companyResult as $ck=>$cv){
                        if($rv["uid"] == $cv["uid"]){
                            $result[$rk]["company_id"] = $cv["company_id"];
                        }
                    }
                }
                $company = D("Companys");
                $companyName = $company->field("id,is_pay,company_name")->where(array("id" => array('IN',$companyid)))->select();
                foreach($result as $kk=>$vv){
                    foreach($companyName as $cks=>$ckv){
                        if($vv["company_id"] == $ckv["id"]){
                            $result[$kk]["is_pay"] = $ckv["is_pay"];
                            $result[$kk]["company_name"] = $ckv["company_name"];
                        }
                    }
                }
                foreach ($result as &$val) {
                    $val['is_pay'] = $val['is_pay'] == 0 ? '未付费' : '<p style="color:red">已付费</p>';
                }
            }
        }
        $this->assign("result", $result);
        $this->assign("page", $show);
        $this->display();
    }
}