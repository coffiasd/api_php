<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/22
 * Time: 11:09
 *  实时数据更新脚本
 */
define('INDEX_FILE', 1);
//加载配置文件
require_once '../libs/config.php';
//加载公共函数
require_once '../libs/common.php';
//加载mysql model类
require_once '../libs/model.php';
//加载数据库
require_once '../libs/mysql.php';



//while (true){
    run();
//}


function run(){
    //查询online_data数据
    $ret = OnlineModel::getOnlineData();
    //处理数据并且更新status
    $ids = [];
    if(!empty($ret) && is_array($ret)){
        foreach ($ret as $k=>$v){
            //讲时间戳转化为时间
            $date = date('Y-m-d',$v['timestamp']);
            $hour = date('H',$v['timestamp']);
            $min = date('i',$v['timestamp']);
            //查询记录是否存在
            RealtimeModel::insertData($date,$hour,$min,$ret[$k]['sid'],$ret[$k]['cid'],$ret[$k]['channel'],$ret[$k]['count']);
            $ids[] = $ret[$k]['id'];
        }
    }

    if(!empty($ids) && is_array($ids)){
        $ids = implode(',',$ids);
        OnlineModel::updateOnlineStatus($ids);
    }
    echo 'success';
}