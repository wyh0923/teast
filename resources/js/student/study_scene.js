$(function(){

    //创建场景
    $('#createscene').click(function(){

        //判断是否存在场景 存在弹出框  不存在 下发场景
        IsexistAndCreate();

    });
    /*
    * 判断场景是否存在
    * 1 存在 显示弹出框
    * 0 不存在下发场景
    * */
    function IsexistAndCreate() {
        if(sceneuuid == ''){
            $('#okBox .promptNews').html('抱歉！该实验没有场景模板');
            fnShow("#okBox","fadeOutUp","fadeInDown");
            setTimeout(function(){
                fnHide("#okBox","fadeInDown","fadeOutUp");
            },2000);

            return false;
        }
        //判断是否存在场景
        var exsistScene = isExsistScene();

        if (exsistScene.isExsist) {
            //显示弹出框
            twoScene(exsistScene);
        } else {
            //下发场景
            createScene();
        }
    }

    /**
     * 创景场景
     */
    function createScene() {
        
        $.ajax({
            url:site_url+'Study/create_scene',
            type:'post',
            data:{'sceneuuid':sceneuuid,'sectioninsid':sectioninsid,'sectionname':sectionname},
            async: false,
            dataType: 'json',
            success : function(message){
                if(message.code == '0000'){
                    var sceneinstanceuuid = message.data.scene_ins_uuid;
                    var taskuuid = message.data.task_uuid;
                    $("#successScene").attr('sceneinstanceuuid',sceneinstanceuuid);
                    $("#successScene").attr('taskuuid',taskuuid);
                } else if (message.code == '0201'){
                    $('#okBox .promptNews').html('请检查网络');
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                } else if (message.code == '1017'){
                    $('#okBox .promptNews').html("抱歉！当前时段有场考试正在进行，考试结束后才能申请实验资源。考试结束时间"+message.data.after_end_time);
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                }else{
                    $('#okBox .promptNews').html(message.msg);
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                }
                setTimeout(function(){
                    fnHide("#okBox","fadeInDown","fadeOutUp");
                },2000);
            }
        })
    }
    /*
    * 已存在场景 显示弹出框
    * exsistScene：存在返回值 ，SceneInstanceUUID  SectionInsID 实例节ID
    * */
    function twoScene(exsistScene){
        $('#sceneBox .promptNews').html("同一用户只能申请一个实验场景,您现在的场景是：“"+ exsistScene.sectionName +"”，请选择以下处理方式!");
        $('#accessBtn').attr('href', site_url+'Study/studysection?taskid='+exsistScene.taskID+'&sectioninsid='+exsistScene.sectionInsID).attr('target', '_Blank');
        $('#endBtn').attr('SceneInstanceUUID', exsistScene.sceneInstanceUUID).attr('SectionInsID', exsistScene.sectionInsID);

        fnShow("#sceneBox","fadeOutUp","fadeInDown");
    }
    /**
     * 是否存在场景(查询该学员下学习的所有场景) 已存在 弹出框提示
     */
    function isExsistScene(){
        var exsistScene = {
            "isExsist" : 0,
            "taskName" : "",
            "taskID" : "",
            "sectionInsID" : "",
            "sceneInstanceUUID" : "",
            "sectionName" : ""
        };
        $.ajax({
            url:site_url+'Study/is_exsist_scene',
            type:'post',
            async: false,
            dataType: 'json',
            success : function(message){
                exsistScene.isExsist = 0;

                if(message.msg > 0){
                    exsistScene.isExsist = 1;
                    exsistScene.sceneInstanceUUID = message.data.SceneInstanceUUID;
                    exsistScene.taskName = message.data.TaskName;
                    exsistScene.taskID = message.data.TaskID;
                    exsistScene.sectionInsID = message.data.SectionInsID;
                    exsistScene.sectionName = message.data.SectionName;
                }
            }
        });
        return exsistScene;
    }

    /*
    * 删除下发的场景
    * 下发新的场景
    * */

    $('#endBtn').click(function(){
        var sceneinstanceuuid = $(this).attr('sceneinstanceuuid');
        var sectioninsid = $(this).attr('sectioninsid');
        
        //提示框更改
        fnShow("#sceneBox","fadeOutUp","fadeInDown");
        $("#sceneBox .popTitle p").html("提示信息");
        $("#sceneBox .promptNews").addClass('promptUp');
        $("#sceneBox .promptNews").addClass('colorYe');
        $('#sceneBox .promptNews').html('<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>正在删除');
        $("#sceneBox .btnBox").hide();

        //删除场景
        $.ajax({
            url:site_url+'Study/del_scene',
            type:'post',
            data:{'sceneinstanceuuid':sceneinstanceuuid,'sectioninsid':sectioninsid},
            async: true,
            dataType: 'json',
            success : function(message){
                //消失并还原
                fnHide("#sceneBox","fadeInDown","fadeOutUp",'1');
                $('#sceneBox').hide();
                $("#sceneBox .popTitle p").html("存在未结束的场景");
                $("#sceneBox .promptNews").removeClass('promptUp');
                $("#sceneBox .promptNews").removeClass('colorYe');
                $("#sceneBox .btnBox").show();

                if(message.code == '0201'){
                    $('#okBox .promptNews').html('请检查网络');
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                }else if(message.code == '0000'){
                    $('#okBox .promptNews').html(message.msg);
                    fnShow("#okBox","fadeOutUp","fadeInDown");

                    //下发新场景
                    IsexistAndCreate();
                }else{
                    $('#okBox .promptNews').html(message.msg);
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                }

                setTimeout(function(){
                    fnHide("#okBox","fadeInDown","fadeOutUp");
                },2000);

            }
        });
      

    });


    //删除场景
    function del_scene(sceneinstanceuuid,sectioninsid) {
        $('#delSceneBox').hide();

        $.ajax({
            url:site_url+'Study/del_scene',
            type:'post',
            data:{'sceneinstanceuuid':sceneinstanceuuid,'sectioninsid':sectioninsid},
            async: false,
            dataType: 'json',
            success : function(message){
                if(message.code == '0201'){
                    $('#okBox .promptNews').html('请检查网络');
                }else{
                    $('#okBox .promptNews').html(message.msg);
                }
                fnShow("#okBox","fadeOutUp","fadeInDown");
                setTimeout(function(){
                    fnHide("#okBox","fadeInDown","fadeOutUp");
                },2000);
            }
        });

    }
    //结束正在下发的场景 弹出框
    $('.stopBtn').click(function () {
        fnShow("#delSceneBox","fadeOutUp","fadeInDown");
    });
    //确定结束正在下发的场景

    $('#delBtn').click(function () {
        var sceneinstanceuuid = $("#successScene").attr('sceneinstanceuuid');
        del_scene(sceneinstanceuuid,sectioninsid);
    });
    //检查场景状态
    function check_scene_status(){
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
            url:site_url+"Study/check_scene_status",
            data:{'sectioninsid':sectioninsid},
            type:'POST',
            async : false,
            dataType: 'json',
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
    function setInterval_scene() {
        var sceneStatus = check_scene_status();
        switch(sceneStatus.status){
            case 1:  //无场景
                $("#createscene").removeClass("outHide").siblings().addClass("outHide");
                break;
            case 2:  //正在下发
                $(".taskprogr").removeClass("outHide").siblings().addClass("outHide");
                scene_progress(sceneStatus.sceneInfo.taskProcess, sceneStatus.sceneInfo.msg);
                break;
            case 3:  //下发完成
                if(sceneStatus.sceneInfo.taskProcess == 100){
                    judge_scene(sceneStatus.sceneInfo.sceneInsUUID);
                }
                $("#successScene").removeClass("outHide").siblings().addClass("outHide");
                $("#successScene").text(sceneStatus.msg);
                break;
            case 4:  //下发失败
                $("#createscene").removeClass("outHide").siblings().addClass("outHide");
                $("#createscene").text(sceneStatus.msg);
                break;
        }
    }
    //下发场景进度条
    function scene_progress(taskProcess, msg) {
        $('.taskpro').css('width',taskProcess+'%');
        $('#proTxt').html(taskProcess+'%    '+msg);
    }
    //进入场景 触发事件
    $('#successScene').click(function(){
        var sceneinstanceuuid = $(this).attr('sceneinstanceuuid');
        //进入场景
        judge_scene(sceneinstanceuuid);
    });

    //判断场景
    function judge_scene(sceneinstanceuuid){
        $.ajax({
            url:site_url+'Study/judge_scene',
            type:'post',
            data:{'sceneinstanceuuid':sceneinstanceuuid},
            async: false,
            dataType: 'json',
            success : function(message){
                if(message.code == '0000'){
                    //进入场景
                    enter_scene(sceneinstanceuuid);
                }else{
                    //场景不存在清空数据库的值
                    update_scene();
                }
            }
        })
    }
    
    //进入场景
    function enter_scene(sceneinstanceuuid) {
        $.ajax({
            url:site_url+'Study/enter_scene',
            type:'post',
            data:{'sceneinstanceuuid':sceneinstanceuuid},
            async: false,
            dataType: 'json',
            success : function(message){
                if(message.code == '0000'){
                    var url = site_url +'Study/vm_vnc?uuid='+ sceneinstanceuuid +'&sectionname='+ sectionname +'&token='+ message.data.token +'&port='+ message.data.port +'&ip='+ message.data.ip +'&loguser='+ message.data.loguser +'&vmuuid='+ message.data.vmuuid + '&logpwd=' + message.data.logpwd + '&sid=' + sectioninsid + '&host_id=' + message.data.host_id + '&scene_end_time=' + message.data.SceneInstance.end_time;
                    window.open(url, 'e春秋',"channelmode=yes,height=800, width=1100, toolbar=no, titlebar=no, menubar=no, scrollbars=no, resizable=no, location=no, status=no");

                }else if(message.code == '1004'){
                    //场景在中间件中 找不到
                    update_scene();
                }else{
                    $('#okBox .promptNews').html(message.msg);
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout(function(){
                        fnHide("#okBox","fadeInDown","fadeOutUp");
                    },2000);
                }
            }
        })
    }
    //场景不存在清空数据库的值
    function update_scene() {
        $.ajax({
            url:site_url+'Study/update_scene',
            type:'post',
            data:{'sectioninsid':sectioninsid},
            async: false,
            dataType: 'json',
            success : function(message){
                if(message.code == '0000'){
                    $('#okBox .promptNews').html('场景不存在');
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout(function(){
                        fnHide("#okBox","fadeInDown","fadeOutUp");
                    },2000);
                }
            }
        })
    }
//如果是网络实验节，则需要开启定时器，查看下发按钮的状态
    if(sectiontype == 2){
        setInterval_scene();
        //定时器
        setInterval(function(){
            setInterval_scene();
        },1000)
    }

    //页面加载后  如果是进入场景 判断场景是否存在
    if(sceneinstanceuuid != '' && taskuuid == ''){
        $.ajax({
            url:site_url+'Study/judge_scene',
            type:'post',
            data:{'sceneinstanceuuid':sceneinstanceuuid},
            async: false,
            dataType: 'json',
            success : function(message){
                if(message.code != '0000'){
                    //场景不存在清空数据库的值
                    update_scene();
                }
            }
        })
    }

});