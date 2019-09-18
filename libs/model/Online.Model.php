<?php

class OnlineModel {

	public static function NewOnline($sid, $cid, $channel, $count, $timestamp) {
		$timestamp = (int)($timestamp/1000);
		$sql = "INSERT INTO `online_data` (`sid`,`rid`,`channel`,`count`,`timestamp`) values ('$sid','$cid','$channel','$count','$timestamp')";
		$id = mysql::getInstance()->addOne($sql);
		if (is_int($id)) {
			return $id;
		} else {
			return 'insert error';
		}
	}

	public static function getOnlineData($limit = 1000){
		$sql = "select id,sid,cid,channel,count,timestamp from online_data where status=0 limit $limit";
		return mysql::getInstance()->queryAll($sql);
	}

	public static function updateOnlineStatus($ids=[]){
		$sql  = "update online_data set `status`=1 where id in ($ids)";
		return mysql::getInstance()->update($sql);
	}
}
?>
