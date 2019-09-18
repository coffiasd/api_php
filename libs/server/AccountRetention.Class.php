<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/23
 * Time: 15:10
 */

class AccountRetention extends RetParent {

	public $logRententionDay = false;
	public $logRententionWeek = false;
	public $logRentention2Week = false;
	public $logRententionMonth = false;
	public $logRententionStrict = false;
	//是否登天第一次登录
	public $isTodayFirstLogin = false;

	public function __construct($accountInfo) {
		$this->accountInfo = $accountInfo;
		parent::__construct($accountInfo);
		parent::setType(1);
	}

	/**
	 *记录留存
	 */
	public function log() {
		$this->updateAccountStat();
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
	private function updateAccountStat() {
		//天记录
		$account_id = $this->accountInfo['account'];
		//查询当天记录是否存在
		$res = AccountModel::getAccountDayStat($account_id);
		if ($res === false) {
			$this->isTodayFirstLogin = true;
			$this->logRententionDay = true; //记录留存
			//不存在 是当天第一次登录 新建记录
			AccountModel::newAccountDayStat($account_id);
		} else {
			//更新当天登录次数
			$open_count = $res['open_count'] + 1;
			AccountModel::updateAccountsOpencount($account_id, $open_count);
		}
		//周记录
		$week = parent::getCountByExp(604800);
		if ($week === false) {
			//当天注册直接返回
			return;
		}
		$weeklog = AccountModel::getAccountWeek($account_id, $week);
		if ($weeklog === false) {
			//不存在 新建
			$this->logRententionWeek = true;
			AccountModel::newAccountWeek($account_id, $week);
		} else {
			$open_count = $weeklog['open_count'] + 1;
			if ($this->isTodayFirstLogin) {
				$open_day = $weeklog['open_day'] + 1;
			} else {
				$open_day = $weeklog['open_day'];
			}
			AccountModel::updateAccountWeek($account_id, $week, $open_count, $open_day);
		}
		//月记录
		$month = parent::getCountByExp(2592000);
		$monthlog = AccountModel::getAccountMonth($account_id, $month);
		if ($monthlog === false) {
			//不存在 新建
			AccountModel::newAccountMonth($account_id, $month);
		} else {
			$open_count = $monthlog['open_count'] + 1;
			if ($this->isTodayFirstLogin) {
				$open_day = $monthlog['open_day'] + 1;
				//月留存
				$this->logRententionMonth = true;
			} else {
				$open_day = $monthlog['open_day'];
			}
			AccountModel::updateAccountMonth($account_id, $month, $open_count, $open_day);
		}
	}

}
