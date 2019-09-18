<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/27
 * Time: 15:51
 */
//单一入口

define('INDEX_FILE', 1);
$url = $_SERVER['REQUEST_URI'];
$req = $_SERVER['QUERY_STRING'];
$urlInfo = explode('/', $url);
$name = isset($urlInfo[2]) ? $urlInfo[2] : 0;
if (empty($name)) {
	die('url error');
}

//加载配置文件
require_once 'libs/config.php';
//加载公共函数
require_once 'libs/common.php';
//加载mysql model类
require_once 'libs/model.php';
//加载数据库
require_once 'libs/mysql.php';

$file = str_replace('?' . $req, '', $name);
if (file_exists('controller/' . $file . '.php')) {
	include 'controller/' . $file . '.php';
} else {
	echo 'url error';
}

?>