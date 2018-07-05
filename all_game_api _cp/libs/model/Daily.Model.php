<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/24
 * Time: 10:38
 */

class DailyModel {
	public static function getDaily($server, $channel, $date = '') {
		$date = !empty($date) ? $date : date('Y-m-d');
		$sql = "select id from daily where server='$server' and channel='$channel' and date='$date'";
		$info = mysql::getInstance()->queryOne($sql);
		if ($info) {
			return $info;
		} else {
			return false;
		}
	}

	public static function newDailyTemp($data) {
		$date = date('y_m_d');
		$sql = "insert into daily_temp_" . $date . " (`data`) values ('$data')";
		return mysql::getInstance()->addOne($sql);
	}

	public static function newDailyTempAccount($data) {
		$date = date('y_m_d');
		$sql = "insert into daily_temp_account_" . $date . " (`data`) values ('$data')";
		return mysql::getInstance2()->addOne($sql);
	}

	public static function newDailyTempDevice($data) {
		$date = date('y_m_d');
		$sql = "insert into daily_temp_device_" . $date . " (`data`) values ('$data')";
		return mysql::getInstance2()->addOne($sql);
	}

	public static function newDailyTempCharacter($data) {
		$date = date('y_m_d');
		$sql = "insert into daily_temp_character_" . $date . " (`data`) values ('$data')";
		return mysql::getInstance2()->addOne($sql);
	}

	public static function newDailyTempPay($data) {
		$date = date('y_m_d');
		$sql = "insert into daily_temp_pay_" . $date . " (`data`) values ('$data')";
		return mysql::getInstance2()->addOne($sql);
	}

	public static function newDaily($server, $channel, $columns, $date) {
		$date = !empty($date) ? $date : date('Y-m-d');
		$strKey = '';
		$strVal = '';
		foreach ($columns as $k => $v) {
			$strKey .= ',`' . $k . '`';
			$strVal .= ",'$v'";
		}

		$sql = "insert into daily (`server`,`channel`,`date`$strKey) values ('$server','$channel','$date'$strVal)";
		$res = mysql::getInstance()->addOne($sql);
		if ($res) {
			return true;
		} else {
			return false;
		}
	}

	public static function updateDaily($server, $channel, $columns, $date = '') {
		$date = !empty($date) ? $date : date('Y-m-d');
		$str = '';
		foreach ($columns as $k => $v) {
			$str .= "`" . $k . "`=" . $v . ",";
		}
		$newStr = substr($str, 0, strlen($str) - 1);
		if (empty($newStr)) {
			return;
		}
		$sql = "update daily set $newStr where date='$date' and server='$server' and channel='$channel'";
		$res = mysql::getInstance()->update($sql);
		if ($res) {
			return $res;
		} else {
			return false;
		}
	}
}