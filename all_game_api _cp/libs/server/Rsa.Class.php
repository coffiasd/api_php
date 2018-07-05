<?php

class Rsa {
    public function __construct() {
        //$this->privateKey = openssl_pkey_get_private($files['prikey']);
        //$this->publicKey = openssl_pkey_get_public($files['pubkey']);
    }

    public function sign($data, $signType = "RSA") {
        //$priKey = \Yii::$app->params['channel_keys']['ali_pay_pri'];
        $res = openssl_get_privatekey($priKey);
        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');

        if ("RSA2" == $signType) {
            openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
        } else {
            openssl_sign($data, $sign, $res);
        }
        openssl_free_key($res);
        $sign = base64_encode($sign);
        return $sign;
    }

    function rsaVerify($prestr, $sign) {
        $sign = base64_decode($sign);
        $public_key = \Yii::$app->params['channel_keys']['ali_pay_pub'];
        $pkeyid = openssl_get_publickey($public_key);
        if ($pkeyid) {
            $verify = openssl_verify($prestr, $sign, $pkeyid);
            openssl_free_key($pkeyid);
        }
        if ($verify == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function encrypt($orginData = '', $maxlength = 64) {
        $length = strlen($orginData);
        if ($length > $maxlength) {
            $output = '';
            while ($orginData) {
                $input = substr($orginData, 0, $maxlength);
                $orginData = substr($orginData, $maxlength);
                openssl_private_encrypt($input, $encrypted, $this->privateKey);
                $output .= base64_encode($encrypted);
            }
            return $output;

        } else {
            return $this->encryptEach($orginData);
        }
    }

    public function encryptEach($originData = '') {
        if (openssl_private_encrypt($originData, $encryptData, $this->privateKey)) {
            return base64_encode($encryptData);
        }

        return false;
    }

    public function decrypt($encryptData = '') {
        $encryptData = base64_decode($encryptData);
        if (openssl_private_decrypt($encryptData, $decryptData, $this->privateKey)) {
            return $decryptData;
        }

        return false;
    }

    public function encrypt_pub($originData = '') {
        if (openssl_public_encrypt($originData, $encryptData, $this->publicKey)) {
            return base64_encode($encryptData);
        }

        return false;
    }

    public function decrypt_pub($encryptData = '') {
        $encryptData = base64_decode($encryptData);
        if (openssl_public_decrypt($encryptData, $decryptData, $this->publicKey)) {
            return $decryptData;
        }

        return false;
    }

    public function rsa_sign($data, $rsaPrivateKeyFilePath) {
        $priKey = file_get_contents($rsaPrivateKeyFilePath);
        $res = openssl_get_privatekey($priKey);
        openssl_sign($data, $sign, $res);
        openssl_free_key($res);
        $sign = base64_encode($sign);
        return $sign;
    }
    public function sign_request($params, $rsaPrivateKeyFilePath) {
        return $this->rsa_sign($this->getSignContent($params), $rsaPrivateKeyFilePath);
    }
    public function sign_response($bizContent, $charset, $rsaPrivateKeyFilePath) {
        $sign = $this->rsa_sign($bizContent, $rsaPrivateKeyFilePath);
        $response = "<?xml version=\"1.0\" encoding=\"$charset\"?><alipay><response>$bizContent</response><sign>$sign</sign><sign_type>RSA</sign_type></alipay>";
        return $response;
    }
    public function rsa_verify($data, $sign, $rsaPublicKeyFilePath) {
        // 读取公钥文件
        $pubKey = file_get_contents($rsaPublicKeyFilePath);

        // 转换为openssl格式密钥
        $res = openssl_get_publickey($pubKey);

        // 调用openssl内置方法验签，返回bool值
        $result = (bool) openssl_verify($data, base64_decode($sign), $res);

        // 释放资源
        openssl_free_key($res);

        return $result;
    }
    public function rsaCheckV2($params, $rsaPublicKeyFilePath) {
        $sign = $params['sign'];
        $params['sign'] = null;

        return $this->rsa_verify($this->getSignContent($params), $sign, $rsaPublicKeyFilePath);
    }
    protected function getSignContent($params) {
        ksort($params);

        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }
        unset($k, $v);
        return $stringToBeSigned;
    }

    /**
     * 校验$value是否非空
     * if not set ,return true;
     * if is null , return true;
     */
    protected function checkEmpty($value) {
        if (!isset($value)) {
            return true;
        }

        if ($value === null) {
            return true;
        }

        if (trim($value) === "") {
            return true;
        }

        return false;
    }
    public function getPublicKeyStr($pub_pem_path) {
        $content = file_get_contents($pub_pem_path);
        $content = str_replace("-----BEGIN PUBLIC KEY-----", "", $content);
        $content = str_replace("-----END PUBLIC KEY-----", "", $content);
        $content = str_replace("\r", "", $content);
        $content = str_replace("\n", "", $content);
        return $content;
    }
}