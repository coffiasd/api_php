<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/26
 * Time: 20:54
 */

class ProductModel{

    public static function checkPrice($pid,$price,$params=[])
    {
        $sql = "select price,gold,send_gold from products where id=$pid";
        $res = mysql::getInstance()->queryOne($sql);
        if($res) {
            if ($res['price'] == $price) {
                return $res['gold'] + $res['send_gold'];
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public static function getProductbyId($id){
        $sql = "select * from products where id=$id";
        $res = mysql::getInstance()->queryOne($sql);
        if($res){
            return $res;
        }else{
            return '';
        }
    }

}
