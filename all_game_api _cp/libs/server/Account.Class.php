<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/23
 * Time: 13:51
 */

class Account {
	public $stat;

	public function __construct($params, $channelPrex) {
		$this->params = $params;
		$this->channelPrex = $channelPrex;
		$this->stat = new Stat($params);
	}

	public function registerAccount($isGuest = false) {
		$params = $this->params;
		$channelPrex = $this->channelPrex;
		$this->install = false;
		$accountInfo = AccountModel::getAcccountByIdChannel($params['id'], $params['channel'], $channelPrex);
		if (empty($accountInfo)) {
			if (isset($params['channel'])) {
				$channelRegister = ChannelModel::channelRegister($params['channel']);
				if ($channelRegister) {
					Common::retJson(['code' => 1, 'msg' => $channelRegister]);
				}
			}
			//实时统计创建账号
			$this->install = true;
			//realTimeAdd(20, $params['channel'], 1, $params['version']);
			//账号不存在 新建账号
			$res = AccountModel::insertAccount($params, $channelPrex);
			//新增统计
			//$this->stat->logDaily(['register'=>1,'device'=>$params['device_id'],'account'=>$res['uid']]);
			//账号id
			if (isset($res['uid'])) {
				//留存统计
				//$this->accountStat(['id'=>$res['uid'],'register_time'=>date('Y-m-d H:i:s')]);
			}
			$register_time = date('Y-m-d H:i:s');
		} else {
			//realTimeAdd(21, $params['channel'], 1, $params['version']);
			//更新留存的记录表
			//$this->accountStat($accountInfo);
			//账号存在 查看账号是否允许登录
			$register_time = $accountInfo['register_time'];
			$allow_login_time = $accountInfo['allow_login_time'];
			if ($allow_login_time > time()) {
				$res = ['code' => 1, 'msg' => 'account not allow login', 'uid' => $accountInfo['id']];
			} else {
				//更新账号登录时间信息
				$res = AccountModel::updateLoginTime($accountInfo, $params);
			}
		}

		if ($params['channel'] == 16) {
			//更新access_token
			$account_cp = $res['account'];
			if (isset($params['refresh_token']) && isset($params['expires_in']) && isset($params['access_token'])) {
				AccountModel::updateAccessToken($account_cp, $params['access_token'] . '|' . $params['refresh_token'] . '|' . $params['expires_in']);
			}
		}

		//记录登录日志
		//$stat = new Stat($this->params);
		//$stat->accountLoginLog($res['uid']);
		$params_copy = Common::getData();
		if ($this->install) {
			$new_guest = 0;
			if ($isGuest) {
				$new_guest = 1;
			}

			global $isBindGuestUser;
			if (isset($isBindGuestUser)) {
				$registerUserNum = 0;
			} else {
				$registerUserNum = 1;
			}

			$this->stat->logDaily(['type' => 'account', 'register' => $registerUserNum, 'new_guest' => $new_guest, 'device' => $params['device_id'], 'account' => $res['uid'], 'params' => $params_copy, 'timestamp' => time(), 'register_time' => $register_time, 'level' => Common::getDeviceLevel($params), 'login_guest' => (int) $isGuest]);
		} else {
			$this->stat->logDaily(['type' => 'account', 'device' => $params['device_id'], 'account' => $res['uid'], 'params' => $params_copy, 'timestamp' => time(), 'register_time' => $register_time, 'level' => Common::getDeviceLevel($params), 'login_guest' => (int) $isGuest]);
		}

		//如果是游客返回Array
		if ($isGuest === true) {
			return $res;
		}
		//返回
		Common::retJson($res);
	}

	/**
	 * 更新留存的记录表
	 */
	private function accountStat($accountInfo) {
		//天  周  月 三张表
		$register_time = $accountInfo['register_time'];
		list($d1, $d2) = explode(' ', $register_time);
		$this->params['register_time'] = $d1;
		$retention = new AccountRetention($this->params, $accountInfo);
		$retention->log($this->stat);
	}

	/**
	 * 检查是否有游客账号
	 * @param $sn
	 * @param $channel
	 * @return string
	 */
	private function checkGuest($sn, $channel) {
		$ret = AccountModel::checkGuest($sn, $channel);
		if (empty($ret)) {
			return '';
		} else {
			$accountInfo = explode('_', $ret['account']);
			return isset($accountInfo[1]) ? $accountInfo[1] : '';
		}
	}
}
