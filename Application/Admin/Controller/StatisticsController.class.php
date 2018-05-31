<?php

namespace Admin\Controller;

use Common\Controller\BaseController;

header("content-type:text/html;charset=utf-8");

class StatisticsController extends BaseController
{
    public function index()
    {
//        echo date('t', strtotime('2000-02'));
        $this->display();
    }
}
