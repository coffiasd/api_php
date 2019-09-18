<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/11
 * Time: 19:32
 */

class PayModel{

    public static function checkUserExist($user,$serverInfo)
    {
        $sql = "SELECT `uid` FROM `t_account` WHERE `username`='{$user}'";
        $res = mysql::getInstance3($serverInfo['db_host'],$serverInfo['db_username'],$serverInfo['db_password'],$serverInfo['db_database'])->queryOne($sql);
        if($res){
            return $res;
        }else{
            return '';
        }
    }

    public static function checkOrderExist($order,$serverInfo)
    {
        $sql = "SELECT `id`, `orderid` FROM `t_log_charge` WHERE `orderid`='{$order}'";
        $res = mysql::getInstance3($serverInfo['db_host'],$serverInfo['db_username'],$serverInfo['db_password'],$serverInfo['db_database'])->queryOne($sql);
        if($res){
            return $res;
        }else{
            return '';
        }
    }

    public static function insertCharge($uid,$user,$gamemoney,$order,$money,$channel,$serverInfo)
    {
        $time2 = time();
        $time1 = $time2*1000;

        $sql = "INSERT INTO `t_log_charge`(`uid`, `payToUser`, `dateline`, `payGold`, `orderid`, `payRMB`, `payTime`,`channel`) VALUES('$uid', '$user', '$time1', '$gamemoney', '{$order}', '{$money}', '{$time2}','$channel') ";
        $res = mysql::getInstance3($serverInfo['db_host'],$serverInfo['db_username'],$serverInfo['db_password'],$serverInfo['db_database'])->addOne($sql);
        if($res){
            return $res;
        }else{
            return '';
        }
    }

}
