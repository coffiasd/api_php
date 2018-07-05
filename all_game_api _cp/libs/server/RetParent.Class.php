<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/21
 * Time: 21:53
 */
class RetParent {
	public $params;
	public $logRententionDay = false;
	public $logRententionWeek = false;
	public $logRentention2Week = false;
	public $logRententionMonth = false;
	public $logRententionStrict = false;
	public $type = 0;

	public function __construct($params) {
		$this->params = $params;
	}

	/**
	 * @param $type
	 * 设置类型 0 设备 1 账号 2角色
	 */
	protected function setType($type) {
		$this->type = $type;
	}

	/**
	 * 是否需要记录留存
	 */
	protected function isExpToLog() {
	}

	/**
	 * 记录留存
	 * @param string $type
	 * @param $version
	 */
	protected function logRetention($type = '', $version) {
	}

	/**
	 * 3天连续登录的判断
	 */
	protected function threeDatLogin() {
	}

	/**
	 * 天记录
	 */
	protected function logDay() {
		$column = $this->getCountByExp(86400);
		if ($column === false) {
			//当天注册
			return;
		}
		$channel = $this->params['channel'];
		$level = $this->params['level'];
		$type = $this->type;
		$version = is_int($this->params['version']) ? $this->params['version'] : Common::getIntVer($this->params['version']);
		$res = RententionModel::getRententionDay($channel, $level, $type, $version, $this->params['register_time']);
		if ($res === false) {
			//不存在新建
			RententionModel::newRententionDay($channel, $level, $type, $version, $column, $this->params['register_time']);
		} else {
			//存在更新数据
			$val = $res[$column] + 1;
			RententionModel::updateRententionDay($channel, $level, $type, $version, $column, $val, $this->params['register_time']);
		}
	}

	/**
	 * 周记录
	 */
	protected function logWeek() {
		$column = $this->getCountByExp(86400 * 7);
		$channel = $this->params['channel'];
		$level = isset($this->params['level']) ? $this->params['level'] : 4;
		$type = $this->type;
		$version = is_int($this->params['version']) ? $this->params['version'] : Common::getIntVer($this->params['version']);
		$res = RententionModel::getRententionWeek($channel, $level, $type, $version, $this->params['register_time']);
		if ($res === false) {
			//不存在新建
			RententionModel::newRententionWeek($channel, $level, $type, $version, $column, $this->params['register_time']);
		} else {
			//存在更新数据
			if (!isset($res[$column])) {
				return;
			}
			$val = $res[$column] + 1;
			RententionModel::updateRententionWeek($channel, $level, $type, $version, $column, $val, $this->params['register_time']);
		}

	}

	/**
	 * 双周记录
	 */
	protected function logWeek2() {
		$column = $this->getCountByExp(86400 * 14);
		$channel = $this->params['channel'];
		//$level = Common::getDeviceLevel($this->params);
		$level = isset($this->params['level']) ? $this->params['level'] : 4;
		$type = $this->type;
		$version = is_int($this->params['version']) ? $this->params['version'] : Common::getIntVer($this->params['version']);

		$res = RententionModel::getRententionWeek2($channel, $level, $type, $version, $this->params['register_time']);
		if ($res === false) {
			//不存在新建
			RententionModel::newRententionWeek2($channel, $level, $type, $version, $column, $this->params['register_time']);
		} else {
			//存在更新数据
			$val = $res[$column] + 1;
			RententionModel::updateRententionWeek2($channel, $level, $type, $version, $column, $val, $this->params['register_time']);
		}

	}

	/**
	 * 月记录
	 */
	protected function logMonth() {
		$column = $this->getCountByExp(86400 * 30);
		$channel = $this->params['channel'];
		//$level = Common::getDeviceLevel($this->params);
		$level = isset($this->params['level']) ? $this->params['level'] : 4;
		$type = $this->type;
		$version = is_int($this->params['version']) ? $this->params['version'] : Common::getIntVer($this->params['version']);

		$res = RententionModel::getRententionMonth($channel, $level, $type, $version, $this->params['register_time']);
		if ($res === false) {
			//不存在新建
			RententionModel::newRententionMonth($channel, $level, $type, $version, $column, $this->params['register_time']);
		} else {
			//存在更新数据
			$val = $res[$column] + 1;
			RententionModel::updateRententionMonth($channel, $level, $type, $version, $column, $val, $this->params['register_time']);
		}

	}

	protected function getCountByExp($exp_time) {
		$d1 = $this->params['register_time'];
		if ($d1 == date("Y-m-d")) {
			return false;
		}
		if ($exp_time == 86400) {
			$count = Common::getDaysByDate($d1);
			if ($count > 30) {
				return false;
			}
			$count = is_int($count) ? $count : (int) $count;
			if ($count == 0) {
				return 1;
			} else {
				return $count;
			}
		} else {
			$count = Common::getDaysByDate($d1);
			$count = Common::getWeekMonthByDates($count, $exp_time);
			if (is_int($count)) {
				return $count;
			} else {
				return (int) $count + 1;
			}
		}
	}

	protected function getSetting() {
		$day = 24 * 3600; //天统计
		$week = $day * 7; //周统计
		$week2 = $day * 14; //双周统计
		$month = $day * 30; //月统计

		$setting = [
			'retention_day' => $day,
			'retention_week' => $week,
			'retention_2week' => $week2,
			'retention_month' => $month,
			'retention_strict_week' => $week,
		];
		return $setting;
	}

}