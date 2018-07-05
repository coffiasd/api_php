<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/21
 * Time: 21:59
 */
//统计相关的操作

class Stat{

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 账号天统计 $columns = ['login'=>1];
     */
    public function logAccountDay($columns,$account_id)
    {
        $device_id = $this->params['device_id'];
        $info = AccountModel::getAccountDay($account_id,$device_id);
        if($info===false){
            //当天的记录不存在
            AccountModel::newAccountDay($account_id,$device_id,$columns);
        }else{
            //当天的记录已经存在
            AccountModel::updateAccountDay($account_id,$device_id,$columns);
        }
    }

    /**
     * $columns = ['install'=>1];
     * Daily表相关统计
     */
    public function logDaily($columns,$a=false)
    {
        if(IS_OPEN_LOG ==1 ){
            //关闭写日志操作
            return ;
        }

        if(isset($columns['type'])){
            if($columns['type']=='account'){
                DailyModel::newDailyTempAccount(json_encode($columns,true));
                if(mysql::getInstance2()->con->errno == 1146){
                    $this->checkTableExist('daily_temp_account',true);
                    DailyModel::newDailyTempAccount(json_encode($columns,true));
                }
            }
            if($columns['type']=='device'){
               DailyModel::newDailyTempDevice(json_encode($columns,true));
                if(mysql::getInstance2()->con->errno == 1146){
                    $this->checkTableExist('daily_temp_device',true);
                    DailyModel::newDailyTempDevice(json_encode($columns,true));
                }
            }
            if($columns['type']=='character'){
                DailyModel::newDailyTempCharacter(json_encode($columns,true));
                if(mysql::getInstance2()->con->errno == 1146){
                    $this->checkTableExist('daily_temp_character',true);
                    DailyModel::newDailyTempCharacter(json_encode($columns,true));
                }
            }
            if($columns['type']=='pay'){
                DailyModel::newDailyTempPay(json_encode($columns,true));
                if(mysql::getInstance2()->con->errno == 1146){
                    $this->checkTableExist('daily_temp_pay',true);
                    DailyModel::newDailyTempPay(json_encode($columns,true));
                }
            }
        }else{
            $date = date('Y-m-d');
            $server = isset($this->params['server'])?$this->params['server']:1;
            $channel = isset($this->params['channel'])?$this->params['channel']:0;
            $version = isset($this->params['version'])?Common::getIntVer($this->params['version']):10000;
            $columns = array_merge($columns,['date'=>$date,'server'=>$server,'channel'=>$channel,'version'=>$version]);
            DailyModel::newDailyTemp(json_encode($columns,true));
            if(mysql::getInstance2()->con->errno == 1146 || mysql::getInstance()->con->errno == 1146){
                $this->checkTableExist('daily_temp',true);
                DailyModel::newDailyTemp(json_encode($columns,true));
            }
        }
    }

    /**
     * 设备登录日志
     */
    public function deviceLoginLog($timestamp)
    {
        $params = $this->params;
        $platform = isset($params['platform'])?$params['platform']:'';
        $table = 'zlog_device_login';
        //$this->checkTableExist($table);
        $serial = isset($params['serial'])?$params['serial']:'';
        $imei = isset($params['imei'])?$params['imei']:'';
        $mac = isset($params['mac'])?$params['mac']:'';
        $res = DeviceModel::newLoginlog($params['device_id'],$params['version'],$params['channel'],$params['bundle'],$platform,$serial,$imei,$mac,$params['ip'],$timestamp);
        if(!$res){
            $this->checkTableExist($table);
            DeviceModel::newLoginlog($params['device_id'],$params['version'],$params['channel'],$params['bundle'],$platform,$serial,$imei,$mac,$params['ip'],$timestamp);
        }
    }

    /**
     * 角色登录
     */
    public function characterLoginLog()
    {
        $table = 'zlog_character_login';
        //$this->checkTableExist($table);
        $params = $this->params;

        $character = $params['cid'];
        $server = $params['sid'];
        $channel = $params['channel'];
        $money = $params['money'];
        $level = $params['level'];
        $time = date('Y-m-d H:i:s');
        $ip = $params['ip'];
        $res = CharacterModel::newLoginLog($character,$server,$channel,$money,$level,$time,$ip);
        if(!$res){
            $this->checkTableExist($table);
            CharacterModel::newLoginLog($character,$server,$channel,$money,$level,$time,$ip);
        }
    }

    /**
     * 账号登录
     * @param $account
     */
    public function accountLoginLog($account,$ip,$timestamp)
    {
        $table = 'zlog_account_login';
        //$this->checkTableExist($table);

        $channel = $this->params['channel'];
        $version = $this->params['version'];
        $device_id = $this->params['device_id'];
        $reg_time = DeviceModel::getRegById($device_id);
        $reg = ( $reg_time!==false )?$reg_time:'';
        $res = AccountModel::newLoginLog($account,$channel,$device_id,$version,$ip,$timestamp,$reg);
        if(!$res){
            $this->checkTableExist($table);
            AccountModel::newLoginLog($account,$channel,$device_id,$version,$ip,$timestamp,$reg);
        }
    }

    /**
     * create table
     * @param $table
     */
    private function checkTableExist($table,$is_app_log=false)
    {
        LoginlogModel::checkTableExist($table,$is_app_log);
    }

}
