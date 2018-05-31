<?php
namespace Admin\Controller;
use Common\Controller\BaseController;
use Think\Page;
use Think\Upload;
class InformationimgController extends BaseController{
    public function index(){
        $map['is_delete'] = "0";
        $informationimg = D("information_img");
        $count = $informationimg->where($map)->order("id asc")->count();
        $Page = new Page($count,10);
        $show = $Page->show();
        $list = $informationimg->field('id,url,create_time')->where($map)
            ->order('id desc')->limit($Page->firstRow,$Page->listRows)->select();

        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }
    public function add(){
        $this->display();
    }
    public function upload(){
        $data['create_time'] = formateTime();
        $upload = new \Think\Upload();
        $upload->maxSize = 3145728;
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
        $upload->rootPath = './Public/Uploads/informationimg/';
        $info = $upload->upload();
        if (!$info) {
            $msg = $upload->getError();
        } else {
            $list['url'] = $upload->rootPath . $info['file']['savepath'] . $info['file']['savename'];
        }
        $informationimg = D("information_img");
        $data["url"] = ltrim($list["url"],'.');
        $datas = $informationimg->create($data);
        if($datas){
            $result = $informationimg->add($datas);
            if($result){
                $this->redirect('Informationimg/index');
            }
        }
    }
    public function update(){
        $id = I("get.id");
        if($id){
            $informationimg = D("information_img");
            $result = $informationimg->field('id,url,update_time')->where("id = ".$id)->find();
            $this->assign("result",$result);
            $this->display();
        }
    }
    public function updateUpload(){
        $id = I("post.id");
        $data['update_time'] = formateTime();
        $upload = new \Think\Upload();
        $upload->maxSize = 3145728;
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
        $upload->rootPath = './Public/Uploads/informationimg/';
        $info = $upload->upload();
        $informationimg = D("information_img");
        $list = $informationimg->field('url')->where("id =".$id)->find();
        unlink($list);
        if (!$info) {
            $msg = $upload->getError();
        } else {
            $list['url'] = $upload->rootPath . $info['file']['savepath'] . $info['file']['savename'];
        }
        $data["url"] = ltrim($list["url"],'.');
        $datas = $informationimg->create($data);
        if($datas){
            $result = $informationimg->where("id =".$id)->save($datas);
            if($result){
                $this->redirect('Informationimg/index');
            }
        }
    }

    public function delete()
    {
        $id = I("get.id");
        $informationimg = D("information_img");
        $result = $informationimg->field('id')->where("id = " . $id)->find();
        $this->assign("result", $result);
        $this->display();

    }
    public function deletelist()
    {
        $id = I("get.id");
        $data['is_delete'] = "1";
        $data['delete_time'] = formateTime();
        $informationimg = D("information_img");
        $datas = $informationimg->create($data);
        if ($datas) {
            $result = $informationimg->where('id = ' . $id)->save($datas);
            if ($result) {
                $this->redirect('Informationimg/index');
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