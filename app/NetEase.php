<?php
/**
 * Created by PhpStorm.
 * User: tggtz
 * Date: 2017/7/7
 * Time: 17:37
 */

namespace App;


class NetEase
{
    static function curl_get($url)
    {
        $refer = "http://music.163.com/";
        $header[] = "Cookie: " . "appver=1.5.0.75771;";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_REFERER, $refer);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    static function get_index()
    {
        //未启用
        $htmllist=array();
        return $htmllist;
    }

    static function getmusicurl($hash)
    {
        //未启用
        $musiciteminfotext = file_get_contents('http://www.kugou.com/yy/index.php?r=play/getdata&hash='.$hash);
        $musicitem = array();
        $musiciteminfojson=json_decode($musiciteminfotext,true);
        $musiciteminfojson=$musiciteminfojson['data'];
        $musicitem['name'] = $musiciteminfojson['song_name'];
        $musicitem['singer'] = $musiciteminfojson['author_name'];
        $musicitem['extName']=$musiciteminfojson['audio_name'];
        $musicitem['imgUrl']=$musiciteminfojson['img'];
        $musicitem['url']=$musiciteminfojson['play_url'];
        $musicitem['lyric']=$musiciteminfojson['lyrics'];
        return $musicitem;
    }

    static function get_search($name)
    {
        //未启用
        $ret=array();
        return $ret;
    }

    static function get_songsheet_index()
    {
        $a=self::curl_get("http://music.163.com/discover");
        $a=explode("<li>\n<div class=\"u-cover u-cover-1\">",$a);
        $a[sizeof($a)-1]=substr($a[sizeof($a)-1],0,strpos($a[sizeof($a)-1],"</li>")+6);
        unset($a[0]);
        $music_list_array=array();
        foreach ($a as $list_item)
        {
            $a_match='/^\s*<img src=\"(?<imgurl>([^\"])*)([^>])*>\s*<a\s*title=\"(?<name>([^\"]*))\"\s*href=\"\/(?<href>([^\"]*))/';
            preg_match($a_match,$list_item,$match);
            $list_item=array();
            $list_item['img']=$match['imgurl'];
            $list_item['name']=$match['name'];
            $list_item['hash']=$match['href'];
            if(substr($list_item['hash'],0,2)!="dj")
            {
                $list_item['hash']=str_replace("playlist?id=","",$list_item['hash']);
                $list_item['hash']=(int)$list_item['hash'];
                $music_list_array[]=$list_item;
            }
            $list_item['from']=$match['netease'];
        }
        return $music_list_array;
    }

}