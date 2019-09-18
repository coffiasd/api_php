<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/23
 * Time: 17:16
 */

class RententionModel{
    public static function getRententionDay($channel,$level,$type,$version,$date)
    {
        $date = date('Y-m-d',strtotime($date));
        $sql = "select * from retention_day where date='$date' and channel='$channel' and level='$level' and type='$type' and `version`='$version'";
        $res = mysql::getInstance()->queryOne($sql);
        if($res){
            return $res;
        }else{
            return false;
        }
    }

    public static function newRententionDay($channel,$level,$type,$version,$column,$date)
    {
        $date = date('Y-m-d',strtotime($date));
        $sql  = "insert into retention_day (`date`,`channel`,`level`,`type`,`version`,`$column`) values ('$date','$channel','$level','$type','$version',1)";
        $res=  mysql::getInstance()->addOne($sql);
        if($res){
            return $res;
        }else{
            return false;
        }
    }

    public static function updateRententionDay($channel,$level,$type,$version,$column,$val,$date)
    {
        $date = date('Y-m-d',strtotime($date));
        $sql = "update retention_day set `$column`=$val where channel='$channel' and level='$level' and type='$type' and `version`='$version' and date='$date'";
        $res = mysql::getInstance()->update($sql);
        if($res){
            return true;
        }else{
            return false;
        }
    }

    public static function getRententionWeek($channel,$level,$type,$version,$date)
    {
        $date = date('Y-m-d',strtotime($date));
        $sql = "select * from retention_week where date='$date' and channel='$channel' and level='$level' and type='$type' and `version`='$version'";
        $res = mysql::getInstance()->queryOne($sql);
        if($res){
            return $res;
        }else{
            return false;
        }
    }

    public static function newRententionWeek($channel,$level,$type,$version,$column,$date)
    {
        $date = date('Y-m-d',strtotime($date));
        $sql  = "insert into retention_week (`date`,`channel`,`level`,`type`,`version`,`$column`) values ('$date','$channel','$level','$type','$version',1)";
        $res=  mysql::getInstance()->addOne($sql);
        if($res){
            return $res;
        }else{
            return false;
        }
    }

    public static function updateRententionWeek($channel,$level,$type,$version,$column,$val,$date)
    {
        $date = date('Y-m-d',strtotime($date));
        $sql = "update retention_week set `$column`=$val where channel='$channel' and level='$level' and type='$type' and `version`='$version' and date='$date'";
        $res = mysql::getInstance()->update($sql);
        if($res){
            return true;
        }else{
            return false;
        }
    }

    public static function getRententionWeek2($channel,$level,$type,$version,$date)
    {
        $date = date('Y-m-d',strtotime($date));
        $sql = "select * from retention_2week where date='$date' and channel='$channel' and level='$level' and type='$type' and `version`='$version'";
        $res = mysql::getInstance()->queryOne($sql);
        if($res){
            return $res;
        }else{
            return false;
        }
    }

    public static function newRententionWeek2($channel,$level,$type,$version,$column,$date)
    {
        $date = date('Y-m-d',strtotime($date));
        $sql  = "insert into retention_2week (`date`,`channel`,`level`,`type`,`version`,`$column`) values ('$date','$channel','$level','$type','$version',1)";
        $res=  mysql::getInstance()->addOne($sql);
        if($res){
            return $res;
        }else{
            return false;
        }
    }

    public static function updateRententionWeek2($channel,$level,$type,$version,$column,$val,$date)
    {
        $date = date('Y-m-d',strtotime($date));
        $sql = "update retention_2week set `$column`=$val where channel='$channel' and level='$level' and type='$type' and `version`='$version' and date='$date'";
        $res = mysql::getInstance()->update($sql);
        if($res){
            return true;
        }else{
            return false;
        }
    }

    public static function getRententionStrictWeek($channel,$level,$type,$version,$date)
    {
        $date = date('Y-m-d',strtotime($date));
        $sql = "select * from retention_strict_week where date='$date' and channel='$channel' and level='$level' and type='$type' and `version`='$version'";
        $res = mysql::getInstance()->queryOne($sql);
        if($res){
            return $res;
        }else{
            return false;
        }
    }

    public static function newRententionStrictWeek($channel,$level,$type,$version,$column,$date)
    {
        $date = date('Y-m-d',strtotime($date));
        $sql  = "insert into retention_strict_week (`date`,`channel`,`level`,`type`,`version`,`$column`) values ('$date','$channel','$level','$type','$version',1)";
        $res=  mysql::getInstance()->addOne($sql);
        if($res){
            return $res;
        }else{
            return false;
        }
    }

    public static function updateRententionStrictWeek($channel,$level,$type,$version,$column,$val,$date)
    {
        $date = date('Y-m-d',strtotime($date));
        $sql = "update retention_strict_week set `$column`=$val where channel='$channel' and level='$level' and type='$type' and `version`='$version' and date='$date'";
        $res = mysql::getInstance()->update($sql);
        if($res){
            return true;
        }else{
            return false;
        }
    }


    public static function getRententionMonth($channel,$level,$type,$version,$date)
    {
        $date = date('Y-m-d',strtotime($date));
        $sql = "select * from retention_month where date='$date' and channel='$channel' and level='$level' and type='$type' and `version`='$version'";
        $res = mysql::getInstance()->queryOne($sql);
        if($res){
            return $res;
        }else{
            return false;
        }
    }

    public static function newRententionMonth($channel,$level,$type,$version,$column,$date)
    {
        $date = date('Y-m-d',strtotime($date));
        $sql  = "insert into retention_month (`date`,`channel`,`level`,`type`,`version`,`$column`) values ('$date','$channel','$level','$type','$version',1)";
        $res=  mysql::getInstance()->addOne($sql);
        if($res){
            return $res;
        }else{
            return false;
        }
    }

    public static function updateRententionMonth($channel,$level,$type,$version,$column,$val,$date)
    {
        $date = date('Y-m-d',strtotime($date));
        $sql = "update retention_month set `$column`=$val where channel='$channel' and level='$level' and type='$type' and `version`='$version' and date='$date'";
        $res = mysql::getInstance()->update($sql);
        if($res){
            return true;
        }else{
            return false;
        }
    }



}