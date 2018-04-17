<?php
namespace Common\Controller;

use Think\Controller;

class BaseController extends Controller
{
	protected static function makeLog(array $logInfo = [], $model)	
	{
		$logInfo['action'] = ACTION_NAME;
		$logInfo['controller'] = CONTROLLER_NAME;
		$logInfo['module'] = MODULE_NAME;

		$return = [
			'code' => '', //返回码，有问题为0，成功为新插入的id
			'mess' => '', //返回消息
		];

		if(!is_array($logInfo)) {
			$return['code']= '0';
			$return['mess']= '日志信息必须是数组';

			return $return;
		} else if (empty($logInfo)) {
			$return['code']= '0';
			$return['mess']= '日志信息不能为空';

			return $return;
		}

		$userLogModel = D($model);
		$result = $userLogModel->create($logInfo);

		if($result) {
			if($userLogModel->add($logInfo)) {
				$return['code'] = '1';
				$return['mess'] = '日志添加成功';
			}
		}

		return $return;
	}

	//检查用户权限
	protected static function checkUserAuth($uid)
	{
		//查询用户角色id
		$model = D('Users');
		$where = ['id' => $uid];
		$roleid = $model->field('is_boss, role_id')->where($where)->find();
		if($roleid['is_boss'] == 1 && $roleid['role_id'] == 0) {
			return true;
		}

		//查询角色权限id
		$mapModel = D('UserRoleAuthMappings');
		$mapWhere = ['role_id' => $roleid['role_id']];
		$authList =  $mapModel->field('auth_id')->where($mapWhere)->select();
		if(!$authList) {
			return false;
		}
		$authIds = array_column($authList, 'auth_id');

		//查询角色权限
		$ruleModel = D('UserAuthRules');
		$ruleWhere = ['auth_id' => ['in', $authIds]];
		$ruleList = $ruleModel->field('module, controller, action')->where($ruleWhere)->select();
		$ruleList = array_map(function($data) {
			$path = '';
			$path .= $data['module'];
			$path .= '/';
			$path .= $data['controller'];
			//$path .= '/';
			//$path .= $data['action'];

			return $path;
		}, $ruleList);

		$requirePath = '';
		$requirePath .= MODULE_NAME;
		$requirePath .= '/';
		$requirePath .= CONTROLLER_NAME;
		//$requirePath .= '/';
		//$requirePath .= ACTION_NAME;

		if(!in_array($requirePath, $ruleList)) {
			return false;
		}

		return true;
	}

}
