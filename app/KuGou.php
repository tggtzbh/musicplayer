<?php
/**
 * Created by PhpStorm.
 * User: tggtz
 * Date: 2017/7/7
 * Time: 17:37
 */

namespace App;


class KuGou
{
    static function get_index()
    {
        $htmltext = file_get_contents('http://m.kugou.com/');
        $htmltext_start=strpos($htmltext,"<!-- start panel-songslist -->");
        $htmltext_end=strpos($htmltext,"<!-- end panel-songslist -->");
        $htmltext = substr($htmltext,$htmltext_start,$htmltext_end - $htmltext_start);
        $htmltext = strip_tags($htmltext,"<li>");
        $htmllist = explode("<li class=\"panel-songslist-item\" id=\"songs_",$htmltext);
        unset($htmllist[0]);
        foreach ($htmllist as $htmllist_key => $htmllist_item)
        {
            $htmllist_item = str_replace("\" onclick=\"playerModule.playSong(this);\">","",$htmllist_item);
            $htmllist_item = str_replace("	","",$htmllist_item);
            $htmllist_item = str_replace("\n\n","\n",$htmllist_item);
            $htmllist_item = str_replace("\n</li>","",$htmllist_item);
            $htmllist_item = explode("\n",$htmllist_item);
            $htmllist_item = json_decode($htmllist_item[2],true);
            $musicitem=array();
            $musicitem['name']=substr($htmllist_item['filename'],strpos($htmllist_item['filename'],'-')+2);
            $musicitem['singer']=substr($htmllist_item['filename'],0,strpos($htmllist_item['filename'],'-')-1);
            //$musicitem['filesize']=$htmllist_item['filesize'];
            $musicitem['hash']=$htmllist_item['hash'];
            $musicitem['from']="kugou";
            $htmllist[$htmllist_key] = $musicitem;
        }
        $htmllist=array_merge($htmllist);
        return $htmllist;
    }

    static function getmusicurl($hash)
    {
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
        $ret=file_get_contents("http://songsearch.kugou.com/song_search_v2?callback=jQuery191034642999175022426_1489023388639&keyword=".$name."&page=1&pagesize=30&userid=-1&clientver=&platform=WebFilter&tag=em&filter=2&iscorrection=1&privilege_filter=0&_=1489023388641");
        /*$ret=preg_replace("/^jQuery191034642999175022426_1489023388639\(/","",$ret);
        $ret=preg_replace("/\)$/","",$ret);*/
        $ret=preg_replace("/^jQuery191034642999175022426_1489023388639\(/","",$ret);
        $ret=preg_replace("/\)$/","",$ret);
        $ret=json_decode($ret,true);
        $list=$ret['data']['lists'];
        $ret=array();
        foreach ($list as $list_item)
        {
            $ret_item=array();
            $ret_item['name']=strip_tags($list_item['SongName']);
            $ret_item['singer']=strip_tags($list_item['SingerName']);
            $ret_item['hash']=$list_item['FileHash'];
            $ret_item['from']="kugou";
            $ret[]=$ret_item;
        }
        return $ret;
    }
}