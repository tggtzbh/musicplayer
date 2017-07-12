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
        $url="https://c.y.qq.com/v8/fcg-bin/fcg_v8_toplist_cp.fcg?topid=26&format=jsonp&inCharset=utf8&outCharset=utf-8&notice=0&platform=yqq&needNewCode=0&jsonpCallback=_jsonposi8sc18xyb";
        $ret=file_get_contents($url);
        $ret=preg_replace("/^ _jsonposi8sc18xyb\(/","",$ret);
        $ret=preg_replace("/\)$/","",$ret);
        $ret=json_decode($ret,true);
        $ret=$ret['songlist'];
        $ret_array=array();
        for($i=0;$i<30;$i++)
        {
            $ret_array_a=array();
            $ret_array_a['name']=$ret[$i]['data']['albumname'];
            $siner_arr=array();
            foreach ($ret[$i]['data']['singer'] as $siner_item)
            {
                $siner_arr[]=$siner_item['name'];
            }
            $ret_array_a['singer']=implode(',',$siner_arr);
            $ret_array_a['hash']=$ret[$i]['data']['songid']."-".$ret[$i]['data']['albumid'];
            $ret_array_a['from']='qqmusic';
            $ret_array[]=$ret_array_a;
        }
        return $ret_array;
    }

    static function getmusicurl($hash)
    {
        $hash=explode("-",$hash);
        $music_lyrc = file_get_contents("https://api.darlin.me/music/lyric/".$hash[0]."?callback=_jsonpaqbojw0dgdt");
        $music_lyrc=preg_replace("/^_jsonpaqbojw0dgdt\(/","",$music_lyrc);
        $music_lyrc=preg_replace("/\)$/","",$music_lyrc);
        $music_lyrc=json_decode($music_lyrc,true);
        $music_lyrc=base64_decode($music_lyrc['lyric']);
        $music_lyrc_array=array();
        $music_lyrc=explode("\n",$music_lyrc);
        $musicitem = array();
        foreach ($music_lyrc as $music_lyrc_item)
        {
            if(strpos($music_lyrc_item,"[ti:")===0)
            {
                $musicitem['name'] = str_replace("[ti:",'',$music_lyrc_item);
                $musicitem['name'] = str_replace("]",'',$musicitem['name']);
            }
            else if(strpos($music_lyrc_item,"[ar:")===0)
            {
                $musicitem['singer'] = str_replace("[ar:",'',$music_lyrc_item);
                $musicitem['singer'] = str_replace("]",'',$musicitem['singer']);
            }
            else if(strpos($music_lyrc_item,"[al:")===0)
            {

            }
            else if(strpos($music_lyrc_item,"[by:")===0)
            {

            }
            else if(strpos($music_lyrc_item,"[offset:")===0)
            {

            }
            else
            {
                $music_lyrc_array[]=$music_lyrc_item;
            }
        }
        $musicitem['extName']=$musicitem['name']."-".$musicitem['singer'] ;
        $musicitem['imgUrl']="http://imgcache.qq.com/music/photo/album_300/".($hash[1]%100)."/300_albumpic_".$hash[1]."_0.jpg";
        $musicitem['url']="http://ws.stream.qqmusic.qq.com/".$hash[0].".m4a?fromtag=46";
        $musicitem['lyric']=implode("\n",$music_lyrc_array);

        return $musicitem;
    }
}