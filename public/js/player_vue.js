let media = document.getElementById("media");
let media_intervalno=0;
let vm = new Vue({
    el: '#example',
    data: {
        "musiclist":{},  //播放列表
        "indexlist":{},   //曲库展示列表
        "playindex":0,   //当前播放歌曲
        "playsrc":"",    //当前播放歌曲源文件
        "playimgsrc":"",    //当前播放歌曲源文件
        "playmod":"list",//播放顺序 list-》顺序播放
        "playvolume":1,  //播放音量
        "playvolume_state":true,  //播放是否静音 true-不静音 false-静音
        "play_time_full":60, //音频长度
        "play_time_complete":0,  //已播放进度
        "play_pause":false, //是否暂停
        "showcontrol":false, //是否显示主面板
        "showlist":false, //是否显示播放列表
        "playingmusic":{}  //当前正在播放的歌曲信息
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
            url:'getIndexList',
            data:{}
            //emulateJSON: true
        }).then(response => {
            play.publicfun.init();
            let res=eval(response.data);
            this.indexlist=res;
        }, response => {
            // error callback
        });

        this.$http({
            method:'GET',
            url:'getMusicList',
            data:{}
            //emulateJSON: true
        }).then(response => {
            play.publicfun.init();
            let res=eval(response.data);
            this.musiclist=res;
            if(this.musiclist.length>0)
            {
                this.playmusic(this.musiclist[this.playindex].hash,this.musiclist[this.playindex].from);
            }
        }, response => {
            // error callback
        });
    },
    methods: {
        search:function(){
          let key=document.getElementById("search_keyword").value;
          this.$http.post('getMusicSearchList',{ keyword: key} , {emulateJSON:true} ).then(
              response => {
                  //console.log(response.body);
                  vm.$set(vm,'indexlist',response.body);
                  document.getElementById("search_keyword").value="";
              },
              response => {
                  // error callback
              }
            );
        },
        playAudio: function (event) {
            if(media.paused) {
                media.play();
                this.play_pause=false;
            } else {
                //media.currentTime=media.duration*0.5;
                media.pause();
                this.play_pause=true;
            }
        },
        playmodchange:function(event) {
            switch (this.playmod)
            {
                case "list":
                    this.playmod="random";
                    break;
                case "random":
                    this.playmod="list";
                    break;
            }
        },
        playpre:function(event) {
            if(this.playmod=="list")
            {
                //顺序播放
                this.playindex=this.playindex-1;
                this.playindex==-1? this.playindex=this.musiclist.length-1:null;
            }
            this.playmusic(this.musiclist[this.playindex].hash,this.musiclist[this.playindex].from);
        },
        playnext:function(event) {
            if(this.playmod=="list")
            {
                //顺序播放
                this.playindex=this.playindex+1;
                this.playindex==this.musiclist.length? this.playindex=0:null;
            }
            if(this.musiclist.length<1)
            {
                return;
            }
            this.playmusic(this.musiclist[this.playindex].hash,this.musiclist[this.playindex].from);
        },
        playlistindex:function(index) {
            this.playindex=index;
            this.playmusic(this.musiclist[this.playindex].hash,this.musiclist[this.playindex].from);
        },
        playvolume_state_set:function(volume) {
            this.playvolume_state=!this.playvolume_state;
            if(media.muted==1)
            {
                media.muted=0;
            }
            else
            {
                media.muted=1;
            }
        },
        playInterval:function(){
            if(media.ended)
            {
                clearInterval(media_intervalno);
                media_intervalno=0;
                this.playnext();
            }
            else
            {
                this.play_time_full=parseInt(media.duration);
                this.play_time_complete=parseInt(media.currentTime);

                let list=document.getElementsByName("lyric");
                //alert( play_time_complete.toFixed(1));
                for (var k = 0, length = list.length; k < length; k++) {
                    if(parseFloat(list[k].getAttribute("tim")).toFixed(1)==media.currentTime.toFixed(1))
                    {
                        for(let j=0;j<list.length;j++)
                        {
                            list[j].style.color="rgb(255, 255, 255)";
                        }
                        list[k].style.color="rgb(166, 226, 45)";
                        let nu=parseInt(list[k].getAttribute("id").replace("lyric_",""));
                        if(nu>0)
                        {
                            nu--;
                            let ltricbox=document.getElementsByClassName("lyric-content");
                            //ltricbox[0].style.top=(210-22*nu)+"px";
                            ltricbox[0].style.top=(210-list[k].offsetTop)+"px";
                        }
                    }
                }
            }
        },
        playmusic:function(hash,from) {
            clearInterval(media_intervalno);
            media_intervalno=0;
            let ltricbox=document.getElementsByClassName("lyric-content");
            if(ltricbox.length>0)
            {
                ltricbox[0].style.top="210px";
            }
            document.getElementsByName("lyric").forEach(function(value, index, array) {
                value.style.color="rgb(255, 255, 255)";
            });
            this.$http.post('getMusicResouse',{ hash: hash,from:from} , {emulateJSON:true} ).then(
                response => {
                    if(media_intervalno==0)
                    {
                        media_intervalno=setInterval("vm.playInterval()",10);
                    }
                    //let res=JSON.parse(response.body);
                    let res=response.body;
                    this.playingmusic=res;
                    this.playingmusic.lyric=this.playingmusic.lyric.split("\n");
                    for (var k = 0, length = this.playingmusic.lyric.length; k < length; k++) {
                        str=this.playingmusic.lyric[k].replace(/\[(\d*):(\d*).(\d*)\]/g, "$1-$2-$3-");
                        str_2=this.playingmusic.lyric[k].replace(/\[(\d*):(\d*).(\d*)\]/g, "");
                        str=str.split("-");
                        str=parseInt(str[0])*60+parseInt(str[1])+"."+str[2];
                        this.playingmusic.lyric[k]={};
                        this.playingmusic.lyric[k]['str']=str_2;
                        this.playingmusic.lyric[k]['tim']=str;
                    }
                    this.playsrc=res.url;
                    this.playimgsrc=res.imgUrl;
                    media.setAttribute('src',this.playsrc);
                    media.play();
                    this.play_pause=false;
                },
                response => {
                    // error callback
                    if(media_intervalno==0)
                    {
                        media_intervalno=setInterval("vm.playInterval()",10);
                    }
                }
            );
        },
        addToPlaylist:function(index,playnow) {
            if(playnow)
            {
                this.musiclist.splice(0,0,this.indexlist[index]);
                //this.musiclist=this.indexlist[index].concat(this.musiclist);
                this.playmusic(this.musiclist[0].hash,this.musiclist[0].from);
            }
            else
            {
                this.musiclist=this.musiclist.concat(this.indexlist[index]);
                if(this.musiclist.length==1)
                {
                    this.playmusic(this.musiclist[0].hash,this.musiclist[0].from);
                }
            }
        },
        removeFromPlaylist:function(index){
            this.musiclist.splice(index,1);
            if(this.playindex==index)
            {
                if(this.musiclist.length>1)
                {
                    this.playnext();
                }
                else
                {
                    media.setAttribute('src',"");
                    media.play();
                    this.play_pause=false;
                }
            }
            else
            {
                if(this.playindex>index)
                {
                    this.playindex--;
                }
            }
        }
    }
})