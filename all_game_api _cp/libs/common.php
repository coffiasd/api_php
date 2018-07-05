<?php

/**
 * Created by PhpStorm.
 * User: lch
 * Date: 2016/10/20
 * Time: 14:20
 */
//自动加载机制
spl_autoload_register(function ($class_name) {
	//xxxModel   xxxServer
	if (strpos($class_name, "Model")) {
		$class_name = str_replace("Model", "", $class_name);
		include APP_ROOT . "/libs/model/$class_name.Model.php";
		return;
	} else {
		include APP_ROOT . "/libs/server/$class_name.Class.php";
		return;
	}
});

class Common {
	public static function getDateTime($date) {
		return date('Y-m-d', strtotime($date));
	}

	public static function getPostData() {
		$data = $_REQUEST;
		$input = file_get_contents('php://input');
		$input = json_decode($input, true);
		if (!empty($input) && is_array($input)) {
			$data = array_merge($data, $input);
		}
		return addslashes(var_export($data, true));
	}

	public static function getData() {
		$gets = $_GET;
		if (!empty($gets) && is_array($gets)) {
			foreach ($gets as $k => $v) {
				$gets[$k] = addslashes($v);
			}
		}
		if (isset($gets['token'])) {
			unset($gets['token']);
		}
		return $gets;
	}

	public static function getUrl() {
		return $_SERVER['REQUEST_URI'];
	}

	public static function retJson($ret = []) {
		header("Content-type: application/json");
		echo json_encode($ret, true);
		exit;
	}

	public static function getRealip() {
		if (isset($_SERVER['HTTP_X_REAL_IP'])) {
			return $_SERVER['HTTP_X_REAL_IP'];
		}
		return $_SERVER["REMOTE_ADDR"];
	}

	public static function randomKey($length = 16) {
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$string = '';
		for ($i = 0; $i < $length; $i++) {
			$string .= $chars[mt_rand(0, strlen($chars) - 1)];
		}
		return $string;
	}

	public static function getPostStr($post = []) {
		if (empty($post) || !is_array($post)) {
			return '';
		}
		$str = '';
		foreach ($post as $key => $value) {
			$str .= $key . ': ' . $value;
		}
		return $str;
	}

	/**
	 * [dateListWeek 遇到五六日标记]
	 * @param  [type] $datelist [description]
	 * @return [type]           [description]
	 */
	public static function dateListWeek($datelist) {
		if (!empty($datelist) && is_array($datelist)) {
			foreach ($datelist as $key => $value) {
				$w = Com::getWFromDate($value);
				if ($w == '五' || $w == '六' || $w == '日') {
					$datelist[$key] = $value . "($w)";
				}
			}
		}
		return $datelist;
	}

	/**
	 * [getDeviceLevel 获得设备档次等级]
	 * @return [type] [description]
	 */
	public static function getDeviceLevel($params) {
		$cpu = isset($params['cpu']) ? $params['cpu'] : 0;
		$cpu_frq = isset($params['cpu_frq']) ? $params['cpu_frq'] : 0;
		$mem_tot = isset($params['mem_tot']) ? $params['mem_tot'] : 0;
		$cpu_cnt = isset($params['cpu_cnt']) ? $params['cpu_cnt'] : 0;
		if ($cpu_frq) {
			$cpu = $cpu_frq;
		}

		$ret = self::getSpecialDevice($params);
		if ($ret) {
			return $ret;
		}

		if ($cpu_cnt >= 8 && $mem_tot > 1600) {
			//高端机
			return 3;
		}

		if ($cpu === 0 || $mem_tot === 0) {
			//未知机型
			return 4;
		}
		if ($cpu <= 1200 || $mem_tot < 850) {
			//低端机
			return 1;
		}
		if ($cpu > 1600 && $mem_tot > 1600) {
			//高端机
			return 3;
		}

		return 2;
	}

	/**
	 * [getSpecialDevice 返回特殊设备]
	 * @return [type] [description]
	 */
	public static function getSpecialDevice($params) {
		$arr = [
			["name" => "xiaomi hm 1", "match" => 2, "level" => 1],
			["name" => "xiaomi hm 2", "match" => 2, "level" => 1],
			["name" => "xiaomi hm2", "match" => 2, "level" => 1],
			["name" => "xiaomi 2013", "match" => 3, "level" => 1],
			["name" => "xiaomi 2014", "match" => 3, "level" => 1],
			["name" => "小米", "match" => 3, "level" => 1],
			["name" => "xiaomi hm note 1", "match" => 3, "level" => 1],
			["name" => "xiaomi mi 3", "match" => 1, "level" => 1],
			["name" => "coolpad coolpad 8720l", "match" => 1, "level" => 1],
			["name" => "cmdc m623c", "match" => 1, "level" => 1],
			["name" => "oppo r831k", "match" => 1, "level" => 1],
			["name" => "oppo r1011", "match" => 1, "level" => 1],
			["name" => "samsung sm-j120h", "match" => 1, "level" => 1],
			["name" => "samsung sm-j320h", "match" => 1, "level" => 1],
			["name" => "samsung sm-t116nu", "match" => 1, "level" => 1],
			["name" => "samsung sm-t231", "match" => 1, "level" => 1],
			["name" => "samsung sm-t561y", "match" => 1, "level" => 1],
			["name" => "samsung sm-g7102", "match" => 1, "level" => 1],
			["name" => "asus asus_t00j", "match" => 1, "level" => 1],
			["name" => "asus asus_z007", "match" => 1, "level" => 1],
			["name" => "asus k012", "match" => 1, "level" => 1],
			["name" => "huawei t1 7.0", "match" => 1, "level" => 1],
			["name" => "huawei mediapad t1 8.0", "match" => 1, "level" => 1],
			["name" => "huawei huawei cun-u29", "match" => 1, "level" => 1],
			["name" => "huawei huawei y541-u02", "match" => 1, "level" => 1],
			["name" => "lenovo lenovo a7000-a", "match" => 1, "level" => 2],
			["name" => "iphone1,", "match" => 3, "level" => 1],
			["name" => "iphone2,", "match" => 3, "level" => 1],
			["name" => "iphone3,", "match" => 3, "level" => 1],
			["name" => "iphone4,", "match" => 3, "level" => 1],
			["name" => "iphone5,", "match" => 3, "level" => 2],
			["name" => "ipod", "match" => 3, "level" => 1],
			["name" => "ipad1,", "match" => 3, "level" => 1],
			["name" => "ipad2,", "match" => 3, "level" => 1],
			["name" => "ipad3,4", "match" => 1, "level" => 2],
			["name" => "ipad3,5", "match" => 1, "level" => 2],
			["name" => "ipad3,6", "match" => 1, "level" => 2],
			["name" => "ipad3,", "match" => 3, "level" => 1],
			["name" => "ipad4,7", "match" => 1, "level" => 3],
			["name" => "ipad4,8", "match" => 1, "level" => 3],
			["name" => "ipad4,9", "match" => 1, "level" => 3],
			["name" => "ipad4,", "match" => 3, "level" => 2],
			["name" => "iphone", "match" => 3, "level" => 3],
			["name" => "ipad", "match" => 3, "level" => 3],

		];

		$str = isset($params['model']) ? $params['model'] : '';
		$str = strtolower($str);
		if (empty($str)) {
			return false;
		}

		foreach ($arr as $v) {
			$function = 'match' . $v['match'];
			if (self::$function(strtolower($v['name']), $str) === true) {
				return $v['level'];
			}
		}
		return false;
	}

	/**
	 * [match1 全匹配]
	 * @return [type] [description]
	 */
	public static function match1($search, $str) {
		if ($search == $str) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * [match2 包含匹配]
	 * @return [type] [description]
	 */
	public static function match2($search, $str) {
		if (strstr($str, $search)) {
			return true;
		}
		return false;
	}

	/**
	 * [match3 开头匹配]
	 * @return [type] [description]
	 */
	public static function match3($search, $str) {
		preg_match("/^$search.*/", $str, $arr);
		if (!empty($arr)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * [getDateByTime 通过完整的日期获得日期]
	 * @return [type] [description]
	 */
	public static function getDateByTime($date = '') {
		if (empty($date)) {
			return;
		}
		list($day, $hour) = explode(" ", $date);
		return $day;
	}

	/**
	 * [getIntVer 版本号的替换]
	 * @param  [type] $ver [description]
	 * @return [type]      [description]
	 */
	public static function getIntVer($ver) {

		$isVer = strpos($ver, '.');
		if (false === $isVer) {
			return $ver;
		}
		$list = explode('.', $ver);
		$num = count($list);
		if ($num > 3) {
			return 1;
		}
		if ($num == 1) {
			$list[1] = 0;
			$list[2] = 0;
		}
		if ($num == 2) {
			$list[2] = 0;
		}
		if (isset($list[0]) && isset($list[1]) && isset($list[2])) {
			$res = $list[0] * 10000 + $list[1] * 100 + $list[2];
			return $res;
		}
		return 1;
	}

	/**
	 * [getDaysByDates 给一个日期计算到今天的天数]
	 * @return [type] [description]
	 */
	public static function getDaysByDate($date) {
		$start = strtotime($date);
		$end = strtotime(date("Y-m-d"));
		if ($start >= $end) {
			return 0;
		}
		return ($end - $start) / 86400;
	}

	/**
	 * [getWeekMonthByDates 通过天数获得周数和月份数]
	 * @return [type] [description]
	 */
	public static function getWeekMonthByDates($count, $exp_time) {
		if ($exp_time == 86400 * 7) {
			//周
			$count = $count / 7;
		}

		if ($exp_time == 86400 * 14) {
			//双周
			$count = $count / 14;
		}

		if ($exp_time == 86400 * 30) {
			//月分
			$count = $count / 30;
		}

		return $count;
	}

	/**
	 * [checkParams 检查参数]
	 * @return [type] [description]
	 */
	public static function checkParams(array $need) {

		//获得请求的参数
		$params = Common::getRquestParams();
		$errors = '';
		foreach ($need as $key => $value) {
			if (!isset($params[$value])) {
				$errors .= $value . ' is not set';
			}
		}
		if (!empty($errors)) {
			Common::loger('params error: ' . $errors);
			//退出程序
			Common::retJson(['code' => 1, 'msg' => $errors]);
		}
		$params['channel'] = Common::getSelfChannelIdByAppidChannel($params['app_id'],$params['channel']);
		return $params;
	}

    /**
     * 通过appid和channel获得渠道id
     */
	public static function getSelfChannelIdByAppidChannel($appid='',$channel=''){
        return ChannelModel::getChannelByAppidChannel($appid,$channel);
    }

	/**
	 * 获得请求的参数
	 */
	public static function getRquestParams($jump = false) {
		$gets = $_REQUEST; //get的参数
		if (!empty($gets) && is_array($gets)) {
			foreach ($gets as $k => $v) {
				$gets[$k] = addslashes($v);
			}
		}
		Common::sign($gets, $jump);
		$postJson = file_get_contents('php://input'); //post的设备参数以json形式展示
		if (!empty($postJson)) {
			$gets['DeviceData'] = $postJson;
			$postJson = json_decode($postJson, true);
			if (!empty($postJson) && is_array($postJson)) {
				$gets = array_merge($gets, $postJson);
			}
		}
		if (DEBUG) {
			Common::loger(var_export($gets, true));
		}
		return $gets;
	}

	public static function sign($params) {
		if (isset($params['jump'])) {
			return true;
		}
		if (!isset($params['sign'])) {
			Common::retJson(['code' => 1, 'msg' => 'sign error']);
		}
		$signbefore = $params['sign'];
		unset($params['sign']);
		ksort($params);
		$str = '';
		foreach ($params as $k => $v) {
			$str .= $k . '|' . $v . '|';
		}
		$str .= 'wizardgame';
		$sign = md5($str);
		if ($sign != $signbefore) {
			Common::retJson(['code' => 1, 'msg' => 'sign error']);
		}
	}

	public static function strToHex($string) {
		$hex = "";
		for ($i = 0; $i < strlen($string); $i++) {
			$hex .= '%' . dechex(ord($string[$i]));
		}

		return $hex;
	}

	/**
	 * 记录日志
	 */
	public static function loger($str, $name = 'default') {
		$str = date('Y-m-d H:i:s') . ' :' . $str . "\r\n";
		$logname = APP_ROOT . '/log/' . date("ymd") . $name . '.log';
		$f = fopen($logname, 'a+');
		fwrite($f, $str);
		fclose($f);
	}

	/**
	 * 计算时间花费
	 */
	public static function logTimeCost($timeStart = '', $name = 'default name') {
		$timeEnd = com::getNowMicrotime();
		$cost = $timeEnd - $timeStart;
		$str = 'timecost-' . $name . ' :' . $cost;
		com::loger($str);
	}

	/**
	 * 获得当前毫秒时间戳
	 */
	public static function getNowMicrotime() {
		$now = microtime();
		list($s1, $s2) = explode(" ", $now);
		return $s2 + $s1;
	}

	/**
	 * @param $account
	 * @param $timestamp
	 * @return string
	 */
	public static function getSignByTimestampAccount($account, $timestamp) {
		return md5($account . $timestamp . SIGN_KEY);
	}

	/**
	 * @param $params
	 * 通过参数计算sign
	 */
	public static function getqqGameSign($string, $path = '/v3/user/is_login', $appkey = 'QzCv1clZH7lNSKea&') {
		$url = parse_url($string, PHP_URL_QUERY);
		$url_arr = explode('&', $url);
		$params = [];
		if (!empty($url_arr) && is_array($url_arr)) {
			foreach ($url_arr as $k => $v) {
				$arr = explode('=', $v);
				$params[$arr[0]] = $arr[1];
			}
		}
		ksort($params);
		$str = '';
		$i = 0;
		foreach ($params as $k => $v) {
			if ($i === 0) {
				$str .= $k . '=' . $v;
			} else {
				$str .= '&' . $k . '=' . $v;
			}
			$i++;
		}
		$string = GET . '&' . rawurlencode($path) . '&' . rawurlencode($str);
		$secret = hash_hmac('sha1', $string, $appkey, true);
		return rawurlencode(base64_encode($secret));
	}

	public static function checkVersionIncludeFile($fileName = 'ssjj') {

		$version = isset($_GET['bundle_version']) ? $_GET['bundle_version'] : '';
		$list = explode('.', $version);
		if (isset($list[0]) && isset($list[1])) {
			$name = 'ssjj/' . $fileName . $list[0] . $list[1] . '.php';
			if (file_exists($name)) {
				return $fileName . $list[0] . $list[1];
			} else {
				return $fileName;
			}
		} else {
			return $fileName;
		}

	}

	/**
	 * @param $url
	 * @param null $post
	 * @param int $timeout
	 * @return mixed
	 */
	public static function quickCurl($url, $post = null, $timeout = 3) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);

		if ($post != null) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}

	/**
	 * @param $url
	 * @param null $post
	 * @param int $timeout
	 * @return mixed
	 */
	public static function quickCurlGet($url, $post = null, $timeout = 3) {
		$ch = curl_init();
		$params = http_build_query($post);
		$url = $url . '?' . $params;
		curl_setopt($ch, CURLOPT_URL, $url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}

	/**
	 *
	 */
	public static function getChannel() {
		$country = $_GET['country'];
		$language = $_GET['language'];
		if (empty($country) || empty($language)) {
			return $_GET['channel'];
		}
		$language = Mysql::getInstance()->queryOne("select id from `language` where code='$language'");
		$country = Mysql::getInstance()->queryOne("select id from `country` where code='$country'");
		print_r($language);
	}

}
