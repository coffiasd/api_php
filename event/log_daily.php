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

//设备新增 活跃设备 新增账号 活跃账号 新增游客 活跃游客 (充值总额 充值人数) 第一次充值 , 当天注册充值
//$install = DeviceLoginLogModel::getTodayInstall();
//$login = DeviceLoginLogModel::getTodayLogin();

while(true){
        //非游客
        $new = AccountLoginLogModel::getTodayInstall();
        toDaily($new, 'new_register');
        $login = $account_login = AccountLoginLogModel::getTodayLogin();
        toDaily($login, 'login_account');

        //游客
        $new_guest = AccountLoginLogModel::getTodayInstall(true);
        toDaily($new_guest, 'new_guest');
        $account_login_guest = AccountLoginLogModel::getTodayLogin(true);
        toDaily($account_login_guest, 'login_guest');

        //充值
        $all_pay = PaymentModel::getTodayPay();
        toDaily($all_pay,'deposit_amount');
        $pay_num = PaymentModel::getTodayPayNum();
        toDaily($pay_num,'deposit_account');
        sleep(5);
}

//充值

//更新到daily表
function toDaily($arr = [], $c_name= '') {
        if (!empty($arr) && is_array($arr)) {
                foreach ($arr as $key => $value) {
                        $col = [];
                        $num = $value['num'];
                        $channel = $value['channel'];
                        $server = $value['sid'];
                        $date = $value['date'];
                        $col = ["$c_name" => $num];
                        $info = DailyModel::getDaily($server, $channel, $date);
                        if (false === $info) {
                                //insert
                                DailyModel::newDaily($server, $channel, $col, $date);
                        } else {
                                //update
                                DailyModel::updateDaily($server, $channel, $col, $date);
                        }
                }
        }
}
~