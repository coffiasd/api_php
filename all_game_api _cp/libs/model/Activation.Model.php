<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/24
 * Time: 15:21
 */

class ActivationModel{

    public static function getActivationByAccount($account)
    {
        $sql = "select * from activation_codes where account='$account'";
        $res = mysql::getInstance()->queryOne($sql);
        if($res){
            return $res;
        }else{
            return false;
        }
    }

    public static function getActivationByCode($code)
    {
        $sql = "select * from activation_codes where code='$code'";
        $res = mysql::getInstance()->queryOne($sql);
        if($res){
            return $res;
        }else{
            return false;
        }
    }

    public static function updateActivation($id,$account)
    {
        $time = date('Y-m-d H:i:s');
        $sql = "update activation_codes set state=2,account='$account',activate_time='$time' where id=$id";
        $res = mysql::getInstance()->update($sql);
        if($res){
            return true;
        }else{
            return false;
        }
    }

    public static function addNum($group)
    {
        $sql = "update activation_code_groups set activated=activated+1 where `group`='$group'";
        $res = mysql::getInstance()->update($sql);
        if($res){
            return true;
        }else{
            return false;
        }
    }
}