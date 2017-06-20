var media = document.getElementById("media");
var media_intervalno=0;
var vm = new Vue({
    el: '#example',
    data: {
        "musiclist":{},  //播放列表
        "playindex":0,   //当前播放歌曲
        "playsrc":"",    //当前播放歌曲源文件
        "playimgsrc":"",    //当前播放歌曲源文件
        "playmod":"list",//播放顺序 list-》顺序播放
        "playvolume":1,  //播放音量
        "play_time_full":60, //音频长度
        "play_time_complete":0  //已播放进度
    },
    computed: {

    },
    filters: {
        formate_time_s: function (value) {
            let s=parseInt(value % 60);
            if( s < 10) {
                s= "0" + s;
            }
            return parseInt(value / 60) + ":" + s;
        },
        formate_count_box_index: function (value) {
            let s=parseInt(value);
            if( s < 10) {
                s= "0" + s;
            }
            return s;
        }
    },
    mounted: function () {
        this.$http({
            method:'GET',
            url:'index.php',
            data:{}
            //emulateJSON: true
        }).then(response => {
            play.publicfun.init();
            let res=eval(response.data);
            this.musiclist=res;
            this.playmusic(this.musiclist[this.playindex].hash);
            media_intervalno=setInterval("vm.playInterval()",100);
        }, response => {
            // error callback
        });
    },
    methods: {
        playAudio: function (event) {
            if(media.paused) {
                media.play();
            } else {
                //media.currentTime=media.duration*0.5;
                media.pause();
            }
        },
        playnext:function(event)
        {
            if(this.playmod=="list")
            {
                //顺序播放
                this.playindex=this.playindex+1;
                this.playindex==this.musiclist.length? this.playindex=0:null;
            }
            this.playmusic(this.musiclist[this.playindex].hash);
        },
        playvolumeset:function(volume)
        {
            if(media.muted==1)
            {
                media.muted=0;
            }
            else
            {
                media.muted=1;
            }
            /*if(volume<0)
                volume=0;
            if(volume>1)
                volume=1;
            media.volume=volume;
            this.playvolume=volume;*/
        },
        playInterval:function(){
            if(media.ended)
            {
                this.playnext();
            }
            else
            {
                this.play_time_full=parseInt(media.duration);
                this.play_time_complete=parseInt(media.currentTime);
            }
        },
        playmusic:function(hash)
        {
            this.$http.post('index.php',{ hash: hash} , {emulateJSON:true} ).then(
                response => {
                    let res=JSON.parse(response.body);
                    this.playsrc=res.url;
                    this.playimgsrc=res.imgUrl;
                    media.setAttribute('src',this.playsrc);
                    media.play();
                },
                response => {
                    // error callback
                }
            );
        }
    }
})
