<?php

namespace Admin\Controller;

use Common\Controller\BaseController;

header("content-type:text/html;charset=utf-8");

class StatisticsController extends BaseController
{
    public function index()
    {
        $user = D("Users");
        //计算出昨天的日期
        $time = time() - (1 * 24 * 60 * 60);
        $yesterday = date("Y-m-d", $time);
        //统计昨天注册会员的数量
        $yesterdayRegisteredTotal = $user->field('id,create_time')
            ->where("date(create_time)='" . $yesterday . "'")->count("id");
        $companys = D("Companys");
        $order = D("Orders");
        //统计昨日付费会员总数
        $yesterdayPayTotal = $order->field('id')
            ->where("date(pay_time)='" . $yesterday . "'" . "and pay_status='2'")->count("id");
        //统计昨日成交金额
        $yesterdayMoenyTotal = $order->field('id,pay_number,pay_time')
            ->where("date(pay_time)='" . $yesterday . "'")->sum("pay_number");
        if ($yesterdayMoenyTotal == null) {
            $yesterdayMoenyTotal = 0;
        }

        //最近七天的订单数据
        for ($i = 0; $i < 7; $i++) {
            $sevenNum = date('Y-m-d', strtotime('-' . $i . ' day'));
            $arr[]["sevenNum"] = $sevenNum;
        }
        foreach ($arr as $key => $value) {
            $sevenDay = $value["sevenNum"];
            //近七天的注册数
            $sevenDayRegisteredTotal = $user->field('id,create_time')
                ->where("date(create_time)='" . $sevenDay . "'")->count("id");
            $arr[$key]["day"] = $sevenDayRegisteredTotal;
            //近七天的付费数
            $sevenDayPayTotal = $order->field('id,pay_number')
                ->where("date(pay_time)='" . $sevenDay . "'" . "and pay_status='2'")->count("id");
            $arr[$key]["pay"] = $sevenDayPayTotal;
        }
        //最近四个月
        for ($i = 0; $i < 4; $i++) {
            $fourMouth = date('Y-m', strtotime('-' . $i . ' month'));
            $con[]["fourMouth"] = $fourMouth;
        }

        foreach ($con as $key => $value) {
            $fours = $value["fourMouth"];
            $fo = strtotime($fours);
            $year = date('Y', $fo);
            $mouth = date('m', $fo);
            //近四个月的注册数
            $fourMouthRegisteredTotal = $user->field('id,create_time')
                ->where("EXTRACT( year from create_time) =" . $year . " and EXTRACT( month from create_time)=" . $mouth)->count("id");
            $con[$key]["day"] = $fourMouthRegisteredTotal;
            //近四个月的付费数
            $fourMouthPayTotal = $companys->field('id,pay_number')
                ->where("EXTRACT( year from create_time) =" . $year . " and EXTRACT( month from create_time)=" . $mouth . " and is_pay='1'")->count("id");
            $con[$key]["pay"] = $fourMouthPayTotal;
            //近四个月的成交金额
            $fourMouthMoenyTotal = $order->field('id,pay_number,pay_time')
                ->where("EXTRACT( year from create_time) =" . $year . " and EXTRACT( month from create_time)=" . $mouth)->sum("pay_number");
            $con[$key]["money"] = $fourMouthMoenyTotal;
        }
        //总金额
        $moneyTotal = $order->where()->sum("pay_number");
        //总会员
        $memberTotal = $user->where()->count("id");

        //按日期查询订单
        if (I('create_time') != "") {
            $map['create_time'] = array('like', '%' . I('create_time') . '%');
        } else {
            $map['create_time'] = "1970-01-01";
        }
        $timeOrder = $order->field('vip_id,date(create_time),sum(pay_number)')->where($map)->select();

        foreach ($timeOrder as $tkey => $tvalue) {
            $cond["create_time"] = $tvalue["date(create_time)"];
            if (!empty($cond["create_time"])) {
                $timeOrderCondition[] = $user->field('id,date(create_time)')
                    ->where(array("date(create_time)" => array('IN', $cond["create_time"])))->count("id");
                foreach ($timeOrderCondition as $tk => $tv) {
                    $timeOrder[$tkey]['registerNum'] = $tv;
                }
                $timeOrderPayNum[] = $order->field('id')
                    ->where(array("date(create_time)" => array('IN', $cond["create_time"]), 'pay_status' => '2'))->count("id");
                foreach ($timeOrderPayNum as $pk => $pv) {
                    $timeOrder[$tkey]['orderPayNum'] = $pv;
                }
            }
        }

        $this->assign("yesterdayRegisteredTotal", $yesterdayRegisteredTotal);
        $this->assign("yesterdayPayTotal", $yesterdayPayTotal);
        $this->assign("yesterdayMoenyTotal", $yesterdayMoenyTotal);
        $this->assign("arr", $arr);
        $this->assign("con", $con);
        $this->assign("moneyTotal", $moneyTotal);
        $this->assign("memberTotal", $memberTotal);
        $this->assign("timeOrder", $timeOrder);
        $this->display();
    }
}
