<?php
require_once 'libs/Redis.php';

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/21
 * Time: 15:34
 */
class Event
{
    public function __construct($params)
    {
        $this->params = $params;
    }

    public function log()
    {
        $this->log_device_event();
        $this->log_event_day();
        $this->log_event_num(); //记录事件请求数量
        return true;
    }

    /**
     * 记录事件表
     */
    private function log_device_event()
    {
        //根据event version device查询记录是否已经存在
        $version = $this->params['version'];
        $event = $this->params['event'];
        $device_id = $this->params['device_id'];
        $res = EventModel::getEventByVersionEvent($version,$event,$device_id);
        if($res===true){
            //空的 新增数据
            EventModel::addNewEvent($this->params);
        }

    }

    /**
     * 事件天统计
     */
    private function log_event_day(){
        //查询设备是否今天注册 不是今天注册的return
        $regTime = DeviceModel::getRegById($this->params['device_id']);
        if(strtotime($regTime)<strtotime(date("Y-m-d"))){
            return ;
        }

        $version = Common::getIntVer($this->params['version']);
        $event = $this->params['event'];
        $level = Common::getDeviceLevel($this->params);
        $channel = $this->params['channel'];
        $redis = new Credis_Client('10.27.162.72','6379','3','',0,'Wooduanunix9ijn0okm');
        $name = date('y-m-d').'-'.$version.'-'.$event.'-'.$level.'-'.$channel;
        $count = $redis->incr($name);
        if($count==1){
            //第一次生成这个key 设置key的过期时间
            $redis->expire($name,259200);
        }
    }

    /**
     * 记录单位时间内事件次数
     */
    private function log_event_num(){
        $key = date("YmdHi").'-log-event-num-'.$this->params['channel'];
        $redis = new Credis_Client('10.27.162.72','6379','3','',0,'Wooduanunix9ijn0okm');
        $count = $redis->incr($key);
        if($count == 1){
            $redis->expire($key,259200);
        }
    }


}