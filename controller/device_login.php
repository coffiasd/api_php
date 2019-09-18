<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/22
 * Time: 16:35
 */

$needs = [
	'sn',
	'channel',
    'plat',
    'bundle',
    'sid',
    'app_id',
    'sign',
];
$params = Common::checkParams($needs);
//查看设备是否存在
$ret = DeviceModel::getDeviceBySn($params['sn']);
if (empty($ret)) {
	//不存在新建设备
    $ret = DeviceModel::registerDevice($params);
    DeviceLoginLogModel::newLoginLog($ret,$params,1);
}else{
    DeviceLoginLogModel::newLoginLog($ret['id'],$params);
}
Common::retJson(['code'=>0,'msg'=>'success']);