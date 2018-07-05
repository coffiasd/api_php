<?php

//服务器
//server
//
//author Oliver
//version 1.0
//date 2016-1-10

//引入库文件
require_once 'utility.php';
require_once 'db.php';
require_once 'msg.php';


//获取服务器
function serverGet($id)
{
	//检查参数
	if(!is_int($id) || $id <= 0)
		return getMsgArray(500);

	//请求sql
	$sql = "select * from servers where id = $id";

	//插入数据
	sql_connect();
	$data = sql_fetch_one($sql);

	//没有服务器
	if($data == null)
		return getMsgArray(501);
	//找到服务器
	else
		return array_merge(getMsgArray(-500), array("data" => $data));
}

//服务器注册账号
function serverRegister($id, $time)
{
	//检查参数
	if(!is_int($id) || $id <= 0)
		return getMsgArray(500);

	//获取日期
	$date = dateVal($time);

	//请求sql
	$sql = "insert into daily_servers ( server, date, register ) values ( $id, '$date', 1 ) on duplicate key update register = register + 1";

	//插入数据
	sql_connect();
	sql_insert($sql);

	return getMsgArray(-501);
}

//服务器登录
function serverLogin($id, $time, $first = false)
{
	//检查参数
	if(!is_int($id) || $id <= 0)
		return getMsgArray(500);

	//获取日期
	$date = dateVal($time);

	//请求sql
	if($first)
		$sql = "insert into daily_servers ( server, date, login, login_account ) values ( $id, '$date', 1, 1 ) on duplicate key update login = login + 1, login_account = login_account + 1";
	else
		$sql = "insert into daily_servers ( server, date, login, login_account ) values ( $id, '$date', 1, 1 ) on duplicate key update login = login + 1";

	//插入数据
	sql_connect();
	sql_insert($sql);

	return getMsgArray(-501);
}

//服务器充值
function serverDeposit($id, $time, $amount, $gold, $first = false)
{
	//检查参数
	if(!is_int($id) || $id <= 0)
		return getMsgArray(500);

	//获取日期
	$date = dateVal($time);

	//请求sql
	if($first)
		$sql = "insert into daily_servers ( server, date, deposit, deposit_account, deposit_amount, deposit_gold_amount ) values ( $id, '$date', 1, 1, $amount, $gold) on duplicate key update deposit = deposit + 1, deposit_account = deposit_account + 1, deposit_amount = deposit_amount + $amount, deposit_gold_amount = deposit_gold_amount + $gold";
	else
		$sql = "insert into daily_servers ( server, date, deposit, deposit_account, deposit_amount, deposit_gold_amount ) values ( $id, '$date', 1, 1, $amount, $gold) on duplicate key update deposit = deposit + 1, deposit_amount = deposit_amount + $amount, deposit_gold_amount = deposit_gold_amount + $gold";

	//插入数据
	sql_connect();
	sql_insert($sql);

	return getMsgArray(-501);
}


?>