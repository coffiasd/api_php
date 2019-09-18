<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/23
 * Time: 15:10
 */

class DeviceRetention extends RetParent {

	public $logRententionDay = false;
	public $logRententionWeek = false;
	public $logRentention2Week = false;
	public $logRententionMonth = false;
	public $logRententionStrict = false;
	//是否登天第一次登录
	public $isTodayFirstLogin = false;

	public function __construct($params) {
		$this->params = $params;
		parent::__construct($params);
		parent::setType(0);
	}

	/**
	 *记录留存
	 */
	public function log() {
		$this->updateDeviceStat();
		if (!$this->isTodayFirstLogin) {
			return;
		}

		if ($this->logRententionDay) {
			$this->logDay();
		}
		if ($this->logRententionWeek) {
			$this->logWeek();
		}
		if ($this->logRententionMonth) {
			$this->logMonth();
		}
		if ($this->logRentention2Week) {
			$this->logWeek2();
		}
		if ($this->logRententionStrict) {
			$this->logStrictWeek();
		}
	}

	/**
	 * 更新账号
	 */
	private function updateDeviceStat() {
		//天记录
		$device_id = $this->params['device'];
		//查询当天记录是否存在
		$res = DeviceModel::getDeviceDayStat($device_id);
		if ($res === false) {
			$this->isTodayFirstLogin = true;
			$this->logRententionDay = true; //记录留存
			//不存在 是当天第一次登录 新建记录
			DeviceModel::newDeviceDayStat($device_id);
		} else {
			//更新当天登录次数
			$open_count = $res['open_count'] + 1;
			DeviceModel::updateDeviceOpencount($device_id, $open_count);
		}

		//周记录
		$week = parent::getCountByExp(604800);
		if ($week === false) {
			//当天注册直接返回
			return;
		}
		$weeklog = DeviceModel::getDeviceWeek($device_id, $week);
		if ($weeklog === false) {
			//不存在 新建
			$this->logRententionWeek = true;
			DeviceModel::newDeviceWeek($device_id, $week);
		} else {
			$open_count = $weeklog['open_count'] + 1;
			if ($this->isTodayFirstLogin) {
				$open_day = $weeklog['open_day'] + 1;
			} else {
				$open_day = $weeklog['open_day'];
			}
			DeviceModel::updateDeviceWeek($device_id, $week, $open_count, $open_day);
		}
		//月记录
		$month = parent::getCountByExp(2592000);
		$monthlog = DeviceModel::getDeviceMonth($device_id, $month);
		if ($monthlog === false) {
			//不存在 新建
			DeviceModel::newDeviceMonth($device_id, $month);
		} else {
			$open_count = $monthlog['open_count'] + 1;
			if ($this->isTodayFirstLogin) {
				$open_day = $monthlog['open_day'] + 1;
			} else {
				$open_day = $monthlog['open_day'];
			}
			$this->logRententionMonth = true;
			DeviceModel::updateDeviceMonth($device_id, $month, $open_count, $open_day);
		}
	}

}
