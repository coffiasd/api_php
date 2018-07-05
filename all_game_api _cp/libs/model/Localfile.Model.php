<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/23
 * Time: 13:53
 */
class LocalfileModel
{
    //写文件
    public static function wSsjj($res = null, $name = 'default')
    {
        if (!empty($res) && is_array($res)) {
            $content = "<?php \r\n return \r\n";
            $content .= var_export($res, true) . ';';
            $f = fopen('ssjj/' . $name . '.php', 'w+');
            fwrite($f, $content);
            fclose($f);
        }
    }

    //读文件
    public static function rSsjj($name = 'default')
    {
        if (file_exists("ssjj/$name.php")) {
            return include "ssjj/$name.php";
        } else {
            return [];
        }
    }

    //渠道关闭
    public static function newChannelClose()
    {
        $sql = "select id as channel,login,loginmsg from channels";
        $res = mysql::getInstance()->queryAll($sql);
        LocalfileModel::wSsjj($res, 'channelclose');
    }

    //更新配置
    public static function newConfig()
    {
        $sql = "select `key` as `name`,`value`,`group`,`channels` from online_configs where active=1";
        $res = mysql::getInstance()->queryAll($sql);
        LocalfileModel::wSsjj($res, 'config');
    }

    //更新公告
    public static function newBoard()
    {
        $sql = "select `id`,`order`, `title`, `type`, `content`, `channels`, `name`, `tag`,`not_in` from boards where active=1";
        $res = mysql::getInstance()->queryAll($sql);
        LocalfileModel::wSsjj($res, 'boards');
    }

    //更新产品
    public static function newProduct()
    {
        $sql = "select `order` as id ,id as product_id,name as product_name,gold as diamond_num,diamond_num_symbol,send_gold as diamond_num_1,currency as money_type,price as money_num,label as flag,platform from products limit 22";
        $res = mysql::getInstance()->queryAll($sql);
        LocalfileModel::wSsjj($res, 'product');
    }

    //更新区服列表
    public static function newServer()
    {
        $sql = "select `id`,`sid`,`name`,`address`,`port`,`msg`,`channel`,`tag`,`state` as `status` from servers where active=1";
        $res = mysql::getInstance()->queryAll($sql);
        LocalfileModel::wSsjj($res, 'server');
    }

    //ip列表
    public static function newIpwhite()
    {
        $sql = "select ip from ip_white";
        $res = mysql::getInstance()->queryAll($sql);
        $iplist = [];
        if (!empty($res) && is_array($res)) {
            foreach ($res as $v) {
                $iplist[] = $v['ip'];
            }
        }
        LocalfileModel::wSsjj($iplist, 'ipwhite');
    }

    //获得ip白名单
    public static function getIpwhite()
    {
        return LocalfileModel::rSsjj('ipwhite');
    }

    //获得配置
    public static function getConfig($channel)
    {
        $ret = [];
        $config = LocalfileModel::rSsjj('config');
        if (!empty($config) && is_array($config)) {
            foreach ($config as $k => $v) {

                $keywords = ',' . $channel . ',';
                if (strstr($v['channels'], $keywords)) {
                    unset($v['channels']);
                    $ret[] = $v;
                }

            }
            return $ret;
        } else {
            return [];
        }
    }

    //获得公告
    public static function getBoard($channel)
    {
        $ret = [];
        $boards = LocalfileModel::rSsjj('boards');
        if (!empty($boards) && is_array($boards)) {
            foreach ($boards as $k => $v) {
                $keywords = ',' . $channel . ',';
                if (strstr($v['channels'], $keywords)) {
                    unset($v['channels']);
                    $ret[] = $v;
                }
            }
            return $ret;
        } else {
            return [];
        }
    }

    //获得产品
    public static function getProduct()
    {
        return LocalfileModel::rSsjj('product');
    }

    //获得区服列表
    public static function getServer($channel)
    {
        $ret = [];
        $servers = LocalfileModel::rSsjj('server');
        if (!empty($servers) && is_array($servers)) {
            foreach ($servers as $k => $v) {

                $keywords = ',' . $channel . ',';
                if (strstr($v['channel'], $keywords)) {
                    $ret[] = $v;
                }
            }
            return $ret;
        } else {
            return [];
        }
    }

    //通过id获得server
    public static function getServerById($id)
    {
        $servers = LocalfileModel::rSsjj('server');
        if (!empty($servers) && is_array($servers)) {
            foreach ($servers as $k => $v) {
                if ($v['id'] == $id) {
                    return $v;
                }
            }
        }
        return [];
    }

    //获得渠道关闭
    public static function getChannelclose($channel)
    {
        $channel_close = LocalfileModel::rSsjj('channelclose');
        if (!empty($channel_close) && is_array($channel_close)) {
            foreach ($channel_close as $k => $v) {
                if ($v['channel'] == $channel) {
                    return $v;
                }
            }
        } else {
            return [];
        }
    }
}