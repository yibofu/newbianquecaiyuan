<?php
namespace Admin\Model;

use Common\Model\BaseModel;
class TableUploadLogsModel extends BaseModel{
    public function getCompanyTableNum($uid, $where)
    {
        $basewhere = ['uid' => $uid];
        $where = !empty($where) ? array_merge($basewhere, $where) : $basewhere;
        return $this->where($where)->count();
    }
}
