<?php
//包含工具类
include("Util.php");

/*
 * 消息发布者者
 */

class HttpProducer
{
    //签名
    private static $signature = "Signature";
    //在MQ控制台创建的Producer ID
    private static $producerid = "ProducerID";
    //阿里云身份验证码
    private static $aks = "AccessKey";
    //配置信息
    private static $configs = null;

    //构造函数
    function __construct()
    {
        //读取配置信息
        $this::$configs = parse_ini_file("config.ini");
    }

    //计算md5
    private function md5($str)
    {
        return md5($str);
    }

    //发布消息流程
    public function process($body)
    {
        //获取Topic
        $topic = $this::$configs["Topic"];
        //获取保存Topic的URL路径
        $url = $this::$configs["URL"];
        //读取阿里云访问码
        $ak = $this::$configs["Ak"];
        //读取阿里云密钥
        $sk = $this::$configs["Sk"];
        //读取Producer ID
        $pid = $this::$configs["ProducerID"];
        //HTTP请求体内容
        $body = utf8_encode($body);
        $newline = "\n";
        //构造工具对象
        $util = new Util();
        //计算时间戳
        $date = time() * 1000;
        //POST请求url
        $postUrl = $url . "/message/?topic=" . $topic . "&time=" . $date . "&tag=http&key=http";
        //签名字符串
        $signString = $topic . $newline . $pid . $newline . $this->md5($body) . $newline . $date;
        //计算签名
        $sign = $util->calSignatue($signString, $sk);
        //初始化网络通信模块
        $ch = curl_init();
        //构造签名标记
        $signFlag = $this::$signature . ":" . $sign;
        //构造密钥标记
        $akFlag = $this::$aks . ":" . $ak;
        //标记
        $producerFlag = $this::$producerid . ":" . $pid;
        //构造HTTP请求头部内容类型标记
        $contentFlag = "Content-Type:text/html;charset=UTF-8";
        //构造HTTP请求头部
        $headers = array(
            $signFlag,
            $akFlag,
            $producerFlag,
            $contentFlag,
        );
        //设置HTTP头部内容
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //设置HTTP请求类型,此处为POST
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        //设置HTTP请求的URL
        curl_setopt($ch, CURLOPT_URL, $postUrl);
        //设置HTTP请求的body
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //构造执行环境
        //开始发送HTTP请求
        curl_exec($ch);
        //获取请求应答消息
        //打印请求应答结果
        //关闭连接
        curl_close($ch);
    }

    public function noreplay($body)
    {
        //获取Topic
        $topic = $this::$configs["Topic"];
        //获取保存Topic的URL路径
        $url = $this::$configs["URL"];
        //读取阿里云访问码
        $ak = $this::$configs["Ak"];
        //读取阿里云密钥
        $sk = $this::$configs["Sk"];
        //读取Producer ID
        $pid = $this::$configs["ProducerID"];
        //HTTP请求体内容
        $body = utf8_encode($body);
        $newline = "\n";
        //构造工具对象
        $util = new Util();
        //计算时间戳
        $date = time() * 1000;
        //POST请求url
        $postUrl = $url . "/message/?topic=" . $topic . "&time=" . $date . "&tag=http&key=http";
        //签名字符串
        $signString = $topic . $newline . $pid . $newline . $this->md5($body) . $newline . $date;
        //计算签名
        $sign = $util->calSignatue($signString, $sk);
        //构造签名标记
        $signFlag = $this::$signature . ":" . $sign;
        //构造密钥标记
        $akFlag = $this::$aks . ":" . $ak;
        //标记
        $producerFlag = $this::$producerid . ":" . $pid;
        //构造HTTP请求头部内容类型标记
        $contentFlag = "Content-Type:text/html;charset=UTF-8";
        $headers = array(
            $signFlag,
            $akFlag,
            $producerFlag,
            $contentFlag,
        );

        




    }
}

?>