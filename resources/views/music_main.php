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
        <div class="musicfilename" v-on:click="showcontrol=!showcontrol">
            {{ musiclist[playindex].name }} - {{ musiclist[playindex].singer }}
        </div>
        <div class="button right" v-on:click="showcontrol=!showcontrol">
            <i class="fa fa-bars"></i>
        </div>
    </div>

    <!-- 播放列表 -->
    <div class="count-box" v-show="!showcontrol" v-for="(musicitem, musicitem_index) in musiclist" v-on:dblclick="playlistindex(musicitem_index)">
        <div class="count-box-index">{{musicitem_index+1 | formate_count_box_index}}</div>
        <div class="count-box-info">
            <!--<p class="count-box-title">{{ musicitem.name }}</p>-->
            <p class="count-box-name">{{ musicitem.singer }} - {{ musicitem.name }}</p>
        </div>
        <div class="count-box-button">
            <i class="icson-angle-down"></i>
        </div>
    </div>

    <!-- 曲库 -->
    <div class="songbook">
        <div class="search_bar">

        </div>

    </div>

    <div style="height: 4.2143rem;">
        <!---_底部空白高度-->
    </div>
    <div id="maincontrol"  v-show="showcontrol">
        <div class="row">
            <div class="button" v-on:click="showcontrol=!showcontrol">
                <i class="fa fa-arrow-down"></i>
            </div>
        </div>
        <div class="row main" style="flex-grow: 1;">
            <div class="row">
                <div class="audio-info-img-main">
                    <img class="audio-info-img" v-bind:src="playimgsrc"/>
                    <div class="audio-info-img-top"></div>
                </div>
                <br/>
                音量：{{ playvolume }}<br/>
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
            <div class="musicfilename">
                {{ musiclist[playindex].name }} - {{ musiclist[playindex].singer }}
            </div>
        </div>

    </div>
</div>
<script src="./js/vue.js"></script>
<script src="./js/vue-resource.js"></script>
<script src="./js/player_control.js"></script>
<script src="./js/player_vue.js"></script>

</body>
</html>