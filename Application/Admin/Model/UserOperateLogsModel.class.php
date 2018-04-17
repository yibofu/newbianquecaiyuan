<?php
namespace Admin\Model;

use Common\Model\BaseModel;
class UserOperateLogsModel extends BaseModel{
    protected function countOperate($uid,$map, $urlarr, $mergeWhere = true)
    {

        if($map == null){
            $where = [
                'uid' => $uid,
                'extract(year from create_time)' => formateTime(null, 'Y'),
                'extract(month from create_time)' => formateTime(null, 'm'),
            ];
        }else{
            $where = [
                'uid' => $uid,
                'extract(year from create_time)' => formateTime($map['create_time'], 'Y'),
                'extract(month from create_time)' => formateTime($map['create_time'], 'm'),
            ];
        }
        $where = $mergeWhere == true ? array_merge($where, $urlarr) : $urlarr;
        $return = $this->field('count(id) as num')->where($where)->find();
        return $return['num'];
    }
    //计算登录次数
    public function logNum($uid,$map)
    {
        $urlarr = [
            'module' => 'APP',
            'controller' => 'Login',
            'action' => ['in', ['login', 'thirdPartyLogin']],
        ];

        return  self::countOperate($uid,$map,$urlarr);
    }
    //查看仪表盘次数
    public function ybpNum($uid,$map)
    {
        $ybpurl = [
            'module' => 'APP',
            'controller' => 'Index',
            'action' => 'index',
        ];

        return self::countOperate($uid,$map, $ybpurl);
    }
    //查看异常次数
    public function ycNum($uid,$map)
    {
        $ycurl = [
            'module' => 'APP',
            'controller' => 'PartnerUnusual',
            'action' => 'index',
        ];

        return  self::countOperate($uid,$map, $ycurl);
    }
    //查看预测次数
    public function yuceNum($uid,$map)
    {
        $yuceurl = [
            'module' => 'APP',
            'controller' => 'Forcast',
            'action' => 'index',
        ];

        return  self::countOperate($uid, $map,$yuceurl);
    }

    //查看下载次数
    public function downLoadNum($uid,$map)
    {
        $downloadurl = [
            'module' => 'CustomerPC',
            'controller' => 'TableDownload',
            'action' => 'download',
        ];

        return  self::countOperate($uid, $map,$downloadurl);
    }
}
