$(function(){

    //刷新页面
    $('#gameReload').click(function(){
        window.location.reload();
    });
    //帮助显示
    $("#gameHelp").click(function (event) {
        if($("#helpBox").css("display")=="none"){
            $("#helpBox").stop().slideDown();
            $("#gameHelp").focus();
        }
        else{
            $("#helpBox").slideUp();
            $("#gameHelp").blur();
        }
        event.stopPropagation();
    });
    $(window).click(function () {
        $('#helpBox').slideUp();
        $("#gameHelp").blur();
    });

    setInterval(function(){
        var vishelp = $("#helpBox").is(":visible");
        if(vishelp){
            $('#helpBox').slideUp();
            $("#gameHelp").blur();
        }

    },5000);

    //结束实验弹出框
    $('#gameover').click(function(){
        fnShow("#endBox","fadeOutUp","fadeInDown");
    });
    
    //结束实验确定按钮
    $('#endBtn').click(function(){
        $.ajax({
            url:site_url+'/Admintrain/del_scene',
            type:'post',
            data:{'sceneinstanceuuid':instanceuuid,'sectioninsid':sid},
            async: true,
            dataType: 'json',
            success : function(message){
            }
        });
        //关闭确认弹框
        if(window.opener){
            window.opener.closeopener();
        }
        $('#endBox').hide();
        $('#okBox .promptNews').html('实验结束成功');
        fnShow("#okBox","fadeOutUp","fadeInDown");
        setTimeout(function(){
            //直接关闭页面
            window.close();
        },2000);

    });

    //检查场景是否存在
    function setInterval_check_scene() {
        $.ajax({
            url:site_url+'/Admintrain/check_scene',
            type:'post',
            data:{'host_id':host_id,'sectioninsid':sid,'sceneinstanceuuid':instanceuuid},
            async: true,
            dataType: 'json',
            success : function(message){
                if(message.code == '0001'){
                    $('#noBox .promptNews').html('场景不存在');
                    fnShow("#noBox","fadeOutUp","fadeInDown");
                }
            }
        });
    }
    //场景不存在 关闭页面
    $('#noBtn').click(function () {
        window.close();
    });
    
    setInterval_check_scene();
    //定时器
    var sceneSetInterval = setInterval(function(){
        //场景是否存在
        setInterval_check_scene();
    },10000);
    timeGo(scene_time);
    var timeSetInterval = setInterval(function(){
        //场景是否存在
         timeGo(scene_time);
    },1000);

    function zero(st){
        if(Math.ceil(st)<10){
            st = "0" +st;
        }
        return st;
    }
    //时间
    function timeGo(time){
        var time = parseInt(time);
        var hours = time / 60 / 60
        var hoursRound = zero(Math.floor(hours));
        var minutes = time /60 - (60 * hoursRound);
        var minutesRound = zero(Math.floor(minutes));
        var seconds = time - (60 * 60 * hoursRound) - (60 * minutesRound);
        var secondsRound = zero(Math.round(seconds));
        if(parseInt(hoursRound) <= 0){
            hoursRound = '00';
        }
        $("#numberXs").html(hoursRound);
        $("#numberFs").html(minutesRound ? minutesRound:'00');
        $("#numberMs").html(secondsRound ? secondsRound:'00');
        scene_time--;
        //比删除场景接口提前1秒 解决：弹框出现缓慢问题
        if(scene_time == -1){
            $('#noBox .promptNews').html('已到结束时间,场景结束');
            fnShow("#noBox","fadeOutUp","fadeInDown");
            //关闭确认弹框
            window.opener.closeopener();
            setTimeout(function () {
                window.close();
            },1000);
        }
        //到结束时间删除场景
        if(scene_time == -2){
            $.ajax({
                url:site_url+'/Admintrain/del_scene',
                type:'post',
                data:{'sceneinstanceuuid':instanceuuid,'sectioninsid':sid},
                async: true,
                dataType: 'json',
                success : function(message){
                }
            });
            clearTimeout(sceneSetInterval); //清除检查场景状态
            clearTimeout(timeSetInterval); //清除时间定时器
        }

    }

});