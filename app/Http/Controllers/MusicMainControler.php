<?php
/**
 * Created by PhpStorm.
 * User: tggtz
 * Date: 2017/7/5
 * Time: 13:05
 */

namespace App\Http\Controllers;


use App\Netdata;
use App\QQMusic;
use Illuminate\Http\Request;

class MusicMainControler extends Controller
{
    public function getIndexView() //获取界面文件
    {
        return view("music_main");
    }

    public function getIndexList() //获取界面文件
    {
        $r=QQMusic::get_index();
        return json_encode($r);
    }

    public function getMusicList() //获取播放列表
    {
        //$res=Netdata::getkugouindex();
        $res=QQMusic::get_index();
        return json_encode($res);
    }

    public function getMusicResouse(Request $request) //获取歌曲资源信息
    {
        $hash=$request->input("hash");
        $from=$request->input("from");
        $res=Netdata::getmusicurl($hash,$from);
        return json_encode($res);
    }
}