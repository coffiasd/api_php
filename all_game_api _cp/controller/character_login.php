<?php

$needs = [
	'sid',
    'cid',
    'channel',
    'app_id',
    'sign',
];

$params = Common::checkParams($needs);
//查看角色是否存在
$ret = CharacterModel::getCharacterByCidAndChannel($params['cid'],$params['sid'],$params['channel']);
if (empty($ret)) {
	//不存在新建角色
    $ret = CharacterModel::newCharacter($params['cid'],$params['sid'],$params['channel']);
    CharacterLoginLogModel::newLoginLog($ret,$params,1);
} else {
	//添加角色登录日志
    CharacterLoginLogModel::newLoginLog($ret['id'],$params);
}
Common::retJson(['code'=>0,'msg'=>'success']);
?>