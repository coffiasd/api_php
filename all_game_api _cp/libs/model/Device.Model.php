<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/21
 * Time: 16:29
 */
//设备相关的Model
class DeviceModel {

	/**
	 * @param $sn
	 * @param $mac
	 * @param $serial
	 */
	public static function getDeviceInfoBySnMacSerial($imei, $mac, $serial) {
		$sql = "SELECT id FROM optimization_device where imei='$imei' and mac='$mac' and serial='$serial'";
		$res = mysql::getInstance()->queryOne($sql);
		if (empty($res)) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * 通过sn获得设备信息
	 * @param $sn
	 */
	public static function getDeviceBySn($sn) {
		$sql = "select id,channel,last_login_channel from devices where sn='$sn'";
		$res = mysql::getInstance()->queryOne($sql);
		if (empty($res)) {
			return false;
		} else {
			return $res;
		}
	}

	public static function getRegById($id) {
		$sql = "select register_time from devices where id=$id";
		$res = mysql::getInstance()->queryOne($sql);
		if (empty($res)) {
			return false;
		} else {
			return $res['register_time'];
		}
	}

	/**
	 * 注册设备 返回insert_id
	 * @param $params
	 */
	public static function registerDevice($params) {
		$sn = $params['sn'];
		$platform = $params['plat'];
		$channel = $params['channel'];
		$bundle = $params['bundle'];
		$tag = isset($params['tag']) ? $params['tag'] : '';
		$info = isset($params['DeviceData'])?$params['DeviceData']:''; //设备post过来的信息
		$register_time = isset($params['reg_time'])?$params['reg_time']:date("Y-m-d H:i:s");
		$register_ip = Common::getRealip();
		$level = Common::getDeviceLevel($params);
		$brand = isset($params['mfr']) ? $params['mfr'] : '';
		$model = isset($params['model']) ? $params['model'] : '';
		$cpu_count = isset($params['cpu_cnt']) ? $params['cpu_cnt'] : '';
		$cpu_freq = isset($params['cpu_frq']) ? $params['cpu_frq'] : '';
		$memory = isset($params['mem_tot']) ? $params['mem_tot'] : '';
		//$version = isset($params['version'])?$params['version']:0;
		$sql = "insert into devices (`sn`,`platform`,`channel`,`bundle`,`tag`,`info`,`register_time`,`register_ip`,`level`,`brand`,`model`,`cpu_count`,`cpu_freq`,`memory`) values ('$sn','$platform','$channel','$bundle','$tag','$info','$register_time','$register_ip','$level','$brand','$model','$cpu_count','$cpu_freq','$memory')";
		return mysql::getInstance()->addOne($sql);
	}

	public static function registerMac($id, $mac) {
		$sql = "insert into device_mac values ('$id','$mac')";
		mysql::getInstance()->addOne($sql);
	}

	public static function registerImei($id, $imei) {
		$sql = "insert into device_imei values ('$id','$imei')";
		mysql::getInstance()->addOne($sql);
	}

	public static function registerSerial($id, $serial) {
		$sql = "insert into device_serial values ('$id','$serial')";
		mysql::getInstance()->addOne($sql);
	}

	public static function updateLoginTimeIp($id, $channel = 0) {
		//更新设备登录时间和ip
		$time = date('Y-m-d H:i:s');
		$ip = Common::getRealip();
		$sql = "update devices set last_login_time='$time',last_login_ip='$ip',last_login_channel='$channel' where id=$id";
		mysql::getInstance()->update($sql);
	}

	public static function getDeviceDayStat($device_id) {
		$date = date('Y-m-d');
		$sql = "select * from devices_day where device ='$device_id' and date='$date'";
		$res = mysql::getInstance()->queryOne($sql);
		if ($res) {
			return $res;
		} else {
			return false;
		}
	}

	/**
	 *新增账号stat
	 */
	public static function newDeviceDayStat($aid) {
		$date = date('Y-m-d');
		$sql = "insert into devices_day (`device`,`date`,`open_count`) values ('$aid','$date',1)";
		$res = mysql::getInstance()->addOne($sql);
		if ($res) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param $aid
	 * @param $open_count
	 * @return bool
	 */
	public static function updateDeviceOpencount($aid, $open_count) {
		$date = date('Y-m-d');
		$sql = "update devices_day set open_count=$open_count where device='$aid' and date='$date'";
		$res = mysql::getInstance()->update($sql);
		if ($res) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param $aid
	 */
	public static function getDeviceWeek($aid, $week) {
		$sql = "select * from devices_week where device='$aid' and weak='$week'";
		$res = mysql::getInstance()->queryOne($sql);
		if ($res) {
			return $res;
		} else {
			return false;
		}
	}

	public static function newDeviceWeek($aid, $week) {
		$sql = "insert into devices_week (`device`,`weak`,`open_count`,`open_day`) values ('$aid','$week',1,1)";
		$res = mysql::getInstance()->addOne($sql);
		if ($res) {
			return true;
		} else {
			return false;
		}
	}

	public static function updateDeviceWeek($aid, $week, $open_count, $open_day) {
		$sql = "update devices_week set open_count='$open_count',open_day='$open_day'  where device='$aid' and weak='$week'";
		$res = mysql::getInstance()->update($sql);
		if ($res) {
			return true;
		} else {
			return false;
		}
	}

	public static function getDeviceMonth($aid, $month) {
		$sql = "select * from devices_month where device='$aid' and month='$month'";
		$res = mysql::getInstance()->queryOne($sql);
		if ($res) {
			return $res;
		} else {
			return false;
		}
	}

	public static function newDeviceMonth($aid, $month) {
		$sql = "insert into devices_month (`device`,`month`,`open_count`,`open_day`) values ('$aid','$month',1,1)";
		$res = mysql::getInstance()->addOne($sql);
		if ($res) {
			return true;
		} else {
			return false;
		}
	}

	public static function updateDeviceMonth($aid, $month, $open_count, $open_day) {
		$sql = "update devices_month set open_count='$open_count',open_day='$open_day' where device='$aid' and month='$month'";
		$res = mysql::getInstance()->update($sql);
		if ($res) {
			return true;
		} else {
			return false;
		}
	}

	public static function getDeviceLevelById($device_id) {
		$sql = "select level from devices where id=$device_id";
		return mysql::getInstance()->queryOne();
	}
}
