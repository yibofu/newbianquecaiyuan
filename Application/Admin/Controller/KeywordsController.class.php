<?php
namespace Admin\Controller;
use Common\Controller\BaseController;
use Think\Page;
class KeywordsController extends BaseController{
    public function index(){
        $map['is_delete'] = "0";
        $keywords = D("Keywords");
        $count = $keywords->where($map)->order("id asc")->count();
        $Page = new Page($count,10);
        $show = $Page->show();
        $list = $keywords->field('id,title,keywords,description,create_time,update_time')->where($map)
            ->order('id asc')->limit($Page->firstRow,$Page->listRows)->select();
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
        $data["keywords"] = I("post.keywords");
        $data["description"] = I("post.description");
        $data["create_time"] = formateTime();
        $keywords = D("Keywords");
        $datas = $keywords->create($data);
        if($datas){
            $res = $keywords->add($datas);
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
            $keywords = D("Keywords");
            $result = $keywords->field('id,title,keywords,description,update_time')->where("id = ".$id)->find();
            $this->assign("result",$result);
            $this->display();
        }
    }
    public function updatelist(){
        $id = I("post.id");
        $data['title'] = I("post.title");
        $data['keywords'] = I("post.keywords");
        $data['description'] = I("post.description");
        $data['update_time'] = formateTime();
        $keywords = D("Keywords");
        $datas = $keywords->create($data);
        if ($datas) {
            $result = $keywords->where('id = ' . $id)->save($datas);
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
        $keywords = D("Keywords");
        $result = $keywords->field('id')->where("id = " . $id)->find();
        $this->assign("result", $result);
        $this->display();

    }
    public function deletelist()
    {
        $id = I("get.id");
        $data['is_delete'] = "1";
        $data['delete_time'] = formateTime();
        $keywords = D("Keywords");
        $datas = $keywords->create($data);
        if ($datas) {
            $result = $keywords->where('id = ' . $id)->save($datas);
            if ($result) {
                $this->redirect('Keywords/index');
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