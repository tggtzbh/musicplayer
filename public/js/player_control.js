var play={
    completion:{
        music_setback:false,
        music_setback_percent:0.0,
        offset_l1:0,
        offset_r1:0,
        main_mouseDown:function (ev)
        {
            play.completion.offset_l1=document.getElementById("audio-progress").offsetLeft;
            play.completion.offset_r1=document.getElementById("audio-progress").clientWidth+play.completion.offset_l1 ;
            play.completion.music_setback=true;
        },
        bak_mouseOver:function(ev)
        {
            if(play.completion.music_setback)
            {
                var offset=(ev.movementX-play.completion.offset_l1)/(play.completion.offset_r1-play.completion.offset_l1);
                offset<0? offset=0:offset>1? offset=1:offset=offset;
                document.getElementById("audio-progress-box").style.width=(offset)*100+"%";
            }
        },
        bak_Click:function(ev)
        {
            if(!play.completion.music_setback)
            {
                play.completion.music_setback=true;
                play.completion.bak_mouseUp(ev);
            }

        },
        bak_mouseUp:function(ev)
        {
            if(play.completion.music_setback)
            {
                document.getElementsByName("lyric").forEach(function(value, index, array) {
                    value.style.color="rgb(255, 255, 255)";
                });
                var offset_l=play.publicfun.getLeft(document.getElementById("audio-progress"));
                var offset_r=document.getElementById("audio-progress").clientWidth+offset_l ;
                //var offset_t=getTop(document.getElementById("audio-progress"));
                //var offset_b=document.getElementById("audio-progress").clientHeight+offset_t ;
                //console.log(offset_t);
                //console.log(offset_b);
                //console.log(ev.movementY);
                if((offset_l -10) <ev.movementX  && ev.movementX < (offset_r+10))
                {
                    play.completion.music_setback_percent=(ev.movementX-offset_l)/(offset_r-offset_l);
                    document.getElementById("audio-progress-box").style.width=(ev.movementX-offset_l)/(offset_r-offset_l)*100+"%";
                }
                play.completion.music_setback_percent<0? play.completion.music_setback_percent=0:play.completion.music_setback_percent>1? play.completion.music_setback_percent=1:play.completion.music_setback_percent=play.completion.music_setback_percent;
                document.getElementById("audio-progress-box").style.width=play.completion.music_setback_percent*100+"%";
                media.currentTime=media.duration*play.completion.music_setback_percent;
                play.completion.music_setback=false;
            }
        }
    },
    publicfun:{
        getLeft:function(e){
            var offset=e.offsetLeft;
            if(e.offsetParent!=null) offset+=play.publicfun.getLeft(e.offsetParent);
            return offset;
        },
        getTop:function (e){
            var offset=e.offsetTop;
            if(e.offsetParent!=null) offset+=play.publicfun.getTop(e.offsetParent);
            return offset;
        },
        init:function()
        {
            document.body.onmouseup=function(event)
            {
                play.completion.bak_mouseUp(event)
            };
            document.body.onmouseover=function(event)
            {
                play.completion.bak_mouseOver(event)
            };
            //document.body.onmouseover="play.completion.bak_mouseOver(event)";
            /*document.addEventListener('mouseup', function(event){
                play.completion.bak_mouseUp(event);
            });
            document.addEventListener('mouseover', function(event){
                play.completion.bak_mouseOver(event);
            });*/
        }
    }
};