<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>榴莲音乐-音乐有味道</title>
    <link rel="stylesheet" type="text/css" href="./css/player_control.css">
    <link rel="stylesheet" type="text/css" href="./css/font-awesome.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
</head>
<body>
<audio id="media" src="">
    Your browser does not support the audio element.
</audio>
<div id="example">
    <div id="controlbox" v-show="false==showcontrol" style="">
        <div class="button" v-on:click="playAudio">
            <i class="fa fa-play" v-bind:class="{ 'fa-play': play_pause , 'fa-pause': !play_pause}"></i>
        </div>
        <div class="button" v-on:click="playnext">
            <i class="fa fa-forward"></i>
        </div>
        <div class="musicfilename" v-on:click="showcontrol=!showcontrol" v-if="musiclist.length>0">
            {{ musiclist[playindex].singer }} - {{ musiclist[playindex].name }}
        </div>
        <div class="musicfilename" v-if="musiclist.length==0">
            榴莲音乐-音乐有味道
        </div>
        <div class="button right" v-on:click="showlist=!showlist">
            <i class="fa fa-bars"></i>
        </div>
    </div>

    <!-- 曲库 -->
    <div class="songbook" v-show="!showcontrol && !showlist">
        <div class="search_bar">
            <div class="search_bar_input">
                    <input id="search_keyword" name="keyword" type="text" placeholder="输入歌名/歌手后按下回车进行搜索" v-on:keyup.enter="search">
            </div>
            <img src="./img/banner.jpg" alt="">
        </div>
        <div class="songbook_list">
            <div class="count-box" style="" v-for="(musicitem, musicitem_index) in indexlist">
                <div class="count-box-index"> {{musicitem_index+1 | formate_count_box_index}}</div>
                <div class="count-box-info"><p class="count-box-name" v-on:dblclick="addToPlaylist(musicitem_index,true)">{{ musicitem.singer }} - {{ musicitem.name }}</p></div>
                <div class="count-box-button">
                    <i class="fa fa-plus" v-on:click="addToPlaylist(musicitem_index,false)"></i>
                    <i class="fa fa-play" v-on:click="addToPlaylist(musicitem_index,true)"></i>
                </div>
            </div>
            <!--<ul>
                <li v-for="(musicitem, musicitem_index) in indexlist">
                    {{musicitem_index+1 | formate_count_box_index}} - {{musicitem.name }}
                </li>
            </ul>-->
        </div>

    </div>


    <!-- 播放列表 -->
    <div class="count-box" v-show="!showcontrol && showlist" v-for="(musicitem, musicitem_index) in musiclist" v-on:dblclick="playlistindex(musicitem_index)">
        <div class="count-box-index">{{musicitem_index+1 | formate_count_box_index}}</div>
        <div class="count-box-info">
            <!--<p class="count-box-title">{{ musicitem.name }}</p>-->
            <p class="count-box-name">{{ musicitem.singer }} - {{ musicitem.name }}</p>
        </div>
        <div class="count-box-button">
            <i class="fa fa-times" aria-hidden="true" v-on:click="removeFromPlaylist(musicitem_index)"></i>
        </div>
    </div>

    <div id="maincontrol"  v-show="showcontrol">
        <div class="row">
            <div class="button" v-on:click="showcontrol=!showcontrol">
                <i class="fa fa-arrow-down"></i>
            </div>
            <div class="button" v-if="musiclist[playindex]" v-on:click="showcontrol=!showcontrol" style="float: right">
                <img v-bind:src="'img/playericon/'+musiclist[playindex].from+'.png'" style="width: 40px"/>
            </div>
        </div>
        <div class="row main" style="flex-grow: 1;">
            <div class="row">
                <div class="audio-info-img-main">
                    <img class="audio-info-img" v-bind:src="playimgsrc"/>
                    <div class="audio-info-img-top"></div>
                </div>
                <br/>
                总长度：{{play_time_full | formate_time_s}}<br/>
                播放长度：{{play_time_complete | formate_time_s}}<br/>
                <div class="button" v-on:click="playmodchange">
                    <i class="fa" v-bind:class="{ 'fa-refresh': playmod=='list' , 'fa-random': playmod=='random'}"></i>
                </div>
                <div class="button" v-on:click="playpre">
                    <i class="fa fa-backward"></i>
                </div>
                <div class="button" v-on:click="playAudio">
                    <i class="fa" v-bind:class="{ 'fa-play': play_pause , 'fa-pause': !play_pause}"></i>
                </div>
                <div class="button" v-on:click="playnext">
                    <i class="fa fa-forward"></i>
                </div>
                <div class="button" v-on:click="playvolume_state_set">
                    <i class="fa" v-bind:class="{ 'fa-volume-off': !playvolume_state , 'fa-volume-up': playvolume_state}"></i>
                </div>
            </div>
            <div class="row">
                <div class="lyric-box">
                    <div class="lyric-content">
                        <p name="lyric" v-for="(lyric_str,lyric_id) in playingmusic.lyric" v-bind:id="'lyric_'+lyric_id" v-bind:tim="lyric_str.tim" style="color: rgb(255, 255, 255);">{{lyric_str.str}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div id="audio-progress" class="audio-progress" onclick="play.completion.bak_Click(event)">
                <div id="audio-progress-box" class="audio-progress-box" v-bind:style="{ width: (play_time_complete/play_time_full)*100+'%' }" >
                    <div class="audio-progress-touch" onmousedown="play.completion.main_mouseDown(event)" draggable="true"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="musicfilename" v-on:click="showcontrol=!showcontrol" v-if="musiclist.length>0">
                {{ musiclist[playindex].singer }} - {{ musiclist[playindex].name }}
            </div>
            <div class="musicfilename" v-if="musiclist.length==0">
                榴莲音乐-音乐有味道
            </div>
        </div>

    </div>
    <div style="height: 4.2143rem;">
        <!---_底部空白高度-->
    </div>
</div>
<script src="./js/vue.js"></script>
<script src="./js/vue-resource.js"></script>
<script src="./js/player_control.js"></script>
<script src="./js/player_vue.js"></script>

</body>
</html>