<?php

/**
 * 订单数据模型
 */

namespace Common\Org\Pay;

class PayVo {

    protected $_orderNo;
    protected $_fee;
    protected $_title;
    protected $_body;
    protected $_callback;
    protected $_url;
    protected $_param;

    /**
     * 设置订单号
     * @param type $order_no
     * @return \Common\Org\Pay\PayVo
     */
    public function setOrderNo($order_no) {
        $this->_orderNo = $order_no;
        return $this;
    }

    /**
     * 设置商品价格
     * @param type $fee
     * @return \Common\Org\Pay\PayVo
     */
    public function setFee($fee) {
        $this->_fee = $fee;
        return $this;
    }

    /**
     * 设置商品名称
     * @param type $title
     * @return \Common\Org\Pay\PayVo
     */
    public function setTitle($title) {
        $this->_title = $title;
        return $this;
    }

    /**
     * 设置商品描述
     * @param type $body
     * @return \Common\Org\Pay\PayVo
     */
    public function setBody($body) {
        $this->_body = $body;
        return $this;
    }

    /**
     * 获取订单号
     * @return type
     */
    public function getOrderNo() {
        return $this->_orderNo;
    }

    /**
     * 获取商品价格
     * @return type
     */
    public function getFee() {
        return $this->_fee;
    }

    /**
     * 获取商品名称
     * @return type
     */
    public function getTitle() {
        return $this->_title;
    }

    /**
     * 获取商品描述
     * @return type
     */
    public function getBody() {
        return $this->_body;
    }


}
