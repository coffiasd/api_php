<?php

class AccountLoginLogModel {

	public static function getNotHandleList($limit = 1000) {
		$table = "account_login_log_" . date("ym");
		$sql = "select id,account,reg_time as register_time,channel,time,isnew from `$table` where `status`=0 order by id asc limit $limit";
		return mysql::getInstance()->queryAll($sql);
	}

	public static function updateStatus($ids = '') {
		$table = "account_login_log_" . date("ym");
		$sql = "update `$table` set `status`=1 where id in ($ids)";
		mysql::getInstance()->update($sql);
	}

	/**
	 * 增加新的登录日志
	 */
	public static function newLoginLog($account, $params = [], $isnew = 0) {
		$isguest = isset($params['isguest']) ? $params['isguest'] : 0;
		$sid = isset($params['sid']) ? $params['sid'] : 0;
		$channel = $params['channel'];
		$time = date('Y-m-d H:i:s');
		$date = date('Y-m-d');
		$ip = Common::getRealip();
		//$reg_time = isset($params['reg_time'])?
		if (isset($params['reg_time'])) {
			$reg_time = date("Y-m-d H:i:s", (int) ($params['reg_time'] / 1000));
		} else {
			//查询注册时间
			$reg_info = AccountModel::getRegTimeById($account);
			$reg_time = isset($reg_info['reg_time']) ? $reg_info['reg_time'] : '';
		}

		$table = 'account_login_log_' . date('ym');
		$sql = "insert into `$table` (`account`,`time`,`ip`,`isguest`,`isnew`,`date`,`channel`,`reg_time`,`sid`) values ('$account','$time','$ip','$isguest','$isnew','$date',$channel,'$reg_time',$sid)";
		$res = mysql::getInstance()->addOne($sql);
		if (isset(mysql::getInstance()->con->errno) && mysql::getInstance()->con->errno == 1146) {
			//表不存在新建表
			$sql_create_table = "create table `$table` like `account_login_log`";
			mysql::getInstance()->query($sql_create_table);
			$res = mysql::getInstance()->addOne($sql);
		}
		if ($res) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 获得当天安装
	 */
	public static function getTodayInstall($isguest = false) {
		$table = "account_login_log_" . date('ym');
		$date = date('Y-m-d');
		if ($isguest) {
			$sql = "select COUNT(*) as num,channel,sid,`date`  from `$table` where `date`='$date' and isguest=1 and isnew=1 GROUP BY channel,sid";
		} else {
			$sql = "select COUNT(*) as num,channel,sid,`date`  from `$table` where `date`='$date' and isnew=1 GROUP BY channel,sid";
		}
		return mysql::getInstance()->queryAll($sql);
	}

	public static function getTodayLogin($isguest = false) {
		$table = "account_login_log_" . date('ym');
		$date = date('Y-m-d');
		if ($isguest) {
			$sql = "SELECT COUNT(*) as num,channel,sid,`date` FROM (SELECT DISTINCT account,channel,sid,`date` FROM `$table` a WHERE `date`='$date' AND isnew=0 and isguest=1)  AS temp GROUP BY channel,sid";
		} else {
			$sql = "SELECT COUNT(*) as num,channel,sid,`date` FROM (SELECT DISTINCT account,channel,sid,`date` FROM `$table` a WHERE `date`='$date' AND isnew=0)  AS temp GROUP BY channel,sid";
		}
		return mysql::getInstance()->queryAll($sql);
	}
}

?>