<?php
namespace Admin\Controller;
use Common\Controller\BaseController;
use Think\Page;
class CurriculumController extends BaseController{
    public function index(){
        $map['is_delete'] = "0";
        $course = D("Course");
        $count = $course->where($map)->count();
        $Page = new Page($count,10);
        $show = $Page->show();
        $list = $course->field('id,coursename,address,month,create_time,is_show')->where($map)
            ->limit($Page->firstRow,$Page->listRows)->select();
        foreach ($list as &$val) {
            $val['is_show'] = $val['is_show'] == 1 ? '显示' : '不显示';
        }
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }
    public function add(){
        $this->display();
    }
    public function addlist(){
        $id = session("admin_id");
        $data["coursename"] = I("post.coursename");
        $data["address"] = I("post.address");
        $data["month"] = I("post.month");
        $data["create_time"] = formateTime();
        $course = D("Course");
        $datas = $course->create($data);
        if($datas){
            $res = $course->add($datas);
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

    public function is_show(){
        $id = I("get.id");
        $is_show = I("get.is_show");
        if($id != ""){
            if($is_show == "显示"){
                $data["is_show"] = "0";
            }else{
                $data["is_show"] = "1";
            }
            $course = D("Course");
            $datas = $course->create($data);
            if ($datas) {
                $result = $course->where('id = ' . $id)->save($datas);
                if ($result) {
                    $this->redirect('Curriculum/index');
                } else {
                    $result = array(
                        'error' => false,
                        'msg' => '修改失败'
                    );
                }
            }
        }
    }

    public function update(){
        $id = I("get.id");
        if($id){
            $course = D("Course");
            $result = $course->field('id,coursename,address,month,create_time')->where("id = ".$id)->find();
            $this->assign("result",$result);
            $this->display();
        }
    }
    public function updatelist(){
        $id = I("post.id");
        $data["coursename"] = I("post.coursename");
        $data["address"] = I("post.address");
        $data["month"] = I("post.month");
        $data['update_time'] = formateTime();
        $course = D("Course");
        $datas = $course->create($data);
        if ($datas) {
            $result = $course->where('id = ' . $id)->save($datas);
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
        $course = D("Course");
        $result = $course->field('id')->where("id = " . $id)->find();
        $this->assign("result", $result);
        $this->display();

    }
    public function deletelist()
    {
        $id = I("get.id");
        $data['is_delete'] = "1";
        $data['delete_time'] = formateTime();
        $course = D("Course");
        $datas = $course->create($data);
        if ($datas) {
            $result = $course->where('id = ' . $id)->save($datas);
            if ($result) {
                $this->redirect('Curriculum/index');
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