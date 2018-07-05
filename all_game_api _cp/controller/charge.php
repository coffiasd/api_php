<?php

//必要参数
$needs = [
    'app_id',
	'channel_uid',
	'channel',
	'money',
	'gold',
	'sid',
	'sign',
    'charge_time',
];

$params = Common::checkParams($needs);

//时间戳转化成日期
if(isset($params['time'])){
    $params['time'] = date('Y-m-d H:i:s',$params['time']);
}
$ret = PaymentModel::newPayment($params);
if (is_int($ret)) {
	//插入成功
	Common::retJson(['code' => 0, 'msg' => 'success']);
} else {
	Common::retJson(['code' => 1, 'msg' => 'insert error']);
}
?>