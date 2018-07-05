<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/21
 * Time: 15:59
 */

class Device
{
    public function __construct()
    {
    }

    /**
     * & 返回device_id(设备注册id)
     * @param $params
     */
    public function registerDevice(&$params){
        //检查设备是否注册
        $isRegister = $this->isRegister($params['sn']);
        $this->install=false;
        if( $isRegister === false){
            $this->install = true;
            //注册设备并返回插入id
            $device_id = $this->register($params);
            if(!$device_id){
                //设备注册失败
                Common::retJson(['code'=>1,'msg'=>'device register error']);
            }
            $params['device_id'] = $device_id;
            $channel_register = $params['channel'];
        }else{
            //设备已经注册
			$params['device_id'] = $isRegister['id'];
			if(isset($isRegister['channel'])){
				$params['register_channel'] = $isRegister['channel'];
			}
            //设备注册时候的渠道
            $channel_register = $isRegister['last_login_channel'];
        }

        //更新设备登录信息
        $this->deviceLogin($params['device_id'],$params['channel']);
        //设备登录日志
        $stat = new Stat($params);

        $params_copy = Common::getData();
        if($this->install){
            $install=1;
        }else{
            $install=0;
        }
        $stat->logDaily(['type'=>'device','install'=>$install,'device'=>$params['device_id'],'old_channel'=>$channel_register,'params'=>$params_copy,'timestamp'=>time(),'date'=>date('Y-m-d'),'level'=>Common::getDeviceLevel($params)]);
        return $params['device_id'];
    }

    /**
     * 更新设备登录信息
     */
    public function deviceLogin($id,$channel){
        DeviceModel::updateLoginTimeIp($id,$channel);
    }


    /**
     * 注册设备
     * @param $params
     */
    public function register($params){
        //realTimeAdd(10, $params['channel'], 1, $params['version']);
        return DeviceModel::registerDevice($params);
    }

    /**
     * 判断设备是否注册
     */
    public function isRegister($sn){
        return DeviceModel::getDeviceBySn($sn);
    }
}
