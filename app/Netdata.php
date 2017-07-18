<?php
/**
 * Created by PhpStorm.
 * User: tggtz
 * Date: 2017/7/5
 * Time: 13:36
 */

namespace App;


class Netdata
{
    static function get_index($from='')
    {
        $htmllist=array();
        if($from=="kugou" || $from=='')
        {
            $htmllist=array_merge($htmllist,KuGou::get_index($from));
        }
        if($from=="qqmusic" || $from=='')
        {
            $htmllist=array_merge($htmllist,QQMusic::get_index($from));
        }
        return $htmllist;
    }

    static function getmusicurl($hash,$from)
    {
        if($from=="kugou")
        {
            $musicitem=KuGou::getmusicurl($hash);
        }
        if($from=="qqmusic")
        {
            $musicitem=QQMusic::getmusicurl($hash);
        }
        return $musicitem;
    }

    static function get_playlist()
    {
        return array();
    }

    static function get_search($keyword)
    {
        $musiclist=KuGou::get_search($keyword);
        return $musiclist;
    }

}