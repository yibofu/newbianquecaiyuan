<?php
namespace Admin\Controller;
use Common\Controller\BaseController;
use Think\Page;
use Think\Upload;
class HomepageController extends BaseController{
    public function banner(){
        $map['is_delete'] = "0";
        $banner = D("Banner");
        $count = $banner->where($map)->order("id asc")->count();
        $Page = new Page($count,10);
        $show = $Page->show();
        $list = $banner->field('id,url,create_time')->where($map)
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
        $upload->rootPath = './Public/Uploads/banner/';
        $info = $upload->upload();
        if (!$info) {
            $msg = $upload->getError();
        } else {
            $list['url'] = $upload->rootPath . $info['file']['savepath'] . $info['file']['savename'];
        }
        $banner = D("Banner");
        $data["url"] = ltrim($list["url"],'.');
        $datas = $banner->create($data);
        if($datas){
            $result = $banner->add($datas);
            if($result){
                $this->redirect('Homepage/banner');
            }
        }
    }
    public function update(){
        $id = I("get.id");
        if($id){
            $banner = D("Banner");
            $result = $banner->field('id,url,update_time')->where("id = ".$id)->find();
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
        $upload->rootPath = './Public/Uploads/banner/';
        $info = $upload->upload();
        $banner = D("Banner");
        $list = $banner->field('url')->where("id =".$id)->find();
        unlink($list);
        if (!$info) {
            $msg = $upload->getError();
        } else {
            $list['url'] = $upload->rootPath . $info['file']['savepath'] . $info['file']['savename'];
        }
        $data["url"] = ltrim($list["url"],'.');
        $datas = $banner->create($data);
        if($datas){
            $result = $banner->where("id =".$id)->save($datas);
            if($result){
                $this->redirect('Homepage/banner');
            }
        }
    }

    public function delete()
    {
        $id = I("get.id");
        $banner = D("Banner");
        $result = $banner->field('id')->where("id = " . $id)->find();
        $this->assign("result", $result);
        $this->display();

    }
    public function deletelist()
    {
        $id = I("get.id");
        $data['is_delete'] = "1";
        $data['delete_time'] = formateTime();
        $banner = D("Banner");
        $datas = $banner->create($data);
        if ($datas) {
            $result = $banner->where('id = ' . $id)->save($datas);
            if ($result) {
                $this->redirect('Homepage/banner');
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