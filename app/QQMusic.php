<?php
/**
 * Created by PhpStorm.
 * User: tggtz
 * Date: 2017/7/7
 * Time: 17:37
 */

namespace App;


class QQMusic
{
    static function get_index()
    {
        $url="http://c.y.qq.com/v8/fcg-bin/fcg_myqq_toplist.fcg?g_tk=5381&uin=0&format=jsonp&inCharset=utf-8&outCharset=utf-8&notice=0&platform=h5&needNewCode=1&_=1499420149279&jsonpCallback=_jsonpksngnp9cjy";
        $ret=file_get_contents($url);
        $ret=preg_replace("/^_jsonpksngnp9cjy\(/","",$ret);
        $ret=preg_replace("/\)$/","",$ret);
        $ret=json_decode($ret,true);
        var_dump($ret);
    }
}