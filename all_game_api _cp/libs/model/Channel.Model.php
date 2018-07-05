<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/12
 * Time: 17:01
 */

class ChannelModel{

    public static function channelCloseLocal($channel)
    {
        $res = LocalfileModel::getChannelClose($channel);
        if($res){
            if(empty($res['login'])){
                //白名单判断
                $ip = Common::getRealip();
                $ip_white_list = LocalfileModel::getIpwhite();
                if(in_array($ip,$ip_white_list)){
                    return false;
                }
                //渠道关闭
                return !empty($res['loginmsg'])?$res['loginmsg']:'服务器火爆，请稍后尝试';
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public static function channelClose($channel){
        $sql = "select login,loginmsg from channels where id=$channel";
        $res = mysql::getInstance()->queryOne($sql);
        if($res){
            if(empty($res['login'])){
                //渠道关闭
                return !empty($res['loginmsg'])?$res['loginmsg']:'服务器火爆，请稍后尝试';
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public static function channelRegister($channel){
        $sql = "select register,regmsg from channels where id=$channel";
        $res = mysql::getInstance()->queryOne($sql);
        if($res){
            if(empty($res['register'])){
                return !empty($res['regmsg'])?$res['regmsg']:'服务器火爆，请稍后尝试';
            }else{
                return false;
            }
        }else{
            return false;
        }
    }


	public static function channelCharge($channel){
		$sql = "select recharge from channels where id=$channel";
		$res = mysql::getInstance()->queryOne($sql);
		if($res){
			if(empty($res['recharge'])){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public static function getCodeNameByChannelId($cid=''){
        $sql = "select code_name from channels where id=$cid";
        return mysql::getInstance()->queryOne($sql);
    }

    public static function getChannelByAppidChannel($app_id,$channel){
        $sql = "select id from channels where app_id='$app_id' and channel_id='$channel'";
        $ret = mysql::getInstance()->queryOne($sql);
        if(empty($ret)){
            //插入channel表
            $sql  = "insert into channels (`channel_id`,`app_id`) values ('$channel','$app_id')";
            return mysql::getInstance()->addOne($sql);
        }else{
            return $ret['id'];
        }

    }

}
