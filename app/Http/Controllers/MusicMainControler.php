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
    public function getIndexView() //��ȡ�����ļ�
    {
        return view("music_main");
    }

    public function getIndexList() //��ȡ�����ļ�
    {
        $r=QQMusic::get_index();
        return json_encode($r);
    }

    public function getMusicList() //��ȡ�����б�
    {
        //$res=Netdata::getkugouindex();
        $res=QQMusic::get_index();
        return json_encode($res);
    }

    public function getMusicResouse(Request $request) //��ȡ������Դ��Ϣ
    {
        $hash=$request->input("hash");
        $from=$request->input("from");
        $res=Netdata::getmusicurl($hash,$from);
        return json_encode($res);
    }
}