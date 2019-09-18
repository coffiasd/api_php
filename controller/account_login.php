<?php

$needs = [
	'channel',
	'app_id',
    'sign',
];

$params = Common::checkParams($needs);
if(!isset($params['channel_uid']) && !isset($params['guest_uid'])){
    Common::retJson(['code'=>1,'msg'=>'params error']);
}

if(isset($params['guest_uid'])){
    /**
     * -------------------游客信息存在-------------------------
     */
    $ret = AccountModel::getAccountInfoByGuest($params['guest_uid'],$params['channel']);
    if(empty($ret)){
        /**
         * -------------------账号信息不存在直接插入一条记录-------------------------
         **/
        $id = AccountModel::insertAccount($params);
        if($id){
            AccountLoginLogModel::newLoginLog($id,$params,1);
            Common::retJson(['code'=>0,'msg'=>'success']);
        }else{
            Common::retJson(['code'=>1,'msg'=>'insert error']);
        }
    }else{
        /**
         * -------------------账号信息存在更新channel_uid字段-------------------------
         **/
        if(isset($params['channel_uid'])){
            AccountModel::updateChannelUid($ret['id'],$params['channel_uid'],$params['channel']);
        }
        AccountLoginLogModel::newLoginLog($ret['id'],$params);
        Common::retJson(['code'=>0,'msg'=>'success']);
    }

}else{
    /**
     * -------------------游客信息不存在-----------------------
     **/
    //查看账号是否存在
    $ret = AccountModel::getAcccountByIdChannel($params['channel_uid'],$params['channel']);
    if(empty($ret)){
        //账号不存在新建
        $id = AccountModel::insertAccount($params);
        if($id){
            //添加登录日志
            AccountLoginLogModel::newLoginLog($id,$params,1);
            Common::retJson(['code'=>0,'msg'=>'success']);
        }else{
            Common::retJson(['code'=>1,'msg'=>'insert error']);
        }
    }else{
        $id = $ret['id'];
        //添加登录日志
        AccountLoginLogModel::newLoginLog($id,$params);
        //账号已经存在
        Common::retJson(['code'=>0,'msg'=>'success']);
    }
}
?>