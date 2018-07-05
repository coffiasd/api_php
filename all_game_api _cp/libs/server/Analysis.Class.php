<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/15
 * Time: 20:13
 */

class Analysis{

    public function __construct()
    {
        $this->AppId = '478746772833';
        $this->Token = '18acd3f0a626e9debeee75923f7cbd30';
    }

    public function request($json)
    {
        $this->doHttpRequest($json);
    }

    private function doHttpRequest($post = null, $ssl = false, $timeout = 6) {
        $url = "https://log.rts.dp.uc.cn:8083/api/v2_1";
        //初始化curl
        $ch = curl_init();
        //设置请求参数
        curl_setopt($ch, CURLOPT_URL, $url); //设置访问地址

        //设置content-type
        $content_type = [
            'X-dp-app-id'=>$this->AppId,
            'X-dp-api-token'=>$this->Token,
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $content_type);

        //设置ssl
        if ($ssl) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); //post到https
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        //设置post
        if ($post != null) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); //跟随页面的跳转
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout); //超时设置

        //执行请求，读取返回数据
        $response = curl_exec($ch);

        //关闭curl
        curl_close($ch);
        //返回请求结果
        return $response;
    }
}