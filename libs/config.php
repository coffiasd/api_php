<?php
if (!defined('INDEX_FILE')) {
	die('error entry file');
}

date_default_timezone_set("PRC");

define('VERSION', '1.0', true);
//数据库连接信息
define('DB_HOST', "192.168.0.31", true);
define('DB_PORT', 3306);
define('DB_DATABASE', "", true);
define('DB_USERNAME', "root", true);
define('DB_PASSWORD', "", true);

//统计数据库连接信息
define('DB_HOST_LOG', "192.168.0.31", true);
define('DB_PORT_LOG', 3306);
define('DB_DATABASE_LOG', "", true);
define('DB_USERNAME_LOG', "root", true);
define('DB_PASSWORD_LOG', "", true);

define('DB_SLOW_TIME', 100, true);
define('DB_SLOW_FILE', "db_slow_query.csv", true);
define('ERROR_FILE', "error.log", true);
define('DEBUG', true); //是否开启debug模式
//是否开启数据库
define('IS_OPEN_MYSQL', '0', true);
define('IS_OPEN_LOG', '0', true);

define('TICKET_TIMEOUT', 300);

define('SIGN_KEY', '29luo631hk6rwo0mk651gi5s0l5d7haa');

define('EXT', '.php');

define('APP_ROOT', 'c:/www/git/all_game_api');

//渠道前缀
$channelPrex = [

];

$ip_white_list = [

];

//渠道生成订单
$channel_pay = [

];

?>
