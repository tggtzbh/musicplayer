<?php
/**
 * Created by PhpStorm.
 * User: tggtz
 * Date: 2017/7/5
 * Time: 13:05
 */

namespace App\Http\Controllers;


use App\Netdata;
use Illuminate\Http\Request;

class MusicMainControler extends Controller
{
    public function getIndexView() //��ȡ�����ļ�
    {
        return view("music_main");
    }

    public function getMusicList() //��ȡ�����б�
    {
        $res=Netdata::getkugouindex();
        return json_encode($res);
    }

    public function getMusicResouse(Request $request) //��ȡ������Դ��Ϣ
    {
        $hash=$request->input("hash");
        $res=Netdata::getmusicurl($hash);
        return json_encode($res);
    }
}