function getUrlParam(name){  
    //构造一个含有目标参数的正则表达式对象  
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");  
    //匹配目标参数  
    var r = window.location.search.substr(1).match(reg);  
    //返回参数值  
    if (r!=null) return unescape(r[2]);  
    return null;  
}



$(function(){


//检查场景状态
function checkSceneStatus(sectionInsId){
    var sceneStatus={
        "status":0,   //1.无场景  2.正在下发  3.下发完成  4.下发失败
        "msg":"进入场景",
        "sceneInfo":{
            "sceneInsUUID":"",
            "msg":"",
            "taskUUID":"",
            "taskProcess":0
        }
    }
    $.ajax({
          url:siteurl+"StuTaskCtl/checkSceneStatus",
          type:'POST',
          async : false,
          data:{'sectionInsId':sectionInsId},
          success:function(message){
                var data=eval('('+message+')');
                //console.log('111'+data.result.status);
                //console.log(message.status);
                if (data.status == 1) {
                    //if(data.result.status == 1 || ata.result.status == 3)
                    sceneStatus.status = data.result.status;
                    sceneStatus.msg = data.result.msg;
                    
                        sceneStatus.sceneInfo.sceneInsUUID = data.result.sceneInfo.sceneInsUUID;
                        sceneStatus.sceneInfo.msg = data.result.sceneInfo.msg;
                        sceneStatus.sceneInfo.taskUUID = data.result.sceneInfo.taskUUID;
                        sceneStatus.sceneInfo.taskProcess = data.result.sceneInfo.taskProcess;
                }
          }
     })
    //Do soming thing
    //console.log(sceneStatus);
    return sceneStatus;
}

function setprogress(taskProcess, msg, realProcess){
    $('.taskpro').css('width',taskProcess+'%');
    $('.taskproinfo').html(taskProcess+'%    '+msg);
}

//场景按钮状态检查函数
function setIntervalScene(){
    var sceneStatus = checkSceneStatus($(".createscene").attr('sectioninsid'));
        console.log(sceneStatus);
        switch(sceneStatus.status){
            case 1:  //无场景
                $(".applysuccess").css('display','none');
                $(".sceneprogress").css('display','none');
                $(".createscene").css('display','inline-block');
                //$(".createscene").text(sceneStatus.msg);
                $(".stopScene").css('display','none');
                break;
            case 2:  //正在下发
                $(".applysuccess").css('display','none');
                $(".createscene").css('display','none');
                $(".sceneprogress").css('display','inline-block');
                setprogress(sceneStatus.sceneInfo.taskProcess, sceneStatus.sceneInfo.msg, true);
                $(".stopScene").css('display','inline-block');
                break;
            case 3:  //下发完成
                //alert(sceneStatus.sceneInfo.taskProcess);insuuid
                if(sceneStatus.sceneInfo.taskProcess == 100){
                    enterSceneJudge(sceneStatus.sceneInfo.sceneInsUUID,$(".createscene").attr('sectioninsid'));
                }
                $(".sceneprogress").css('display','none');
                $(".createscene").css('display','none');
                $(".applysuccess").css('display','inline-block');
                $(".applysuccess").text(sceneStatus.msg);
                $(".stopScene").css('display','none');
                break;
            case 4:  //下发失败
                $(".applysuccess").css('display','none');
                $(".sceneprogress").css('display','none');
                $(".createscene").css('display','inline-block');
                $(".createscene").text(sceneStatus.msg);
                $(".stopScene").css('display','none');
                break;
        }
}


    //var SectionType = "<?php echo $datas[0]['SectionType']?>";
    // var auto = "<?php echo $auto;?>";
    //如果是网络实验节，则需要开启定时器，轮训下发按钮的状 态
    if(SectionType == 2){
    	setIntervalScene();
    	//定时器
    	setInterval(function(){
    		setIntervalScene();
    	},1000)
    }

    //如果检测到自动标记，则对应不同的场景类型开启自动播放视频或者自动下发场景
    if(auto != '') {
        if (SectionType == 2) {
            createScene();
        }
    }

	$(".czsca").click(function(){
        $(this).toggleClass("up");
        $(this).parent().next().toggleClass("xs");
	});


    //显示弹出框 
    //exsistScene：存在返回值 ，uuid：模板ID SectionInsIDNew：p_section_instance表ID，SectionCodeNew：新场景的按钮状态
    function generateSelectWindow(exsistScene,uuid,SectionInsIDNew,SectionCodeNew){
        var action;
        if(exsistScene.taskType ==2){
            action = 'finished';
        }else{
            action = 'underway';
        }
        $('.confirmBox #title').html("同一用户只能申请一个实验场景,您现在的场景是：“"+ exsistScene.sectionName +"”，请选择以下处理方式!");
        $('.confirmBox #comeBtn').attr('href', siteurl+'StuTaskCtl/sectionstudy/'+action+'?SectionInsID='+exsistScene.sectionInsID+'&TaskID='+exsistScene.taskID).attr('target', '_Blank');

        $('.confirmBox #deleteBtn').attr('SceneInstanceUUID', exsistScene.sceneInstanceUUID).attr('SectionCode', exsistScene.sectionCode).attr('SectionCodeNew', SectionCodeNew).attr('SectionInsID', exsistScene.sectionInsID).attr("uuid",uuid).attr("SectionInsIDNew",SectionInsIDNew);

        //出现弹出框
        fnShow("confirmBox","fadeOutUp","fadeInDown");
    }

    //点击触发事件
    $('.createscene').click(function(){
        createScene();
    })
    
    //创建场景
    function createScene(){
        //createScene();
        var uuid = $(".createscene").attr('uuid');
        var TaskID = $(".createscene").attr('TaskID');
        var SectionCode = $(".createscene").attr('SectionCode');
        var SectionInsID = $(".createscene").attr('sectioninsid');

        //判断是否存在场景 存在弹出框  不存在 下发场景
        IsexistAndCreate(SectionCode,uuid,TaskID,SectionInsID);
    };

    /**
    * 判断是否存在场景 ，存在弹出框   不存在 下发场景
    */
    function IsexistAndCreate(SectionCode,uuid,TaskID,SectionInsID){

        //判断是否存在场景 
        var exsistScene = isExsistScene(TaskID);
        
        if( exsistScene.isExsist ){//存在弹出框
            generateSelectWindow(exsistScene,uuid,SectionInsID,SectionCode);
        }else{// 不存在 下发场景
            createNew(SectionCode,uuid,TaskID,SectionInsID);
        }

    }

    /**
    *   ajax 下发新场景 
    *   uuid：模板ID ，SectionInsID:SectionInsID ，
    */
    function  createNew(SectionCode,uuid,TaskID,SectionInsID){

        $.ajax({
            url:siteurl+"StuTaskCtl/createZone",
            data:{'CourseCode':SectionCode,'TemplateUUID':uuid,'TaskID':TaskID,'SectionInsID':SectionInsID},
            type:'POST',
            datatype:"json",
            async : false,
            success:function(message){
                var obj=eval('('+message+')');
                if( obj.RespHead.ErrorCode ==0 ){
                    uuid=obj.RespBody.Result.scene_ins_uuid;
                    var taskuuid = obj.RespBody.Result.task_uuid;
                    $(".applysuccess").attr('insuuid',uuid);
                    $(".applysuccess").attr('taskuuid',taskuuid);
                }else if( obj.RespHead.ErrorCode == 1017 ){
                    //结束时间 +计划任务清除时间
                    /*var mydate= new Date(obj.RespBody.Result.after_end_time);
                    mydate.setMinutes(mydate.getMinutes()+30);
                    var time = formatDate(mydate);*/
                    $("#okBox p").html("抱歉！当前时段有场考试正在进行，考试结束后才能申请实验资源。考试结束时间"+obj.RespBody.Result.after_end_time);
                    fnShow("okBox","fadeOutUp","fadeInDown");
                }else if( obj.RespHead.ErrorCode == 2003 ){
                    $("#okBox p").html("其他计划任务正在清理中");
                    fnShow("okBox","fadeOutUp","fadeInDown");
                }else if( obj.RespHead.ErrorCode == 2004 ){
                    $("#okBox p").html("请检查子节点设备是否被移除");
                    fnShow("okBox","fadeOutUp","fadeInDown");
                }

            }    
        });
    }

//中国标准时间转换成标准格式
function formatDate(date) { 
    var year = date.getFullYear(); 
    var month = date.getMonth() + 1; 
    var day = date.getDate(); 
    var hour = date.getHours(); 
    var minute = date.getMinutes(); 
    var second = date.getSeconds(); 
    return year + "-" + month + "-" + day+ " " + hour + ":" + minute+ ":" + second; 
} 

    /**
    *   是否存在场景(查询该学员下考试的所有场景) 已存在 弹出框提示
    *   
    */
    function isExsistScene(TaskID){
        var exsistScene = {
            "isExsist" : 0,
            "taskName" : "", 
            "taskID" : "", 
            "sectionInsID" : "",
            "sceneInstanceUUID" : "",
            "sectionName" : "",
            "taskType" : ""
        };
        $.ajax({
            url : siteurl+'StuTaskCtl/isExsistScene',
            type : "POST",
            async : false,
            data : { TaskID: TaskID },
            async : false,
            success : function(data){
                var cols = eval('('+data+')');
                if( cols.length > 0) {
                    exsistScene.isExsist = 1;
                    exsistScene.sceneInstanceUUID = cols[0].SceneInstanceUUID;
                    exsistScene.taskName = cols[0].TaskName;
                    exsistScene.taskID = cols[0].TaskID;
                    exsistScene.sectionInsID = cols[0].SectionInsID;
                    exsistScene.sectionName = cols[0].SectionName;
                    exsistScene.taskType = cols[0].TaskType;
                } else{
                    exsistScene.isExsist = 0;
                }
                
            }
        });
        //console.log(exsistScene);
        return exsistScene;
    }

    $('.confirmBox #deleteBtn').click(function(){
        var SceneInstanceUUID = $(this).attr('SceneInstanceUUID');
        var SectionInsID = $(this).attr('SectionInsID');
        var SectionInsIDNew = $(this).attr('SectionInsIDNew');
        var SectionCodeNew = $(this).attr('SectionCodeNew');
        var uuid = $(this).attr('uuid');

        
        /**
        *   ajax 删除场景 
        *   SceneInstanceUUID：删除场景接口所需 ， SectionInsID:p_section_instance表ID 更改SceneInstanceUUID为空
        */
        
        delsceneuuid(SceneInstanceUUID, SectionInsID);

        //判断是否存在场景 ，存在弹出框   不存在 下发场景
        //SectionCode,uuid,TaskID,SectionInsID
        IsexistAndCreate(SectionCodeNew, uuid, 0, SectionInsIDNew);
    });

    //删除场景
    function delsceneuuid(SceneInstanceUUID, SectionInsID)
    {
        if(SceneInstanceUUID){
            var data={'uuid':SceneInstanceUUID,'Sii':SectionInsID};

            $.ajax({
                url:siteurl+'StuTaskCtl/delscene',
                type:'POST',
                data:data,
                async : false,
                success:function(message){
                    msg = JSON.parse(message);
                    if( msg.RespHead.ErrorCode == 0 ) {
                        fnHide("confirmBox","fadeInDown","fadeOutUp");
                    } else {
                        //alert("结束场景失败，请重新尝试");
                        $('#confirmBox').hide();
                        $("#okBox p").html('场景正在下发，不能结束');
                        fnShow("okBox","fadeOutUp","fadeInDown");
                    }

                }
            });
            /*$('#confirmBox').hide();
            //fnShow("okBox","fadeOutUp","fadeInDown");
            $('#okBox').show();
            $('#okBox p').html('<span><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>正在结束场景</span>');*/
        }
    }

    //页面点击停止下发场景
	$(".stopScene").click(function(){
		fnShow("deleteBox","fadeOutUp","fadeInDown");
	})
	$("#SceneDeleteBtn").click(function(){
		var SectionInsID=$(".createscene").attr('sectioninsid');
		var SceneInstanceUUID=$(".applysuccess").attr('insuuid');
		delsceneuuid(SceneInstanceUUID,SectionInsID);
		fnHide("deleteBox","fadeInDown","fadeOutUp");
		$('.stopScene').hide();
	})
    	

    var task_status = ['正在排队，请耐心等待！','已收到请求','已经开始申请','申请失败','正在重试','申请成功','已撤销','已拒绝','进程中','排队等候中'];

    	//进入场景判断
	$(".applysuccess").click(function(){
    	var sceneInsUUID=$(this).attr('insuuid');
    	var SectionInsID=$(".applysuccess").attr('SectionInsID');

        //checkSceneExistInNode

        enterSceneJudge(sceneInsUUID,SectionInsID);

	});

    //进入场景判断 函数
    function enterSceneJudge(sceneInsUUID,SectionInsID){
        $.ajax({
            url : siteurl+"StuTaskCtl/checkSceneExistInNode",
            type : 'POST',
            data : {'sceneInsUUID':sceneInsUUID},
            async : false,
            success :function(message){

                msg = JSON.parse(message);
                if( msg.RespHead.ErrorCode == 0 )
                {
                    sceneExist = msg.RespBody.Result;
                    if(sceneExist) { //存在场景
                        scene(sceneInsUUID, SectionInsID);
                    }else{
                        //alert("场景不存在，请重新申请");
                        $("#okBox p").html('场景不存在，请重新申请');
                        fnShow("okBox","fadeOutUp","fadeInDown");
                        delsceneuuid(sceneInsUUID,SectionInsID);
                    }
                }

            }
        });
    }


	//进入场景 该场景已没有 清空数据库的值
	function updateScene(SectionInsID){
		$.ajax({
		url:siteurl+"StuTaskCtl/updateScene",
		type:"POST",
		data:{'SectionInsID':SectionInsID},
		async : false,
		success:function(message){
		    console.log(message);
		}
    	})
	}

	//进入场景
	function scene(insuuid, SectionInsID){
    	$.ajax({
		url:siteurl+"StuTaskCtl/jumpnovnc",
		type:"POST",
		data:{'uuid':insuuid},
		async : false,
		success:function(message){
		    var obj=eval('('+message+')')
		    if( obj.length < 1 ) { return; }
		    if(obj.RespHead.ErrorCode==0){
		       // var url = siteurl+'StuTaskCtl/vmnvc?uuid='+insuuid+'&token='+obj.RespBody.Result.token+'&port='+obj.RespBody.Result.port+'&ip='+obj.RespBody.Result.ip+'&loguser='+ obj.RespBody.Result.loguser+'&vmuuid='+ obj.RespBody.Result.vmuuid + '&logpwd=' + obj.RespBody.Result.logpwd + '&Sii=' + SectionInsID;
		        var url = siteurl+'StuTaskCtl/vmnvc?uuid='+insuuid+'&SectionInsNametitle='+TaskSectionName+'&token='+obj.RespBody.Result.token+'&port='+obj.RespBody.Result.port+'&ip='+obj.RespBody.Result.ip+'&loguser='+ obj.RespBody.Result.loguser+'&vmuuid='+ obj.RespBody.Result.vmuuid + '&logpwd=' + obj.RespBody.Result.logpwd + '&Sii=' + SectionInsID + '&host_id=' + obj.RespBody.Result.host_id;
			        
		        window.open(url, 'e春秋',"channelmode=yes,height=800, width=1100, toolbar=no, titlebar=no, menubar=no, scrollbars=no, resizable=no, location=no, status=no");

		    }else if(obj.RespHead.ErrorCode == 404 || obj.RespHead.ErrorCode == 1004){
                $('#okBox p').html('场景不存在，请重新申请');
                fnShow("okBox","fadeOutUp","fadeInDown");
                delsceneuuid(insuuid,SectionInsID);
                setTimeout(function(){
                    fnHide("okBox","fadeInDown","fadeOutUp");
                    window.location.reload();
                },2000)
            }else{
		        console.info('错误码 :'+ obj.RespHead.ErrorCode + ', 错误信息 :' + obj.RespHead.Message);
		    }
		}
    	})
	}

    $('.over').click(function(){
        var SceneInsUUID = $(this).attr('insuuid');
        var SectionInsID = $(this).attr('SectionInsID');
        if(SceneInsUUID){
            var data={'uuid':SceneInsUUID,'Sii':SectionInsID}
            $.ajax({
                url:siteurl+'StuTaskCtl/delscene',
                type:'POST',
                data:data,
                async : false,
                success:function(message){
                    // alert("结束成功");
                    // window.location.href=siteurl+"StuTaskCtl/sectionstudy?SectionInsID="+SectionInsID;
                }
            })
        }
    });


    //当滚动条的位置处于距顶部100像素以下时，跳转链接出现，否则消失
    $(function () {
        $(window).scroll(function(){
            if ($(window).scrollTop()>100){
                $("#back-to-top").fadeIn(500);
            }
            else
            {
                $("#back-to-top").fadeOut(500);
            }
        });

        //当点击跳转链接后，回到页面顶部位置
        $("#back-to-top").click(function(){
            $('body,html').animate({scrollTop:0},0);
            return false;
        });
		
    });


})