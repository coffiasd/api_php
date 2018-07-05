<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/21
 * Time: 21:08
 */
class EventModel{

    /**
     * 查询事件是否已经记录
     * @param $version
     * @param $event
     * @param $device_id
     */
    public static function getEventByVersionEvent($version,$event,$device_id)
    {
        $sql = "select id from devices_events where device='$device_id' and event='$event' and version=$version";
        return empty(mysql::getInstance()->queryOne($sql));
    }

    /**
     * 新增事件数据
     * @param $params
     */
    public static function addNewEvent($params)
    {
        $device = $params['device_id'];
        $event = $params['event'];
        $version = $params['version'];
        $time = date('Y-m-d H:i:s');
        $args = isset($params['args'])?$params['args']:'';
        //添加帧数统计需要的参数
        $level = Common::getDeviceLevel($params);
        $model = isset($params['model']) ? $params['model'] : '';
        $cpu_count = isset($params['cpu_cnt']) ? $params['cpu_cnt'] : '';
        $cpu_freq = isset($params['cpu_frq']) ? $params['cpu_frq'] : '';
        $memory = isset($params['mem_tot']) ? $params['mem_tot'] : '';
        $string  = $level.'|'.$model.'|'.$cpu_count.'|'.$cpu_freq.'|'.$memory;

        $sql = "insert into devices_events (`device`,`event`,`args`,`version`,`time`,`frame_string`) values ('$device','$event','$args','$version','$time','$string')";

        return mysql::getInstance()->addOne($sql);
    }

}