<?php

class CharacterLoginLogModel {

	public static function getNotHandleList($limit = 1000) {
		$table = "character_login_log_" . date("ym");
		$sql = "select id,`character`,reg_time as register_time,channel from `$table` where `status`=0 order by id asc limit $limit";
		return mysql::getInstance()->queryAll($sql);
	}

	/**
	 * @param $character
	 * @param int $isnew
	 * @return bool
	 */
	public static function newLoginLog($character, $params, $isnew = 0) {
		$time = date('Y-m-d H:i:s');
		$ip = Common::getRealip();
		$table = 'character_login_log_' . date('ym');
		$date = date('Y-m-d');
		$sid = $params['sid'];
		$channel = $params['channel'];
		if (isset($params['reg_time'])) {
			$reg_time = date("Y-m-d H:i:s", (int) ($params['reg_time'] / 1000));
		} else {
			$reg_time = date("Y-m-d H:i:s");
		}
		$sql = "insert into $table (`character`,`time`,`ip`,`isnew`,`channel`,`date`,`sid`,`reg_time`) values ('$character','$time','$ip','$isnew',$channel,'$date',$sid,'$reg_time')";
		$res = mysql::getInstance()->addOne($sql);
		if (isset(mysql::getInstance()->con->errno) && mysql::getInstance()->con->errno == 1146) {
			//表不存在新建表
			$sql_create_table = "create table `$table` like `character_login_log`";
			mysql::getInstance()->query($sql_create_table);
			$res = mysql::getInstance()->addOne($sql);
		}
		if ($res) {
			return true;
		} else {
			return false;
		}
	}

	public static function updateStatus($ids) {
		$table = "character_login_log_" . date("ym");
		$sql = "update `$table` set `status`=1 where id in ($ids)";
		mysql::getInstance()->update($sql);
	}
}

?>