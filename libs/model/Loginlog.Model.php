<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/24
 * Time: 11:41
 */
//登录日志
class LoginlogModel{

    /**
     * 查询表是否存在
     */
    public static function checkTableExist($table,$is_app_log=false)
    {
        $date =date('y_m_d');
        $tableName = $table.'_'.$date;
        $sql = "create table  `$tableName` like `$table` ";
        if(true === $is_app_log){
            mysql::getInstance2()->query($sql);
        }else{
            mysql::getInstance()->query($sql);
        }
    }
}