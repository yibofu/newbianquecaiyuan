<?php
/**
 *格式化时间函数
 *@param string	$formate 	时间格式,默认 Y-m-d H:i:s
 *@param int $time	时间戳
 *@return string	格式化后的时间	 
 */
function formateTime($time=null, $formate='Y-m-d H:i:s') 
{
	$time = is_null($time) ? time() : $time;
	return date($formate, $time);
}
function keywords(){
    $map['is_delete'] = "0";
    $keyWords = D("Keywords");
    $result = $keyWords->field('id,title,keywords,description')->where($map)->order('id desc')->limit(1)->select();
//    $this->assign("result",$result);
    return $result;
}

/**
 * 过滤供应商和客户名称表格数据
 * @param mixed $data 表格数据
 * @return mixed $data 过滤后的数据
 */
function tableDataFilter($data) 
{
	$data = trim($data);				//去除空格
	$data = strip_tags($data);			//去除html标签
	$data = htmlspecialchars($data);	//转义实体
	$data = addslashes($data);			//添加反斜线
    if(empty($data)){
        return 0;
    }else{
        return $data;
    }
}
/**
 * 对用户的密码进行加密
 * @param $password
 * @param $encrypt //传入加密串，在修改密码时做认证
 * @return array/password
 */
function password($password,$encrypt=''){
    $pwd = array();
    $pwd['encrypt'] = '';
    $pwd['password'] = md5(md5(trim($password)).$pwd['encrypt']);
    return $encrypt ? $pwd['password'] : $pwd;
}

/**
 * 以万为单位，对金额进行四舍五入，保留两位小数
 * @param $fund		要格式化的金额
 * @param $wei		要保留的小数位数
 * @return $return	格式化后的金额
 */
function formateFund($fund, $wei = 2)
{
	return sprintf('%.2f', round($fund / 1e4, $wei));
}

function shareImgUrl($uid){
    $key = md5($uid);
    return 'Uploads/share/'.substr($key,0,2).'/'.$key.'.png';
}

function headImgUrl($uid){
    $key = md5($uid);
    return 'Uploads/head/'.substr($key,0,2).'/'.$key.'.png';
}

function TestLog($content){
    $content = json_encode($content,JSON_UNESCAPED_UNICODE);
    return addStrLog($content);
}

function addStrLog($content){
    return M('test')->add(['content'=>$content,'add_time'=>date('Y-m-d H:i:s')]);
}
