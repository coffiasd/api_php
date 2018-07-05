<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/20
 * Time: 16:36
 */

class RealtimeModel{

    public static function getRealtime($date,$time,$type,$channel,$server,$version,$data=1){
        $sql = "insert into real_time (date, time, channel, server, version, type, data) values ( '" .
            $date . "', " . $time . ", " . $channel . ", " . $server . ", " . $version . ", " . $type . ", " . $data . ") " .
            "on duplicate key update data = data + " . $data;
        $res = mysql::getInstance()->query($sql);
        if($res){
            return true;
        }else{
            return false;
        }
    }

    public static function checkData($date,$hour,$min,$sid,$cid){
        $sql  = "select id from real_time_data where date='$date' and hour='$hour' and min='$min' and server='$sid' and cid='$cid'";
        return mysql::getInstance()->queryOne($sql);
    }

    public static function insertData($date,$hour,$min,$sid,$cid,$channel,$online){
        $sql = "insert into real_time_data (`date`,`hour`,`min`,`server`,`cid`,`channel`,`online`) values ('$date','$hour','$min','$sid','$cid','$channel','$online')";
        return mysql::getInstance()->addOne($sql);
    }

    public static function checkRealTime($date,$hour,$min,$channel,$server,$cid,$column,$count){
        $sql = "select id from `real_time_data` where `date`='$date' and hour=$hour and min=$min and channel=$channel and  server=$server and cid=$cid";
        $ret = mysql::getInstance()->queryOne($sql);
        if(empty($ret)){
            //insert
            $sql = "insert into `real_time_data` (`date`,`hour`,`min`,`channel`,`server`,`cid`,`$column`) VALUES ('$date','$hour','$min','$channel','$server','$cid',$count)";
            mysql::getInstance()->addOne($sql);
        }else{
            //update
            $sql = "update `real_time_data` set `$column`=`$column`+$count where id=".$ret['id'];
            mysql::getInstance()->update($sql);
        }
    }


}