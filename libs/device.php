<?php

//设备系统
//devcie
//
//author Oliver
//version 1.0
//date 2016-1-10

//引入库文件
require_once 'utility.php';
require_once 'db.php';
require_once 'msg.php';

//获取并创建设备
function deviceGetAndCreate($device_sn, $device_info, $channel, $bundle, $tag, $ip)
{
	//解析设备平台
	$platform = 0;

	if(!isNullOrEmpty($device_info))
	{
		try
		{
			$d = stripslashes($device_info);
			$info = json_decode($d, true);
			$platform = intval($info["plat"]);
		}
		catch(Exception $e)
		{
			@file_put_contents(ERROR_FILE, "parse device info json error : " . $e ."\n" , FILE_APPEND);
			$platform = 0;
		}
	}

	//请求sql
	$sql = "insert into devices (sn, platform, channel, bundle, tag, info, register_time, register_ip, last_open_time, last_open_ip) values " .
	"( '$device_sn', $platform, $channel, '$bundle', '$tag', '$device_info', now(), '$ip', now(), '$ip') " .
	"on duplicate key update last_open_time = now(), last_open_ip = '$ip'";

	//插入数据
	sql_connect();
	$id = sql_update($sql);
	$create = ($id == 1);

	//请求sql
	$sql = "select * from devices where sn = '$device_sn'";
	$record = sql_fetch_one($sql);

	return array_merge(getMsgArray(-300), array("create" => $create, "data" => $record));
}

//设备打开应用
function deviceOpen($device_sn, $device_info, $channel, $bundle, $tag, $ip)
{
	//创建设备
	$result = deviceGetAndCreate($device_sn, $device_info, $channel, $bundle, $tag, $ip);

	//创建失败
	if($result["code"] != 0)
		return getMsgArray(300);

	$create = $result["create"];
	$device_id = $result["data"]["id"];

	//插入数据
	sql_connect();

	//获取日期
	$date = dateVal();

	//请求sql
	$sql = "insert into daily_devices ( device, date, open_count ) values ( $device_id, '$date', 1 ) on duplicate key update open_count = open_count + 1";

	//读取数据
	$id = sql_update($sql);
	$first = ($id == 1);

	return array_merge(getMsgArray(-301), array("create" => $create, "first" => $first, "device_id" => $device_id));
}

echo json(deviceOpen("b1234", "{\"plat\":2}", 4, "com.demo", "wd", "127.0.0.1"));

?>