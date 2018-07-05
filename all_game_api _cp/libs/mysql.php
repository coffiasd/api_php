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
    protected static $_instance2 = NULL;
    protected static $_instance3 = NULL;

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
        $con = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        if ($con->connect_errno) {
            die($con->connect_error);
        }
        $this->con = $con;
        $this->exec_query('set names utf8');
    }

	public static function getInstance2($host='',$user='',$password='',$database='')
    {
        if ( !empty($host) || self::$_instance2==null) {
            self::$_instance2 = new self();
            //连接数据库
            $host = !empty($host)?$host:DB_HOST_LOG;
            $user = !empty($user)?$user:DB_USERNAME_LOG;
            $password = !empty($password)?$password:DB_PASSWORD_LOG;
            $database = !empty($database)?$database:DB_DATABASE_LOG;

            $con = new mysqli($host,$user,$password,$database);
			if ($con->connect_errno) {
				die($con->connect_error);
			}
			self::$_instance2->con = $con;
			self::$_instance2->exec_query('set names utf8');
        }
        return self::$_instance2;
    }

    public static function getInstance3($host='',$user='',$password='',$database='')
    {
        if ( !empty($host) || self::$_instance3==null) {
            self::$_instance3 = new self();
            //连接数据库
            $host = !empty($host)?$host:DB_HOST_LOG;
            $user = !empty($user)?$user:DB_USERNAME_LOG;
            $password = !empty($password)?$password:DB_PASSWORD_LOG;
            $database = !empty($database)?$database:DB_DATABASE_LOG;

            $con = new mysqli($host,$user,$password,$database);
            if ($con->connect_errno) {
                die($con->connect_error);
            }
            self::$_instance3->con = $con;
            self::$_instance3->exec_query('set names utf8');
        }
        return self::$_instance3;
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

    /**
     * 关闭数据库
     */
    public static function close(){
        if(isset(self::$_instance2)){
            self::$_instance2=null;
        }
    }

    private function exec_query($sql)
    {
        $starttime = microtime(true);
        $result = $this->con->query($sql);

        $endtime = microtime(true);
        $costtime = (int)(($endtime - $starttime) * 1000);
        if ($costtime > 300) {
            $sql = $costtime . ':' . $sql;
            Common::loger($sql,'costtime');
        }
        if (!$result) {
            $error = $this->con->error;
            $errno = $this->con->errno;
            $debug = var_export(debug_backtrace(),true);
            Common::loger($debug,'mysqlerror');
            return ['errno'=>$errno,'error'=>$error];
        }
        return $result;
    }

    /**
     * 获得一条数据
     */
    public function queryOne($sql = '')
    {
        $result = $this->exec_query($sql);
        if($result){
            return $result->fetch_assoc();
        }else{
            return '';
        }
    }

    /**
     * 获得多条数据
     */
    public function queryAll($sql = '')
    {
        $result = $this->exec_query($sql);
        $ret = [];
        if($result){
            while ($row = $result->fetch_assoc()) {
                $ret[] = $row;
            }
        }
        return $ret;
    }

    /**
     * 更新操作
     */
    public function update($sql = '')
    {
        $this->exec_query($sql);
        return $this->con->affected_rows;
    }

    /**
     * 新增一条数据
     */
    public function addOne($sql = '')
    {
        $this->exec_query($sql);
        return $this->con->insert_id;
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
