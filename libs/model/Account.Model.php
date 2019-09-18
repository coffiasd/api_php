<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/21
 * Time: 16:34
 */

class AccountModel {
	/*
		 * 通过id,渠道获得账号信息
	*/
	public static function getAcccountByIdChannel($channel_id, $channel) {
		$sql = "SELECT id from accounts where channel_account_id='$channel_id' and channel_id=$channel";
		return mysql::getInstance()->queryOne($sql);
	}

	/**
	 * 更新登录时间
	 * @param $accountInfo
	 * @return array
	 */
	public static function updateLoginTime($accountInfo, $params) {

	}

	/**
	 * @param $arr
	 * @param array $channelPrex
	 * @return array
	 */
	public static function insertAccount($params = []) {
	    $channel_id = isset($params['channel_uid'])?$params['channel_uid']:'';
        $channel = $params['channel'];
        $c_info = ChannelModel::getCodeNameByChannelId($channel);
        $account = $c_info['code_name'].'_'.$channel_id;
        $isguest = isset($params['isguest'])?$params['isguest']:0;
		$time = isset($params['reg_time'])?date("Y-m-d H:i:s",(int)($params['reg_time']/1000)):date('Y-m-d H:i:s');
		$guest_uid = isset($params['guest_uid'])?$params['guest_uid']:'';
		$ip = Common::getRealip();
		$sql = "INSERT INTO accounts (`channel_id`,`channel_account_id`,`register_time`,`register_ip`,`isguest`,`account`,`guest_uid`) VALUES ('$channel','$channel_id','$time','$ip',$isguest,'$account','$guest_uid')";
		$insertId = mysql::getInstance()->addOne($sql);
		if ($insertId) {
			return $insertId;
		} else {
			return false;
		}
	}


	/**
	 * 账号是否创建
	 * @param string $account
	 */
	public static function getAccountInfoByAccount($account = '', $device_id = '') {
		if ($device_id) {
			$sql = "SELECT id,charge_limit FROM accounts WHERE account='$account' and device='$device_id'";
		} else {
			$sql = "SELECT id,charge_limit FROM accounts WHERE account='$account'";
		}
		$res = mysql::getInstance()->queryOne($sql);
		if (empty($res)) {
			return false;
		} else {
			return $res;
		}
	}


	public static function getTodayNewGuest() {
		$today = date('Y-m-d');
		$sql = "select count(*) from accounts where time>$today and isnew=1 and isguest=1";
		return mysql::getInstance()->queryOne($sql);
	}

	public static function loginAccount(){
        $today = date('Y-m-d');
    }

	/**
	 * @param $aid
	 * @return array|bool|string
	 */
	public static function getAccountDayStat($aid) {
		$date = date('Y-m-d');
		$sql = "select account,open_count from accounts_day where date='$date' and account='$aid'";
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
	public static function newAccountDayStat($aid) {
		$date = date('Y-m-d');
		$sql = "insert into accounts_day (`account`,`date`,`open_count`) values ('$aid','$date',1)";
		$res = mysql::getInstance()->addOne($sql);
		if ($res) {
			return true;
		} else {
			return false;
		}
	}

/**
 * @param $aid
 */
	public static function getAccountWeek($aid, $week) {
		$sql = "select * from accounts_week where account='$aid' and weak='$week'";
		$res = mysql::getInstance()->queryOne($sql);
		if ($res) {
			return $res;
		} else {
			return false;
		}
	}

	public static function newAccountWeek($aid, $week) {
		$sql = "insert into accounts_week (`account`,`weak`,`open_count`,`open_day`) values ('$aid','$week',1,1)";
		$res = mysql::getInstance()->addOne($sql);
		if ($res) {
			return true;
		} else {
			return false;
		}
	}

	public static function updateAccountWeek($aid, $week, $open_count, $open_day) {
		$sql = "update accounts_week set open_count='$open_count',open_day='$open_day'  where account='$aid' and weak='$week'";
		$res = mysql::getInstance()->update($sql);
		if ($res) {
			return true;
		} else {
			return false;
		}
	}

	public static function getAccountMonth($aid, $month) {
		$sql = "select * from accounts_month where account='$aid' and month='$month'";
		$res = mysql::getInstance()->queryOne($sql);
		if ($res) {
			return $res;
		} else {
			return false;
		}
	}

	public static function newAccountMonth($aid, $month) {
		$sql = "insert into accounts_month (`account`,`month`,`open_count`,`open_day`) values ('$aid','$month',1,1)";
		$res = mysql::getInstance()->addOne($sql);
		if ($res) {
			return true;
		} else {
			return false;
		}
	}

	public static function updateAccountMonth($aid, $month, $open_count, $open_day) {
		$sql = "update accounts_month set open_count='$open_count',open_day='$open_day' where account='$aid' and month='$month'";
		$res = mysql::getInstance()->update($sql);
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
	public static function updateAccountsOpencount($aid, $open_count) {
		$date = date('Y-m-d');
		$sql = "update accounts_day set open_count=$open_count where account='$aid' and date='$date'";
		$res = mysql::getInstance()->update($sql);
		if ($res) {
			return true;
		} else {
			return false;
		}
	}

	public static function getRegTimeById($id){
	    $sql = "select register_time as reg_time from accounts where id=$id";
	    return mysql::getInstance()->queryOne($sql);
    }

    /**
     * @param $guest_uid
     * @param $channel
     * @return array|string
     */
    public static function getAccountInfoByGuest($guest_uid,$channel){
        $sql = "select id,channel_account_id from accounts where guest_uid='$guest_uid' and channel_id='$channel'";
        return mysql::getInstance()->queryOne($sql);
    }

    /**
     * 绑定游客更新channel_uid
     * @param array $params
     */
    public static function updateChannelUid($id,$channel_uid,$channel){
        $code_name = ChannelModel::getCodeNameByChannelId($channel);
        $code_name = isset($code_name['code_name'])?$code_name['code_name']:'';
        $account = $code_name.'_'.$channel_uid;
        $sql = "update accounts set channel_account_id='$channel_uid',account='$account' where id=$id";
        return mysql::getInstance()->query($sql);
    }
}
