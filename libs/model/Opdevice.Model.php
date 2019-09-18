<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/17
 * Time: 16:44
 */

class OpdeviceModel{

    public static function newOpAccount($account){
        $sql = "insert into op_account values ('$account')";
        $res = mysql::getInstance()->addOne($sql);
        if($res){
            return $res;
        }else{
            return '';
        }
    }

    public static function newOpCharacter($character){
        $sql = "insert into op_character values ('$character')";
        $res = mysql::getInstance()->addOne($sql);
        if($res){
            return $res;
        }else{
            return '';
        }
    }

    public static function newOpDevice($device){
        $sql = "insert into op_device values ('$device')";
        $res = mysql::getInstance()->addOne($sql);
        if($res){
            return $res;
        }else{
            return '';
        }
    }

    public static function getOpAccount($account){
        $sql  = "select id from op_account where account='$account'";
        $res = mysql::getInstance()->queryOne($sql);
        if($res){
            return $res;
        }else{
            return '';
        }
    }

    public static function getOpCharacter($character){
        $sql = "select id from op_character where `character`='$character'";
        $res = mysql::getInstance()->queryOne($sql);
        if($res){
            return $res;
        }else{
            return '';
        }
    }

    public static function getOpDevice($device){
        $sql  = "select id from op_device where device='$device'";
        $res = mysql::getInstance()->queryOne($sql);
        if($res){
            return $res;
        }else{
            return '';
        }
    }


}