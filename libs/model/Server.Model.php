<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/26
 * Time: 16:21
 */

class ServerModel{

    public static function getServerByChannel($channel)
    {
        //$sql = "select `id`,`sid`,`name`,`address`,`port`,`msg`,`channel`,`tag`,`state` as `status` from servers where channel='$channel' or channel=0";
        $sql = "select `id`,`sid`,`name`,`address`,`port`,`msg`,`channel`,`tag`,`state` as `status` from servers where channel like '%,$channel,%' or channel=$channel or channel=0";
        $res = mysql::getInstance()->queryAll($sql);
        if($res){
            return $res;
        }else{
            return false;
        }
    }

    public static function getServerById($sid=1)
    {
        $sql = "select * from servers where id=$sid";
        $res = mysql::getInstance()->queryOne($sql);
        if($res){
            return $res;
        }else{
            return false;
        }
    }
}