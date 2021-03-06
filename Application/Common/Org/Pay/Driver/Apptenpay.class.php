<?php

namespace Common\Org\Pay\Driver;

/* * ************************微信支付配置***************************************** */
define("WxPayDir", str_replace('\\', '/', __DIR__));
define('SSLCERT_PATH', WxPayDir . '/WxPayPubHelper/appcacert/apiclient_cert.pem');
define('SSLKEY_PATH', WxPayDir . '/WxPayPubHelper/appcacert/apiclient_cert.pem');
define('CURL_TIMEOUT', "30");

class Apptenpay extends \Common\Org\Pay\Pay {

    function __construct($config) {
        $this->config = $config;
        $this->check();
        define('APPID', $this->config['APPID']);
        define('MCHID', $this->config['MCHID']);
        define('KEY', $this->config['KEY']);
        define('APPSECRET', $this->config['APPSECRET']);
        define('NOTIFY_URL', $this->config['notify_url']);
    }

    public function check() {
        if (!$this->config['MCHID'] || !$this->config['KEY'] || !$this->config['APPID']) {
            E("微信设置有误！");
        }
        return true;
    }

    public function buildRequestForm(\Common\Org\Pay\PayVo $vo) {
        //使用统一支付接口
        $unifiedOrder = new UnifiedOrder_pub();
        //设置统一支付接口参数
        //sign已填,商户无需重复填写
        $unifiedOrder->setParameter("body", $vo->getTitle()); //商品描述
        //自定义订单号，此处仅作举例
        $total_fee = $vo->getFee();
        $unifiedOrder->setParameter("out_trade_no", $vo->getOrderNo()); //商户订单号 
        $unifiedOrder->setParameter("total_fee", $total_fee * 100); //总金额
        $unifiedOrder->setParameter("notify_url", NOTIFY_URL); //通知地址
        $unifiedOrder->setParameter("trade_type", "APP"); //交易类型
        $prepay_id = $unifiedOrder->getPrepayId();
        $appApi = new AppApi_pub();
        $appApi->setPrepayId($prepay_id);
        return rawurlencode(\Common\Org\Des::share()->encode($appApi->getParameters()));
    }

    /**
     * 针对notify_url验证消息是回调出的合法消息
     * @return 验证结果
     */
    public function verifyNotify($notifydata) {
        //使用通用通知接口
        $notify = new Notify_pub();

        //存储微信的回调
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $notify->saveData($xml);
        //验证签名，并回应微信。
        //对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
        //微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
        //尽可能提高通知的成功率，但微信不保证通知最终能成功。
        if ($notify->checkSign() == FALSE) {
            $notify->setReturnParameter("return_code", "FAIL"); //返回状态码
            $notify->setReturnParameter("return_msg", "签名失败"); //返回信息
        } else {
            $notify->setReturnParameter("return_code", "SUCCESS"); //设置返回码
        }
        $returnXml = $notify->returnXml();
        //echo $returnXml;
        //D("test")->add(array('test'=>$returnXml));//测试可以删除
        //D("test")->add(array('test'=>"verifyNotify"));//测试可以删除
        //==商户根据实际情况设置相应的处理流程，此处仅作举例=======
        //以log文件形式记录回调信息
        if ($notify->checkSign() == TRUE) {
            // D("test")->add(array('test'=>"签名成功了"));//测试可以删除
            if ($notify->data["return_code"] == "FAIL") {
                //此处应该更新一下订单状态，商户自行增删操作
                //$log_->log_result($log_name,"【通信出错】:\n".$xml."\n");
                D("test")->add(array('test' => "【通信出错】:\n" . $xml . "\n")); //测试可以删除作
                return false;
            } elseif ($notify->data["result_code"] == "FAIL") {
                //此处应该更新一下订单状态，商户自行增删操作
                //$log_->log_result($log_name,"【业务出错】:\n".$xml."\n");
                D("test")->add(array('test' => "【业务出错】:\n" . $xml . "\n")); //测试可以删除作
                return false;
            } else {
                //此处应该更新一下订单状态，商户自行增删操
                //	D("test")->add(array('test'=>"支付成功"));//测试可以删除作
                $this->setInfo($notifydata);
                return true;
                //$log_->log_result($log_name,"【支付成功】:\n".$xml."\n");
            }

            //商户自行增加处理流程,
            //例如：更新订单状态
            //例如：数据库操作
            //例如：推送支付完成信息
        }
    }

    protected function setInfo($notify) {
        //支付状态
        $info['status'] = ($notify['result_code'] == 'success' || $notify['result_code'] == 'SUCCESS' || $notify['trade_status'] == 'TRADE_SUCCESS') ? true : false;
        //$info['money'] = $notify['total_fee'];
        $info['out_trade_no'] = $notify['out_trade_no'];
        $this->info = $info;
    }

}

/**
 * 所有接口的基类
 */
class Common_util_pub {

    function __construct() {
        
    }

    function trimString($value) {
        $ret = null;
        if (null != $value) {
            $ret = $value;
            if (strlen($ret) == 0) {
                $ret = null;
            }
        }
        return $ret;
    }

    /**
     * 	作用：产生随机字符串，不长于32位
     */
    public function createNoncestr($length = 32) {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str.= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 	作用：格式化参数，签名过程需要使用
     */
    function formatBizQueryParaMap($paraMap, $urlencode) {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if ($urlencode) {
                $v = urlencode($v);
            }
            //$buff .= strtolower($k) . "=" . $v . "&";
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar;
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }

    /**
     * 	作用：生成签名
     */
    public function getSign($Obj) {
        foreach ($Obj as $k => $v) {
            $Parameters[$k] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
        //echo '【string1】'.$String.'</br>';
        //签名步骤二：在string后加入KEY
        $String = $String . "&key=" . KEY;
        //echo "【string2】".$String."</br>";
        //签名步骤三：MD5加密
        $String = md5($String);
        //echo "【string3】 ".$String."</br>";
        //签名步骤四：所有字符转为大写
        $result_ = strtoupper($String);
        //echo "【result】 ".$result_."</br>";
        return $result_;
    }

    /**
     * 	作用：array转xml
     */
    function arrayToXml($arr) {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml.="<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml.="<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    /**
     * 	作用：将xml转为array
     */
    public function xmlToArray($xml) {
        //将XML转为array        
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }

    /**
     * 	作用：以post方式提交xml到对应的接口url
     */
    public function postXmlCurl($xml, $url, $second = 30) {
        //初始化curl        
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOP_TIMEOUT, $second);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        curl_close($ch);
        //返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            echo "curl出错，错误码:$error" . "<br>";
            echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
            curl_close($ch);
            return false;
        }
    }

    /**
     * 	作用：使用证书，以post方式提交xml到对应的接口url
     */
    function postXmlSSLCurl($xml, $url, $second = 30) {
        $ch = curl_init();
        //超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //设置证书
        //使用证书：cert 与 key 分别属于两个.pem文件
        //默认格式为PEM，可以注释
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
        curl_setopt($ch, CURLOPT_SSLCERT, SSLCERT_PATH);
        //默认格式为PEM，可以注释
        curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
        curl_setopt($ch, CURLOPT_SSLKEY, SSLKEY_PATH);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $data = curl_exec($ch);
        //返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            echo "curl出错，错误码:$error" . "<br>";
            echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
            curl_close($ch);
            return false;
        }
    }

    /**
     * 	作用：打印数组
     */
    function printErr($wording = '', $err = '') {
        print_r('<pre>');
        echo $wording . "</br>";
        var_dump($err);
        print_r('</pre>');
    }

}

/**
 * 请求型接口的基类
 */
class Wxpay_client_pub extends Common_util_pub {

    var $parameters; //请求参数，类型为关联数组
    public $response; //微信返回的响应
    public $result; //返回参数，类型为关联数组
    var $url; //接口链接
    var $curl_timeout; //curl超时时间

    /**
     * 	作用：设置请求参数
     */

    function setParameter($parameter, $parameterValue) {
        $this->parameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
    }

    /**
     * 	作用：设置标配的请求参数，生成签名，生成接口参数xml
     */
    function createXml() {
        $this->parameters["appid"] = APPID; //公众账号ID
        $this->parameters["mch_id"] = MCHID; //商户号
        $this->parameters["nonce_str"] = $this->createNoncestr(); //随机字符串
        $this->parameters["sign"] = $this->getSign($this->parameters); //签名
        return $this->arrayToXml($this->parameters);
    }

    /**
     * 	作用：post请求xml
     */
    function postXml() {
        $xml = $this->createXml();
        $this->response = $this->postXmlCurl($xml, $this->url, $this->curl_timeout);
        //echo 1231231;exit();
        //header("Content-type: text/html; charset=utf-8");
        // echo(htmlspecialchars($this->response));exit();
        //var_dump($this->response);exit();
        return $this->response;
    }

    /**
     * 	作用：使用证书post请求xml
     */
    function postXmlSSL() {
        $xml = $this->createXml();
        $this->response = $this->postXmlSSLCurl($xml, $this->url, $this->curl_timeout);
        return $this->response;
    }

    /**
     * 	作用：获取结果，默认不使用证书
     */
    function getResult() {
        $this->postXml();
        $this->result = $this->xmlToArray($this->response);
        return $this->result;
    }

}

/* * ************************统一支付接口类***************************************** */

/**
 * 统一支付接口类
 */
class UnifiedOrder_pub extends Wxpay_client_pub {

    function __construct() {
        //设置接口链接
        $this->url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        //设置curl超时时间
        $this->curl_timeout = CURL_TIMEOUT;
    }

    /**
     * 生成接口参数xml
     */
    function createXml() {
        try {
            //检测必填参数
            if ($this->parameters["out_trade_no"] == null) {
                throw new SDKRuntimeException("缺少统一支付接口必填参数out_trade_no！" . "<br>");
            } elseif ($this->parameters["body"] == null) {
                throw new SDKRuntimeException("缺少统一支付接口必填参数body！" . "<br>");
            } elseif ($this->parameters["total_fee"] == null) {
                throw new SDKRuntimeException("缺少统一支付接口必填参数total_fee！" . "<br>");
            } elseif ($this->parameters["notify_url"] == null) {
                throw new SDKRuntimeException("缺少统一支付接口必填参数notify_url！" . "<br>");
            } elseif ($this->parameters["trade_type"] == null) {
                throw new SDKRuntimeException("缺少统一支付接口必填参数trade_type！" . "<br>");
            } elseif ($this->parameters["trade_type"] == "JSAPI" &&
                    $this->parameters["openid"] == NULL) {
                throw new SDKRuntimeException("统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！" . "<br>");
            }
            $this->parameters["appid"] = APPID; //公众账号ID
            $this->parameters["mch_id"] = MCHID; //商户号
            $this->parameters["spbill_create_ip"] = $_SERVER['REMOTE_ADDR']; //终端ip	    
            $this->parameters["nonce_str"] = $this->createNoncestr(); //随机字符串
            $this->parameters["sign"] = $this->getSign($this->parameters); //签名
            $r = $this->arrayToXml($this->parameters);
            return $r;
        } catch (SDKRuntimeException $e) {
            die($e->errorMessage());
        }
    }

    /**
     * 获取prepay_id
     */
    function getPrepayId() {
        $this->postXml();

        $this->result = $this->xmlToArray($this->response);
        //dump(($this->result));
        $prepay_id = $this->result["prepay_id"];
        return $prepay_id;
    }

}

/**
 * 订单查询接口
 */
class OrderQuery_pub extends Wxpay_client_pub {

    function __construct() {
        //设置接口链接
        $this->url = "https://api.mch.weixin.qq.com/pay/orderquery";
        //设置curl超时时间
        $this->curl_timeout = CURL_TIMEOUT;
    }

    /**
     * 生成接口参数xml
     */
    function createXml() {
        try {
            //检测必填参数
            if ($this->parameters["out_trade_no"] == null &&
                    $this->parameters["transaction_id"] == null) {
                throw new SDKRuntimeException("订单查询接口中，out_trade_no、transaction_id至少填一个！" . "<br>");
            }
            $this->parameters["appid"] = APPID; //公众账号ID
            $this->parameters["mch_id"] = MCHID; //商户号
            $this->parameters["nonce_str"] = $this->createNoncestr(); //随机字符串
            $this->parameters["sign"] = $this->getSign($this->parameters); //签名
            return $this->arrayToXml($this->parameters);
        } catch (SDKRuntimeException $e) {
            die($e->errorMessage());
        }
    }

}

/**
 * 退款申请接口
 */
class Refund_pub extends Wxpay_client_pub {

    function __construct() {
        //设置接口链接
        $this->url = "https://api.mch.weixin.qq.com/secapi/pay/refund";
        //设置curl超时时间
        $this->curl_timeout = CURL_TIMEOUT;
    }

    /**
     * 生成接口参数xml
     */
    function createXml() {
        try {
            //检测必填参数
            if ($this->parameters["out_trade_no"] == null && $this->parameters["transaction_id"] == null) {
                throw new SDKRuntimeException("退款申请接口中，out_trade_no、transaction_id至少填一个！" . "<br>");
            } elseif ($this->parameters["out_refund_no"] == null) {
                throw new SDKRuntimeException("退款申请接口中，缺少必填参数out_refund_no！" . "<br>");
            } elseif ($this->parameters["total_fee"] == null) {
                throw new SDKRuntimeException("退款申请接口中，缺少必填参数total_fee！" . "<br>");
            } elseif ($this->parameters["refund_fee"] == null) {
                throw new SDKRuntimeException("退款申请接口中，缺少必填参数refund_fee！" . "<br>");
            } elseif ($this->parameters["op_user_id"] == null) {
                throw new SDKRuntimeException("退款申请接口中，缺少必填参数op_user_id！" . "<br>");
            }
            $this->parameters["appid"] = APPID; //公众账号ID
            $this->parameters["mch_id"] = MCHID; //商户号
            $this->parameters["nonce_str"] = $this->createNoncestr(); //随机字符串
            $this->parameters["sign"] = $this->getSign($this->parameters); //签名
            return $this->arrayToXml($this->parameters);
        } catch (SDKRuntimeException $e) {
            die($e->errorMessage());
        }
    }

    /**
     * 	作用：获取结果，使用证书通信
     */
    function getResult() {
        $this->postXmlSSL();
        $this->result = $this->xmlToArray($this->response);
        return $this->result;
    }

}

/**
 * 退款查询接口
 */
class RefundQuery_pub extends Wxpay_client_pub {

    function __construct() {
        //设置接口链接
        $this->url = "https://api.mch.weixin.qq.com/pay/refundquery";
        //设置curl超时时间
        $this->curl_timeout = CURL_TIMEOUT;
    }

    /**
     * 生成接口参数xml
     */
    function createXml() {
        try {
            if ($this->parameters["out_refund_no"] == null &&
                    $this->parameters["out_trade_no"] == null &&
                    $this->parameters["transaction_id"] == null &&
                    $this->parameters["refund_id "] == null) {
                throw new SDKRuntimeException("退款查询接口中，out_refund_no、out_trade_no、transaction_id、refund_id四个参数必填一个！" . "<br>");
            }
            $this->parameters["appid"] = APPID; //公众账号ID
            $this->parameters["mch_id"] = MCHID; //商户号
            $this->parameters["nonce_str"] = $this->createNoncestr(); //随机字符串
            $this->parameters["sign"] = $this->getSign($this->parameters); //签名
            return $this->arrayToXml($this->parameters);
        } catch (SDKRuntimeException $e) {
            die($e->errorMessage());
        }
    }

    /**
     * 	作用：获取结果，使用证书通信
     */
    function getResult() {
        $this->postXmlSSL();
        $this->result = $this->xmlToArray($this->response);
        return $this->result;
    }

}

/**
 * 对账单接口
 */
class DownloadBill_pub extends Wxpay_client_pub {

    function __construct() {
        //设置接口链接
        $this->url = "https://api.mch.weixin.qq.com/pay/downloadbill";
        //设置curl超时时间
        $this->curl_timeout = CURL_TIMEOUT;
    }

    /**
     * 生成接口参数xml
     */
    function createXml() {
        try {
            if ($this->parameters["bill_date"] == null) {
                throw new SDKRuntimeException("对账单接口中，缺少必填参数bill_date！" . "<br>");
            }
            $this->parameters["appid"] = APPID; //公众账号ID
            $this->parameters["mch_id"] = MCHID; //商户号
            $this->parameters["nonce_str"] = $this->createNoncestr(); //随机字符串
            $this->parameters["sign"] = $this->getSign($this->parameters); //签名
            return $this->arrayToXml($this->parameters);
        } catch (SDKRuntimeException $e) {
            die($e->errorMessage());
        }
    }

    /**
     * 	作用：获取结果，默认不使用证书
     */
    function getResult() {
        $this->postXml();
        $this->result = $this->xmlToArray($this->result_xml);
        return $this->result;
    }

}


/**
 * 响应型接口基类
 */
class Wxpay_server_pub extends Common_util_pub {

    public $data; //接收到的数据，类型为关联数组
    var $returnParameters; //返回参数，类型为关联数组

    /**
     * 将微信的请求xml转换成关联数组，以方便数据处理
     */

    function saveData($xml) {
        $this->data = $this->xmlToArray($xml);
    }

    function checkSign() {
        $tmpData = $this->data;
        unset($tmpData['sign']);
        $sign = $this->getSign($tmpData); //本地签名
        if ($this->data['sign'] == $sign) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 获取微信的请求数据
     */
    function getData() {
        return $this->data;
    }

    /**
     * 设置返回微信的xml数据
     */
    function setReturnParameter($parameter, $parameterValue) {
        $this->returnParameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
    }

    /**
     * 生成接口参数xml
     */
    function createXml() {
        return $this->arrayToXml($this->returnParameters);
    }

    /**
     * 将xml数据返回微信
     */
    function returnXml() {
        $returnXml = $this->createXml();
        return $returnXml;
    }

}

/**
 * 通用通知接口
 */
class Notify_pub extends Wxpay_server_pub {
    
}

/**
 * 请求商家获取商品信息接口
 */
class NativeCall_pub extends Wxpay_server_pub {

    /**
     * 生成接口参数xml
     */
    function createXml() {
        if ($this->returnParameters["return_code"] == "SUCCESS") {
            $this->returnParameters["appid"] = APPID; //公众账号ID
            $this->returnParameters["mch_id"] = MCHID; //商户号
            $this->returnParameters["nonce_str"] = $this->createNoncestr(); //随机字符串
            $this->returnParameters["sign"] = $this->getSign($this->returnParameters); //签名
        }
        return $this->arrayToXml($this->returnParameters);
    }

    /**
     * 获取product_id
     */
    function getProductId() {
        $product_id = $this->data["product_id"];
        return $product_id;
    }

}




/**
 * APPAPI支付
 */
class AppApi_pub extends Common_util_pub {

    var $code; //code码，用以获取openid
    var $openid; //用户的openid
    var $parameters; //jsapi参数，格式为json
    var $prepay_id; //使用统一支付接口得到的预支付id
    var $curl_timeout; //curl超时时间

    function __construct() {
        //设置curl超时时间
        $this->curl_timeout = CURL_TIMEOUT;
    }

    /**
     * 	作用：设置prepay_id
     */
    function setPrepayId($prepayId) {
        $this->prepay_id = $prepayId;
    }

    /**
     * 	作用：设置code
     */
    function setCode($code_) {
        $this->code = $code_;
    }

    /**
     * 	作用：设置jsapi的参数
     */
    public function getParameters() {
        $data = array();
        $data["appid"] = APPID;
        $timeStamp = time();
        $data["timestamp"] = "$timeStamp";
        $data["noncestr"] = $this->createNoncestr();
        $data["package"] = "Sign=WXPay";
        $data["prepayid"] = $this->prepay_id;
        $data["partnerid"] = MCHID;
        $data["sign"] = $this->getSign($data);
        $this->parameters = json_encode($data);
        return $this->parameters;
    }

}
class SDKRuntimeException extends \Exception {

    public function errorMessage() {
        return $this->getMessage();
    }

}
