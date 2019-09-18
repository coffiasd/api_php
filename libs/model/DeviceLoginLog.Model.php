<?php

class DeviceLoginLogModel {

	public static function getNotHandleList($limit = 1000) {
		$table = "device_login_log_" . date("ym");
		$sql = "select id,device,reg_time as register_time,channel,time from `$table` where `status`=0 order by id asc limit $limit";
		return mysql::getInstance()->queryAll($sql);
	}

    public static function newLoginLog($device,$params = [], $isnew = 0) {
        $time = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $channel = $params['channel'];
        $ip = Common::getRealip();
        $table = 'device_login_log_' . date('ym');
        $sid = $params['sid'];
        if(isset($params['reg_time'])){
            $reg_time = $params['reg_time'];
        }else{
            //通过设备id查询注册时间
            $reg_info = DeviceModel::getRegById($device);
            $reg_time = isset($reg_info['reg_time'])?$reg_info['reg_time']:'';
        }
        $sql = "insert into `$table` (`device`,`time`,`ip`,`isnew`,`reg_time`,`channel`,`date`,`sid`) values ('$device','$time','$ip','$isnew','$reg_time',$channel,'$date',$sid)";
        $res = mysql::getInstance()->addOne($sql);
        if(isset(mysql::getInstance()->con->errno) && mysql::getInstance()->con->errno ==1146){
            //表不存在新建表
            $sql_create_table  = "create table `$table` like `device_login_log`";
            mysql::getInstance()->query($sql_create_table);
            $res = mysql::getInstance()->addOne($sql);
        }
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    public static function updateStatus($ids=''){
        $table = "device_login_log_" . date("ym");
        $sql = "update `$table` set `status`=1 where id in ($ids)";
        mysql::getInstance()->update($sql);
    }

    /**
     * 获得当天安装
     */
    public static function getTodayInstall(){
        $table = "device_login_log_".date('ym');
        $date = date('Y-m-d');
        $sql = "select channel,sid  from `$table` where `date`='$date' and isnew=1";
        return mysql::getInstance()->queryAll($sql);
    }

    public static function getTodayLogin(){
        $table = "device_login_log_".date('ym');
        $date = date('Y-m-d');
        $sql  ="select channel,sid from `$table` where isnew=0 and `date`='$date'";
        return mysql::getInstance()->queryAll($sql);
    }
}

?>