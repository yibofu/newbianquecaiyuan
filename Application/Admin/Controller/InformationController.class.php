<?php
namespace Admin\Controller;
use Common\Controller\BaseController;
use Think\Page;
class InformationController extends BaseController{
    public function index(){
        if (I("title")) {
            $map['title'] = array('like', '%' . I('title') . '%');
        }
        $map['is_delete'] = "0";
        $information = D("Information");
        $count = $information->where($map)->order("id desc")->count();
        $Page = new Page($count,10);
        $show = $Page->show();
        $list = $information->field('id,title,auth,condition,create_time')->where($map)
            ->order('id desc')->limit($Page->firstRow,$Page->listRows)->select();
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }
    public function add(){
        $this->display();
    }
    public function addlist(){
        $id = session("admin_id");
        $data["title"] = I("post.title");
        $data["auth"] = I("post.auth");
        $data["condition"] = I("post.condition");
        $data["create_time"] = formateTime();
        $information = D("Information");
        $datas = $information->create($data);
        if($datas){
            $res = $information->add($datas);
            if($res){
                $result = array(
                    'error'=>true,
                    'msg' =>'增加成功'
                );
            }else{
                $result = array(
                    'error'=>false,
                    'msg' => '增加失败'
                );
            }
            $datalog['admin_id'] = $id;
            $logdata = [
                'admin_id' => $datalog['admin_id'],
                'create_time' => formatetime(),
            ];
            self::makeLog($logdata, 'admin_operate_logs');
        }
        echo json_encode($result);
    }
    public function update(){
        $id = I("get.id");
        if($id){
            $information = D("Information");
            $result = $information->field('id,title,auth,condition')->where("id = ".$id)->find();
            $result['condition'] = htmlspecialchars_decode($result['condition']);
            $this->assign("result",$result);
            $this->display();
        }
    }
    public function updatelist(){
        $id = I("post.id");
        $data['title'] = I("post.title");
        $data["auth"] = I("post.auth");
        $data['condition'] = I("post.condition");
        $data['update_time'] = formateTime();
        $information = D("Information");
        $datas = $information->create($data);
        if ($datas) {
            $result = $information->where('id = ' . $id)->save($datas);
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
                'update_time' => formateTime(),
            ];
            self::makeLog($logdata, 'admin_operate_logs');
        }
        echo json_encode($result);
    }

    public function delete()
    {
        $id = I("get.id");
        $information = D("Information");
        $result = $information->field('id')->where("id = " . $id)->find();
        $this->assign("result", $result);
        $this->display();

    }
    public function deletelist()
    {
        $id = I("get.id");
        $data['is_delete'] = "1";
        $data['delete_time'] = formateTime();
        $information = D("Information");
        $datas = $information->create($data);
        if ($datas) {
            $result = $information->where('id = ' . $id)->save($datas);
            if ($result) {
                $this->redirect('Information/index');
            }
            //模拟登陆管理员的id
            $datalog['admin_id'] = session("admin_id");
            $logdata = [
                'admin_id' => $datalog['admin_id'],
                'delete_time' => formateTime(),
            ];
            self::makeLog($logdata, 'admin_operate_logs');
        }

    }
}