<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/26
 * Time: 17:09
 */

class PaymentModel {

	public static function getPaymentInfoByPid($pid) {
		$sql = "select * from payments where pid='$pid'";
		$res = mysql::getInstance()->queryOne($sql);
		if ($res) {
			return $res;
		} else {
			return false;
		}
	}

	public static function newPayment($params) {
		//时间没传的话按照当前的时间
		if (empty($time)) {
			$time = date('Y-m-d H:i:s');
		}
		$channel_uid = $params['channel_uid'];
		$channel = $params['channel'];
		$money = $params['money'];
		$gold = $params['gold'];
		$sid = isset($params['sid']) ? $params['sid'] : 0;
		$charge_time = isset($params['charge_time']) ? date("Y-m-d H:i:s", $params['charge_time']) : date('Y-m-d H:i:s');
		$money_type = isset($params['money_type']) ? $params['money_type'] : 0;
		$charge_type = isset($params['charge_type']) ? $params['charge_type'] : 0;

		$date = date('Y-m-d');
		//获得渠道前缀
		$c_info = ChannelModel::getCodeNameByChannelId($channel);
		$sql = "insert into payment (`channel_uid`,`channel`,`money`,`gold`,`sid`,`date`,`charge_time`,`money_type`,`charge_type`) values ('$channel_uid','$channel','$money','$gold','$sid','$date','$charge_time','$money_type','$charge_type')";
		$res = mysql::getInstance()->addOne($sql);
		if ($res) {
			return $res;
		} else {
			return false;
		}
	}

	public static function getAccountIsFirstPay($account) {
		$start = date('Y-m-d') . ' 00:00:00';
		$end = date('Y-m-d') . ' 24:00:00';
		$sql = "select id from payments where account='$account' and state=2 and pay_time>'$start' and pay_time<'$end'";
		$res = mysql::getInstance()->queryOne($sql);
		if ($res) {
			return false;
		} else {
			return true;
		}
	}

	public static function updatePaymentsPayed($id, $orderid, $time, $state = 2, $money) {

	}

	public static function getRecentOrder($account) {

	}

	public static function getChargeSum($account = '') {
		if (empty($account)) {
			return 0;
		}

		$sql = "select sum(amount) as num from payments where account='" . $account . "'";
		$res = mysql::getInstance()->queryOne($sql);
		if ($res) {
			return $res['num'];
		} else {
			return 0;
		}
	}

	/**
	 * @param int $limit
	 */
	public static function getNotHandleList($limit = 1000) {
		$sql = "select id,channel_id,channel,amount,gold,sid,time from payment where `status`=0 limit $limit";
		return mysql::getInstance()->queryAll($sql);
	}

	/**
	 *充值总额
	 */
	public static function getTodayPay($date = '') {
		$date = !empty($date) ? $date : date('Y-m-d');
		$sql = "select channel,sid,sum(money) as num  from payment where `date`='$date' group by channel,sid";
		return mysql::getInstance()->queryAll($sql);
	}

	/**
	 *充值人数
	 */
	public static function getTodayPayNum($date = '') {
		$date = !empty($date) ? $date : date('Y-m-d');
		$sql = "SELECT COUNT(*) as num,channel,sid FROM (SELECT DISTINCT channel_uid,channel,sid FROM payment WHERE DATE='$date') AS temp GROUP BY channel,sid";
		return mysql::getInstance()->queryAll($sql);
	}

	/**
	 *新用户付费
	 */
	public static function getNewTodayPay($date = '') {
		$date = !empty($date) ? $date : date('Y-m-d');
		$sql = "select p.channel,p.sid,p.amount from payment p,accounts a  where `p.date`='$date' and p.account=a.account and a.register>'$date'";
		return mysql::getInstance()->queryAll($sql);
	}

	public static function getNewTodayPayNum() {
		$date = date('Y-m-d');
		$sql = "select p.channel,p.sid,count(*) as num from payment p,accounts a where `p.date`='$date' and p.account=a.account and a.register>'$date' group by p.account,p.sid";
		return mysql::getInstance()->queryAll($sql);
	}

	//第一次充值
	public static function getFirstTodayPay() {
		$date = date('Y-m-d');
		$sql = "select id,account,channel,amount,sid from payment where account not in (select account from payment where date<'$date') order by id desc";
		return mysql::getInstance()->queryAll($sql);
	}

	/**第一次充值人数
		     * @return array
	*/
	public static function getFirstTodayPayNum() {
		$date = date('Y-m-d');
		$sql = "select id,account,channel,amount,sid from payment where account not in (select account from payment where date<'$date') group by account,sid order by id desc";
		return mysql::getInstance()->queryAll($sql);
	}

}
