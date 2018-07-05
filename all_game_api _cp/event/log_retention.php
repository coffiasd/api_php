<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/29
 * Time: 11:21
 */
//从登陆日志中拿取信息
define('INDEX_FILE', 1);
//加载配置文件
require_once '../libs/config.php';
//加载公共函数
require_once '../libs/common.php';
//加载mysql model类
require_once '../libs/model.php';
//加载数据库
require_once '../libs/mysql.php';

//循环处理

run1();
run();

//账号留存
function run1() {
	$list = AccountLoginLogModel::getNotHandleList();
	$ids = '';
	//计算留存
	foreach ($list as $key => $value) {
		$retention = new AccountRetention(array_merge($value, ['version' => 1, 'level' => 1]));
		$retention->log();
		if (empty($ids)) {
			$ids .= $value['id'];
		} else {
			$ids .= ',' . $value['id'];
		}
	}
	//计算新增和登录
	$accountLoginLog = new AccountLoginLog($list);
	$accountLoginLog->countNew();
	$accountLoginLog->countLogin();
	//更新status
	AccountLoginLogModel::updateStatus($ids);
}

//设备留存
function run2() {
	$list = DeviceLoginLogModel::getNotHandleList();
	$ids = '';
	foreach ($list as $key => $value) {
		//通过设备id获得设备信息
		//$level = DeviceModel::getLevelById($value['device']);
		$retention = new DeviceRetention(array_merge($value, ['version' => 1, 'level' => 1]));
		$retention->log();
		if (empty($ids)) {
			$ids .= $value['id'];
		} else {
			$ids .= ',' . $value['id'];
		}
	}
	$deviceLoginLog = new DeviceLoginLog($list);
	$deviceLoginLog->countNew();
	//更新status
	DeviceLoginLogModel::updateStatus($ids);
}

//角色留存
function run() {
	$list = CharacterLoginLogModel::getNotHandleList();
	$ids = '';
	foreach ($list as $key => $value) {
		$retention = new CharacterRetention(array_merge($value, ['version' => 1]));
		$retention->log();
		if (empty($ids)) {
			$ids .= $value['id'];
		} else {
			$ids .= ',' . $value['id'];
		}
	}
	//更新status
	CharacterLoginLogModel::updateStatus($ids);
}
