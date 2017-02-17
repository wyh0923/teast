/**
 * Created by qirupeng on 2016/8/30.
 */
var uuid = '';
var taskuuid = '';
var SceneTemplateUUID = '';
var timer;
var SectionInsNametitle = '';
$(function(){
    $(".fa-search").click(function(){
        var search = $.trim($(".iptSearch-a").val());
        window.location.href= site_url + '/Admintrain/scenelist' + "/search/"+encodeURI(translate(search));
    });
    $('.iptSearch-a').keydown(function(e){
        if(e.keyCode==13){
            var search = $.trim($(".iptSearch-a").val());
            window.location.href= site_url + '/Admintrain/scenelist' + "/search/"+encodeURI(translate(search));
        }
    });
});

$(function(){
    $(".filter").find("label").on({
        click:function(){
            $(this).addClass("cur").siblings("label").removeClass("cur");

        }
    });
    /*删除弹出框*/
    $('.forRed').click(function(){
        var code = $(this).attr('code');
        $('.okBtn').attr('code',code);
        fnShow("#scenetemplatelistPopBox","fadeOutUp","fadeInDown")
    });

    $('.forYellow').click(function(){
        var code = $(this).attr('code');
        $('.LAN1').text('').parent('tr').addClass('outHide');
        $('.LAN2').text('').parent('tr').addClass('outHide');
        $('.LAN3').text('').parent('tr').addClass('outHide');
        $.ajax({
            url:site_url+"/Admintrain/sceneinfo",
            type:'post',
            data:{'code':code},
            dataType:'json',
            success:function(message){
                if(message.code=='0000'){
                    $('.scenename').text(message.data.scene_name);
                    $('.zonecount').text(message.data.zone_count);
                    $('.createtime').text(message.data.create_time);
                    $('.scenedesc').text(message.data.description);
                    var vm_info = message.data.zone_vm_info;
                    for (vm in vm_info)
                    {
                        if(vm=='OPER'){
                            $('.oper').text(vm_info[vm].vm_display_name);
                        }else {
                            var  str = '';
                            for (m in vm_info[vm])
                            {
                                str += vm_info[vm][m].vm_display_name+'  ';
                            }
                            $('.'+vm).text(str).parent('tr').removeClass('outHide');
                        }
                    }
                    fnShow("#sceneTemplateDetail","fadeOutUp","fadeInDown");
                }
            }
        })
    });

    $('.okBtn').click(function(){
        var code = $(this).attr('code');
        $.ajax({
            url:site_url+"/Admintrain/del_scene_tpl",
            type:'post',
            data:{'code':code},
            dataType:'json',
            success:function(message){
                $('#scenetemplatelistPopBox').hide();
                if(message.code == '0000'){
                    $('#okBox p.promptNews').html('删除成功');
                    //fnHide("#scenetemplatelistPopBox","fadeInDown","fadeOutUp");
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout(function(){
                        //fnHide("#okBox","fadeInDown","fadeOutUp");
                        location.href = site_url+'/Admintrain/scenelist';
                    },2000);
                }else {
                    $('#okBox p.promptNews').html(message.msg);
                    //fnHide("#scenetemplatelistPopBox","fadeInDown","fadeOutUp");
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout(function(){
                        location.href = site_url+'/Admintrain/scenelist';
                        //fnHide("#okBox","fadeInDown","fadeOutUp");
                    },2000);

                }
            }
        })
    });

    $('.forBlue').click(function(){
        var code = $(this).attr('code');
        $('#scenestart').attr('code',code);
        $('#scene_start p.promptNews').html('');
        $('#scene_start p.promptNews').addClass('outHide');
        $('#scenestart').removeClass('noCanBg');
        SectionInsNametitle = $(this).attr('tplname');
        SceneTemplateUUID = code;
        $(".createscene").siblings().addClass("outHide");
        $(".createscene").removeClass("outHide");
        clearInterval(timer);
        //检测用户是否有下发
        $.ajax({
            url:site_url+"/Admintrain/check_issue",
            type:'post',
            data:{'taskuuid':taskuuid},
            dataType:'json',
            success:function(message){
                if(message.code != '0000'){
                    //此处有一个如果点击的是当前场景则直接显示进入场景
                    $(".applysuccess").attr('insuuid',message.data.scene_ins_uuid);
                    $(".applysuccess").attr('taskuuid',message.data.task_uuid);
                    $(".applysuccess").attr('scenename',message.data.scenename);
                    $(".applysuccess").removeClass('outHide');

                    if(message.data.template_uuid == SceneTemplateUUID){
                        $('#scene_start p.promptNews').html('');
                        $('#scene_start p.promptNews').addClass('outHide');
                        $(".applysuccess").siblings().addClass("outHide");
                        $(".applysuccess").removeClass("outHide");
                    }else {
                        //SectionInsNametitle = message.data.scenename;
                        $('#scene_start p.promptNews').html('同一用户只能申请一个实验场景，您现在的实验场景是："'+message.data.scenename+'"，请选择以下处理方式!');
                        $('#scene_start p.promptNews').removeClass('outHide');
                        $("#scenestart").addClass('outHide');
                        $("#scenestop").attr('code',code);
                        $("#scenestop").attr('scenename',SectionInsNametitle);
                        $("#scenestop").attr('insuuid',message.data.scene_ins_uuid);
                        $("#scenestop").removeClass('outHide');
                    }

                }
            }
        });
        fnShow("#scene_start","fadeOutUp","fadeInDown")
    });
    $('#close_issue').click(function () {
        clearInterval(timer);
        $('#scene_start p.promptNews').html('');
        //清除数据库里原来的

        fnHide("#scene_start","fadeInDown","fadeOutUp");
    });
    //结束正在下发的场景 弹出框
    $('.stopBtn').click(function () {
        //fnHide("#scene_start","fadeInDown","fadeOutUp");
        fnShow("#delSceneBox","fadeOutUp","fadeInDown");

    });
    //确定结束正在下发的场景
    $('#delBtn').click(function () {
        clearInterval(timer);
        //var sceneinstanceuuid = $(".applysuccess").attr('insuuid');
        //$('#scene_start').hide();
        $('#delSceneBox').hide();
        delsceneuuid(uuid);
    });
    $('#scenestart').click(function(){

        var code = $(this).attr('code');
        $.ajax({
            url:site_url+"/Admintrain/start_scene",
            type:'post',
            data:{'sceneuuid':code,'scenename':SectionInsNametitle},
            dataType:'json',
            success:function(message){
                if(message.code == '0000'){
                    uuid = message.data.scene_ins_uuid;
                    taskuuid = message.data.task_uuid;
                    $(".applysuccess").attr('insuuid',uuid);
                    $(".applysuccess").attr('taskuuid',taskuuid);
                    $(".applysuccess").attr('scenename',SectionInsNametitle);
                    //定时器
                    timer = setInterval(function(){
                        setIntervalScene();
                    },1000);
                }else if (message.code == '1017'){
                    $('#scene_start p.promptNews').html("抱歉！当前时段有场考试正在进行，考试结束后才能申请实验资源。考试结束时间"+message.data.after_end_time);
                    $('#scene_start p.promptNews').removeClass('outHide');
                    $('#scenestart').addClass('outHide');
                    clearInterval(timer);
                    setTimeout(function () {
                       // $('#scene_start p.promptNews').addClass('outHide');
                        fnHide("#scene_start","fadeInDown","fadeOutUp");
                    },2000);
                    //$('#scenestart').addClass('noCanBg');
                }else {
                    $('#scene_start p.promptNews').html(message.msg);
                    $('#scene_start p.promptNews').removeClass('outHide');
                    //$('#scenestart').addClass('noCanBg');
                    $('#scenestart').addClass('outHide');
                    clearInterval(timer);
                    setTimeout(function () {
                        // $('#scene_start p.promptNews').addClass('outHide');
                        fnHide("#scene_start","fadeInDown","fadeOutUp");
                    },2000);
                }

            }
        })
    });

    //进入场景判断
    $(".applysuccess").click(function(){
        var sceneInsUUID=$(this).attr('insuuid');
        SectionInsNametitle = $(this).attr('scenename');
        enterSceneJudge(sceneInsUUID);
        fnHide("#scene_start","fadeInDown","fadeOutUp")

    });
    //结束并下发场景
    $("#scenestop").click(function(){
        var code = $(this).attr('code');
        var sceneinstanceuuid = $(this).attr('insuuid');
        SectionInsNametitle = $(this).attr('scenename');

        $('#scene_start p.promptNews').html('请耐心等待，正在结束场景中...');
        $(".applysuccess").addClass('outHide');
        $('#scenestop').addClass('outHide');
        $.ajax({
            url:site_url+'/Admintrain/del_scene',
            type:'POST',
            data:{'sceneinstanceuuid':sceneinstanceuuid},
            async : false,
            success:function(message){
                msg = JSON.parse(message);
                if( msg.code == '0000' ) {
                    $('#scenestop').addClass('outHide');
                    $('#scene_start p.promptNews').html('');
                    $('#scene_start p.promptNews').addClass('outHide');
                    $(".applysuccess").removeClass('outHide');
                    //开始下发场景
                    issue(code,SectionInsNametitle);
                } else {
                    $('#scene_start p.promptNews').html('场景结束失败，请稍后重试');
                    $('#scene_start p.promptNews').removeClass('outHide');
                }

            }
        });



    });



});
//进入场景判断 函数
function enterSceneJudge(sceneInsUUID){
    $.ajax({
        url : site_url+"/Admintrain/check_scene_in_node",
        type : 'POST',
        data : {'sceneInsUUID':sceneInsUUID},
        async : false,
        success :function(message){

            msg = JSON.parse(message);
            if( msg.code == '0000' )
            {
                scene(sceneInsUUID);

            }else {
                $('#scene_start p.promptNews').html('场景不存在，请重新申请');
                $('#scene_start p.promptNews').removeClass('outHide');
                //fnShow("#okBox","fadeOutUp","fadeInDown");
                delsceneuuid(sceneInsUUID,'场景不存在，自动清除下发任务成功');//先不删除
            }

        }
    });
}
//进入场景
function scene(insuuid){
    var scenename = $(".applysuccess").attr('scenename');
    $.ajax({
        url:site_url+"/Admintrain/enter_scene",
        type:"POST",
        data:{'sceneinstanceuuid':insuuid},
        async : false,
        dataType:'json',
        success:function(message){
            if(message.code=='0000'){

                var url = site_url+'/Admintrain/vm_vnc?uuid='+insuuid+'&SectionInsNametitle='+scenename+'&token='+message.data.token+'&port='+message.data.port+'&ip='+message.data.ip+'&loguser='+ message.data.loguser+'&vmuuid='+ message.data.vmuuid + '&logpwd=' + message.data.logpwd + '&Sii=' + '' + '&host_id=' + message.data.host_id+ '&scene_end_time=' + message.data.SceneInstance.end_time;

                window.open(url, 'e春秋',"channelmode=yes,height=800, width=1100, toolbar=no, titlebar=no, menubar=no, scrollbars=no, resizable=no, location=no, status=no");

            }else if(message.code=='1004'){
                $('#scene_start p.promptNews').html('场景已经不存在！将自动清除计划任务');
                $('#scene_start p.promptNews').removeClass('outHide');
                delsceneuuid(insuuid,'场景不存在，自动清除下发任务成功');
            }else {
                $('#scene_start p.promptNews').html(message.msg);
                $('#scene_start p.promptNews').removeClass('outHide');
                delsceneuuid(insuuid,'场景不存在，自动清除下发任务成功');
            }
        }
    })
}

//删除场景
function delsceneuuid(SceneInstanceUUID,info)
{
    if(SceneInstanceUUID){
        info = info || '删除成功';
        var data={'sceneinstanceuuid':SceneInstanceUUID};
        $.ajax({
            url:site_url+'/Admintrain/del_scene',
            type:'POST',
            data:data,
            async : false,
            success:function(message){
                msg = JSON.parse(message);
                if( msg.code == '0000' ) {
                    //fnHide("#scene_start","fadeInDown","fadeOutUp");
                    $('#scene_start').hide();
                    $('#okBox p.promptNews').html(info);
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout(function(){
                        $('#scene_start p.promptNews').addClass('outHide');
                        fnHide("#okBox","fadeInDown","fadeOutUp");
                    },1000);

                } else {
                    //$('#confirmBox').hide();
                    $('#scene_start p.promptNews').html('场景正在下发，不能结束，请稍后重试');
                    $('#scene_start p.promptNews').removeClass('outHide');
                    //fnShow("okBox","fadeOutUp","fadeInDown");
                }

            }
        });
    }
}

//检查场景状态
function checkSceneStatus(){
    var sceneStatus={
        "status":0,   //1.无场景  2.正在下发  3.下发完成  4.下发失败
        "msg":"进入场景",
        "sceneInfo":{
            "sceneInsUUID":"",
            "msg":"",
            "taskUUID":"",
            "taskProcess":0
        }
    };
    $.ajax({
        url:site_url+"/Admintrain/check_scene_status",
        type:'POST',
        async : false,
        data:{'SceneTemplateUUID':SceneTemplateUUID},
        dataType:'json',
        success:function(message){

            if (message.code != '0000') {
                sceneStatus.status = message.code;
                sceneStatus.msg = message.msg;

                sceneStatus.sceneInfo.sceneInsUUID = message.data.sceneInsUUID;
                sceneStatus.sceneInfo.msg = message.data.msg;
                sceneStatus.sceneInfo.taskUUID = message.data.taskUUID;
                sceneStatus.sceneInfo.taskProcess = message.data.taskProcess;
            }
        }
    });
    return sceneStatus;
}
//场景按钮状态检查函数
function setIntervalScene(){
    var sceneStatus = checkSceneStatus();
    switch(sceneStatus.status){
        case 1:  //无场景
            $(".createscene").siblings().addClass("outHide");
            $(".createscene").removeClass("outHide");
            break;
        case 2:  //正在下发
            $(".taskprogr").siblings().addClass("outHide");
            $(".taskprogr").removeClass("outHide");
            setprogress(sceneStatus.sceneInfo.taskProcess, sceneStatus.sceneInfo.msg);
            break;
        case 3:  //下发完成
            if(sceneStatus.sceneInfo.taskProcess == 100){
                enterSceneJudge(sceneStatus.sceneInfo.sceneInsUUID);
            }
            $(".applysuccess").siblings().addClass("outHide");
            $(".applysuccess").text(sceneStatus.msg);
            $(".applysuccess").removeClass("outHide");
            break;
        case 4:  //下发失败
            $(".createscene").siblings().addClass("outHide");
            $('#scene_start p.promptNews').html('下发失败，将自动清除场景...');
            $('#scene_start p.promptNews').removeClass('outHide');
            //$(".createscene").text(sceneStatus.msg);
            $(".createscene").removeClass("outHide");
            delsceneuuid(uuid,'下发失败，自动清除场景成功');//删除场景原
            break;
    }
}
function setprogress(taskProcess, msg){
    $('.taskpro').css('width',taskProcess+'%');
    $('#proTxt').html(taskProcess+'%    '+msg);
}
/***
 * 从vnc界面调用关闭弹窗
 */
function closeopener() {
    clearInterval(timer);
    $('#scene_start p.promptNews').html('');
    $('#scene_start p.promptNews').addClass('outHide');
    $(".createscene").siblings().addClass("outHide");
    $(".createscene").removeClass("outHide");
    fnHide("#scene_start","fadeInDown","fadeOutUp");
}
/***
 * 结束后下发
 */
function issue(code,scenename) {
    $.ajax({
        url:site_url+"/Admintrain/start_scene",
        type:'post',
        data:{'sceneuuid':code,'scenename':scenename},
        dataType:'json',
        success:function(message){
            if(message.code == '0000'){
                uuid = message.data.scene_ins_uuid;
                taskuuid = message.data.task_uuid;
                $(".applysuccess").attr('insuuid',uuid);
                $(".applysuccess").attr('taskuuid',taskuuid);
                $(".applysuccess").attr('scenename',scenename);
            }else {
                $('#scene_start p.promptNews').html(message.msg);
                $('#scene_start p.promptNews').removeClass('outHide');
                $('#scenestart').addClass('noCanBg');
            }
            //定时器
            timer = setInterval(function(){
                setIntervalScene();
            },1000);
        }
    })
}

