<?php

//字符串字典（简体中文）

//字符串字典数组
$msg_dict = array();

//负数表示请求成功，正数表示请求失败

//系统（1-99）
$msg_dict[1] = "无效的访问";
$msg_dict[2] = "系统内部错误";
$msg_dict[3] = "参数错误";

//IP地址（100-199）
$msg_dict[-100] = "IP地址检测成功";
$msg_dict[-101] = "IP地址封禁成功";
$msg_dict[-102] = "IP地址解封成功";

$msg_dict[100] = "IP地址不能为空";
$msg_dict[101] = "IP地址格式错误";
$msg_dict[102] = "IP地址被封禁，请稍后再试";
$msg_dict[103] = "时间参数不能为空";
$msg_dict[104] = "时间参数格式错误";
$msg_dict[105] = "封禁类型错误";

//应用（200-299）
$msg_dict[-200] = "应用统计更新成功";

//设备（300-399）
$msg_dict[-300] = "设备获取成功";
$msg_dict[-301] = "设备统计更新成功";

$msg_dict[300] = "设备创建失败";

//渠道（400-499）
$msg_dict[-400] = "渠道获取成功";
$msg_dict[-401] = "渠道统计更新成功";

$msg_dict[400] = "渠道ID错误";
$msg_dict[401] = "渠道不存在";

//服务器（500-599）
$msg_dict[-500] = "服务器获取成功";
$msg_dict[-501] = "服务器统计更新成功";

$msg_dict[500] = "服务器ID错误";
$msg_dict[501] = "服务器不存在";

//账号（600-699）
$msg_dict[-600] = "账号获取成功";
$msg_dict[-601] = "账号统计更新成功";

$msg_dict[600] = "账号创建失败";
$msg_dict[601] = "账号不存在";

//函数：获取字符串
function getMsg($code)
{
	global $msg_dict;
	return $msg_dict[$code];
}

//函数：获取响应数组
function getMsgArray($code)
{
	if($code < 0)
		return array("code" => 0, "msg" => urlencode(getMsg($code)));
	else
		return array("code" => $code, "msg" => urlencode(getMsg($code)));
}

//函数：获取响应数据(Json格式)
function getMsgArrayJson($code)
{
	return urldecode(json_encode(getMsgArray($code, $success)));
}

?>