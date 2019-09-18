<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/9
 * Time: 17:55
 */
class DeviceLoginLog{
    public function __construct(array $params)
    {
        $this->list = $params;
    }

    /**
     *
     */
    public function countNew(){
        if(!isset($this->list)){
            return false;
        }
        $ret = [];
        foreach ($this->list  as $v){
            $time = $v['time'];
            $date = substr($time,0,10);
            $hour  = substr($time,11,2);
            $min= substr($time,14,2);
            $channel = $v['channel'];
            $key = $date.'-'.$hour.'-'.$min;
            if(isset($ret[$channel][$key])){
                $ret[$channel][$key]++;
            }else{
                $ret[$channel][$key]=1;
            }
        }
        $this->updateRealTime($ret,'install');
    }

    /**
     * 更新到real_time
     */
    public function updateRealTime($ret = [],$column = ''){
        if(empty($ret)){
            return ;
        }
        foreach ($ret as $channel=>$val){
            foreach ($val as $time=>$count){
                $date = substr($time,0,10);
                $hour = substr($time,11,2);
                $min = substr($time,14,2);
                RealtimeModel::checkRealTime($date,$hour,$min,$channel,1,1,$column,$count);
            }
        }
    }
}