<?php

$need = [
	'sid',
	'channel',
	'count',
    'rid',
	'timestamp',
	'sign',
    'app_id',
];

$params = Common::checkParams($need);
//将online数据插入数据库
$ret = OnlineModel::NewOnline($params['sid'], $params['rid'], $params['channel'], $params['count'], $params['timestamp']);
if (is_int($ret)) {
	//插入成功
	Common::retJson(['code' => 0, 'msg' => 'success']);
} else {
	Common::retJson(['code' => 1, 'msg' => 'insert error']);
}
?>