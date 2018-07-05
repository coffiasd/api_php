<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/20
 * Time: 15:39
 */
class Mysql
{

    protected static $_instance = NULL;

    private function __construct()
    {
    }

    /**
     * 私有化clone函数
     */
    private function __clone()
    {
    }

    public function mysql_pconnect()
    {
        $conn=mysql_pconnect(DB_HOST,DB_USERNAME,DB_PASSWORD);
        mysql_set_charset("utf8",$conn);
        mysql_select_db(DB_DATABASE, $conn);
        $this->conn = $conn;
        return ;
        $con = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        if ($con->connect_errno) {
            die($con->connect_error);
        }
        $this->con = $con;
    }

    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self();
            //连接数据库
            self::$_instance->mysql_pconnect();
        }
        return self::$_instance;
    }

    private function sql_query($sql)
    {
        $result=mysql_query($sql, $this->conn);
        return $result;
        $starttime = microtime(true);
        $result = $this->con->query($sql);

        $endtime = microtime(true);
        $costtime = (int)(($endtime - $starttime) * 1000);
        if ($costtime > 300) {
            $sql = $costtime . ':' . $sql;
            Common::loger($sql,'costtime');
        }

        if (!$result) {
            $err = $this->con->error;
            echo $sql;echo '<br />';
            echo $err;exit;
            Common::loger($err,'mysqlerror');
        }
        return $result;
    }

    /**
     * 获得一条数据
     */
    public function queryOne($sql = '')
    {
        $res = $this->sql_query($sql);
        $data = mysql_fetch_assoc($res);
        mysql_free_result($res);
        return $data;
    }

    /**
     * 获得多条数据
     */
    public function queryAll($sql = '')
    {
        $res = $this->sql_query($sql);
        $data = array();
        while($row = mysql_fetch_assoc($res))
        {
            array_push($data, $row);
        }
        mysql_free_result($res);
        return $data;
    }

    /**
     * 更新操作
     */
    public function update($sql = '')
    {
        $result=mysql_query($sql, $this->conn);
        $err = mysql_error();

        return mysql_affected_rows($this->conn);
    }

    /**
     * 新增一条数据
     */
    public function addOne($sql = '')
    {
        $result=mysql_query($sql, $this->conn);
        $err = mysql_error();
        if($err)
            throw new Exception("sql: " . $sql . " error: " . $err);

        return mysql_insert_id($this->conn);
    }

    /**
     * 执行外部sql语句
     * @param string $sql
     */
    public function query($sql = '')
    {
        $this->exec_query($sql);
    }


}