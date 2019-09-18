<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/21
 * Time: 16:32
 */

//角色相关的Model
class CharacterModel{
    /**
     * 获得角色信息
     */
    public static function getCharacter($aid,$sid){
        $sql = "SELECT * FROM characters WHERE account='$aid' AND server='$sid'";
        $res = mysql::getInstance()->queryOne($sql);
        if(empty($res)){
            return false;
        }else{
            return $res;
        }
    }

    /**
     * @param string $charName
     * @param string $channel
     * @return array|string
     */
    public static function getCharacterByCidAndChannel($charName='',$sid = '',$channel=''){
        $sql  = "select * from `characters` where charName='$charName' and  sid= '$sid' and channel='$channel'";
        return mysql::getInstance()->queryOne($sql);
    }


    public static function newCharacter($charName='',$sid = '',$channel=''){
        $sql = "insert into `characters` (`charName`,`sid`,`channel`) values ('$charName',$sid,$channel)";
        return mysql::getInstance()->addOne($sql);
    }
    /**
     * 创建角色
     * @param $params
     */
    public static function createChara($aid,$name,&$params){
        $sid = $params['sid'];
        $ip = $params['ip'];
        $time = date('Y-m-d H:i:s');
        $params['register_time'] = $time;
        $sql = "INSERT INTO characters (`account`,`server`,`name`,`register_ip`,`register_time`) VALUES ('$aid','$sid','$name','$ip','$time')";
        $insertId = mysql::getInstance()->addOne($sql);
        if($insertId){
            return $insertId;
        }else{
            Common::loger($sql,'mysqlerror');
            return false;
        }
    }

    /**
     * 更新角色信息
     */
    public static function updateChara($name,$aid,$sid,$last_login_ip,$cid){
        $last_login_time = date('Y-m-d H:i:s');
        $sql = "UPDATE characters SET name='$name',account='$aid',server='$sid',last_login_time='$last_login_time',last_login_ip='$last_login_ip' WHERE id=$cid";
        $res = mysql::getInstance()->update($sql);
        if(empty($res)){
            Common::loger($sql,'mysqlerrorsql');
            return false;
        }else{
            return true;
        }
    }


    public static function getCharacterDayStat($device_id)
    {
        $date = date('Y-m-d');
        $sql = "select * from character_day where `character` ='$device_id' and date='$date'";
        $res = mysql::getInstance()->queryOne($sql);
        if($res){
            return $res;
        }else{
            return false;
        }
    }

    /**
     *新增账号stat
     */
    public static function newCharacterDayStat($aid){
        $date=date('Y-m-d');
        $sql = "insert into character_day (`character`,`date`,`open_count`) values ('$aid','$date',1)";
        $res = mysql::getInstance()->addOne($sql);
        if($res){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $aid
     * @param $open_count
     * @return bool
     */
    public static function updateCharacterOpencount($aid,$open_count)
    {
        $date = date('Y-m-d');
        $sql = "update character_day set open_count=$open_count where `character`='$aid' and date='$date'";
        $res = mysql::getInstance()->update($sql);
        if($res){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $aid
     */
    public static function getCharacterWeek($aid,$week)
    {
        $sql = "select * from character_week where `character`='$aid' and weak='$week'";
        $res = mysql::getInstance()->queryOne($sql);
        if($res){
            return $res;
        }else{
            return false;
        }
    }

    public static function newCharacterWeek($aid,$week)
    {
        $sql = "insert into character_week (`character`,`weak`,`open_count`,`open_day`) values ('$aid','$week',1,1)";
        $res = mysql::getInstance()->addOne($sql);
        if($res){
            return true;
        }else{
            return false;
        }
    }

    public static function updateCharacterWeek($aid,$week,$open_count,$open_day)
    {
        $sql = "update character_week set open_count='$open_count',open_day='$open_day' where `character`='$aid' and weak='$week'";
        $res = mysql::getInstance()->update($sql);
        if($res){
            return true;
        }else{
            return false;
        }
    }

    public static function getCharacterMonth($aid,$month){
        $sql = "select * from character_month where `character`='$aid' and month='$month'";
        $res = mysql::getInstance()->queryOne($sql);
        if($res){
            return $res;
        }else{
            return false;
        }
    }

    public static function newCharacterMonth($aid,$month){
        $sql = "insert into character_month (`character`,`month`,`open_count`,`open_day`) values ('$aid','$month',1,1)";
        $res = mysql::getInstance()->addOne($sql);
        if($res){
            return true;
        }else{
            return false;
        }
    }

    public static function updateCharacterMonth($aid,$month,$open_count,$open_day){
        $sql = "update character_month set open_count='$open_count',open_day='$open_day' where `character`='$aid' and month='$month'";
        $res = mysql::getInstance()->update($sql);
        if($res){
            return true;
        }else{
            return false;
        }
    }
}