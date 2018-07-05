<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/23
 * Time: 15:10
 */

class CharacterRetention extends RetParent {

	public $logRententionDay = false;
	public $logRententionWeek = false;
	public $logRentention2Week = false;
	public $logRententionMonth = false;
	public $logRententionStrict = false;
	//是否登天第一次登录
	public $isTodayFirstLogin = false;

	public function __construct($params) {
		$this->params = $params;
		$res = OpdeviceModel::getOpCharacter($params['character']);
		if (!empty($res)) {
			$params['version'] = -1;
		}
		parent::__construct($params);
		parent::setType(2);
	}

	/**
	 *记录留存
	 */
	public function log() {

		$this->updateDeviceStat();
		if ($this->isTodayFirstLogin) {
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
		$cid = $this->params['character'];
		//查询当天记录是否存在
		$res = CharacterModel::getCharacterDayStat($cid);
		if ($res === false) {
			$this->isTodayFirstLogin = true;
			$this->logRententionDay = true; //记录留存
			//不存在 是当天第一次登录 新建记录
			CharacterModel::newCharacterDayStat($cid);
		} else {
			//更新当天登录次数
			$open_count = $res['open_count'] + 1;
			CharacterModel::updateCharacterOpencount($cid, $open_count);
		}

		//周记录
		$week = parent::getCountByExp(604800);
		if ($week === false) {
			//当天注册直接返回
			return;
		}
		$weeklog = CharacterModel::getCharacterWeek($cid, $week);
		if ($weeklog === false) {
			//不存在 新建
			$this->logRententionWeek = true;
			CharacterModel::newCharacterWeek($cid, $week);
		} else {
			$open_count = $weeklog['open_count'] + 1;
			if ($this->isTodayFirstLogin) {
				$open_day = $weeklog['open_day'] + 1;
			} else {
				$open_day = $weeklog['open_day'];
			}
			CharacterModel::updateCharacterWeek($cid, $week, $open_count, $open_day);
		}
		//月记录
		$month = parent::getCountByExp(2592000);
		$monthlog = CharacterModel::getCharacterMonth($cid, $month);
		if ($monthlog === false) {
			//不存在 新建
			CharacterModel::newCharacterMonth($cid, $month);
		} else {
			$open_count = $monthlog['open_count'] + 1;
			if ($this->isTodayFirstLogin) {
				$open_day = $monthlog['open_day'] + 1;
			} else {
				$open_day = $monthlog['open_day'];
			}
			$this->logRententionMonth = true;
			CharacterModel::updateCharacterMonth($cid, $month, $open_count, $open_day);
		}
	}

}
