<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/9
 * Time: 15:30
 */
class AccountLoginLog{
    public function __construct(array $params)
    {
        $this->params = $params;
        if(!empty($this->params)){
            foreach ($this->params as $v){
                if($v['isnew'] == 1){
                    $this->new[] = $v;
                }else{
                    $this->login[] = $v;
                }
            }
        }
    }

    /**
     *实时新增
     */
    public function countNew(){
        if(!isset($this->new)){
            return false;
        }
        $ret = [];
        foreach ($this->new  as $v){
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
        $this->updateRealTime($ret,'register');
    }

    /**
     *活跃
     */
    public function countLogin(){
        if(!isset($this->login)){
            return false;
        }
        $ret = [];
        foreach ($this->login as $v){
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
        $this->updateRealTime($ret,'login');

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