<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/24
 * Time: 17:09
 */

class StatusModel{

    public static function getBoardsByChannel($channel)
    {
        $sql = "select `order`, `title`, `type`, `content`, `channels`, `name`, `tag`,`not_in` from boards where channels=$channel or channels=0 and active=1";
        $res = mysql::getInstance()->queryAll($sql);
        if($res){
            return $res;
        }else{
            return false;
        }
    }


    public static function getConfigByChannel($channel)
    {
        $sql = "select `key` as `name`,`value`,`group` from online_configs where channels=$channel or channels=0 and active=1";
        $res = mysql::getInstance()->queryAll($sql);
        if($res){
            return $res;
        }else{
            return [];
        }
    }

    public static function getProductByChannel()
    {
        $sql  = "select `order` as id ,id as product_id,name as product_name,gold as diamond_num,diamond_num_symbol,send_gold as diamond_num_1,currency as money_type,price as money_num,label as flag from products limit 8";
        $res = mysql::getInstance()->queryAll($sql);
        if($res){
            return $res;
        }else{
            return [];
        }
    }

}